<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\MacroAnalysisResult;
use App\Models\MacroScenario;
use App\Models\SectorCache;
use App\Services\MacroReasoningService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class HeadToHeadDuelController extends Controller
{
    /**
     * Display the Head-to-Head Sector & Stock Duel comparison page.
     */
    public function index(Request $request): View
    {
        $allCompanies = Company::with('sector')->orderBy('symbol')->get();
        $allSectors = SectorCache::with('companies')->orderBy('sector_name')->get();
        $presetScenarios = MacroScenario::where('is_preset', true)->get();

        // Handle custom scenario submission or scenario selection
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
        $sectorResults = MacroAnalysisResult::with('sector')
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

            $sectorResults = MacroAnalysisResult::with('sector')
                ->where('scenario_id', $activeScenario->id)
                ->get()
                ->keyBy(fn ($r) => $r->sector->sector_code);
        }

        // Pre-defined quick duels for instant demonstration
        $presetDuels = [
            'banking' => [
                'name' => 'Bank Swasta vs Bank BUMN',
                'mode' => 'stock',
                'fighter_a' => 'BBCA',
                'fighter_b' => 'BBRI',
                'scenario_hint' => 'Kenaikan Suku Bunga & Likuiditas',
                'description' => 'Adu ketahanan likuiditas CASA rendah biaya milik BBCA melawan penetrasi portofolio kredit UMKM milik BBRI.',
            ],
            'energy' => [
                'name' => 'Batubara vs Minyak Bumi & Gas',
                'mode' => 'stock',
                'fighter_a' => 'ADRO',
                'fighter_b' => 'MEDC',
                'scenario_hint' => 'Lonjakan Komoditas & Pelemahan Kurs',
                'description' => 'Adu cadangan kas melimpah & hilirisasi hijau ADRO melawan sensitivitas harga minyak mentah global dan leverage MEDC.',
            ],
            'consumer' => [
                'name' => 'Big FMCG: Mi Instan vs Biskuit Global',
                'mode' => 'stock',
                'fighter_a' => 'ICBP',
                'fighter_b' => 'MYOR',
                'scenario_hint' => 'Inflasi Pangan & Tekanan Impor Gandum',
                'description' => 'Adu pricing power defensif Indofood CBP melawan kekuatan ekspor consumer goods Mayora.',
            ],
            'sector_energy_transport' => [
                'name' => 'Sektor Energi vs Sektor Transportasi',
                'mode' => 'sector',
                'fighter_a' => 'idx-energy',
                'fighter_b' => 'idx-transportation',
                'scenario_hint' => 'Lonjakan Harga Energi Global',
                'description' => 'Pertarungan antara sektor penerima durian runtuh kenaikan harga energi melawan sektor yang tercekik biaya bahan bakar.',
            ],
            'sector_finance_property' => [
                'name' => 'Sektor Keuangan vs Sektor Properti',
                'mode' => 'sector',
                'fighter_a' => 'idx-financials',
                'fighter_b' => 'idx-properties',
                'scenario_hint' => 'Kenaikan Suku Bunga Acuan BI',
                'description' => 'Adu sektor perbankan dengan NIM tinggi melawan sektor properti yang sensitif terhadap beban KPR dan utang pengembang.',
            ],
        ];

        $mode = $request->input('mode', 'stock');
        if (! in_array($mode, ['stock', 'sector'])) {
            $mode = 'stock';
        }

        // Apply preset duel if selected
        $selectedPreset = $request->input('preset');
        if ($selectedPreset && isset($presetDuels[$selectedPreset])) {
            $presetConfig = $presetDuels[$selectedPreset];
            $mode = $presetConfig['mode'];
            $fighterA_key = $presetConfig['fighter_a'];
            $fighterB_key = $presetConfig['fighter_b'];
        } else {
            $fighterA_key = $request->input('fighter_a', $mode === 'stock' ? 'BBCA' : 'idx-energy');
            $fighterB_key = $request->input('fighter_b', $mode === 'stock' ? 'BBRI' : 'idx-transportation');
        }

        // Build Fighter A and Fighter B data depending on mode
        if ($mode === 'stock') {
            $fighterA = $this->buildStockFighter($fighterA_key, $allCompanies, $sectorResults);
            $fighterB = $this->buildStockFighter($fighterB_key, $allCompanies, $sectorResults);

            // Fallback if fighter A and B are the same
            if ($fighterA['symbol'] === $fighterB['symbol']) {
                $fallback = $allCompanies->first(fn ($c) => $c->symbol !== $fighterA['symbol']);
                if ($fallback) {
                    $fighterB = $this->buildStockFighter($fallback->symbol, $allCompanies, $sectorResults);
                }
            }
        } else {
            $fighterA = $this->buildSectorFighter($fighterA_key, $allSectors, $sectorResults);
            $fighterB = $this->buildSectorFighter($fighterB_key, $allSectors, $sectorResults);

            // Fallback if sector A and B are the same
            if ($fighterA['code'] === $fighterB['code']) {
                $fallback = $allSectors->first(fn ($s) => $s->sector_code !== $fighterA['code']);
                if ($fallback) {
                    $fighterB = $this->buildSectorFighter($fallback->sector_code, $allSectors, $sectorResults);
                }
            }
        }

        // Determine Match Outcome
        $scoreA = $fighterA['score'];
        $scoreB = $fighterB['score'];
        $diff = round(abs($scoreA - $scoreB), 1);

        if ($scoreA > $scoreB) {
            $winner = $fighterA;
            $loser = $fighterB;
            $verdict = 'winner_a';
        } elseif ($scoreB > $scoreA) {
            $winner = $fighterB;
            $loser = $fighterA;
            $verdict = 'winner_b';
        } else {
            $winner = null;
            $loser = null;
            $verdict = 'draw';
        }

        // Comparative Analysis & Pair Trading Synthesis
        $pairTradingThesis = $this->generatePairTradingThesis($fighterA, $fighterB, $winner, $loser, $diff, $activeScenario, $mode);

        return view('duel', [
            'mode' => $mode,
            'activeScenario' => $activeScenario,
            'presetScenarios' => $presetScenarios,
            'allCompanies' => $allCompanies,
            'allSectors' => $allSectors,
            'presetDuels' => $presetDuels,
            'selectedPreset' => $selectedPreset,
            'fighterA' => $fighterA,
            'fighterB' => $fighterB,
            'winner' => $winner,
            'loser' => $loser,
            'verdict' => $verdict,
            'scoreDiff' => $diff,
            'pairTradingThesis' => $pairTradingThesis,
        ]);
    }

    /**
     * Build normalized stock fighter data structure.
     *
     * @param  Collection<int, Company>  $allCompanies
     * @param  Collection<string, MacroAnalysisResult>  $sectorResults
     * @return array<string, mixed>
     */
    private function buildStockFighter(string $symbol, $allCompanies, $sectorResults): array
    {
        $company = $allCompanies->firstWhere('symbol', $symbol) ?? $allCompanies->first();
        $sectorCode = $company->sector?->sector_code ?? '';
        $sectorResult = $sectorResults->get($sectorCode);

        $sectorScore = (float) ($sectorResult->impact_score ?? 0);
        $der = (float) ($company->der ?? 1.0);
        $npm = (float) ($company->npm ?? 10.0);

        // Individual adjustment
        $companyScore = $sectorScore;
        $derDiff = $der - (float) ($company->sector?->avg_der ?? 1.0);

        if ($sectorScore < 0 && $derDiff > 0.3) {
            $companyScore -= 1.0;
        } elseif ($sectorScore > 0 && $npm > 15.0) {
            $companyScore += 0.5;
        }
        $companyScore = max(-10, min(10, round($companyScore, 1)));

        // Pillar scores based on breakdown + metrics
        $breakdown = $sectorResult?->score_breakdown ?? [];
        $revImpact = (int) ($breakdown['revenue_impact'] ?? ($sectorScore > 0 ? 3 : -3));
        $costImpact = (int) ($breakdown['cost_impact'] ?? ($sectorScore > 0 ? 2 : -3));
        $balImpact = (int) ($breakdown['balance_sheet_impact'] ?? ($der > 1.2 ? -3 : 2));

        return [
            'type' => 'stock',
            'symbol' => $company->symbol,
            'name' => $company->name,
            'sector_name' => $company->sector?->sector_name ?? 'Umum',
            'sector_code' => $sectorCode,
            'score' => $companyScore,
            'sector_score' => $sectorScore,
            'der' => $der,
            'npm' => $npm,
            'market_cap' => (int) ($company->market_cap ?? 0),
            'pe_ratio' => (float) ($company->pe_ratio ?? 0),
            'pbv_ratio' => (float) ($company->pbv_ratio ?? 0),
            'revenue_impact' => $revImpact,
            'revenue_note' => $breakdown['revenue_note'] ?? 'Dampak pendapatan sejalan dengan transmisi sektor konstituen.',
            'cost_impact' => $costImpact,
            'cost_note' => $breakdown['cost_note'] ?? 'Sensitivitas beban input dan marjin operasional.',
            'balance_sheet_impact' => $balImpact,
            'balance_sheet_note' => $breakdown['balance_sheet_note'] ?? 'Ketahanan utang (DER: '.$der.'x).',
            'raw' => $company,
        ];
    }

    /**
     * Build normalized sector fighter data structure.
     *
     * @param  Collection<int, SectorCache>  $allSectors
     * @param  Collection<string, MacroAnalysisResult>  $sectorResults
     * @return array<string, mixed>
     */
    private function buildSectorFighter(string $sectorCode, $allSectors, $sectorResults): array
    {
        $sector = $allSectors->firstWhere('sector_code', $sectorCode) ?? $allSectors->first();
        $code = $sector->sector_code;
        $sectorResult = $sectorResults->get($code);

        $impactScore = (float) ($sectorResult->impact_score ?? 0);
        $avgDer = (float) ($sector->avg_der ?? 1.1);
        $avgNpm = (float) ($sector->avg_npm ?? 12.0);

        $breakdown = $sectorResult?->score_breakdown ?? [];

        return [
            'type' => 'sector',
            'code' => $code,
            'symbol' => strtoupper(str_replace('idx-', '', $code)),
            'name' => $sector->sector_name,
            'sector_name' => $sector->sector_name,
            'sector_code' => $code,
            'score' => $impactScore,
            'sector_score' => $impactScore,
            'der' => $avgDer,
            'npm' => $avgNpm,
            'market_cap' => (int) ($sector->market_cap ?? 0),
            'pe_ratio' => (float) ($sector->avg_pe ?? 0),
            'pbv_ratio' => (float) ($sector->avg_pbv ?? 0),
            'company_count' => $sector->companies->count(),
            'revenue_impact' => (int) ($breakdown['revenue_impact'] ?? ($impactScore > 0 ? 3 : -3)),
            'revenue_note' => $breakdown['revenue_note'] ?? 'Dampak agregat penjualan sektor.',
            'cost_impact' => (int) ($breakdown['cost_impact'] ?? ($impactScore > 0 ? 2 : -3)),
            'cost_note' => $breakdown['cost_note'] ?? 'Tekanan biaya bahan baku & operasional.',
            'balance_sheet_impact' => (int) ($breakdown['balance_sheet_impact'] ?? ($avgDer > 1.2 ? -3 : 2)),
            'balance_sheet_note' => $breakdown['balance_sheet_note'] ?? 'Ketahanan utang agregat sektor.',
            'reasoning' => $sectorResult?->reasoning ?? 'Analisis transmisi makro terhadap sektor ini.',
            'raw' => $sector,
        ];
    }

    /**
     * Generate pair-trading thesis and comparative explanation.
     *
     * @param  array<string, mixed>  $fighterA
     * @param  array<string, mixed>  $fighterB
     * @param  array<string, mixed>|null  $winner
     * @param  array<string, mixed>|null  $loser
     * @return array<string, string>
     */
    private function generatePairTradingThesis(
        array $fighterA,
        array $fighterB,
        ?array $winner,
        ?array $loser,
        float $diff,
        ?MacroScenario $scenario,
        string $mode
    ): array {
        $scenarioTitle = $scenario?->title ?? 'Guncangan Makro';

        if (! $winner || ! $loser) {
            return [
                'headline' => 'Kekuatan Berimbang (Market Neutral)',
                'thesis' => "Kedua entitas ({$fighterA['name']} dan {$fighterB['name']}) memiliki daya tahan dan sensitivitas yang seimbang terhadap skenario '{$scenarioTitle}'. Tidak ada keunggulan alfa yang signifikan di antara keduanya.",
                'pair_action' => 'Netral / Standby: Pertahankan alokasi proporsional tanpa pembobotan ekstrem.',
            ];
        }

        $winnerName = $winner['symbol'] ?? $winner['name'];
        $loserName = $loser['symbol'] ?? $loser['name'];

        $reasonParts = [];
        if ($winner['score'] > 0 && $loser['score'] <= 0) {
            $reasonParts[] = "{$winnerName} berada pada jalur penerima manfaat transmisi ekonomi makro (Skor {$winner['score']}), sementara {$loserName} mengalami tekanan negatif (Skor {$loser['score']}).";
        }

        if ($winner['der'] < $loser['der']) {
            $reasonParts[] = "Struktur solvabilitas {$winnerName} jauh lebih aman dengan rasio DER {$winner['der']}x berbanding {$loser['der']}x milik {$loserName}, sehingga risiko lonjakan biaya bunga jauh lebih rendah.";
        }

        if ($winner['npm'] > $loser['npm']) {
            $reasonParts[] = "Tingkat profitabilitas {$winnerName} (NPM {$winner['npm']}%) menyediakan bantalan kas penyerap inflasi yang lebih tebal dibandingkan {$loserName} (NPM {$loser['npm']}%).";
        }

        $explanation = count($reasonParts) > 0
            ? implode(' Selain itu, ', $reasonParts)
            : "{$winnerName} mencatatkan resiliensi lebih unggul dengan selisih skor ketahanan sebesar +{$diff} poin.";

        return [
            'headline' => "Pair Strategy: Long {$winnerName} / Short or Underweight {$loserName}",
            'thesis' => "Pada skenario '{$scenarioTitle}', {$explanation}",
            'pair_action' => "Overweight {$winnerName} sebagai jangkar ketahanan portofolio, dan kurangi eksposur pada {$loserName} untuk meminimalisasi volatilitas.",
        ];
    }
}
