<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\MacroScenario;
use App\Models\SectorCache;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InitialMacroDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Seed 11 Official IDX Sectors
        $sectorsData = [
            [
                'sector_code' => 'IDX-ENERGY',
                'sector_name' => 'Energi',
                'avg_der' => 0.62,
                'avg_npm' => 18.5,
                'companies' => [
                    ['symbol' => 'ADRO', 'name' => 'Adaro Energy Indonesia Tbk', 'market_cap' => 118000000000000, 'pe_ratio' => 4.8, 'pbv_ratio' => 0.85, 'der' => 0.28, 'npm' => 24.3],
                    ['symbol' => 'PTBA', 'name' => 'Bukit Asam Tbk', 'market_cap' => 31000000000000, 'pe_ratio' => 5.2, 'pbv_ratio' => 1.45, 'der' => 0.45, 'npm' => 15.2],
                    ['symbol' => 'MEDC', 'name' => 'Medco Energi Internasional Tbk', 'market_cap' => 33000000000000, 'pe_ratio' => 6.1, 'pbv_ratio' => 1.12, 'der' => 1.75, 'npm' => 14.8],
                    ['symbol' => 'PGAS', 'name' => 'Perusahaan Gas Negara Tbk', 'market_cap' => 38000000000000, 'pe_ratio' => 7.8, 'pbv_ratio' => 0.80, 'der' => 0.85, 'npm' => 8.4],
                    ['symbol' => 'AKRA', 'name' => 'AKR Corporindo Tbk', 'market_cap' => 31500000000000, 'pe_ratio' => 11.2, 'pbv_ratio' => 2.40, 'der' => 0.72, 'npm' => 6.2],
                ],
            ],
            [
                'sector_code' => 'IDX-BASIC-MATERIALS',
                'sector_name' => 'Barang Baku',
                'avg_der' => 0.95,
                'avg_npm' => 9.2,
                'companies' => [
                    ['symbol' => 'BRPT', 'name' => 'Barito Pacific Tbk', 'market_cap' => 98000000000000, 'pe_ratio' => 42.0, 'pbv_ratio' => 3.10, 'der' => 1.35, 'npm' => 3.8],
                    ['symbol' => 'ANTM', 'name' => 'Aneka Tambang Tbk', 'market_cap' => 37000000000000, 'pe_ratio' => 12.5, 'pbv_ratio' => 1.48, 'der' => 0.38, 'npm' => 7.2],
                    ['symbol' => 'MDKA', 'name' => 'Merdeka Copper Gold Tbk', 'market_cap' => 55000000000000, 'pe_ratio' => -18.2, 'pbv_ratio' => 2.75, 'der' => 1.15, 'npm' => -2.1],
                    ['symbol' => 'INKP', 'name' => 'Indah Kiat Pulp & Paper Tbk', 'market_cap' => 44000000000000, 'pe_ratio' => 7.1, 'pbv_ratio' => 0.48, 'der' => 0.82, 'npm' => 9.5],
                    ['symbol' => 'TPIA', 'name' => 'Chandra Asri Pacific Tbk', 'market_cap' => 78000000000000, 'pe_ratio' => -35.0, 'pbv_ratio' => 2.10, 'der' => 0.92, 'npm' => -1.8],
                ],
            ],
            [
                'sector_code' => 'IDX-INDUSTRIALS',
                'sector_name' => 'Perindustrian',
                'avg_der' => 0.88,
                'avg_npm' => 7.8,
                'companies' => [
                    ['symbol' => 'ASII', 'name' => 'Astra International Tbk', 'market_cap' => 205000000000000, 'pe_ratio' => 6.5, 'pbv_ratio' => 0.98, 'der' => 0.92, 'npm' => 10.1],
                    ['symbol' => 'UNTR', 'name' => 'United Tractors Tbk', 'market_cap' => 98000000000000, 'pe_ratio' => 4.9, 'pbv_ratio' => 1.15, 'der' => 0.42, 'npm' => 15.6],
                    ['symbol' => 'HEXA', 'name' => 'Hexindo Adiperkasa Tbk', 'market_cap' => 5200000000000, 'pe_ratio' => 6.2, 'pbv_ratio' => 1.70, 'der' => 0.82, 'npm' => 8.9],
                    ['symbol' => 'MARK', 'name' => 'Mark Dynamics Indonesia Tbk', 'market_cap' => 3800000000000, 'pe_ratio' => 14.5, 'pbv_ratio' => 4.20, 'der' => 0.25, 'npm' => 28.5],
                ],
            ],
            [
                'sector_code' => 'IDX-CONSUMER-NON-CYCLICALS',
                'sector_name' => 'Konsumer Primer',
                'avg_der' => 0.72,
                'avg_npm' => 11.4,
                'companies' => [
                    ['symbol' => 'ICBP', 'name' => 'Indofood CBP Sukses Makmur Tbk', 'market_cap' => 134000000000000, 'pe_ratio' => 14.8, 'pbv_ratio' => 2.85, 'der' => 0.78, 'npm' => 11.2],
                    ['symbol' => 'INDF', 'name' => 'Indofood Sukses Makmur Tbk', 'market_cap' => 61000000000000, 'pe_ratio' => 7.2, 'pbv_ratio' => 0.95, 'der' => 0.95, 'npm' => 8.5],
                    ['symbol' => 'UNVR', 'name' => 'Unilever Indonesia Tbk', 'market_cap' => 86000000000000, 'pe_ratio' => 21.5, 'pbv_ratio' => 18.2, 'der' => 2.10, 'npm' => 12.8],
                    ['symbol' => 'AMRT', 'name' => 'Sumber Alfaria Trijaya Tbk', 'market_cap' => 128000000000000, 'pe_ratio' => 34.0, 'pbv_ratio' => 8.90, 'der' => 1.25, 'npm' => 3.2],
                    ['symbol' => 'MYOR', 'name' => 'Mayora Indah Tbk', 'market_cap' => 54000000000000, 'pe_ratio' => 17.5, 'pbv_ratio' => 3.20, 'der' => 0.65, 'npm' => 9.4],
                ],
            ],
            [
                'sector_code' => 'IDX-CONSUMER-CYCLICALS',
                'sector_name' => 'Konsumer Non-Primer',
                'avg_der' => 1.15,
                'avg_npm' => 5.4,
                'companies' => [
                    ['symbol' => 'MAPI', 'name' => 'Mitra Adiperkasa Tbk', 'market_cap' => 24000000000000, 'pe_ratio' => 12.0, 'pbv_ratio' => 2.20, 'der' => 1.45, 'npm' => 5.8],
                    ['symbol' => 'ACES', 'name' => 'Aspirasi Hidup Indonesia Tbk', 'market_cap' => 14500000000000, 'pe_ratio' => 17.2, 'pbv_ratio' => 2.45, 'der' => 0.32, 'npm' => 9.8],
                    ['symbol' => 'ERAA', 'name' => 'Erajaya Swasembada Tbk', 'market_cap' => 6800000000000, 'pe_ratio' => 8.1, 'pbv_ratio' => 0.85, 'der' => 1.62, 'npm' => 1.8],
                    ['symbol' => 'AUTO', 'name' => 'Astra Otoparts Tbk', 'market_cap' => 10500000000000, 'pe_ratio' => 5.8, 'pbv_ratio' => 0.72, 'der' => 0.38, 'npm' => 9.2],
                ],
            ],
            [
                'sector_code' => 'IDX-HEALTHCARE',
                'sector_name' => 'Kesehatan',
                'avg_der' => 0.48,
                'avg_npm' => 12.5,
                'companies' => [
                    ['symbol' => 'KLBF', 'name' => 'Kalbe Farma Tbk', 'market_cap' => 74000000000000, 'pe_ratio' => 24.5, 'pbv_ratio' => 3.45, 'der' => 0.22, 'npm' => 9.8],
                    ['symbol' => 'MIKA', 'name' => 'Mitra Keluarga Karyasehat Tbk', 'market_cap' => 42000000000000, 'pe_ratio' => 38.0, 'pbv_ratio' => 6.80, 'der' => 0.15, 'npm' => 26.5],
                    ['symbol' => 'SIDO', 'name' => 'Industri Jamu Dan Farmasi Sido Muncul Tbk', 'market_cap' => 21000000000000, 'pe_ratio' => 19.5, 'pbv_ratio' => 5.90, 'der' => 0.12, 'npm' => 28.2],
                    ['symbol' => 'HEAL', 'name' => 'Medikaloka Hermina Tbk', 'market_cap' => 22500000000000, 'pe_ratio' => 36.2, 'pbv_ratio' => 4.10, 'der' => 0.65, 'npm' => 8.9],
                ],
            ],
            [
                'sector_code' => 'IDX-FINANCIALS',
                'sector_name' => 'Keuangan',
                'avg_der' => 4.80,
                'avg_npm' => 28.2,
                'companies' => [
                    ['symbol' => 'BBCA', 'name' => 'Bank Central Asia Tbk', 'market_cap' => 1240000000000000, 'pe_ratio' => 23.5, 'pbv_ratio' => 4.80, 'der' => 4.10, 'npm' => 46.5],
                    ['symbol' => 'BBRI', 'name' => 'Bank Rakyat Indonesia Tbk', 'market_cap' => 780000000000000, 'pe_ratio' => 13.2, 'pbv_ratio' => 2.45, 'der' => 5.20, 'npm' => 32.1],
                    ['symbol' => 'BMRI', 'name' => 'Bank Mandiri Tbk', 'market_cap' => 630000000000000, 'pe_ratio' => 11.5, 'pbv_ratio' => 2.25, 'der' => 5.50, 'npm' => 35.8],
                    ['symbol' => 'BBNI', 'name' => 'Bank Negara Indonesia Tbk', 'market_cap' => 205000000000000, 'pe_ratio' => 9.8, 'pbv_ratio' => 1.35, 'der' => 5.80, 'npm' => 28.4],
                    ['symbol' => 'BRIS', 'name' => 'Bank Syariah Indonesia Tbk', 'market_cap' => 135000000000000, 'pe_ratio' => 22.0, 'pbv_ratio' => 3.10, 'der' => 6.10, 'npm' => 25.6],
                ],
            ],
            [
                'sector_code' => 'IDX-PROPERTIES',
                'sector_name' => 'Properti & Real Estat',
                'avg_der' => 0.78,
                'avg_npm' => 15.6,
                'companies' => [
                    ['symbol' => 'BSDE', 'name' => 'Bumi Serpong Damai Tbk', 'market_cap' => 24500000000000, 'pe_ratio' => 8.2, 'pbv_ratio' => 0.58, 'der' => 0.65, 'npm' => 24.5],
                    ['symbol' => 'CTRA', 'name' => 'Ciputra Development Tbk', 'market_cap' => 23000000000000, 'pe_ratio' => 11.4, 'pbv_ratio' => 1.05, 'der' => 0.78, 'npm' => 19.8],
                    ['symbol' => 'PWON', 'name' => 'Pakuwon Jati Tbk', 'market_cap' => 21500000000000, 'pe_ratio' => 10.1, 'pbv_ratio' => 1.02, 'der' => 0.45, 'npm' => 29.2],
                    ['symbol' => 'SMRA', 'name' => 'Summarecon Agung Tbk', 'market_cap' => 9500000000000, 'pe_ratio' => 12.8, 'pbv_ratio' => 0.85, 'der' => 1.45, 'npm' => 11.2],
                ],
            ],
            [
                'sector_code' => 'IDX-TECHNOLOGY',
                'sector_name' => 'Teknologi',
                'avg_der' => 0.35,
                'avg_npm' => -14.2,
                'companies' => [
                    ['symbol' => 'GOTO', 'name' => 'GoTo Gojek Tokopedia Tbk', 'market_cap' => 62000000000000, 'pe_ratio' => -8.5, 'pbv_ratio' => 1.85, 'der' => 0.28, 'npm' => -18.5],
                    ['symbol' => 'BUKA', 'name' => 'Bukalapak.com Tbk', 'market_cap' => 12800000000000, 'pe_ratio' => -15.0, 'pbv_ratio' => 0.52, 'der' => 0.08, 'npm' => -25.0],
                    ['symbol' => 'EMTK', 'name' => 'Elang Mahkota Teknologi Tbk', 'market_cap' => 26000000000000, 'pe_ratio' => 32.0, 'pbv_ratio' => 0.88, 'der' => 0.18, 'npm' => 4.2],
                    ['symbol' => 'DCII', 'name' => 'DCI Indonesia Tbk', 'market_cap' => 95000000000000, 'pe_ratio' => 145.0, 'pbv_ratio' => 38.0, 'der' => 0.42, 'npm' => 38.5],
                ],
            ],
            [
                'sector_code' => 'IDX-INFRASTRUCTURES',
                'sector_name' => 'Infrastruktur',
                'avg_der' => 1.85,
                'avg_npm' => 14.8,
                'companies' => [
                    ['symbol' => 'TLKM', 'name' => 'Telkom Indonesia Tbk', 'market_cap' => 310000000000000, 'pe_ratio' => 12.8, 'pbv_ratio' => 2.15, 'der' => 0.95, 'npm' => 16.5],
                    ['symbol' => 'ISAT', 'name' => 'Indosat Tbk', 'market_cap' => 88000000000000, 'pe_ratio' => 18.5, 'pbv_ratio' => 2.80, 'der' => 1.85, 'npm' => 9.5],
                    ['symbol' => 'EXCL', 'name' => 'XL Axiata Tbk', 'market_cap' => 30500000000000, 'pe_ratio' => 22.0, 'pbv_ratio' => 1.15, 'der' => 2.10, 'npm' => 4.8],
                    ['symbol' => 'JSMR', 'name' => 'Jasa Marga Tbk', 'market_cap' => 36000000000000, 'pe_ratio' => 11.2, 'pbv_ratio' => 1.25, 'der' => 2.95, 'npm' => 18.2],
                    ['symbol' => 'PGEO', 'name' => 'Pertamina Geothermal Energy Tbk', 'market_cap' => 48000000000000, 'pe_ratio' => 18.0, 'pbv_ratio' => 1.65, 'der' => 0.45, 'npm' => 35.0],
                ],
            ],
            [
                'sector_code' => 'IDX-TRANSPORTATION',
                'sector_name' => 'Transportasi & Logistik',
                'avg_der' => 1.42,
                'avg_npm' => 8.1,
                'companies' => [
                    ['symbol' => 'BIRD', 'name' => 'Blue Bird Tbk', 'market_cap' => 4800000000000, 'pe_ratio' => 9.8, 'pbv_ratio' => 0.85, 'der' => 0.35, 'npm' => 10.5],
                    ['symbol' => 'SMDR', 'name' => 'Samudera Indonesia Tbk', 'market_cap' => 5200000000000, 'pe_ratio' => 5.1, 'pbv_ratio' => 0.65, 'der' => 0.82, 'npm' => 9.2],
                    ['symbol' => 'TMAS', 'name' => 'Temas Tbk', 'market_cap' => 8900000000000, 'pe_ratio' => 10.5, 'pbv_ratio' => 3.10, 'der' => 0.95, 'npm' => 18.4],
                    ['symbol' => 'ASSA', 'name' => 'Adi Sarana Armada Tbk', 'market_cap' => 2900000000000, 'pe_ratio' => 14.2, 'pbv_ratio' => 1.10, 'der' => 1.95, 'npm' => 3.8],
                ],
            ],
        ];

        foreach ($sectorsData as $sectorItem) {
            $sector = SectorCache::updateOrCreate(
                ['sector_code' => $sectorItem['sector_code']],
                [
                    'sector_name' => $sectorItem['sector_name'],
                    'avg_der' => $sectorItem['avg_der'],
                    'avg_npm' => $sectorItem['avg_npm'],
                    'raw_data' => ['sample_synced' => true, 'source' => 'Sectors API Cache'],
                    'last_synced_at' => $now,
                ]
            );

            foreach ($sectorItem['companies'] as $comp) {
                Company::updateOrCreate(
                    ['symbol' => $comp['symbol']],
                    [
                        'name' => $comp['name'],
                        'sector_id' => $sector->id,
                        'market_cap' => $comp['market_cap'],
                        'pe_ratio' => $comp['pe_ratio'],
                        'pbv_ratio' => $comp['pbv_ratio'],
                        'der' => $comp['der'],
                        'npm' => $comp['npm'],
                        'last_synced_at' => $now,
                    ]
                );
            }
        }

        // 2. Seed Preset Macro Scenarios
        $scenarios = [
            [
                'title' => 'The Fed & BI Naikkan Suku Bunga Acuan (+50 bps)',
                'description' => 'Pengetatan kebijakan moneter global untuk meredam inflasi. Biaya kredit perbankan meningkat, beban bunga utang korporasi membengkak, dan minat belanja kredit properti/otomotif melemah.',
                'category' => 'interest_rate',
                'is_preset' => true,
            ],
            [
                'title' => 'Depresiasi Rupiah Tajam (USD/IDR Melemah ke Rp16.800)',
                'description' => 'Penguatan indeks Dolar AS (DXY) memicu arus modal keluar dari pasar negara berkembang. Beban impor membengkak namun eksportir komoditas berpenghasilan USD diuntungkan.',
                'category' => 'currency',
                'is_preset' => true,
            ],
            [
                'title' => 'Lonjakan Minyak Mentah Dunia (> $95/Bbl) Akibat Eskalasi Geopolitik',
                'description' => 'Gangguan rute pasokan energi di Timur Tengah memicu reli harga minyak mentah dan gas alam dunia. Biaya bahan bakar dan logistik manufaktur melompat tinggi.',
                'category' => 'commodity',
                'is_preset' => true,
            ],
            [
                'title' => 'Disrupsi Rantai Pasok Global & Krisis Kontainer Logistik',
                'description' => 'Kemacetan pelabuhan utama dunia dan penutupan jalur maritim strategis menyebabkan tarif kargo kontainer meroket dan keterlambatan pasokan komponen manufaktur.',
                'category' => 'supply_chain',
                'is_preset' => true,
            ],
            [
                'title' => 'Pemberlakuan Tarif Impor Baru & Perang Dagang Global',
                'description' => 'Negara mitra dagang utama memberlakukan proteksionisme dan bea masuk tinggi terhadap produk manufaktur dan komoditas olahan dari Asia Tenggara.',
                'category' => 'geopolitical',
                'is_preset' => true,
            ],
        ];

        foreach ($scenarios as $scen) {
            MacroScenario::updateOrCreate(
                ['title' => $scen['title']],
                $scen
            );
        }
    }
}
