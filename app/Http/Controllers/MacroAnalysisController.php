<?php

namespace App\Http\Controllers;

use App\Models\MacroAnalysisResult;
use App\Models\MacroScenario;
use App\Models\SectorCache;
use App\Services\MacroReasoningService;
use App\Services\SectorsApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class MacroAnalysisController extends Controller
{
    public function __construct(
        protected MacroReasoningService $reasoningService,
        protected SectorsApiService $sectorsApiService
    ) {}

    /**
     * Display the main MacroSectors AI dashboard.
     */
    public function index(Request $request): View
    {
        $presetScenarios = MacroScenario::where('is_preset', true)->get();
        $recentCustomScenarios = MacroScenario::where('is_preset', false)->latest()->take(5)->get();

        // Determine current active scenario
        $scenarioId = $request->query('scenario_id');
        $activeScenario = null;

        if ($scenarioId) {
            $activeScenario = MacroScenario::find($scenarioId);
        }

        if (! $activeScenario) {
            $activeScenario = $presetScenarios->first();
        }

        $analysisData = null;
        $sectorResults = [];

        if ($activeScenario) {
            // Check if analysis results already exist in database
            $existingResults = MacroAnalysisResult::with(['sector.companies'])
                ->where('scenario_id', $activeScenario->id)
                ->get();

            if ($existingResults->isEmpty()) {
                // Generate analysis and save to database
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
                                'vulnerable_companies' => $data['vulnerable_companies'] ?? [],
                                'beneficiary_companies' => $data['beneficiary_companies'] ?? [],
                            ]
                        );
                    }
                }

                $analysisData = [
                    'executive_summary' => $generated['executive_summary'],
                    'key_takeaway' => $generated['key_takeaway'],
                    'engine_used' => $generated['engine_used'],
                ];
            } else {
                $analysisData = [
                    'executive_summary' => "Analisis dampak makroekonomi untuk skenario '{$activeScenario->title}'. Menyoroti ketahanan fundamental emiten berbasis data Sectors API.",
                    'key_takeaway' => 'Gunakan data rasio solvabilitas (DER) dan marjin laba (NPM) untuk menentukan alokasi portofolio.',
                    'engine_used' => 'MacroSectors AI Intelligence Engine (Cached)',
                ];
            }

            // Fetch structured results for display
            $sectorResults = MacroAnalysisResult::with(['sector.companies'])
                ->where('scenario_id', $activeScenario->id)
                ->get()
                ->sortByDesc('impact_score');
        }

        $sectorsPayload = [];
        if (isset($sectorResults) && $sectorResults instanceof Collection) {
            foreach ($sectorResults as $r) {
                $sectorsPayload[$r->sector->sector_code] = [
                    'code' => $r->sector->sector_code,
                    'name' => $r->sector->sector_name,
                    'score' => $r->impact_score,
                    'status' => $r->resilience_status,
                    'reasoning' => $r->reasoning,
                    'avg_der' => (float) $r->sector->avg_der,
                    'avg_npm' => (float) $r->sector->avg_npm,
                    'vulnerable_companies' => $r->vulnerable_companies ?? [],
                    'beneficiary_companies' => $r->beneficiary_companies ?? [],
                    'companies' => $r->sector->companies->map(fn ($c) => [
                        'id' => $c->id,
                        'symbol' => $c->symbol,
                        'name' => $c->name,
                        'market_cap' => $c->market_cap,
                        'pe_ratio' => $c->pe_ratio,
                        'pbv_ratio' => $c->pbv_ratio,
                        'der' => $c->der,
                        'npm' => $c->npm,
                    ])->values()->toArray(),
                ];
            }
        }

        $allSectors = SectorCache::with('companies')->get();
        $isSectorsApiConfigured = $this->sectorsApiService->isConfigured();

        return view('dashboard', [
            'presetScenarios' => $presetScenarios,
            'recentCustomScenarios' => $recentCustomScenarios,
            'activeScenario' => $activeScenario,
            'analysisData' => $analysisData,
            'sectorResults' => $sectorResults,
            'sectorsJson' => json_encode($sectorsPayload),
            'allSectors' => $allSectors,
            'isSectorsApiConfigured' => $isSectorsApiConfigured,
        ]);
    }

    /**
     * Handle submission of new scenario (custom or preset selection).
     */
    public function analyze(Request $request): RedirectResponse
    {
        $request->validate([
            'scenario_id' => ['nullable', 'exists:macro_scenarios,id'],
            'custom_scenario' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($request->filled('scenario_id')) {
            return redirect()->route('dashboard', ['scenario_id' => $request->scenario_id]);
        }

        if ($request->filled('custom_scenario')) {
            $text = trim((string) $request->custom_scenario);
            $title = mb_substr($text, 0, 100).(mb_strlen($text) > 100 ? '...' : '');

            $scenario = MacroScenario::create([
                'title' => $title,
                'description' => $text,
                'category' => 'geopolitical',
                'is_preset' => false,
            ]);

            return redirect()->route('dashboard', ['scenario_id' => $scenario->id]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Trigger manual sync with Sectors API.
     */
    public function syncSectors(): RedirectResponse
    {
        $result = $this->sectorsApiService->syncAll();

        return redirect()->route('dashboard')->with(
            $result['success'] ? 'success' : 'info',
            $result['message']
        );
    }
}
