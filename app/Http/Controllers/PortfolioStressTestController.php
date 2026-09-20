<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\MacroAnalysisResult;
use App\Models\MacroScenario;
use App\Models\SectorCache;
use App\Services\MacroReasoningService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PortfolioStressTestController extends Controller
{
    /**
     * Display the Portfolio Stress-Test simulator page.
     */
    public function index(Request $request): View
    {
        $allCompanies = Company::with('sector')->orderBy('symbol')->get();
        $presetScenarios = MacroScenario::where('is_preset', true)->get();

        // Handle custom scenario submission or preset selection
        if ($request->filled('custom_scenario')) {
            $text = trim((string) $request->custom_scenario);
            $title = mb_substr($text, 0, 100).(mb_strlen($text) > 100 ? '...' : '');

            $activeScenario = MacroScenario::create([
                'title' => $title,
                'description' => $text,
                'category' => 'geopolitical',
                'is_preset' => false,
            ]);
        } else {
            $scenarioId = $request->input('scenario_id');
            $activeScenario = null;

            if ($scenarioId && $scenarioId !== 'baseline' && $scenarioId !== '0') {
                $activeScenario = MacroScenario::find($scenarioId);
            }

            if (! $activeScenario) {
                $activeScenario = $presetScenarios->first();
            }
        }

        // Fetch sector results for this scenario, or generate dynamically if not present
        $sectorResults = MacroAnalysisResult::with(['sector.companies'])
            ->where('scenario_id', $activeScenario?->id)
            ->get()
            ->keyBy(fn ($r) => $r->sector->sector_code);

        if ($sectorResults->isEmpty() && $activeScenario) {
            $reasoningService = app(MacroReasoningService::class);
            $generated = $reasoningService->analyzeScenario($activeScenario->title, $activeScenario->description);
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

            $sectorResults = MacroAnalysisResult::with(['sector.companies'])
                ->where('scenario_id', $activeScenario->id)
                ->get()
                ->keyBy(fn ($r) => $r->sector->sector_code);
        }

        // Pre-defined portfolio templates for instant demo
        $presetPortfolios = [
            'balanced' => [
                'name' => 'Big-Cap Konservatif (IHSG Blue-Chips)',
                'description' => 'Portofolio defensif dengan kapitalisasi pasar raksasa dan neraca stabil.',
                'items' => [
                    ['symbol' => 'BBCA', 'weight' => 35],
                    ['symbol' => 'BMRI', 'weight' => 25],
                    ['symbol' => 'ASII', 'weight' => 20],
                    ['symbol' => 'ICBP', 'weight' => 20],
                ],
            ],
            'commodity' => [
                'name' => 'Eksportir Energi & Komoditas USD',
                'description' => 'Portofolio berbasis komoditas dan translasi valuta asing USD.',
                'items' => [
                    ['symbol' => 'ADRO', 'weight' => 35],
                    ['symbol' => 'MEDC', 'weight' => 30],
                    ['symbol' => 'PTBA', 'weight' => 20],
                    ['symbol' => 'ANTM', 'weight' => 15],
                ],
            ],
            'domestic_growth' => [
                'name' => 'Konsumsi & Pertumbuhan Domestik',
                'description' => 'Fokus pada konsumsi ritel domestik, infrastruktur digital, dan properti.',
                'items' => [
                    ['symbol' => 'TLKM', 'weight' => 30],
                    ['symbol' => 'MAPI', 'weight' => 25],
                    ['symbol' => 'KLBF', 'weight' => 25],
                    ['symbol' => 'BSDE', 'weight' => 20],
                ],
            ],
        ];

        // Parse user selected holdings from request or default to 'balanced'
        $selectedPresetKey = $request->query('preset', 'balanced');
        $customHoldings = $request->input('holdings');

        $holdings = [];
        if (is_array($customHoldings) && count($customHoldings) > 0) {
            foreach ($customHoldings as $item) {
                if (! empty($item['symbol']) && isset($item['weight'])) {
                    $holdings[] = [
                        'symbol' => strtoupper(trim((string) $item['symbol'])),
                        'weight' => max(1, min(100, (float) $item['weight'])),
                    ];
                }
            }
        } elseif (isset($presetPortfolios[$selectedPresetKey])) {
            $holdings = $presetPortfolios[$selectedPresetKey]['items'];
        } else {
            $holdings = $presetPortfolios['balanced']['items'];
        }

        // Normalize weights so total = 100%
        $totalInputWeight = array_sum(array_column($holdings, 'weight'));
        if ($totalInputWeight > 0 && $totalInputWeight !== 100.0) {
            foreach ($holdings as &$h) {
                $h['weight'] = round(($h['weight'] / $totalInputWeight) * 100, 1);
            }
            unset($h);
        }

        // Analyze portfolio holdings
        $analyzedHoldings = [];
        $portfolioWeightedScore = 0.0;
        $weightedDer = 0.0;
        $weightedNpm = 0.0;

        foreach ($holdings as $holding) {
            $company = $allCompanies->firstWhere('symbol', $holding['symbol']);
            if (! $company) {
                continue;
            }

            $sectorCode = $company->sector?->sector_code ?? '';
            $sectorRes = $sectorResults->get($sectorCode);
            $sectorScore = (float) ($sectorRes->impact_score ?? 0);

            // Compute individualized company score based on sector impact + company DER/NPM sensitivity
            $companyScore = $sectorScore;
            $derDiff = (float) $company->der - (float) ($company->sector?->avg_der ?? 1.0);

            if ($sectorScore < 0 && $derDiff > 0.3) {
                // Higher debt in a suffering sector hurts more
                $companyScore -= 1.0;
            } elseif ($sectorScore > 0 && (float) $company->npm > 15.0) {
                // High margin in a winning sector benefits more
                $companyScore += 0.5;
            }

            $companyScore = max(-10, min(10, round($companyScore, 1)));
            $contribution = ($holding['weight'] / 100.0) * $companyScore;
            $portfolioWeightedScore += $contribution;

            $weightedDer += ($holding['weight'] / 100.0) * (float) $company->der;
            $weightedNpm += ($holding['weight'] / 100.0) * (float) $company->npm;

            $analyzedHoldings[] = [
                'company' => $company,
                'weight' => $holding['weight'],
                'sector_name' => $company->sector?->sector_name ?? 'N/A',
                'sector_code' => $sectorCode,
                'company_score' => $companyScore,
                'weighted_contribution' => round($contribution, 2),
                'der' => (float) $company->der,
                'npm' => (float) $company->npm,
            ];
        }

        $portfolioWeightedScore = round($portfolioWeightedScore, 1);
        $weightedDer = round($weightedDer, 2);
        $weightedNpm = round($weightedNpm, 1);

        // Determine portfolio health status
        if ($portfolioWeightedScore >= 4.0) {
            $portfolioStatus = 'Sangat Tangguh (Resilient)';
            $statusBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
            $verdict = 'Portofolio ini memiliki struktur aset yang selaras dengan guncangan makro saat ini dan berpotensi mencatatkan kinerja di atas rata-rata IHSG (*outperform*).';
        } elseif ($portfolioWeightedScore >= 0.5) {
            $portfolioStatus = 'Tahan Guncangan (Defensive)';
            $statusBadge = 'bg-blue-50 text-blue-700 border-blue-200';
            $verdict = 'Portofolio mampu menyerap transmisi dampak ekonomi makro dengan baik berkat bantalan kas dan defensivitas sektor konstituen.';
        } elseif ($portfolioWeightedScore >= -2.0) {
            $portfolioStatus = 'Netral / Moderat (Mixed)';
            $statusBadge = 'bg-amber-50 text-amber-700 border-amber-200';
            $verdict = 'Terdapat keseimbangan antara saham yang diuntungkan dan saham yang tertekan. Diperlukan penyesuaian bobot selektif.';
        } else {
            $portfolioStatus = 'Rentan Guncangan (High Risk)';
            $statusBadge = 'bg-rose-50 text-rose-700 border-rose-200';
            $verdict = 'Portofolio memiliki eksposur risiko tinggi terhadap skenario ini akibat konsentrasi pada sektor berutang tinggi atau berbiaya impor sensitif.';
        }

        // Sort holdings to identify top protector and top risk drag
        $sortedByScore = collect($analyzedHoldings)->sortByDesc('company_score');
        $topProtector = $sortedByScore->first(fn ($h) => $h['company_score'] > 0);
        $topRiskDrag = $sortedByScore->last(fn ($h) => $h['company_score'] < 0);

        // AI Rebalancing suggestions
        $recommendations = [];
        if ($topRiskDrag) {
            $recommendations[] = [
                'type' => 'reduce',
                'title' => "Pangkas Bobot {$topRiskDrag['company']->symbol} ({$topRiskDrag['sector_name']})",
                'reason' => "Saham ini memberikan kontribusi beban terberat ({$topRiskDrag['weighted_contribution']} poin) akibat sensitivitas sektor dan leverage DER {$topRiskDrag['der']}x.",
                'action' => 'Kurangi alokasi sebesar 5% - 10%.',
            ];
        }

        // Find best resilient sectors in active scenario
        $bestResilientSector = $sectorResults->sortByDesc('impact_score')->first();
        if ($bestResilientSector && $bestResilientSector->impact_score > 2) {
            $recommendations[] = [
                'type' => 'add',
                'title' => "Rotasi Hedging ke Sektor {$bestResilientSector->sector->sector_name}",
                'reason' => "Sektor ini menjadi penerima manfaat utama (Skor +{$bestResilientSector->impact_score}) pada skenario '{$activeScenario?->title}'.",
                'action' => 'Tambahkan saham konstituen seperti '.($bestResilientSector->sector->companies->first()?->symbol ?? 'sektor terkait').' sebagai bantalan lindung nilai (*hedging*).',
            ];
        }

        return view('portfolio', [
            'activeScenario' => $activeScenario,
            'presetScenarios' => $presetScenarios,
            'allCompanies' => $allCompanies,
            'presetPortfolios' => $presetPortfolios,
            'selectedPresetKey' => $selectedPresetKey,
            'analyzedHoldings' => $analyzedHoldings,
            'portfolioScore' => $portfolioWeightedScore,
            'weightedDer' => $weightedDer,
            'weightedNpm' => $weightedNpm,
            'portfolioStatus' => $portfolioStatus,
            'statusBadge' => $statusBadge,
            'verdict' => $verdict,
            'topProtector' => $topProtector,
            'topRiskDrag' => $topRiskDrag,
            'recommendations' => $recommendations,
        ]);
    }
}
