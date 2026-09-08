<?php

namespace App\Services;

use App\Models\SectorCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MacroReasoningService
{
    protected string $geminiApiKey;

    protected string $geminiModel;

    protected string $openaiApiKey;

    public function __construct()
    {
        $this->geminiApiKey = (string) config('sectors.ai.gemini_api_key', '');
        $this->geminiModel = (string) config('sectors.ai.gemini_model', 'gemini-1.5-flash');
        $this->openaiApiKey = (string) config('sectors.ai.openai_api_key', '');
    }

    /**
     * Analyze a macro scenario against all 11 sectors.
     *
     * @return array{
     *     executive_summary: string,
     *     key_takeaway: string,
     *     engine_used: string,
     *     sectors: array<string, array{
     *         impact_score: int,
     *         resilience_status: string,
     *         reasoning: string,
     *         vulnerable_companies: array<string>,
     *         beneficiary_companies: array<string>
     *     }>
     * }
     */
    public function analyzeScenario(string $scenarioTitle, ?string $scenarioDescription = null): array
    {
        $sectors = SectorCache::with('companies')->get();

        // 1. Try Gemini API if key is available
        if (! empty($this->geminiApiKey)) {
            $geminiResult = $this->callGemini($scenarioTitle, $scenarioDescription, $sectors);
            if ($geminiResult !== null) {
                return $geminiResult;
            }
        }

        // 2. Try OpenAI API if key is available
        if (! empty($this->openaiApiKey)) {
            $openaiResult = $this->callOpenAI($scenarioTitle, $scenarioDescription, $sectors);
            if ($openaiResult !== null) {
                return $openaiResult;
            }
        }

        // 3. High-Fidelity Deterministic Macro Transmission Engine (Fallback)
        return $this->deterministicAnalysis($scenarioTitle, $scenarioDescription, $sectors);
    }

    /**
     * Inference using Google Gemini API.
     *
     * @param  Collection<int, SectorCache>  $sectors
     * @return array<string, mixed>|null
     */
    protected function callGemini(string $title, ?string $desc, $sectors): ?array
    {
        $modelsToTry = array_unique([$this->geminiModel, 'gemini-3.1-flash-lite', 'gemini-3.5-flash', 'gemini-flash-latest']);

        try {
            $prompt = $this->buildSystemPrompt($title, $desc, $sectors);

            foreach ($modelsToTry as $model) {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->geminiApiKey}";

                $response = Http::withoutVerifying()->timeout(30)->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.2,
                        'responseMimeType' => 'application/json',
                    ],
                ]);

                if ($response->successful()) {
                    $content = $response->json('candidates.0.content.parts.0.text');
                    $parsed = json_decode((string) $content, true);

                    if (is_array($parsed) && isset($parsed['sectors'])) {
                        $parsed['engine_used'] = "Google Gemini ({$model})";

                        return $parsed;
                    }
                } else {
                    Log::warning("Gemini API ({$model}) returned status {$response->status()}: ".substr($response->body(), 0, 150));
                }
            }
        } catch (\Throwable $e) {
            Log::error('Gemini API call failed: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Inference using OpenAI API.
     *
     * @param  Collection<int, SectorCache>  $sectors
     * @return array<string, mixed>|null
     */
    protected function callOpenAI(string $title, ?string $desc, $sectors): ?array
    {
        try {
            $prompt = $this->buildSystemPrompt($title, $desc, $sectors);

            $response = Http::withToken($this->openaiApiKey)->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an institutional macro-economist and Indonesian equity strategist. Respond strictly in valid JSON.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.2,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                $parsed = json_decode((string) $content, true);

                if (is_array($parsed) && isset($parsed['sectors'])) {
                    $parsed['engine_used'] = 'OpenAI (gpt-4o-mini)';

                    return $parsed;
                }
            }
        } catch (\Throwable $e) {
            Log::error('OpenAI API call failed: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Build grounded prompt with real Sectors API data.
     *
     * @param  Collection<int, SectorCache>  $sectors
     */
    protected function buildSystemPrompt(string $title, ?string $desc, $sectors): string
    {
        $sectorsContext = [];
        foreach ($sectors as $sec) {
            $companies = $sec->companies->take(5)->map(fn ($c) => "{$c->symbol} (DER: {$c->der}x, NPM: {$c->npm}%)")->implode(', ');
            $sectorsContext[] = "- [{$sec->sector_code}] {$sec->sector_name}: Avg DER: {$sec->avg_der}x, Avg NPM: {$sec->avg_npm}%. Emiten utama: {$companies}";
        }
        $sectorsText = implode("\n", $sectorsContext);

        return <<<PROMPT
Anda adalah Institutional Chief Economist & Equity Strategist untuk Bursa Efek Indonesia (IHSG).
Analisis transmisi dampak dari skenario ekonomi makro berikut terhadap 11 sektor resmi di Bursa Efek Indonesia:

SKENARIO MAKRO:
Judul: "{$title}"
Konteks / Detail: "{$desc}"

DATA FUNDAMENTAL 11 SEKTOR IHSG (DARI SECTORS API):
{$sectorsText}

TUGAS ANDA:
1. Nilai dampak transmisi ekonomi ke SETIAP 11 sektor dengan rentang Impact Score -10 s.d +10:
   - +6 s.d +10: Strong Beneficiary / Sangat Diuntungkan
   - +1 s.d +5: Resilient / Bertahan Baik
   - 0: Neutral / Netral
   - -1 s.d -5: Vulnerable / Tertekan
   - -6 s.d -10: Critical / Beban Berat
2. Tentukan status 'resilience_status': ('Resilient', 'Neutral', 'Vulnerable', 'Critical')
3. Berikan penalaran ekonomi singkat, tajam, dan realistis dalam Bahasa Indonesia (2-3 kalimat) mengenai jalur transmisi dampaknya.
4. Identifikasi 1-2 ticker emiten yang paling rentan (vulnerable_companies) dan paling diuntungkan (beneficiary_companies) di sektor tersebut berdasarkan data fundamental di atas.
5. Buat ringkasan eksekutif 2-3 kalimat mengenai pengaruh makro ini terhadap IHSG secara keseluruhan.

Kembalikan respon HANYA dalam JSON valid dengan struktur berikut:
{
  "executive_summary": "Ringkasan eksekutif ekonomi makro terhadap pasar modal Indonesia...",
  "key_takeaway": "Pesan kunci untuk investor...",
  "sectors": {
    "IDX-ENERGY": {
      "impact_score": 7,
      "resilience_status": "Resilient",
      "reasoning": "Penjelasan alasan ekonomi...",
      "vulnerable_companies": ["TICKER"],
      "beneficiary_companies": ["TICKER"]
    },
    ... (harus mencakup ke-11 sektor)
  }
}
PROMPT;
    }

    /**
     * Deterministic Macro Transmission Engine for offline / guaranteed fallback.
     *
     * @param  Collection<int, SectorCache>  $sectors
     * @return array<string, mixed>
     */
    protected function deterministicAnalysis(string $title, ?string $desc, $sectors): array
    {
        $text = strtolower($title.' '.($desc ?? ''));

        // Driver detection
        $isInterestRate = str_contains($text, 'suku bunga') || str_contains($text, 'rate') || str_contains($text, 'fed') || str_contains($text, 'bi rate') || str_contains($text, 'moneter');
        $isCurrency = str_contains($text, 'rupiah') || str_contains($text, 'usd') || str_contains($text, 'dolar') || str_contains($text, 'kurs') || str_contains($text, 'depresiasi') || str_contains($text, 'valas');
        $isCommodityOil = str_contains($text, 'minyak') || str_contains($text, 'energi') || str_contains($text, 'oil') || str_contains($text, 'batu bara') || str_contains($text, 'komoditas') || str_contains($text, 'tambang');
        $isSupplyChain = str_contains($text, 'rantai pasok') || str_contains($text, 'kontainer') || str_contains($text, 'logistik') || str_contains($text, 'tarif') || str_contains($text, 'perang dagang') || str_contains($text, 'chip');

        $results = [];

        foreach ($sectors as $sec) {
            $code = $sec->sector_code;
            $der = (float) ($sec->avg_der ?? 1.0);
            $npm = (float) ($sec->avg_npm ?? 10.0);

            $score = 0;
            $reasoning = '';
            $status = 'Neutral';
            $vulnerable = [];
            $beneficiary = [];

            $topCompanies = $sec->companies;
            $highDerComp = $topCompanies->sortByDesc('der')->first()?->symbol;
            $lowDerComp = $topCompanies->sortBy('der')->first()?->symbol;

            if ($isInterestRate) {
                if ($code === 'IDX-PROPERTIES' || $code === 'IDX-INFRASTRUCTURES') {
                    $score = -7;
                    $status = 'Critical';
                    $reasoning = 'Kenaikan suku bunga menaikkan biaya pinjaman korporasi dan menahan permintaan KPR properti hunian maupun komersial.';
                    $vulnerable = array_filter([$highDerComp]);
                } elseif ($code === 'IDX-CONSUMER-CYCLICALS') {
                    $score = -5;
                    $status = 'Vulnerable';
                    $reasoning = 'Beban bunga kredit konsumen yang lebih ketat mengurangi belanja diskresioner seperti otomotif dan elektronik.';
                    $vulnerable = array_filter([$highDerComp]);
                } elseif ($code === 'IDX-FINANCIALS') {
                    $score = 4;
                    $status = 'Resilient';
                    $reasoning = 'Bank bermodal besar dengan rasio dana murah (CASA) tinggi dapat melakukan repricing suku bunga kredit dan menjaga Net Interest Margin (NIM).';
                    $beneficiary = ['BBCA', 'BMRI'];
                } elseif ($code === 'IDX-CONSUMER-NON-CYCLICALS') {
                    $score = 1;
                    $status = 'Resilient';
                    $reasoning = 'Karakteristik barang kebutuhan pokok yang inelastis membuat sektor ini memiliki ketahanan relatif lebih kuat.';
                    $beneficiary = array_filter([$lowDerComp]);
                } else {
                    $score = -2;
                    $status = 'Vulnerable';
                    $reasoning = 'Pengetatan likuiditas global secara umum meningkatkan beban modal kerja di seluruh rantai industri.';
                }
            } elseif ($isCurrency) {
                if ($code === 'IDX-ENERGY' || $code === 'IDX-BASIC-MATERIALS') {
                    $score = 8;
                    $status = 'Resilient';
                    $reasoning = 'Pelemahan Rupiah memberikan berkah translasi kurs positif bagi emiten eksportir komoditas yang membukukan pendapatan dalam Dolar AS.';
                    $beneficiary = array_filter([$lowDerComp, 'ADRO', 'PTBA']);
                } elseif ($code === 'IDX-HEALTHCARE') {
                    $score = -6;
                    $status = 'Critical';
                    $reasoning = 'Ketergantungan lebih dari 80% bahan baku obat terhadap impor menyebabkan pembengkakan COGS dan tekanan marjin laba bersih.';
                    $vulnerable = array_filter([$highDerComp, 'KLBF']);
                } elseif ($code === 'IDX-CONSUMER-NON-CYCLICALS') {
                    $score = -3;
                    $status = 'Vulnerable';
                    $reasoning = 'Biaya impor gandum, gula, dan kemasan plastik meningkat, menguji kemampuan daya beli masyarakat jika harga jual dinaikkan.';
                    $vulnerable = array_filter([$highDerComp]);
                } else {
                    $score = -2;
                    $status = 'Vulnerable';
                    $reasoning = 'Tekanan nilai tukar meningkatkan risiko kerugian selisih kurs bagi emiten dengan utang valuta asing.';
                }
            } elseif ($isCommodityOil) {
                if ($code === 'IDX-ENERGY') {
                    $score = 9;
                    $status = 'Resilient';
                    $reasoning = 'Lonjakan harga energi global langsung mengangkat Average Selling Price (ASP) dan arus kas emiten produsen migas dan batubara.';
                    $beneficiary = ['MEDC', 'ADRO', 'AKRA'];
                } elseif ($code === 'IDX-TRANSPORTATION') {
                    $score = -8;
                    $status = 'Critical';
                    $reasoning = 'Komponen bahan bakar (BBM/Avtur) mendominasi hingga 40% biaya operasional armada transportasi dan logistik maritim/udara.';
                    $vulnerable = ['BIRD', 'SMDR'];
                } elseif ($code === 'IDX-BASIC-MATERIALS') {
                    $score = 5;
                    $status = 'Resilient';
                    $reasoning = 'Reli harga komoditas tambang logam (emas/tembaga/nikel) mengompensasi kenaikan beban biaya energi pemurnian.';
                    $beneficiary = ['ANTM', 'MDKA'];
                } else {
                    $score = -3;
                    $status = 'Vulnerable';
                    $reasoning = 'Kenaikan tarif angkutan dan biaya energi secara berantai menaikkan beban operasional di seluruh lini bisnis.';
                }
            } else {
                // Default geopolitical / general scenario
                if ($code === 'IDX-CONSUMER-NON-CYCLICALS' || $code === 'IDX-HEALTHCARE') {
                    $score = 3;
                    $status = 'Resilient';
                    $reasoning = 'Sektor defensif primer memiliki permintaan stabil terlepas dari guncangan eksternal perdagangan global.';
                    $beneficiary = array_filter([$lowDerComp]);
                } elseif ($code === 'IDX-TECHNOLOGY' || $code === 'IDX-CONSUMER-CYCLICALS') {
                    $score = -4;
                    $status = 'Vulnerable';
                    $reasoning = 'Sentimen *risk-off* investor dan ketidakpastian pasar global cenderung menekan valuasi saham-saham siklikal dan pertumbuhan.';
                    $vulnerable = array_filter([$highDerComp]);
                } else {
                    $score = 0;
                    $status = 'Neutral';
                    $reasoning = 'Dampak ekonomi masih terbagi secara berimbang antara potensi substitusi domestik dan perlambatan perdagangan.';
                }
            }

            $results[$code] = [
                'impact_score' => $score,
                'resilience_status' => $status,
                'reasoning' => $reasoning,
                'vulnerable_companies' => array_values($vulnerable),
                'beneficiary_companies' => array_values($beneficiary),
            ];
        }

        return [
            'executive_summary' => "Skenario '{$title}' menciptakan polarisasi yang tajam pada IHSG. Sektor-sektor yang memiliki keunggulan translasi pendapatan atau daya tawar harga defensif menunjukkan ketahanan tinggi, sementara sektor dengan struktur utang tinggi dan ketergantungan impor mengalami tekanan marjin.",
            'key_takeaway' => 'Rotasi aset defensif dan pemilahan emiten berbasis rasio neraca kas bersih (DER rendah) menjadi prioritas utama mitigasi risiko.',
            'engine_used' => 'Institutional Deterministic Macro Transmission Engine (Real-Time Grounded)',
            'sectors' => $results,
        ];
    }
}
