<?php

namespace App\Http\Controllers;

use App\Models\MacroAnalysisResult;
use App\Models\MacroScenario;
use App\Models\SectorCache;
use App\Services\MacroReasoningService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class MacroReportController extends Controller
{
    public function __construct(
        protected MacroReasoningService $reasoningService
    ) {}

    /**
     * Render the Institutional Macro Equity Research Tear-Sheet.
     */
    public function tearSheet(Request $request): View
    {
        $presetScenarios = MacroScenario::where('is_preset', true)->get();
        $recentCustomScenarios = MacroScenario::where('is_preset', false)->latest()->take(5)->get();

        $scenarioId = $request->query('scenario_id');
        $activeScenario = null;

        if ($scenarioId && $scenarioId !== 'baseline' && $scenarioId !== '0') {
            $activeScenario = MacroScenario::find($scenarioId);
        }

        if (! $activeScenario) {
            $activeScenario = $presetScenarios->first();
        }

        $sectorResults = collect();
        $narrativeSummary = null;

        if ($activeScenario) {
            $existingResults = MacroAnalysisResult::with(['sector.companies'])
                ->where('scenario_id', $activeScenario->id)
                ->get();

            if ($existingResults->isEmpty()) {
                $generated = $this->reasoningService->analyzeScenario(
                    $activeScenario->title,
                    $activeScenario->description
                );

                $sectorsMap = SectorCache::all()->keyBy('sector_code');

                foreach ($generated['sectors'] as $sectorCode => $data) {
                    if (isset($sectorsMap[$sectorCode])) {
                        MacroAnalysisResult::updateOrCreate(
                            [
                                'scenario_id' => $activeScenario->id,
                                'sector_id' => $sectorsMap[$sectorCode]->id,
                            ],
                            [
                                'impact_score' => $data['impact_score'],
                                'resilience_status' => $data['resilience_status'],
                                'reasoning' => $data['reasoning'],
                                'score_breakdown' => $data['score_breakdown'] ?? null,
                                'vulnerable_companies' => $data['vulnerable_companies'] ?? [],
                                'beneficiary_companies' => $data['beneficiary_companies'] ?? [],
                            ]
                        );
                    }
                }

                $narrativeSummary = [
                    'executive_summary' => $generated['executive_summary'] ?? 'Analisis transmisi makro terhadap 11 sektor di Bursa Efek Indonesia.',
                    'key_takeaway' => $generated['key_takeaway'] ?? 'Pantau ketat emiten dengan rasio DER tinggi dan eksposur bahan baku impor.',
                ];

                $sectorResults = MacroAnalysisResult::with(['sector.companies'])
                    ->where('scenario_id', $activeScenario->id)
                    ->get();
            } else {
                $sectorResults = $existingResults;
                $narrativeSummary = $this->synthesizeNarrative($activeScenario, $existingResults);
            }
        }

        // Sort sectors: Highest impact score first
        $sortedSectors = $sectorResults->sortByDesc('impact_score')->values();

        // Calculate quantitative market aggregates
        $avgMarketScore = $sortedSectors->isNotEmpty()
            ? round($sortedSectors->avg('impact_score'), 1)
            : 0.0;

        $resilientSectors = $sortedSectors->where('impact_score', '>', 0)->values();
        $vulnerableSectors = $sortedSectors->where('impact_score', '<', 0)->values();
        $neutralSectors = $sortedSectors->where('impact_score', '==', 0)->values();

        // Institutional stance based on market aggregate score
        $marketStance = match (true) {
            $avgMarketScore >= 2.0 => [
                'label' => 'Net Bullish / Risk-On',
                'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                'description' => 'Guncangan makro memberikan efek akselerasi laba pada mayoritas sektor berbobot besar di IHSG.',
            ],
            $avgMarketScore <= -2.0 => [
                'label' => 'Net Defensive / Risk-Off',
                'badge' => 'bg-rose-100 text-rose-800 border-rose-300',
                'description' => 'Tekanan transmisi makro sistemik menyempitkan marjin operasi dan meningkatkan risiko beban utang emiten.',
            ],
            default => [
                'label' => 'Bifurcated / Market Neutral',
                'badge' => 'bg-amber-100 text-amber-800 border-amber-300',
                'description' => 'Dampak asimetris terbelah tajam; peluang rotasi sektoral selektif lebih dominan daripada tren pasar agregat.',
            ],
        };

        // Tactical Asset Allocation (Overweight, Neutral, Underweight)
        $tacticalAllocation = [
            'overweight' => $sortedSectors->where('impact_score', '>=', 3)->values(),
            'neutral' => $sortedSectors->filter(fn ($s) => $s->impact_score >= -2 && $s->impact_score <= 2)->values(),
            'underweight' => $sortedSectors->where('impact_score', '<=', -3)->values(),
        ];

        // Report ID & Metadata
        $reportId = 'MS-IRB-'.date('Ymd').'-'.strtoupper(substr(md5(($activeScenario->id ?? '1').$activeScenario?->title), 0, 4));
        $generatedAt = now()->locale('id')->isoFormat('D MMMM Y, HH:mm [WIB]');

        return view('report.tear-sheet', [
            'presetScenarios' => $presetScenarios,
            'recentCustomScenarios' => $recentCustomScenarios,
            'activeScenario' => $activeScenario,
            'sortedSectors' => $sortedSectors,
            'narrativeSummary' => $narrativeSummary,
            'avgMarketScore' => $avgMarketScore,
            'resilientCount' => $resilientSectors->count(),
            'vulnerableCount' => $vulnerableSectors->count(),
            'neutralCount' => $neutralSectors->count(),
            'marketStance' => $marketStance,
            'tacticalAllocation' => $tacticalAllocation,
            'reportId' => $reportId,
            'generatedAt' => $generatedAt,
        ]);
    }

    /**
     * Synthesize narrative summary if not present from live AI generation.
     */
    protected function synthesizeNarrative(MacroScenario $scenario, Collection $results): array
    {
        $topPositive = $results->sortByDesc('impact_score')->first();
        $topNegative = $results->sortBy('impact_score')->first();

        $topPosName = $topPositive?->sector?->sector_name ?? 'Sektor Terkait';
        $topPosScore = $topPositive?->impact_score ?? 0;
        $topNegName = $topNegative?->sector?->sector_name ?? 'Sektor Terkait';
        $topNegScore = $topNegative?->impact_score ?? 0;

        $posText = $topPosScore > 0
            ? "Peluang outperformance utama terkonsentrasi pada {$topPosName} (Skor +{$topPosScore}) berkat keunggulan penyerapan harga atau pendapatan valas."
            : 'Mayoritas sektor berada dalam posisi defensif atau netral.';

        $negText = $topNegScore < 0
            ? "Sebaliknya, tekanan transmisi paling tajam dirasakan oleh {$topNegName} (Skor {$topNegScore}) akibat ketergantungan bahan baku impor dan sensitivitas solvabilitas utang."
            : 'Tidak terdeteksi sektor dengan risiko penurunan kritis.';

        return [
            'executive_summary' => "Simulasi makro '{$scenario->title}' menghasilkan dampak asimetris terhadap struktur laba dan solvabilitas emiten di Bursa Efek Indonesia. {$posText} {$negText}",
            'key_takeaway' => "Terapkan rotasi taktis dengan menambah alokasi pada {$topPosName} dan mengurangi porsi pada {$topNegName} guna menjaga ketahanan portofolio.",
        ];
    }
}
