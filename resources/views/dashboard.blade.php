<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacroSectors AI — Indonesia Capital Market Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .font-mono-num {
            font-family: 'Geist Mono', monospace;
            font-variant-numeric: tabular-nums;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen antialiased selection:bg-emerald-500 selection:text-slate-950" x-data="{ selectedSector: null, customModal: false }">

    <!-- Top Navigation -->
    <header class="border-b border-slate-800/80 bg-slate-900/90 sticky top-0 z-30 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 font-mono-num font-bold text-base">
                    MS
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-slate-100 tracking-tight text-lg">MacroSectors <span class="text-emerald-400">AI</span></span>
                        <span class="px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider bg-slate-800 text-slate-300 rounded border border-slate-700">Track 3: Market Intelligence</span>
                    </div>
                    <p class="text-xs text-slate-400 hidden sm:block">Macro-to-Micro Sector Resilience Engine • Indonesia Capital Market (IHSG)</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium {{ $isSectorsApiConfigured ? 'bg-emerald-950/60 text-emerald-400 border border-emerald-800/60' : 'bg-amber-950/60 text-amber-400 border border-amber-800/60' }}">
                    <span class="w-2 h-2 rounded-full {{ $isSectorsApiConfigured ? 'bg-emerald-400' : 'bg-amber-400 animate-pulse' }}"></span>
                    <span class="font-mono-num text-[11px]">{{ $isSectorsApiConfigured ? 'Sectors API: Live' : 'Sectors Cache: 11 Sectors Ready' }}</span>
                </div>

                <form action="{{ route('sectors.sync') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Sync Sectors</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-3 bg-emerald-950/60 border border-emerald-800 text-emerald-300 text-sm rounded-lg flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <span class="text-xs text-emerald-400">Tersinkron</span>
            </div>
        </div>
    @elseif(session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="p-3 bg-slate-900 border border-slate-800 text-slate-300 text-sm rounded-lg flex items-center justify-between">
                <span>{{ session('info') }}</span>
                <span class="text-xs text-slate-400">Info</span>
            </div>
        </div>
    @endif

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Macro Scenario Control Panel -->
        <section class="bg-slate-900 border border-slate-800 rounded-xl p-5 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-4 border-b border-slate-800">
                <div>
                    <h2 class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Macro Disruption Scenario</h2>
                    <p class="text-lg font-bold text-slate-100 mt-0.5">{{ $activeScenario?->title ?? 'Pilih atau Masukkan Skenario Makro' }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-slate-400">Kategori:</span>
                    <span class="px-2 py-0.5 text-xs font-medium rounded bg-slate-800 text-emerald-400 border border-slate-700 capitalize">
                        {{ str_replace('_', ' ', $activeScenario?->category ?? 'geopolitical') }}
                    </span>
                </div>
            </div>

            <!-- Preset Scenario Chips -->
            <div class="mt-4">
                <p class="text-xs font-medium text-slate-400 mb-2">Pilih Skenario Cepat (Preset):</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($presetScenarios as $preset)
                        <a href="{{ route('dashboard', ['scenario_id' => $preset->id]) }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-medium transition border {{ $activeScenario && $activeScenario->id === $preset->id ? 'bg-emerald-500/15 border-emerald-500/60 text-emerald-300 font-semibold' : 'bg-slate-950 hover:bg-slate-800/80 border-slate-800 text-slate-300' }}">
                            {{ $preset->title }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Custom Scenario Input Form -->
            <form action="{{ route('analyze') }}" method="POST" class="mt-4 pt-4 border-t border-slate-800/60 flex flex-col sm:flex-row gap-2.5">
                @csrf
                <div class="relative flex-1">
                    <input type="text" name="custom_scenario" placeholder="Atau ketik berita / skenario baru (misal: 'Pemerintah menetapkan PPN 12% dan cukai rokok naik')..."
                           class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3.5 py-2 text-sm text-slate-200 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition">
                </div>
                <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold text-xs rounded-lg transition shrink-0 flex items-center justify-center gap-1.5 shadow-sm shadow-emerald-500/20">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span>Analisis Dampak</span>
                </button>
            </form>
        </section>

        <!-- Executive Narrative Panel -->
        @if($analysisData)
            <section class="bg-slate-900 border border-slate-800 rounded-xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">AI Executive Briefing</h3>
                    </div>
                    <span class="text-[11px] font-mono-num text-slate-400 px-2 py-0.5 bg-slate-950 rounded border border-slate-800">
                        {{ $analysisData['engine_used'] }}
                    </span>
                </div>
                <p class="text-sm text-slate-200 leading-relaxed font-normal">
                    {{ $analysisData['executive_summary'] }}
                </p>
                <div class="mt-3 pt-3 border-t border-slate-800/80 flex items-center gap-2 text-xs text-slate-400">
                    <span class="font-semibold text-emerald-400">Key Takeaway:</span>
                    <span>{{ $analysisData['key_takeaway'] }}</span>
                </div>
            </section>
        @endif

        <!-- 11 Sectors Resilience Heatmap -->
        <section class="space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h3 class="text-base font-bold text-slate-100 tracking-tight">Peta Ketahanan 11 Sektor IHSG (Resilience Heatmap)</h3>
                    <p class="text-xs text-slate-400">Skor dampak dari <span class="font-mono-num text-rose-400 font-semibold">-10 (Kritis)</span> hingga <span class="font-mono-num text-emerald-400 font-semibold">+10 (Sangat Diuntungkan)</span>. Klik sektor untuk melihat rincian emiten.</p>
                </div>

                <!-- Legend -->
                <div class="flex items-center gap-3 text-xs text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded bg-emerald-500"></span>
                        <span>Resilient (+1 s/d +10)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded bg-slate-600"></span>
                        <span>Netral (0)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded bg-rose-500"></span>
                        <span>Vulnerable (-1 s/d -10)</span>
                    </div>
                </div>
            </div>

            <!-- Heatmap Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3.5">
                @forelse($sectorResults as $item)
                    @php
                        $score = $item->impact_score;
                        $isPositive = $score > 0;
                        $isNegative = $score < 0;
                        $badgeBg = $isPositive ? 'bg-emerald-950/50 text-emerald-400 border-emerald-800/60' : ($isNegative ? 'bg-rose-950/50 text-rose-400 border-rose-800/60' : 'bg-slate-800 text-slate-300 border-slate-700');
                        $scoreBg = $isPositive ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/30' : ($isNegative ? 'text-rose-400 bg-rose-500/10 border-rose-500/30' : 'text-slate-400 bg-slate-800 border-slate-700');
                        $barColor = $isPositive ? 'bg-emerald-500' : ($isNegative ? 'bg-rose-500' : 'bg-slate-500');
                        $barPercent = abs($score) * 10;
                    @endphp

                    <div @click="selectedSector = {{ json_encode([
                        'name' => $item->sector->sector_name,
                        'code' => $item->sector->sector_code,
                        'score' => $score,
                        'status' => $item->resilience_status,
                        'reasoning' => $item->reasoning,
                        'avg_der' => $item->sector->avg_der,
                        'avg_npm' => $item->sector->avg_npm,
                        'companies' => $item->sector->companies,
                        'vulnerable_companies' => $item->vulnerable_companies ?? [],
                        'beneficiary_companies' => $item->beneficiary_companies ?? []
                    ]) }}"
                         class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-xl p-4 transition cursor-pointer hover:shadow-lg hover:-translate-y-0.5 group flex flex-col justify-between">
                        
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-2.5">
                                <div>
                                    <span class="text-[10px] font-mono-num uppercase tracking-wider text-slate-400">{{ $item->sector->sector_code }}</span>
                                    <h4 class="font-bold text-slate-100 text-base group-hover:text-emerald-400 transition">{{ $item->sector->sector_name }}</h4>
                                </div>
                                <div class="px-2 py-1 rounded-md border font-mono-num font-bold text-sm {{ $scoreBg }}">
                                    {{ $score > 0 ? '+' : '' }}{{ $score }}
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="flex items-center justify-between text-[11px] font-mono-num text-slate-400 mb-1">
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-medium border {{ $badgeBg }}">{{ $item->resilience_status }}</span>
                                    <span>DER: {{ $item->sector->avg_der }}x • NPM: {{ $item->sector->avg_npm }}%</span>
                                </div>
                                <div class="w-full bg-slate-950 h-1.5 rounded-full overflow-hidden">
                                    <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $barPercent }}%"></div>
                                </div>
                            </div>

                            <p class="text-xs text-slate-300 line-clamp-3 leading-relaxed">
                                {{ $item->reasoning }}
                            </p>
                        </div>

                        <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs text-slate-400">
                            <span class="font-mono-num text-[11px]">{{ $item->sector->companies->count() }} Emiten Terpantau</span>
                            <span class="text-emerald-400 font-semibold group-hover:underline flex items-center gap-1 text-[11px]">
                                Deep Dive
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center bg-slate-900 border border-slate-800 rounded-xl">
                        <p class="text-slate-400 text-sm">Belum ada hasil analisis untuk skenario ini.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Company Exposure Modal / Drawer (Alpine.js) -->
        <div x-show="selectedSector !== null" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
            
            <div @click.away="selectedSector = null" 
                 class="bg-slate-900 border border-slate-800 rounded-2xl max-w-3xl w-full p-6 shadow-2xl space-y-5 animate-in fade-in zoom-in-95 duration-200">
                
                <!-- Modal Header -->
                <div class="flex items-start justify-between border-b border-slate-800 pb-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-mono-num text-slate-400" x-text="selectedSector?.code"></span>
                            <span class="px-2 py-0.5 rounded text-xs font-mono-num font-bold"
                                  :class="selectedSector?.score > 0 ? 'bg-emerald-950 text-emerald-400 border border-emerald-800' : (selectedSector?.score < 0 ? 'bg-rose-950 text-rose-400 border border-rose-800' : 'bg-slate-800 text-slate-300')">
                                Impact: <span x-text="(selectedSector?.score > 0 ? '+' : '') + selectedSector?.score"></span>
                            </span>
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700" x-text="selectedSector?.status"></span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-100" x-text="selectedSector?.name"></h3>
                    </div>
                    <button @click="selectedSector = null" class="text-slate-400 hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Sector Economic Reasoning -->
                <div class="bg-slate-950 border border-slate-800/80 rounded-xl p-4 space-y-2">
                    <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Transmisi Makro & Rasionalisasi Sektor</h5>
                    <p class="text-sm text-slate-200 leading-relaxed" x-text="selectedSector?.reasoning"></p>
                    <div class="flex items-center gap-4 text-xs font-mono-num text-slate-400 pt-2 border-t border-slate-800/60">
                        <span>Rata-rata DER: <strong class="text-slate-200" x-text="selectedSector?.avg_der + 'x'"></strong></span>
                        <span>Rata-rata NPM: <strong class="text-slate-200" x-text="selectedSector?.avg_npm + '%'"></strong></span>
                    </div>
                </div>

                <!-- Companies Table -->
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Daftar Emiten Utama & Rasio Fundamental (Sectors API)</h5>
                        <span class="text-xs text-slate-400">Data Fundamental Riil</span>
                    </div>

                    <div class="border border-slate-800 rounded-xl overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-950 text-slate-400 uppercase font-mono-num text-[11px] border-b border-slate-800">
                                <tr>
                                    <th class="px-3.5 py-2.5 font-semibold">Ticker</th>
                                    <th class="px-3.5 py-2.5 font-semibold">Nama Perusahaan</th>
                                    <th class="px-3.5 py-2.5 font-semibold text-right">Market Cap</th>
                                    <th class="px-3.5 py-2.5 font-semibold text-right">DER</th>
                                    <th class="px-3.5 py-2.5 font-semibold text-right">NPM</th>
                                    <th class="px-3.5 py-2.5 font-semibold text-right">P/E</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/80">
                                <template x-for="comp in selectedSector?.companies" :key="comp.id">
                                    <tr class="hover:bg-slate-800/40 transition">
                                        <td class="px-3.5 py-2.5 font-mono-num font-bold text-slate-100 flex items-center gap-1.5">
                                            <span x-text="comp.symbol"></span>
                                            <template x-if="selectedSector?.vulnerable_companies?.includes(comp.symbol)">
                                                <span class="px-1.5 py-0.2 rounded text-[10px] bg-rose-950 text-rose-400 border border-rose-800">Rentan</span>
                                            </template>
                                            <template x-if="selectedSector?.beneficiary_companies?.includes(comp.symbol)">
                                                <span class="px-1.5 py-0.2 rounded text-[10px] bg-emerald-950 text-emerald-400 border border-emerald-800">Tangguh</span>
                                            </template>
                                        </td>
                                        <td class="px-3.5 py-2.5 text-slate-300" x-text="comp.name"></td>
                                        <td class="px-3.5 py-2.5 font-mono-num text-right text-slate-200" x-text="(comp.market_cap / 1000000000000).toFixed(1) + ' T'"></td>
                                        <td class="px-3.5 py-2.5 font-mono-num text-right" 
                                            :class="comp.der > 1.5 ? 'text-rose-400 font-semibold' : 'text-slate-300'"
                                            x-text="comp.der ? comp.der + 'x' : '-'"></td>
                                        <td class="px-3.5 py-2.5 font-mono-num text-right" 
                                            :class="comp.npm < 0 ? 'text-rose-400 font-semibold' : 'text-emerald-400 font-semibold'"
                                            x-text="comp.npm ? comp.npm + '%' : '-'"></td>
                                        <td class="px-3.5 py-2.5 font-mono-num text-right text-slate-400" x-text="comp.pe_ratio ? comp.pe_ratio + 'x' : '-'"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="pt-2 flex justify-end">
                    <button @click="selectedSector = null" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-lg transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Institutional Regulatory Disclaimer Banner -->
        <footer class="mt-12 pt-6 border-t border-slate-800/80 text-xs text-slate-400 space-y-2">
            <div class="flex items-center gap-2 text-slate-400 font-semibold">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>LEGAL & INVESTMENT RISK DISCLAIMER</span>
            </div>
            <p class="leading-relaxed">
                Platform <strong>MacroSectors AI</strong> dirancang secara eksklusif sebagai instrumen simulasi analisis riset kuantitatif pasar modal. Seluruh luaran, skor dampak, dan sintesis naratif merupakan hasil inferensi model pemrosesan data berbasis Sectors REST API dan model komputasi, <strong>bukan merupakan rekomendasi investasi, anjuran beli/jual saham, maupun nasihat keuangan legal</strong> dalam yurisdiksi Republik Indonesia. Investor diwajibkan melakukan due diligence mandiri (*Do Your Own Research*) sebelum mengambil keputusan alokasi modal.
            </p>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-[11px] text-slate-400 pt-2 border-t border-slate-900">
                <span>© 2026 MacroSectors AI • Author: Wesley Wilnio • Sectors Hackathon Indonesia 2026</span>
                <span>Powered by Sectors REST API & Modern Quantitative Macro Models</span>
            </div>
        </footer>

    </main>

</body>
</html>
