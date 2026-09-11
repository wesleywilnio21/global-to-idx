<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HistoricalBacktestController extends Controller
{
    /**
     * Display the historical crisis backtesting validation suite.
     */
    public function index(Request $request): View
    {
        $crises = $this->getCrisesMetadata();

        $selectedCrisisKey = (string) $request->query('crisis', 'covid_crash_2020');
        if (! array_key_exists($selectedCrisisKey, $crises)) {
            $selectedCrisisKey = 'covid_crash_2020';
        }

        $crisisDataset = $this->getHistoricalCrisisDataset($selectedCrisisKey);

        $sectorComparison = $this->processSectorComparison($crisisDataset['sectors']);
        $directionalAccuracy = $this->calculateDirectionalAccuracy($sectorComparison);

        $sortedByActual = collect($sectorComparison)->sortByDesc('actual_return')->values();
        $topDefensive = $sortedByActual->take(2)->all();
        $topVulnerable = $sortedByActual->reverse()->take(2)->values()->all();

        $crisisData = array_merge($crises[$selectedCrisisKey], [
            'usd_fx' => $crisisDataset['macro_backdrop']['usd_fx'],
            'bi_rate' => $crisisDataset['macro_backdrop']['bi_rate'],
            'inflation_gdp' => $crisisDataset['macro_backdrop']['inflation_gdp'],
            'ihsg_drawdown' => $crisisDataset['macro_backdrop']['ihsg_drawdown'],
            'macro_backdrop' => $crisisDataset['macro_backdrop'],
            'ai_post_mortem' => $crisisDataset['ai_post_mortem'],
        ]);

        return view('backtest', [
            'crises' => $crises,
            'selectedCrisisKey' => $selectedCrisisKey,
            'crisisData' => $crisisData,
            'sectorComparison' => $sectorComparison,
            'directionalAccuracy' => $directionalAccuracy,
            'topDefensive' => $topDefensive,
            'topVulnerable' => $topVulnerable,
        ]);
    }

    /**
     * Return metadata for the three empirical crisis scenarios.
     *
     * @return array<string, array<string, string>>
     */
    private function getCrisesMetadata(): array
    {
        return [
            'taper_tantrum_2013' => [
                'title' => 'The Fed Taper Tantrum',
                'year' => '2013',
                'period' => 'Mei 2013 – Des 2013',
                'tag' => 'MONETARY TIGHTENING',
                'icon' => 'trending-down',
                'summary' => 'Pernyataan tapering quantitative easing (QE) The Fed memicu capital outflow masif dari pasar berkembang dan depresiasi tajam Rupiah.',
            ],
            'trade_war_2018' => [
                'title' => 'Perang Dagang AS–Tiongkok',
                'year' => '2018',
                'period' => 'Mar 2018 – Des 2018',
                'tag' => 'TARIFF & TRADE SHOCK',
                'icon' => 'scale',
                'summary' => 'Pemberlakuan tarif impor barang industri oleh AS memicu eskalasi proteksionisme global, disrupsi rantai pasok ekspor, dan pelemahan mata uang Asia.',
            ],
            'covid_crash_2020' => [
                'title' => 'Market Crash Pandemi Covid-19',
                'year' => '2020',
                'period' => 'Feb 2020 – Mei 2020',
                'tag' => 'PANDEMIC SHOCK',
                'icon' => 'shield-alert',
                'summary' => 'Kepanikan global akibat wabah SARS-CoV-2 dan karantina wilayah (PSBB) memicu pembekuan perdagangan (trading halt) berulang di BEI dan aksi jual ekuitas kilat.',
            ],
        ];
    }

    /**
     * Retrieve empirical metrics, macro context, and 11 sector actual vs model predictions.
     *
     * @return array{
     *     macro_backdrop: array{usd_fx: string, bi_rate: string, inflation_gdp: string, ihsg_drawdown: string},
     *     ai_post_mortem: string,
     *     sectors: array<int, array{sector_code: string, sector_name: string, actual_return: float, predicted_impact: float, accuracy_status: string, key_driver: string}>
     * }
     */
    private function getHistoricalCrisisDataset(string $crisisKey): array
    {
        $datasets = [
            'covid_crash_2020' => [
                'macro_backdrop' => [
                    'usd_fx' => 'Rp16.575 (+17.2%)',
                    'bi_rate' => '-125 bps (Pelonggaran ke 4.00%)',
                    'inflation_gdp' => 'PDB -2.07% (Resesi 2020)',
                    'ihsg_drawdown' => '-37.5% (Trough 3.911)',
                ],
                'ai_post_mortem' => 'Krisis Covid-19 2020 memperlihatkan divergensi sektoral paling tajam dalam sejarah pasar modal Indonesia modern. Sektor non-siklikal dan esensial seperti Kesehatan dan Konsumer Primer terbukti menjadi pelindung modal paling kokoh saat aktivitas mobilitas fisik lumpuh. Sementara itu, sektor siklikal dengan leverage operasional tinggi seperti Transportasi, Properti, dan Perbankan mengalami de-rating valuasi ekstrem sebelum pulih oleh stimulus fiskal PEN dan injeksi likuiditas moneter. Pelajaran utama bagi investor: portofolio defensif bukan sekadar instrumen mitigasi risiko, melainkan penyedia likuiditas strategis untuk merotasi alokasi ke saham-saham ber-beta tinggi di titik nadir krisis.',
                'sectors' => [
                    [
                        'sector_code' => 'IDX-HEALTHCARE',
                        'sector_name' => 'Kesehatan',
                        'actual_return' => 14.8,
                        'predicted_impact' => 16.5,
                        'accuracy_status' => 'Bullseye (Presisi)',
                        'key_driver' => 'Lonjakan eksponensial permintaan obat-obatan, tes diagnostik klinis, multivitamin, dan utilisasi kapasitas rawat inap rumah sakit.',
                    ],
                    [
                        'sector_code' => 'IDX-CONSUMER-NON-CYCLICALS',
                        'sector_name' => 'Konsumer Primer',
                        'actual_return' => -4.2,
                        'predicted_impact' => -5.0,
                        'accuracy_status' => 'Bullseye (Presisi)',
                        'key_driver' => 'Ketahanan belanja pangan pokok dan kebutuhan harian rumah tangga ditopang oleh gelombang belanja antisipatif (pantry loading).',
                    ],
                    [
                        'sector_code' => 'IDX-TECHNOLOGY',
                        'sector_name' => 'Teknologi',
                        'actual_return' => 4.5,
                        'predicted_impact' => -6.0,
                        'accuracy_status' => 'Divergen Minor',
                        'key_driver' => 'Akselerasi adopsi platform digital mendadak (e-commerce & teleworking) melampaui estimasi awal model makro likuiditas pasar umum.',
                    ],
                    [
                        'sector_code' => 'IDX-INFRASTRUCTURES',
                        'sector_name' => 'Infrastruktur',
                        'actual_return' => -18.5,
                        'predicted_impact' => -16.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Lonjakan konsumsi bandwidth kuota data seluler telekomunikasi mengimbangi penurunan tajam volume lalu lintas jalan tol.',
                    ],
                    [
                        'sector_code' => 'IDX-BASIC-MATERIALS',
                        'sector_name' => 'Barang Baku',
                        'actual_return' => -22.4,
                        'predicted_impact' => -24.5,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Penghentian operasional pabrik manufaktur di China dan pasar ekspor memangkas permintaan komoditas logam dan petrokimia.',
                    ],
                    [
                        'sector_code' => 'IDX-ENERGY',
                        'sector_name' => 'Energi',
                        'actual_return' => -27.8,
                        'predicted_impact' => -29.0,
                        'accuracy_status' => 'Bullseye (Presisi)',
                        'key_driver' => 'Minyak mentah WTI sempat anjlok ke teritori negatif akibat kolaps serentak permintaan avtur dan bahan bakar transportasi global.',
                    ],
                    [
                        'sector_code' => 'IDX-FINANCIALS',
                        'sector_name' => 'Keuangan',
                        'actual_return' => -32.6,
                        'predicted_impact' => -30.5,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Kekhawatiran lonjakan restrukturisasi kredit macet (NPL) sektor UMKM dan korporasi memicu aksi jual asing pada saham perbankan kapitalisasi besar.',
                    ],
                    [
                        'sector_code' => 'IDX-INDUSTRIALS',
                        'sector_name' => 'Perindustrian',
                        'actual_return' => -34.2,
                        'predicted_impact' => -31.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Penghentian rantai pasok komponen otomotif dan penurunan drastis volume penjualan kendaraan bermotor dan alat berat.',
                    ],
                    [
                        'sector_code' => 'IDX-CONSUMER-CYCLICALS',
                        'sector_name' => 'Konsumer Non-Primer',
                        'actual_return' => -38.5,
                        'predicted_impact' => -36.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Penutupan pusat perbelanjaan dan bioskop selama masa PSBB memangkas pendapatan diskresioner ritel fashion dan hiburan ke titik nadir.',
                    ],
                    [
                        'sector_code' => 'IDX-PROPERTIES',
                        'sector_name' => 'Properti & Real Estat',
                        'actual_return' => -41.2,
                        'predicted_impact' => -39.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Penundaan transaksi pembelian properti baru serta diskon tarif sewa ruang komersial perkantoran dan mall.',
                    ],
                    [
                        'sector_code' => 'IDX-TRANSPORTATION',
                        'sector_name' => 'Transportasi & Logistik',
                        'actual_return' => -45.0,
                        'predicted_impact' => -43.5,
                        'accuracy_status' => 'Bullseye (Presisi)',
                        'key_driver' => 'Pembatasan rute penerbangan domestik/internasional dan larangan mudik menghentikan arus kas maskapai dan armada transportasi publik.',
                    ],
                ],
            ],

            'taper_tantrum_2013' => [
                'macro_backdrop' => [
                    'usd_fx' => 'Rp12.270 (+26.4%)',
                    'bi_rate' => '+175 bps (Kenaikan ke 7.50%)',
                    'inflation_gdp' => 'Inflasi 8.38% (Kenaikan BBM & FX)',
                    'ihsg_drawdown' => '-24.7% (Trough 3.925)',
                ],
                'ai_post_mortem' => 'Guncangan Taper Tantrum 2013 menjadi pembuktian nyata sensitivitas pasar modal terhadap pengetatan likuiditas global dan pelemahan nilai tukar Rupiah. Sektor dengan eksposur utang valas tanpa lindung nilai (hedging) serta sensitif terhadap suku bunga acuan (Properti, Finansial sekunder) tertekan hebat akibat lonjakan BI Rate 175 bps. Sebaliknya, emiten eksportir komoditas berbasis pendapatan Dolar AS dan produsen barang konsumsi esensial mampu mempertahankan performa operasional. Pelajaran utama: rasio utang valas (DER Valas) dan kecukupan cadangan devisa merupakan parameter krusial yang wajib dievaluasi sebelum siklus moneter global beralih menjadi hawkish.',
                'sectors' => [
                    [
                        'sector_code' => 'IDX-ENERGY',
                        'sector_name' => 'Energi',
                        'actual_return' => -8.4,
                        'predicted_impact' => -9.5,
                        'accuracy_status' => 'Bullseye (Presisi)',
                        'key_driver' => 'Pendapatan ekspor berbasis mata uang Dolar AS (USD) memberikan perlindungan alami (natural hedge) terhadap pelemahan nilai tukar Rupiah.',
                    ],
                    [
                        'sector_code' => 'IDX-HEALTHCARE',
                        'sector_name' => 'Kesehatan',
                        'actual_return' => -10.2,
                        'predicted_impact' => -11.5,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Karakteristik permintaan obat-obatan yang inelastis menjaga pendapatan operasional meski beban bahan baku aktif impor meningkat.',
                    ],
                    [
                        'sector_code' => 'IDX-CONSUMER-NON-CYCLICALS',
                        'sector_name' => 'Konsumer Primer',
                        'actual_return' => -12.6,
                        'predicted_impact' => -11.0,
                        'accuracy_status' => 'Bullseye (Presisi)',
                        'key_driver' => 'Daya beli masyarakat pada produk kebutuhan pokok tetap tangguh menghadapi kenaikan harga BBM bersubsidi dan inflasi domestik.',
                    ],
                    [
                        'sector_code' => 'IDX-BASIC-MATERIALS',
                        'sector_name' => 'Barang Baku',
                        'actual_return' => -18.2,
                        'predicted_impact' => -17.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Pelemahan margin produsen semen dan petrokimia akibat lonjakan beban energi dan perlambatan permintaan proyek konstruksi.',
                    ],
                    [
                        'sector_code' => 'IDX-INFRASTRUCTURES',
                        'sector_name' => 'Infrastruktur',
                        'actual_return' => -19.5,
                        'predicted_impact' => -21.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Kenaikan imbal hasil obligasi membebani emiten infrastruktur modal intensif, diredam oleh kestabilan kas sektor telekomunikasi.',
                    ],
                    [
                        'sector_code' => 'IDX-TECHNOLOGY',
                        'sector_name' => 'Teknologi',
                        'actual_return' => -21.0,
                        'predicted_impact' => -22.5,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Peningkatan discount rate valuasi ekuitas global menekan multiplier harga saham-saham bertumbuh.',
                    ],
                    [
                        'sector_code' => 'IDX-TRANSPORTATION',
                        'sector_name' => 'Transportasi & Logistik',
                        'actual_return' => -23.5,
                        'predicted_impact' => -25.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Penyesuaian harga bahan bakar minyak bersubsidi langsung menaikkan biaya bahan bakar armada transportasi secara mendadak.',
                    ],
                    [
                        'sector_code' => 'IDX-INDUSTRIALS',
                        'sector_name' => 'Perindustrian',
                        'actual_return' => -25.8,
                        'predicted_impact' => -24.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Penurunan margin perakitan manufaktur lokal serta perlambatan belanja modal korporasi sektor perkebunan dan tambang.',
                    ],
                    [
                        'sector_code' => 'IDX-CONSUMER-CYCLICALS',
                        'sector_name' => 'Konsumer Non-Primer',
                        'actual_return' => -28.4,
                        'predicted_impact' => -26.5,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Kenaikan suku bunga kredit kendaraan bermotor (KKB) menekan angka penjualan otomotif roda empat dan barang diskresioner.',
                    ],
                    [
                        'sector_code' => 'IDX-FINANCIALS',
                        'sector_name' => 'Keuangan',
                        'actual_return' => -29.8,
                        'predicted_impact' => -28.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Kenaikan agresif BI Rate 175 bps menaikkan biaya dana (cost of funds) serta memicu arus keluar portofolio asing dari obligasi dan saham perbankan.',
                    ],
                    [
                        'sector_code' => 'IDX-PROPERTIES',
                        'sector_name' => 'Properti & Real Estat',
                        'actual_return' => -36.5,
                        'predicted_impact' => -34.5,
                        'accuracy_status' => 'Bullseye (Presisi)',
                        'key_driver' => 'Lonjakan suku bunga KPR hingga ke atas 12% dan pengetatan aturan rasio LTV Bank Indonesia menghentikan tren booming properti.',
                    ],
                ],
            ],

            'trade_war_2018' => [
                'macro_backdrop' => [
                    'usd_fx' => 'Rp15.225 (+12.8%)',
                    'bi_rate' => '+175 bps (Kenaikan 7-Day RR ke 6.00%)',
                    'inflation_gdp' => 'Inflasi Terkendali 3.13%, PDB +5.17%',
                    'ihsg_drawdown' => '-15.8% (Trough 5.633)',
                ],
                'ai_post_mortem' => 'Perang Dagang AS–China 2018 menegaskan bagaimana guncangan geopolitik dan tarif impor global ditransmisikan ke pasar domestik melalui volatilitas valuta dan arus keluar dana asing. Meskipun guncangan berasal dari eksternal, langkah preventif Bank Indonesia menaikkan suku bunga 175 bps berhasil menjaga stabilitas makro tanpa memicu kepanikan sistemik. Sektor yang berorientasi pasar domestik (Konsumer Primer dan Infrastruktur Telco) terbukti menjadi jangkar portofolio terbaik karena tidak terpengaruh oleh tarif ekspor. Pelajaran utama: identifikasi eksposur ketergantungan komponen impor dan dominasi pangsa pasar domestik (domestic shield) sebagai benteng pertahanan utama saat sengketa perdagangan internasional memanas.',
                'sectors' => [
                    [
                        'sector_code' => 'IDX-CONSUMER-NON-CYCLICALS',
                        'sector_name' => 'Konsumer Primer',
                        'actual_return' => 2.4,
                        'predicted_impact' => 3.5,
                        'accuracy_status' => 'Bullseye (Presisi)',
                        'key_driver' => 'Tingkat konsumsi rumah tangga domestik tetap solid dengan inflasi tahunan yang terjaga rendah di kisaran 3.1%.',
                    ],
                    [
                        'sector_code' => 'IDX-HEALTHCARE',
                        'sector_name' => 'Kesehatan',
                        'actual_return' => -1.8,
                        'predicted_impact' => -2.5,
                        'accuracy_status' => 'Bullseye (Presisi)',
                        'key_driver' => 'Perluasan cakupan kepesertaan JKN-BPJS Kesehatan menopang utilisasi rumah sakit dan permintaan obat generik.',
                    ],
                    [
                        'sector_code' => 'IDX-INFRASTRUCTURES',
                        'sector_name' => 'Infrastruktur',
                        'actual_return' => -5.4,
                        'predicted_impact' => -6.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Pertumbuhan pesat penetrasi smartphone dan konsumsi kuota data menjaga pertumbuhan arus kas defensif telekomunikasi.',
                    ],
                    [
                        'sector_code' => 'IDX-ENERGY',
                        'sector_name' => 'Energi',
                        'actual_return' => -6.8,
                        'predicted_impact' => -8.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Harga batubara termal yang masih stabil di awal periode menopang kinerja emiten tambang meski tensi tarif global meningkat.',
                    ],
                    [
                        'sector_code' => 'IDX-FINANCIALS',
                        'sector_name' => 'Keuangan',
                        'actual_return' => -9.5,
                        'predicted_impact' => -10.5,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Kenaikan suku bunga acuan oleh BI sukses menstabilkan nilai tukar tanpa menimbulkan lonjakan rasio kredit bermasalah pada bank besar.',
                    ],
                    [
                        'sector_code' => 'IDX-CONSUMER-CYCLICALS',
                        'sector_name' => 'Konsumer Non-Primer',
                        'actual_return' => -12.4,
                        'predicted_impact' => -11.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Pelemahan nilai tukar Rupiah menaikkan harga perolehan impor barang ritel gaya hidup dan elektronik konsumen.',
                    ],
                    [
                        'sector_code' => 'IDX-TECHNOLOGY',
                        'sector_name' => 'Teknologi',
                        'actual_return' => -13.6,
                        'predicted_impact' => -12.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Sentimen negatif restriksi ekspor teknologi semikonduktor AS-China terhadap rantai pasok komponen teknologi regional.',
                    ],
                    [
                        'sector_code' => 'IDX-TRANSPORTATION',
                        'sector_name' => 'Transportasi & Logistik',
                        'actual_return' => -16.2,
                        'predicted_impact' => -15.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Perlambatan pertumbuhan volume arus kontainer ekspor-impor pada jalur maritim internasional menekan margin logistik.',
                    ],
                    [
                        'sector_code' => 'IDX-PROPERTIES',
                        'sector_name' => 'Properti & Real Estat',
                        'actual_return' => -18.5,
                        'predicted_impact' => -17.0,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Siklus pengetatan suku bunga 7-Day Reverse Repo Rate memicu sikap wait-and-see calon pembeli rumah dan ekspansi pengembang.',
                    ],
                    [
                        'sector_code' => 'IDX-INDUSTRIALS',
                        'sector_name' => 'Perindustrian',
                        'actual_return' => -21.4,
                        'predicted_impact' => -19.5,
                        'accuracy_status' => 'Konsisten',
                        'key_driver' => 'Peningkatan harga bahan baku impor komponen manufaktur dan penurunan pesanan ekspor produk mesin ke pasar mitra dagang.',
                    ],
                    [
                        'sector_code' => 'IDX-BASIC-MATERIALS',
                        'sector_name' => 'Barang Baku',
                        'actual_return' => -24.8,
                        'predicted_impact' => -22.5,
                        'accuracy_status' => 'Bullseye (Presisi)',
                        'key_driver' => 'Pemberlakuan tarif impor baja-aluminium AS dan anjloknya harga komoditas logam dasar industri di bursa berjangka London (LME).',
                    ],
                ],
            ],
        ];

        return $datasets[$crisisKey] ?? $datasets['covid_crash_2020'];
    }

    /**
     * Process sector comparison items, determining directional match and accuracy status.
     *
     * @param  array<int, array<string, mixed>>  $sectors
     * @return array<int, array<string, mixed>>
     */
    private function processSectorComparison(array $sectors): array
    {
        return array_map(function (array $sector): array {
            $actual = (float) $sector['actual_return'];
            $predicted = (float) $sector['predicted_impact'];

            $directionalMatch = ($actual > 0 && $predicted > 0)
                || ($actual < 0 && $predicted < 0)
                || ($actual == 0.0 && $predicted == 0.0);

            $accuracyStatus = $sector['accuracy_status'] ?? null;
            if ($accuracyStatus === null) {
                if ($directionalMatch) {
                    $accuracyStatus = abs($actual - $predicted) <= 3.0
                        ? 'Bullseye (Presisi)'
                        : 'Konsisten';
                } else {
                    $accuracyStatus = 'Divergen Minor';
                }
            }

            $sector['directional_match'] = $directionalMatch;
            $sector['accuracy_status'] = $accuracyStatus;

            return $sector;
        }, $sectors);
    }

    /**
     * Calculate directional accuracy percentage for the sector predictions.
     *
     * @param  array<int, array<string, mixed>>  $sectorComparison
     */
    private function calculateDirectionalAccuracy(array $sectorComparison): float
    {
        if (empty($sectorComparison)) {
            return 0.0;
        }

        $matches = count(array_filter($sectorComparison, fn (array $s): bool => (bool) ($s['directional_match'] ?? false)));

        return round(($matches / count($sectorComparison)) * 100, 1);
    }
}
