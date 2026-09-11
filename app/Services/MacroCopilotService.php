<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MacroCopilotService
{
    protected string $geminiApiKey;

    protected string $geminiModel;

    public function __construct()
    {
        $this->geminiApiKey = (string) config('sectors.ai.gemini_api_key', '');
        $this->geminiModel = (string) config('sectors.ai.gemini_model', 'gemini-3.1-flash-lite');
    }

    /**
     * Process a macro copilot query and return structured analysis.
     *
     * @param  array<int, array{role: string, content: string}>  $history
     * @return array{
     *     answer: string,
     *     engine: string,
     *     suggested_followups: array<int, string>
     * }
     */
    public function ask(string $message, ?string $page = null, array $history = []): array
    {
        // 1. Attempt Google Gemini AI Studio if API key is present
        if (! empty($this->geminiApiKey)) {
            $geminiResult = $this->callGemini($message, $page, $history);
            if ($geminiResult !== null) {
                return $geminiResult;
            }
        }

        // 2. High-Fidelity Deterministic Macro Transmission Engine Fallback
        return $this->deterministicAnalysis($message, $page);
    }

    /**
     * Query Google Gemini API for macro intelligence.
     *
     * @param  array<int, array{role: string, content: string}>  $history
     * @return array{
     *     answer: string,
     *     engine: string,
     *     suggested_followups: array<int, string>
     * }|null
     */
    protected function callGemini(string $message, ?string $page = null, array $history = []): ?array
    {
        $modelsToTry = array_unique([$this->geminiModel, 'gemini-3.1-flash-lite', 'gemini-2.5-flash', 'gemini-1.5-flash']);

        try {
            $prompt = $this->buildGeminiPrompt($message, $page, $history);

            foreach ($modelsToTry as $model) {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$this->geminiApiKey}";

                $response = Http::withoutVerifying()->timeout(20)->post($endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.3,
                    ],
                ]);

                if ($response->successful()) {
                    $rawText = (string) $response->json('candidates.0.content.parts.0.text');

                    if (! empty(trim($rawText))) {
                        return $this->parseGeminiResponse($rawText, "Google Gemini ({$model})");
                    }
                } else {
                    Log::warning("Gemini Copilot API ({$model}) returned status {$response->status()}: ".substr($response->body(), 0, 150));
                }
            }
        } catch (\Throwable $e) {
            Log::error('Gemini Copilot API call failed: '.$e->getMessage());
        }

        return null;
    }

    /**
     * Parse raw Gemini output into answer and suggested followups.
     *
     * @return array{
     *     answer: string,
     *     engine: string,
     *     suggested_followups: array<int, string>
     * }
     */
    protected function parseGeminiResponse(string $rawText, string $engine): array
    {
        $delimiter = '---SUGGESTED_FOLLOWUPS---';
        $suggestedFollowups = [];

        if (str_contains($rawText, $delimiter)) {
            $parts = explode($delimiter, $rawText, 2);
            $answer = trim($parts[0]);
            $followupText = trim($parts[1] ?? '');

            $lines = explode("\n", $followupText);
            foreach ($lines as $line) {
                $clean = trim(preg_replace('/^[\d\.\-\*\s]+/', '', $line));
                if (! empty($clean) && strlen($clean) > 5) {
                    $suggestedFollowups[] = $clean;
                }
            }
        } else {
            $answer = trim($rawText);
            // Extract question lines from end of text if available
            preg_match_all('/(?:Saran Follow-up|Pertanyaan Lanjutan|Follow-up):\s*(.*)$/is', $rawText, $matches);
            if (! empty($matches[1][0])) {
                $subLines = explode("\n", trim($matches[1][0]));
                foreach ($subLines as $subLine) {
                    $clean = trim(preg_replace('/^[\d\.\-\*\s]+/', '', $subLine));
                    if (! empty($clean) && strlen($clean) > 5) {
                        $suggestedFollowups[] = $clean;
                    }
                }
            }
        }

        if (empty($suggestedFollowups)) {
            $suggestedFollowups = [
                'Bagaimana korelasi skenario ini terhadap IHSG?',
                'Emiten mana yang memiliki margin of safety tertinggi?',
                'Sektor apa yang paling direkomendasikan untuk defensif?',
            ];
        }

        return [
            'answer' => $answer,
            'engine' => $engine,
            'suggested_followups' => array_values(array_slice($suggestedFollowups, 0, 3)),
        ];
    }

    /**
     * Build institutional system prompt and user context for Gemini.
     *
     * @param  array<int, array{role: string, content: string}>  $history
     */
    protected function buildGeminiPrompt(string $message, ?string $page = null, array $history = []): string
    {
        $pageContext = match ($page) {
            '/scanner' => 'Pengguna berada di halaman Debt Solvency & Vulnerability Scanner (/scanner). Fokuskan analisis pada batas DER (Debt to Equity Ratio), beban utang valas, status Red-Line, dan likuiditas emiten.',
            '/portfolio' => 'Pengguna berada di halaman Portfolio Stress Testing (/portfolio). Fokuskan analisis pada alokasi bobot portofolio, beta-adjusted shocks, estimasi drawdown, dan strategi hedging.',
            '/backtest' => 'Pengguna berada di halaman Historical Crisis Backtest (/backtest). Fokuskan analisis pada validasi empiris krisis masa lalu (Covid 2020, Taper Tantrum 2013, Trade War 2018) dan transmisi sektor.',
            '/duel' => 'Pengguna berada di halaman Head-to-Head Duel (/duel). Fokuskan analisis pada perbandingan langsung 2 emiten/sektor, radar sensitivitas makro, dan margin of safety.',
            '/tear-sheet' => 'Pengguna berada di halaman Macro Report & Tear Sheet (/tear-sheet). Fokuskan analisis pada ringkasan eksekutif komprehensif, peringkat sektor, dan actionable trade setups.',
            default => 'Pengguna berada di Macro Transmission Dashboard. Fokuskan analisis pada transmisi makro terhadap 11 sektor resmi Bursa Efek Indonesia (IHSG).',
        };

        $historyContext = '';
        if (! empty($history)) {
            $historyLines = [];
            foreach (array_slice($history, -4) as $h) {
                $role = ($h['role'] ?? 'user') === 'user' ? 'User' : 'Copilot';
                $content = substr((string) ($h['content'] ?? ''), 0, 300);
                $historyLines[] = "{$role}: {$content}";
            }
            $historyContext = "RIWAYAT CHAT SEBELUMNYA:\n".implode("\n", $historyLines)."\n\n";
        }

        return <<<PROMPT
Anda adalah Institutional Indonesian Macro-Economist & IDX Equity Strategist untuk Bursa Efek Indonesia (IHSG).
Gaya analisis Anda:
1. Lugas, profesional, institusional, dan berbasis data transmisi makro-sektoral.
2. Menggunakan istilah keuangan baku (DER, NPM, COGS, CASA, Net Interest Margin, Foreign Debt Exposure, Beta Shocks).
3. Memberikan contoh konkret ticker emiten BEI (misal BBCA, BBRI, BMRI, ASII, ADRO, KLBF, ICBP) yang relevan.
4. Gunakan format Markdown rapi (bullet points, bold text).

KONTEKS HALAMAN AKTIF:
{$pageContext}

{$historyContext}PERTANYAAN INVESTOR:
"{$message}"

INSTRUKSI FORMAT RESPON:
Berikan jawaban analitis mendalam dalam Bahasa Indonesia.
Di baris paling akhir setelah jawaban Anda, sertakan tepat 2 sampai 3 pertanyaan follow-up yang tajam dan relevan bagi investor menggunakan pemisah tepat berikut:
---SUGGESTED_FOLLOWUPS---
1. [Pertanyaan follow-up 1]
2. [Pertanyaan follow-up 2]
3. [Pertanyaan follow-up 3]
PROMPT;
    }

    /**
     * Deterministic Macro Transmission Engine for zero-latency, 100% reliable fallback.
     *
     * @return array{
     *     answer: string,
     *     engine: string,
     *     suggested_followups: array<int, string>
     * }
     */
    protected function deterministicAnalysis(string $message, ?string $page = null): array
    {
        $text = strtolower($message);
        $engine = 'MacroSectors Deterministic Engine (Zero-Latency Fallback)';

        // 1. Currency / USD / Rupiah / Kurs / Valas
        if (str_contains($text, 'usd') || str_contains($text, 'kurs') || str_contains($text, 'rupiah') || str_contains($text, 'valas') || str_contains($text, 'depresiasi') || str_contains($text, 'dolar')) {
            $answer = <<<'MD'
### 🌐 Transmisi Pelemahan Rupiah terhadap USD

Pelemahan nilai tukar Rupiah terhadap Dolar AS (USD) memicu **transmisi asimetris** di Bursa Efek Indonesia (IHSG):

1. **Sektor Tertekan (High Vulnerability):**
   * **Farmasi & Kimia (IDX-HEALTH, IDX-BASIC):** Ketergantungan bahan baku impor (*API/Active Pharmaceutical Ingredients*) mencapai 80-90% dengan *lead time* pembelian USD. Emiten seperti **KLBF** dan **KAEF** menghadapi kompresi gross margin jika tidak mampu menaikkan ASP (*Average Selling Price*).
   * **Infrastruktur & Utilitas (IDX-INFRA):** Emiten dengan utang denominasi valas tanpa lindung nilai (*hedging*) penuh menghadapi lonjakan beban selisih kurs yang mengikis laba bersih.
   * **Manufaktur & Konsumen:** Emiten seperti **UNVR** dan **ICBP** sensitif terhadap gandum, gula, dan kemasan impor, meskipun ICBP memiliki *offset* dari pendapatan luar negeri (Pinehill).

2. **Sektor Diuntungkan (Natural Beneficiaries):**
   * **Energi & Komoditas Ekspor (IDX-ENERGY, IDX-BASIC):** Emiten batubara dan mineral (**ADRO, ITMG, PTBA, MEDC, MDKA, ANTM**) membukukan *revenue* dalam USD sementara sebagian beban operasional (*cash cost*) berdenominasi Rupiah, menghasilkan *windfall margin expansion*.

3. **Implikasi Strategis:**
   * Alokasikan bobot ke eksportir komoditas berneraca bersih kas (*net cash*) dan kurangi eksposur pada importir dengan DER > 1.5x.
MD;

            $followups = [
                'Bagaimana posisi utang valas KLBF vs KAEF?',
                'Emiten apa saja dengan porsi pendapatan USD tertinggi di BEI?',
                'Apakah pelemahan Rupiah akan memaksa Bank Indonesia menaikkan BI-Rate?',
            ];

            return $this->formatResult($answer, $engine, $followups, $page);
        }

        // 2. Banking / BBCA vs BBRI / Sektor Perbankan
        if (str_contains($text, 'bbca') || str_contains($text, 'bbri') || str_contains($text, 'bank') || str_contains($text, 'perbankan') || str_contains($text, 'bmri') || str_contains($text, 'bbni')) {
            $answer = <<<'MD'
### 🏦 Komparasi Ketahanan Perbankan: BBCA vs BBRI

Dalam siklus pengetatan moneter dan volatilitas makroekonomi, profil ketahanan perbankan Big 4 sangat ditentukan oleh struktur pendanaan dan eksposur kredit:

1. **BBCA (Ultra-Defensive Benchmark):**
   * **Struktur Dana Murah (CASA):** Rasio CASA BBCA konsisten di kisaran **80-82%**, menjadikannya bank dengan *Cost of Funds* (CoF) terendah di industri perbankan nasional.
   * **Kualitas Aset & Risiko:** Eksposur dominan pada korporasi *blue-chip* dan KPR/KKB dengan *Loan at Risk* (LaR) terendah (~6%). Ketika likuiditas pasar mengetat, BBCA justru menikmati ekspansi NIM tanpa lonjakan beban provisi kredit macet.

2. **BBRI (High-Yield Growth Engine):**
   * **Portofolio Kredit:** Berfokus pada segmen mikro (*Kupedes, Holding UMi*) yang memiliki *loan yield* tinggi (>15-18%).
   * **Sensitivitas Makro:** Segmen mikro sangat sensitif terhadap inflasi pangan dan daya beli akar rumput. Kenaikan suku bunga atau pelemahan ekonomi menaikkan rasio pembentukan NPL baru (*credit cost*), sehingga memerlukan cadangan provisi yang lebih tebal.

3. **Kesimpulan Taktis:**
   * **BBCA** menjadi instrumen *core defensive* saat suku bunga bertahan di level tinggi (*higher-for-longer*).
   * **BBRI** menawarkan *rebound upside* dan *dividend yield* yang lebih menarik ketika siklus pemangkasan suku bunga dimulai.
MD;

            $followups = [
                'Kenapa CASA ratio BBCA mampu menahan kenaikan Cost of Funds?',
                'Bagaimana perbandingan rasio NIM dan NPL antara BBCA, BBRI, BMRI, dan BBNI?',
                'Apakah dividen yield BBRI masih aman jika credit cost meningkat?',
            ];

            return $this->formatResult($answer, $engine, $followups, $page);
        }

        // 3. Suku Bunga / BI-Rate / The Fed / Bunga Acuan
        if (str_contains($text, 'bunga') || str_contains($text, 'rate') || str_contains($text, 'bi-rate') || str_contains($text, 'fed') || str_contains($text, 'moneter') || str_contains($text, 'hawkish')) {
            $answer = <<<'MD'
### 📈 Dampak Kenaikan Suku Bunga Acuan (BI-Rate & Fed Funds Rate)

Kenaikan suku bunga acuan mengubah lanskap biaya modal (*cost of capital*) dan perilaku konsumsi:

1. **Dampak ke Sektor Properti & Otomotif (IDX-PROPERT, IDX-CYCLIC):**
   * Kenaikan suku bunga KPR/KKB menekan daya beli masyarakat dan memperlambat *marketing sales*. Emiten seperti **BSDE, CTRA, SMRA** menghadapi siklus *presales* yang melambat serta beban pembiayaan proyek yang membengkak.
   * Saham otomotif (**ASII**) tertekan akibat pengetatan kredit pembiayaan kendaraan bermotor.

2. **Dampak ke Emiten Leverage Tinggi (DER > 2.0x):**
   * Lonjakan *interest expense* secara langsung menekan *Interest Coverage Ratio* (ICR). Sektor konstruksi BUMN dan manufaktur padat modal paling rentan mengalami *cash flow burn*.

3. **Sektor Defensif:**
   * **Consumer Non-Cyclical (ICBP, INDF, AMRT):** Permintaan barang kebutuhan pokok bersifat inelastis terhadap suku bunga.
   * **Large-Cap Banking:** Menikmati kenaikan *lending rate* sebelum suku bunga simpanan dinaikkan secara penuh.
MD;

            $followups = [
                'Sektor mana yang menjadi safe-haven saat suku bunga tinggi?',
                'Bagaimana cara menghitung dampak lonjakan beban bunga terhadap laba bersih emiten properti?',
                'Apakah BI-Rate diproyeksikan akan dipangkas dalam 6 bulan ke depan?',
            ];

            return $this->formatResult($answer, $engine, $followups, $page);
        }

        // 4. Scanner / Vulnerability / Red-Line / Solvency / Utang
        if (str_contains($text, 'scanner') || str_contains($text, 'red-line') || str_contains($text, 'rentan') || str_contains($text, 'solvensi') || str_contains($text, 'utang') || str_contains($text, 'der') || str_contains($text, 'kebangkrutan')) {
            $answer = <<<'MD'
### 🚨 Metodologi Deteksi Red-Line Solvency & Vulnerability

Model **Vulnerability Scanner** mengidentifikasi risiko struktural neraca emiten BEI melalui 3 kriteria kritis:

1. **Indikator Pemicu Red-Line (Distress Quadrant):**
   * **Leverage Kritis:** Debt to Equity Ratio (**DER > 2.5x**) menandakan eksposur kewajiban yang melampaui kapasitas modal sendiri.
   * **Kompresi Profitabilitas:** Net Profit Margin (**NPM < 2.0%**) atau bahkan negatif, mencerminkan ketiadaan bantalan operasional untuk menyerap *macro shock*.
   * **Foreign Debt Exposure:** Utang valas > 30% dari total liabilitas tanpa instrumen *hedging* derivatif resmi.

2. **Klaster Sektor Paling Rentan:**
   * **Konstruksi & Properti Leverage Tinggi:** Beban bunga utang berbunga (*interest-bearing debt*) menggerus arus kas operasional.
   * **Basic Materials Impor:** Margin laba tergerus ganda oleh lonjakan harga bahan baku dan fluktuasi kurs.

3. **Tindakan Taktis Investor:**
   * Terapkan *stop-loss* ketat atau lakukan rotasi keluar dari emiten yang menyentuh zona Red-Line menuju emiten dengan rasio kas bersih (*net cash position*).
MD;

            $followups = [
                'Emiten mana saja yang saat ini berada di kuadran Red-Line pada Scanner?',
                'Bagaimana cara menguji ketahanan utang valas emiten menggunakan stres-skenario?',
                'Berapa batas DER yang aman untuk sektor manufaktur konsumer?',
            ];

            return $this->formatResult($answer, $engine, $followups, $page);
        }

        // 5. Energi / Komoditas / Batubara / Minyak
        if (str_contains($text, 'energi') || str_contains($text, 'komoditas') || str_contains($text, 'batubara') || str_contains($text, 'minyak') || str_contains($text, 'tambang') || str_contains($text, 'coal') || str_contains($text, 'oil')) {
            $answer = <<<'MD'
### ⚡ Prospek Transmisi Sektor Energi & Komoditas

Lonjakan harga komoditas global menciptakan redistribusi likuiditas di pasar modal Indonesia:

1. **Dampak Positif (Pure Exporters):**
   * **Batubara (ADRO, ITMG, PTBA):** Struktur kas solid, ketiadaan utang signifikan (*net cash*), dan kebijakan pembagian dividen jumbo (*dividend yield > 10-15%*) menjadikan sektor ini penyerap likuiditas asing (*foreign inflow*).
   * **Migas & Logam (MEDC, MDKA, ANTM, INCO):** Pertumbuhan pendapatan seiring eskalasi geopolitik dan akselerasi rantai pasok kendaraan listrik (*EV ecosystem*).

2. **Dampak Negatif (Sektor Penerima Beban Biaya):**
   * **Transportasi & Logistik (GIAA, BIRD):** Lonjakan harga avtur dan BBM langsung menekan margin operasional karena keterbatasan *pricing power*.
   * **Konsumen & Semen (INTP, SMGR):** Kenaikan biaya energi dan logistik menaikkan COGS yang menekan margin kotor.
MD;

            $followups = [
                'Apakah siklus pembagian dividen emiten batubara masih sustainable?',
                'Bagaimana sensitivitas laba MEDC terhadap setiap kenaikan $10 harga minyak Brent?',
                'Sektor manufaktur mana yang paling cepat tertekan jika harga batubara domestik naik?',
            ];

            return $this->formatResult($answer, $engine, $followups, $page);
        }

        // 6. Rotasi Sektor / Portofolio / Krisis / Defensif / Backtest
        if (str_contains($text, 'rotasi') || str_contains($text, 'defensif') || str_contains($text, 'krisis') || str_contains($text, 'alokasi') || str_contains($text, 'portfolio') || str_contains($text, 'backtest')) {
            $answer = <<<'MD'
### 🔄 Strategi Rotasi Sektor & Alokasi Portofolio Institusional

Ketika indikator makroekonomi mengarah pada perlambatan pertumbuhan (*late-cycle phase*) atau ketidakpastian pasar:

1. **Strategi De-Risking:**
   * Pangkas bobot saham siklikal dengan **Beta > 1.2** (Teknologi, Properti, Konstruksi).
   * Alihkan likuiditas ke sektor **Consumer Non-Cyclical (ICBP, INDF, MYOR, AMRT)** dan **Healthcare (KLBF)** yang memiliki *inelastic demand* dan arus kas stabil.

2. **Karakteristik Saham Safe-Haven di BEI:**
   * Rasio Kas/Kapitalisasi Pasar tinggi (*Strong Net Cash*).
   * *Dividend yield* konsisten di atas suku bunga deposito (SBN 10-yr benchmark).
   * *Pricing power* dominan untuk meneruskan inflasi biaya ke konsumen akhir (*cost pass-through*).

3. **Validasi Historis (Empirical Crises):**
   * Pada Krisis Covid-19 (2020) dan Taper Tantrum (2013), sektor defensif mencatat *drawdown* 40-60% lebih dangkal dibandingkan IHSG secara keseluruhan.
MD;

            $followups = [
                'Bagaimana komposisi bobot portofolio ideal untuk skenario stagflasi?',
                'Berapa rata-rata drawdown sektor defensif saat krisis Covid-19 2020?',
                'Kapan waktu yang tepat untuk beralih kembali ke sektor High-Beta?',
            ];

            return $this->formatResult($answer, $engine, $followups, $page);
        }

        // 7. General Institutional Macro Fallback
        $answer = <<<'MD'
### 📊 Analisis Transmisi Makroekonomi Bursa Efek Indonesia

Kondisi makroekonomi domestik dan global saling bertransmisi ke kinerja 11 sektor IHSG melalui tiga kanal utama:

1. **Kanal Suku Bunga & Likuiditas Perbankan:**
   * Mengatur biaya modal, ketersediaan kredit, serta penilaian valuasi saham berbasis discounted cash flow (DCF).
2. **Kanal Nilai Tukar & Neraca Perdagangan:**
   * Menentukan pemenang (eksportir komoditas berpenghasilan USD) vs pecundang (importir bahan baku dan pemilik utang valas).
3. **Kanal Daya Beli & Beban Input Riil:**
   * Mempengaruhi elastisitas penjualan barang konsumsi primer vs sekunder serta margin operasional korporasi.

Gunakan fitur **Macro Copilot**, **Vulnerability Scanner**, dan **Portfolio Stress Testing** untuk mengevaluasi ketahanan portofolio Anda terhadap berbagai skenario makro spesifik.
MD;

        $followups = [
            'Sektor apa yang paling kebal terhadap kombinasi inflasi dan depresiasi kurs?',
            'Bagaimana cara menguji sensitivitas portofolio saham terhadap shock suku bunga?',
            'Emiten mana yang memiliki rasio kecukupan modal dan dividen paling tebal di IHSG?',
        ];

        return $this->formatResult($answer, $engine, $followups, $page);
    }

    /**
     * Format final deterministic response with page context embellishment.
     *
     * @param  array<int, string>  $followups
     * @return array{
     *     answer: string,
     *     engine: string,
     *     suggested_followups: array<int, string>
     * }
     */
    protected function formatResult(string $answer, string $engine, array $followups, ?string $page = null): array
    {
        if (! empty($page)) {
            $contextNote = match ($page) {
                '/scanner' => "\n\n*📍 Konteks: Vulnerability & Solvency Scanner — Perhatikan ambang batas DER > 2.5x pada emiten berisiko.*",
                '/portfolio' => "\n\n*📍 Konteks: Portfolio Stress Testing — Sesuaikan bobot aset untuk meminimalkan estimasi drawdown.*",
                '/backtest' => "\n\n*📍 Konteks: Historical Crisis Backtest — Memvalidasi akurasi model terhadap krisis masa lalu BEI.*",
                '/duel' => "\n\n*📍 Konteks: Head-to-Head Duel — Analisis perbandingan sensitivitas makro antar 2 saham/sektor.*",
                '/tear-sheet' => "\n\n*📍 Konteks: Macro Tear Sheet — Ringkasan eksekutif dan rekomendasi taktikal terkonsolidasi.*",
                default => null,
            };

            if ($contextNote !== null) {
                $answer .= $contextNote;
            }
        }

        return [
            'answer' => $answer,
            'engine' => $engine,
            'suggested_followups' => array_values($followups),
        ];
    }
}
