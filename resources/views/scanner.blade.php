<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Macro Vulnerability & Red-Line Scanner — MacroSectors AI</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Geist+Mono:wght@400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        .num-tabular {
            font-family: 'Geist Mono', monospace;
            font-variant-numeric: tabular-nums;
        }
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="min-h-full flex flex-col sm:flex-row bg-slate-50 text-slate-800 antialiased selection:bg-blue-600 selection:text-white"
      x-data="{ mobileMenuOpen: false }">

    <!-- Mobile Top Navigation Header -->
    <div class="sm:hidden bg-white border-b border-slate-200 p-4 flex items-center justify-between no-print">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm">MS</div>
            <span class="font-bold text-slate-900 text-sm">MacroSectors AI</span>
        </a>
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-600 hover:text-slate-900 cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Drawer -->
    <div x-show="mobileMenuOpen" x-cloak class="sm:hidden fixed inset-0 z-50 bg-slate-900/60 no-print" @click="mobileMenuOpen = false">
        <div class="w-64 h-full bg-white shadow-xl" @click.stop>
            @include('layouts.sidebar')
        </div>
    </div>

    <!-- Desktop Persistent Sidebar -->
    <div class="hidden sm:block shrink-0 sticky top-0 h-screen z-40 no-print">
        @include('layouts.sidebar')
    </div>

    <!-- Main Content Workspace -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top App Bar -->
        <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 shadow-xs px-6 py-4 sm:py-5 flex items-center justify-between no-print">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center font-bold shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span>Macro Vulnerability & Red-Line Scanner</span>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200 font-bold uppercase tracking-wider">EWS</span>
                    </h2>
                    <p class="text-xs text-slate-500">Early Warning System: Deteksi Ambang Batas Solvabilitas & Marjin 49 Emiten BEI</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Monitoring 49 Emiten (11 Sektor)</span>
                </div>
                <button type="button" onclick="window.print()" class="px-3.5 py-2 text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-white rounded-xl shadow-xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak PDF</span>
                </button>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-8 pt-6 sm:pt-8 pb-16 flex flex-col gap-8">

            <!-- 1. STRESS SCENARIO CONTROL DECK -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <span>Skenario Stres Makro (Preset 1-Klik & Kustomisasi)</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">Uji ambang batas ketahanan likuiditas emiten terhadap kenaikan suku bunga dan kurs valas</p>
                    </div>
                    <span class="text-xs text-slate-400 font-mono bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200/80">
                        Baseline Kurs Acuan: Rp16.000 / USD
                    </span>
                </div>

                <!-- 3 Preset Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                    <!-- Preset Mild -->
                    <a href="{{ route('scanner.index', ['preset' => 'mild']) }}" 
                       class="p-4 rounded-xl border transition-all flex flex-col justify-between gap-2.5 {{ ($rateHike == 25 && $usdRate == 16200) ? 'bg-blue-50/70 border-blue-400 ring-2 ring-blue-500/20 shadow-xs' : 'bg-slate-50/80 hover:bg-white border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-extrabold text-slate-900 flex items-center gap-1.5">
                                <span>⚡</span>
                                <span>Mild Soft-Landing</span>
                            </span>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-md font-bold {{ ($rateHike == 25 && $usdRate == 16200) ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-800' }}">
                                Ringan
                            </span>
                        </div>
                        <div class="space-y-1 text-xs text-slate-600">
                            <div class="flex items-center justify-between font-mono text-[11px]">
                                <span>BI-Rate: <strong>+25 bps</strong></span>
                                <span>Kurs: <strong>Rp16.200</strong></span>
                            </div>
                            <p class="text-[11px] text-slate-400 font-sans">Inflasi Biaya Input: +1.0%</p>
                        </div>
                    </a>

                    <!-- Preset Moderate -->
                    <a href="{{ route('scanner.index', ['preset' => 'moderate']) }}" 
                       class="p-4 rounded-xl border transition-all flex flex-col justify-between gap-2.5 {{ ($rateHike == 50 && $usdRate == 16500) ? 'bg-amber-50/70 border-amber-400 ring-2 ring-amber-500/20 shadow-xs' : 'bg-slate-50/80 hover:bg-white border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-extrabold text-slate-900 flex items-center gap-1.5">
                                <span>⚠️</span>
                                <span>Moderate FX Pressure</span>
                            </span>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-md font-bold {{ ($rateHike == 50 && $usdRate == 16500) ? 'bg-amber-600 text-white' : 'bg-amber-100 text-amber-800' }}">
                                Sedang
                            </span>
                        </div>
                        <div class="space-y-1 text-xs text-slate-600">
                            <div class="flex items-center justify-between font-mono text-[11px]">
                                <span>BI-Rate: <strong>+50 bps</strong></span>
                                <span>Kurs: <strong>Rp16.500</strong></span>
                            </div>
                            <p class="text-[11px] text-slate-400 font-sans">Inflasi Biaya Input: +2.0%</p>
                        </div>
                    </a>

                    <!-- Preset Severe -->
                    <a href="{{ route('scanner.index', ['preset' => 'severe']) }}" 
                       class="p-4 rounded-xl border transition-all flex flex-col justify-between gap-2.5 {{ ($rateHike == 150 && $usdRate == 17200) ? 'bg-rose-50/70 border-rose-400 ring-2 ring-rose-500/20 shadow-xs' : 'bg-slate-50/80 hover:bg-white border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-extrabold text-slate-900 flex items-center gap-1.5">
                                <span>🚨</span>
                                <span>Severe Liquidity Crisis</span>
                            </span>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-md font-bold {{ ($rateHike == 150 && $usdRate == 17200) ? 'bg-rose-600 text-white' : 'bg-rose-100 text-rose-800' }}">
                                Kritis
                            </span>
                        </div>
                        <div class="space-y-1 text-xs text-slate-600">
                            <div class="flex items-center justify-between font-mono text-[11px]">
                                <span>BI-Rate: <strong>+150 bps</strong></span>
                                <span>Kurs: <strong>Rp17.200</strong></span>
                            </div>
                            <p class="text-[11px] text-slate-400 font-sans">Inflasi Biaya Input: +4.5%</p>
                        </div>
                    </a>
                </div>

                <!-- Custom Parameter Inline Deck -->
                <form method="GET" action="{{ route('scanner.index') }}" class="pt-3 border-t border-slate-100">
                    <input type="hidden" name="zone_filter" value="{{ $zoneFilter }}">
                    <input type="hidden" name="sector_filter" value="{{ $sectorFilter }}">
                    <input type="hidden" name="sort_by" value="{{ $sortBy }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 items-end">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Kenaikan Suku Bunga Acuan</label>
                            <div class="relative">
                                <input type="number" name="rate_hike" value="{{ $rateHike }}" min="0" max="500" step="25"
                                       class="w-full text-xs font-mono bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all pr-12" />
                                <span class="absolute right-3.5 top-2.5 text-xs text-slate-400 font-mono font-semibold">bps</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Target Nilai Tukar USD / IDR</label>
                            <div class="relative">
                                <input type="number" name="usd_rate" value="{{ $usdRate }}" min="15000" max="22000" step="100"
                                       class="w-full text-xs font-mono bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all pr-12" />
                                <span class="absolute right-3.5 top-2.5 text-xs text-slate-400 font-mono font-semibold">IDR</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">Inflasi Biaya Bahan Baku (COGS)</label>
                            <div class="relative">
                                <input type="number" name="cogs_inflation" value="{{ $cogsInflation }}" min="0" max="20" step="0.5"
                                       class="w-full text-xs font-mono bg-slate-50 hover:bg-white focus:bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all pr-8" />
                                <span class="absolute right-3.5 top-2.5 text-xs text-slate-400 font-mono font-semibold">%</span>
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="w-full h-[39px] px-4 text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <span>Pindai Ulang Red-Line</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 2. SUMMARY KPI STATS (Spacious 4-Column Grid) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-xs">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Emiten Terpindai</p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-slate-900 num-tabular">{{ $totalScanned }}</p>
                    <p class="text-xs text-slate-500 mt-2 font-medium">11 Sektor Resmi Bursa Efek</p>
                </div>

                <div class="bg-rose-50/70 rounded-2xl border border-rose-200 p-5 sm:p-6 shadow-xs">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-xs font-bold text-rose-800 uppercase tracking-wider">🔴 Red-Line Breached</p>
                        <span class="text-[11px] font-mono px-2 py-0.5 rounded-full bg-rose-200/80 text-rose-900 font-extrabold">{{ $redPercentage }}%</span>
                    </div>
                    <p class="text-3xl sm:text-4xl font-extrabold text-rose-700 num-tabular">{{ $totalRed }}</p>
                    <p class="text-xs text-rose-700/90 mt-2 font-medium">Defisit Laba / Solvabilitas Kritis</p>
                </div>

                <div class="bg-amber-50/70 rounded-2xl border border-amber-200 p-5 sm:p-6 shadow-xs">
                    <p class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-1">🟡 Warning Zone</p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-amber-700 num-tabular">{{ $totalWarning }}</p>
                    <p class="text-xs text-amber-700/90 mt-2 font-medium">Marjin Tergerus Berat (&lt;4.0%)</p>
                </div>

                <div class="bg-emerald-50/70 rounded-2xl border border-emerald-200 p-5 sm:p-6 shadow-xs">
                    <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">🟢 Safe & Resilient</p>
                    <p class="text-3xl sm:text-4xl font-extrabold text-emerald-700 num-tabular">{{ $totalSafe }}</p>
                    <p class="text-xs text-emerald-700/90 mt-2 font-medium">Bantalan Laba & Kas Tebal</p>
                </div>
            </div>

            <!-- 3. TOP VULNERABLE VS TOP RESILIENT (Polished Side-by-Side Cards) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Top 3 Most Vulnerable -->
                <div class="bg-white rounded-2xl border border-rose-200 p-6 shadow-sm flex flex-col justify-between gap-4">
                    <div>
                        <div class="flex items-center justify-between border-b border-rose-100 pb-3 mb-4">
                            <h4 class="text-xs font-bold text-rose-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                                <span>3 Emiten Paling Rentan (Top Red-Line Breached)</span>
                            </h4>
                            <span class="text-[10px] font-mono text-rose-700 bg-rose-100 px-2 py-0.5 rounded-md font-bold">
                                Prioritas Exit / Hedging
                            </span>
                        </div>

                        <div class="space-y-3">
                            @foreach($topVulnerable as $item)
                                <div class="p-4 bg-slate-50 hover:bg-rose-50/40 rounded-xl border border-slate-200/80 hover:border-rose-200 transition-all flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-800 border border-rose-200 flex items-center justify-center font-mono font-bold text-xs shrink-0">
                                            {{ substr($item['symbol'], 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-extrabold text-slate-900">{{ $item['symbol'] }}</span>
                                                <span class="text-xs text-slate-500">{{ $item['sector_name'] }}</span>
                                            </div>
                                            <span class="inline-block mt-0.5 text-[11px] font-semibold text-rose-700">
                                                {{ $item['risk_verdict'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <div class="text-[11px] text-slate-400 font-mono">NPM Awal &rarr; Proyeksi</div>
                                        <div class="text-xs font-bold num-tabular flex items-center justify-end gap-1.5 mt-0.5">
                                            <span class="text-slate-400 font-normal">{{ $item['npm'] }}%</span>
                                            <span class="text-slate-400">&rarr;</span>
                                            <span class="text-rose-700 font-extrabold text-sm">{{ $item['projected_npm'] }}%</span>
                                        </div>
                                        <span class="text-[10px] font-mono font-semibold text-rose-600">
                                            Shock: -{{ $item['total_compression'] }}%
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 italic border-t border-slate-100 pt-3">
                        *Emiten di atas memiliki DER tinggi dan marjin awal tipis, sehingga kenaikan suku bunga dan kurs langsung memotong habis laba operasional.
                    </p>
                </div>

                <!-- Top 3 Most Resilient -->
                <div class="bg-white rounded-2xl border border-emerald-200 p-6 shadow-sm flex flex-col justify-between gap-4">
                    <div>
                        <div class="flex items-center justify-between border-b border-emerald-100 pb-3 mb-4">
                            <h4 class="text-xs font-bold text-emerald-900 uppercase tracking-wider flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                                <span>3 Emiten Paling Kokoh (Top Shock Resilient)</span>
                            </h4>
                            <span class="text-[10px] font-mono text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md font-bold">
                                Defensive Anchor
                            </span>
                        </div>

                        <div class="space-y-3">
                            @foreach($topResilient as $item)
                                <div class="p-4 bg-slate-50 hover:bg-emerald-50/40 rounded-xl border border-slate-200/80 hover:border-emerald-200 transition-all flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center justify-center font-mono font-bold text-xs shrink-0">
                                            {{ substr($item['symbol'], 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-extrabold text-slate-900">{{ $item['symbol'] }}</span>
                                                <span class="text-xs text-slate-500">{{ $item['sector_name'] }}</span>
                                            </div>
                                            <span class="inline-block mt-0.5 text-[11px] font-semibold text-emerald-700">
                                                {{ $item['risk_verdict'] }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <div class="text-[11px] text-slate-400 font-mono">NPM Awal &rarr; Proyeksi</div>
                                        <div class="text-xs font-bold num-tabular flex items-center justify-end gap-1.5 mt-0.5">
                                            <span class="text-slate-400 font-normal">{{ $item['npm'] }}%</span>
                                            <span class="text-slate-400">&rarr;</span>
                                            <span class="text-emerald-700 font-extrabold text-sm">{{ $item['projected_npm'] }}%</span>
                                        </div>
                                        <span class="text-[10px] font-mono font-semibold text-emerald-600">
                                            Buffer: +{{ $item['projected_npm'] }}%
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 italic border-t border-slate-100 pt-3">
                        *Didukung pendapatan valas USD (natural hedge) atau marjin laba tebal dengan rasio utang (DER) sangat konservatif.
                    </p>
                </div>
            </div>

            <!-- 4. SECTOR RISK CONCENTRATION (Clean, Spacious Cards with Toggle) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm" x-data="{ showAllSectors: false }">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-600"></span>
                            <span>Konsentrasi Risiko Red-Line per 11 Sektor IHSG</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">Persentase emiten dalam setiap sektor yang melanggar batas aman solvabilitas atau defisit laba</p>
                    </div>

                    <button type="button" @click="showAllSectors = !showAllSectors" 
                            class="text-xs font-semibold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3.5 py-2 rounded-xl border border-blue-200 transition-all flex items-center gap-1.5 cursor-pointer self-start sm:self-auto shadow-2xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <span x-text="showAllSectors ? 'Tampilkan 6 Sektor Teratas' : 'Lihat Seluruh 11 Sektor (' + {{ count($sectorRiskMap) }} + ')'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($sectorRiskMap as $index => $sec)
                        <div x-show="showAllSectors || {{ $index }} < 6" 
                             x-cloak
                             class="p-4 bg-slate-50/80 border border-slate-200 rounded-xl flex flex-col justify-between gap-3 hover:bg-white hover:border-slate-300 transition-all">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 truncate">{{ $sec['sector_name'] }}</span>
                                <span class="text-[10px] font-mono font-bold px-2 py-0.5 rounded-md {{ $sec['red_ratio'] > 40 ? 'bg-rose-100 text-rose-800 border border-rose-200' : ($sec['red_ratio'] > 0 ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200') }}">
                                    {{ $sec['red_ratio'] }}% Red
                                </span>
                            </div>

                            <!-- Clean Solid Progress Bar -->
                            <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all {{ $sec['red_ratio'] > 40 ? 'bg-rose-500' : ($sec['red_ratio'] > 0 ? 'bg-amber-500' : 'bg-emerald-500') }}" 
                                     style="width: {{ max(6, $sec['red_ratio']) }}%"></div>
                            </div>

                            <!-- Metric Pills -->
                            <div class="flex items-center justify-between text-xs font-mono pt-1 border-t border-slate-200/60">
                                <span class="text-rose-700 font-semibold">Kritis: <strong>{{ $sec['red_count'] }}</strong></span>
                                <span class="text-amber-700 font-semibold">Waspada: <strong>{{ $sec['warning_count'] }}</strong></span>
                                <span class="text-emerald-700 font-semibold">Aman: <strong>{{ $sec['safe_count'] }}</strong></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 5. CORE EMITEN SCREENING TABLE (Spacious 6-Column Institutional Layout) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Table Filter & Search Header -->
                <div class="p-6 border-b border-slate-200 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <span>Daftar 49 Emiten Terpindai</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">Hasil stres uji solvabilitas leverage dan daya tahan marjin operasional</p>
                    </div>

                    <!-- Filters & Sort -->
                    <form method="GET" action="{{ route('scanner.index') }}" class="flex flex-wrap items-center gap-2.5 text-xs">
                        <input type="hidden" name="rate_hike" value="{{ $rateHike }}">
                        <input type="hidden" name="usd_rate" value="{{ $usdRate }}">
                        <input type="hidden" name="cogs_inflation" value="{{ $cogsInflation }}">

                        <!-- Zone Filter -->
                        <select name="zone_filter" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="all" {{ $zoneFilter === 'all' ? 'selected' : '' }}>Semua Zona ({{ $totalScanned }})</option>
                            <option value="red" {{ $zoneFilter === 'red' ? 'selected' : '' }}>🔴 Hanya Red-Line ({{ $totalRed }})</option>
                            <option value="warning" {{ $zoneFilter === 'warning' ? 'selected' : '' }}>🟡 Warning Zone ({{ $totalWarning }})</option>
                            <option value="safe" {{ $zoneFilter === 'safe' ? 'selected' : '' }}>🟢 Safe Zone ({{ $totalSafe }})</option>
                        </select>

                        <!-- Sector Filter -->
                        <select name="sector_filter" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-700 font-semibold max-w-[180px] focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="all">Semua Sektor</option>
                            @foreach($allSectors as $s)
                                <option value="{{ $s->sector_code }}" {{ strcasecmp($sectorFilter, $s->sector_code) === 0 ? 'selected' : '' }}>
                                    {{ $s->sector_name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Sort By -->
                        <select name="sort_by" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="vulnerability_desc" {{ $sortBy === 'vulnerability_desc' ? 'selected' : '' }}>Kerentanan Tertinggi</option>
                            <option value="projected_npm_asc" {{ $sortBy === 'projected_npm_asc' ? 'selected' : '' }}>Proyeksi NPM Terendah</option>
                            <option value="der_desc" {{ $sortBy === 'der_desc' ? 'selected' : '' }}>DER (Utang) Tertinggi</option>
                            <option value="market_cap_desc" {{ $sortBy === 'market_cap_desc' ? 'selected' : '' }}>Market Cap Terbesar</option>
                        </select>

                        <!-- Per Page Dropdown -->
                        <select name="per_page" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 / hal</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25 / hal</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>Semua (50)</option>
                        </select>
                    </form>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider select-none">
                                <th class="px-6 py-4">Ticker & Nama Emiten</th>
                                <th class="px-5 py-4 text-center">Sektor</th>
                                <th class="px-5 py-4 text-center">Status Zona & Skor</th>
                                <th class="px-5 py-4 text-right">Leverage (DER)</th>
                                <th class="px-6 py-4 text-right">Marjin: Awal &rarr; Proyeksi</th>
                                <th class="px-6 py-4">Diagnosa & Beban Shock</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm num-tabular">
                            @forelse($paginatedCompanies as $c)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <!-- 1. Ticker & Name -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs font-mono {{ $c['zone'] === 'red' ? 'bg-rose-50 text-rose-700 border border-rose-200' : ($c['zone'] === 'warning' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                                {{ substr($c['symbol'], 0, 2) }}
                                            </div>
                                            <div>
                                                <span class="font-extrabold text-slate-900 text-sm block">{{ $c['symbol'] }}</span>
                                                <span class="text-xs text-slate-400 font-sans block truncate max-w-[170px]">{{ $c['name'] }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- 2. Sector -->
                                    <td class="px-5 py-4 text-center">
                                        <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200/80">
                                            {{ $c['sector_name'] }}
                                        </span>
                                    </td>

                                    <!-- 3. Status Zone & Score -->
                                    <td class="px-5 py-4 text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="px-3 py-0.5 rounded-full text-[11px] font-bold border {{ $c['zone_badge'] }}">
                                                {{ $c['zone_label'] }}
                                            </span>
                                            <span class="text-[11px] font-mono font-bold text-slate-500">
                                                Skor: {{ $c['vulnerability_score'] }}/100
                                            </span>
                                        </div>
                                    </td>

                                    <!-- 4. Leverage (DER) -->
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex flex-col items-end">
                                            <span class="font-bold text-sm {{ $c['der'] >= 2.0 ? 'text-rose-600' : ($c['der'] >= 1.2 ? 'text-amber-600' : 'text-slate-800') }}">
                                                {{ number_format($c['der'], 2) }}x
                                            </span>
                                            <span class="text-[10px] font-sans {{ $c['der'] >= 2.0 ? 'text-rose-500 font-semibold' : ($c['der'] >= 1.2 ? 'text-amber-500' : 'text-slate-400') }}">
                                                {{ $c['der'] >= 2.0 ? 'Sangat Tinggi' : ($c['der'] >= 1.2 ? 'Moderat' : 'Konservatif') }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- 5. Margin Impact (NPM Initial -> Projected) -->
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex flex-col items-end">
                                            <div class="flex items-center gap-1.5 font-bold">
                                                <span class="text-xs text-slate-400 line-through">{{ number_format($c['npm'], 1) }}%</span>
                                                <span class="text-slate-400 text-xs">&rarr;</span>
                                                <span class="text-sm font-extrabold {{ $c['projected_npm'] <= 0 ? 'text-rose-600' : ($c['projected_npm'] <= 4.0 ? 'text-amber-600' : 'text-emerald-600') }}">
                                                    {{ number_format($c['projected_npm'], 1) }}%
                                                </span>
                                            </div>
                                            <span class="text-[11px] text-rose-600 font-semibold font-mono">
                                                Total Kompresi: -{{ number_format($c['total_compression'], 1) }}%
                                            </span>
                                        </div>
                                    </td>

                                    <!-- 6. Diagnosis & Breakdown -->
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1 max-w-sm">
                                            <span class="font-bold text-xs {{ $c['zone'] === 'red' ? 'text-rose-900' : ($c['zone'] === 'warning' ? 'text-amber-900' : 'text-emerald-900') }}">
                                                {{ $c['risk_verdict'] }}
                                            </span>
                                            <div class="flex flex-wrap gap-1.5 text-[10px] font-mono text-slate-500">
                                                <span class="bg-slate-100 px-1.5 py-0.5 rounded">Bunga: -{{ $c['interest_shock'] }}%</span>
                                                <span class="bg-slate-100 px-1.5 py-0.5 rounded">Kurs: {{ $c['fx_shock'] > 0 ? '-'.$c['fx_shock'] : '+'.abs($c['fx_shock']) }}%</span>
                                                <span class="bg-slate-100 px-1.5 py-0.5 rounded">COGS: -{{ $c['cogs_shock'] }}%</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                        Tidak ada emiten yang memenuhi kriteria filter terpilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer Bar -->
                @if($paginatedCompanies->total() > 0)
                    <div class="p-5 border-t border-slate-200 bg-slate-50/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                        <div class="text-slate-600 font-medium">
                            Menampilkan <span class="font-extrabold text-slate-900">{{ $paginatedCompanies->firstItem() }}</span> &ndash; <span class="font-extrabold text-slate-900">{{ $paginatedCompanies->lastItem() }}</span> dari <span class="font-extrabold text-slate-900">{{ $paginatedCompanies->total() }}</span> emiten terpindai
                        </div>

                        <!-- Pagination Navigation Buttons -->
                        @if($paginatedCompanies->hasPages())
                            <div class="flex items-center gap-1.5">
                                {{-- Previous Button --}}
                                @if ($paginatedCompanies->onFirstPage())
                                    <span class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-300 bg-slate-100 cursor-not-allowed text-xs font-semibold">
                                        &larr; Prev
                                    </span>
                                @else
                                    <a href="{{ $paginatedCompanies->previousPageUrl() }}" class="px-3 py-1.5 rounded-xl border border-slate-300 text-slate-700 bg-white hover:bg-slate-100 transition-all text-xs font-semibold shadow-2xs">
                                        &larr; Prev
                                    </a>
                                @endif

                                {{-- Page Number Links --}}
                                @foreach ($paginatedCompanies->getUrlRange(1, $paginatedCompanies->lastPage()) as $pageNumber => $url)
                                    @if ($pageNumber == $paginatedCompanies->currentPage())
                                        <span class="w-8 h-8 flex items-center justify-center rounded-xl bg-blue-600 text-white font-bold text-xs shadow-xs">
                                            {{ $pageNumber }}
                                        </span>
                                    @else
                                        <a href="{{ $url }}" class="w-8 h-8 flex items-center justify-center rounded-xl border border-slate-300 text-slate-700 bg-white hover:bg-slate-100 transition-all text-xs font-semibold shadow-2xs">
                                            {{ $pageNumber }}
                                        </a>
                                    @endif
                                @endforeach

                                {{-- Next Button --}}
                                @if ($paginatedCompanies->hasMorePages())
                                    <a href="{{ $paginatedCompanies->nextPageUrl() }}" class="px-3 py-1.5 rounded-xl border border-slate-300 text-slate-700 bg-white hover:bg-slate-100 transition-all text-xs font-semibold shadow-2xs">
                                        Next &rarr;
                                    </a>
                                @else
                                    <span class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-300 bg-slate-100 cursor-not-allowed text-xs font-semibold">
                                        Next &rarr;
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- 6. Regulatory Disclaimer -->
            <div class="border-t border-slate-200 pt-6 text-center text-xs text-slate-400 space-y-1.5">
                <p>
                    <strong>Pernyataan Kepatuhan:</strong> Fitur Macro Vulnerability & Red-Line Scanner merupakan instrumen simulasi kuantitatif berbasis rasio neraca dan laba rugi emiten. Bukan merupakan rekomendasi jual/beli efek.
                </p>
                <p class="text-[11px] font-mono text-slate-400">
                    Sectors Hackathon 2026 &bull; MacroSectors AI Intelligence Engine
                </p>
            </div>

        </main>
    </div>

</body>
</html>
