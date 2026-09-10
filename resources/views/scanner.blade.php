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
                <div class="w-8 h-8 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span>Macro Vulnerability & Red-Line Scanner</span>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200 font-bold uppercase">Early Warning System</span>
                    </h2>
                    <p class="text-xs text-slate-500">Deteksi Titik Kritis Kebocoran Laba & Sensitivitas Neraca 49 Emiten BEI</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Scanner Aktif: {{ $totalScanned }} Emiten</span>
                </div>
                <button onclick="window.print()" class="px-3.5 py-1.5 text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-white rounded-xl shadow-xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-16 flex flex-col gap-8">

            <!-- 1. Quick Stress Presets (One-Click Scenarios) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            <span>Preset Guncangan Makro Cepat (1-Click Stress Presets)</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pilih skenario standar institusional atau sesuaikan parameter di bawah</p>
                    </div>
                    <span class="text-[11px] text-slate-400 font-mono">Baseline Kurs: Rp16.000/USD</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <!-- Preset Mild -->
                    <a href="{{ route('scanner.index', ['preset' => 'mild']) }}" 
                       class="p-3.5 rounded-xl border transition-all text-left flex flex-col justify-between gap-2 {{ ($rateHike == 25 && $usdRate == 16200) ? 'bg-blue-50/80 border-blue-300 ring-2 ring-blue-500/20 shadow-xs' : 'bg-slate-50 hover:bg-white border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800">⚡ Mild Soft-Landing</span>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 font-bold">Ringan</span>
                        </div>
                        <p class="text-[11px] text-slate-600">BI +25 bps &bull; Kurs Rp16.200 &bull; Inflasi Biaya +1.0%</p>
                    </a>

                    <!-- Preset Moderate -->
                    <a href="{{ route('scanner.index', ['preset' => 'moderate']) }}" 
                       class="p-3.5 rounded-xl border transition-all text-left flex flex-col justify-between gap-2 {{ ($rateHike == 50 && $usdRate == 16500) ? 'bg-amber-50/80 border-amber-300 ring-2 ring-amber-500/20 shadow-xs' : 'bg-slate-50 hover:bg-white border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800">⚠️ Moderate FX Pressure</span>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-bold">Sedang</span>
                        </div>
                        <p class="text-[11px] text-slate-600">BI +50 bps &bull; Kurs Rp16.500 &bull; Inflasi Biaya +2.0%</p>
                    </a>

                    <!-- Preset Severe -->
                    <a href="{{ route('scanner.index', ['preset' => 'severe']) }}" 
                       class="p-3.5 rounded-xl border transition-all text-left flex flex-col justify-between gap-2 {{ ($rateHike == 150 && $usdRate == 17200) ? 'bg-rose-50/80 border-rose-300 ring-2 ring-rose-500/20 shadow-xs' : 'bg-slate-50 hover:bg-white border-slate-200 hover:border-slate-300' }}">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800">🚨 Severe Liquidity Crisis</span>
                            <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-rose-100 text-rose-800 font-bold">Kritis</span>
                        </div>
                        <p class="text-[11px] text-slate-600">BI +150 bps &bull; Kurs Rp17.200 &bull; Inflasi Biaya +4.5%</p>
                    </a>
                </div>

                <!-- Interactive Parameter Custom Form -->
                <form method="GET" action="{{ route('scanner.index') }}" class="mt-4 pt-4 border-t border-slate-200 grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Kenaikan Suku Bunga</label>
                        <div class="relative">
                            <input type="number" name="rate_hike" value="{{ $rateHike }}" min="0" max="500" step="25"
                                   class="w-full text-xs font-mono bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            <span class="absolute right-3 top-2 text-xs text-slate-400 font-mono">bps</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Target Kurs USD/IDR</label>
                        <div class="relative">
                            <input type="number" name="usd_rate" value="{{ $usdRate }}" min="15000" max="22000" step="100"
                                   class="w-full text-xs font-mono bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            <span class="absolute right-3 top-2 text-xs text-slate-400 font-mono">IDR</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Inflasi Biaya Input (COGS)</label>
                        <div class="relative">
                            <input type="number" name="cogs_inflation" value="{{ $cogsInflation }}" min="0" max="20" step="0.5"
                                   class="w-full text-xs font-mono bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            <span class="absolute right-3 top-2 text-xs text-slate-400 font-mono">%</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-2 px-4 text-xs font-bold bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-xs transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Pindai Ulang Red-Line</span>
                    </button>
                </form>
            </div>

            <!-- 2. Summary KPI Cards (4-Column) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Emiten Terpindai</p>
                    <p class="text-3xl font-extrabold text-slate-900 num-tabular">{{ $totalScanned }}</p>
                    <p class="text-xs text-slate-500 mt-1">11 Sektor Resmi Bursa Efek</p>
                </div>

                <div class="bg-rose-50/60 rounded-2xl border border-rose-200 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">🔴 Red-Line Breached</p>
                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-rose-200/70 text-rose-900 font-bold">{{ $redPercentage }}%</span>
                    </div>
                    <p class="text-3xl font-extrabold text-rose-700 num-tabular">{{ $totalRed }}</p>
                    <p class="text-xs text-rose-600/80 mt-1 font-medium">Laba Defisit / Solvabilitas Kritis</p>
                </div>

                <div class="bg-amber-50/60 rounded-2xl border border-amber-200 p-5 shadow-xs">
                    <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider mb-1">🟡 Warning Zone</p>
                    <p class="text-3xl font-extrabold text-amber-700 num-tabular">{{ $totalWarning }}</p>
                    <p class="text-xs text-amber-700/80 mt-1 font-medium">Marjin Tergerus Berat (&lt;4%)</p>
                </div>

                <div class="bg-emerald-50/60 rounded-2xl border border-emerald-200 p-5 shadow-xs">
                    <p class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider mb-1">🟢 Safe & Resilient</p>
                    <p class="text-3xl font-extrabold text-emerald-700 num-tabular">{{ $totalSafe }}</p>
                    <p class="text-xs text-emerald-700/80 mt-1 font-medium">Bantalan Laba & Kas Tebal</p>
                </div>
            </div>

            <!-- 3. Top Vulnerable vs Top Resilient Callout Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Top 3 Most Vulnerable -->
                <div class="bg-white rounded-2xl border border-rose-200 p-5 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold text-rose-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-600"></span>
                                <span>3 Emiten Paling Rentan (Top Red-Line Breached)</span>
                            </h4>
                            <span class="text-[10px] font-mono text-rose-600 bg-rose-50 px-2 py-0.5 rounded font-bold">Prioritas Exit / Hedging</span>
                        </div>
                        <div class="space-y-2.5">
                            @foreach($topVulnerable as $item)
                                <div class="p-3 bg-rose-50/50 rounded-xl border border-rose-100 flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-extrabold text-slate-900">{{ $item['symbol'] }}</span>
                                            <span class="text-[10px] text-slate-500">{{ $item['sector_name'] }}</span>
                                        </div>
                                        <p class="text-[11px] text-rose-700 mt-0.5">{{ $item['risk_verdict'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[10px] text-slate-400 font-mono">Proyeksi NPM</div>
                                        <div class="text-xs font-bold num-tabular text-rose-700">
                                            {{ $item['projected_npm'] }}% <span class="text-[10px] text-slate-400 font-normal">({{ $item['npm'] }}%)</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-3 italic border-t border-slate-100 pt-2">
                        *Emiten di atas memiliki DER tinggi dan marjin awal tipis, sehingga kenaikan suku bunga dan kurs langsung memotong habis laba operasional.
                    </p>
                </div>

                <!-- Top 3 Most Resilient -->
                <div class="bg-white rounded-2xl border border-emerald-200 p-5 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold text-emerald-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                                <span>3 Emiten Paling Kokoh (Top Shock Resilient)</span>
                            </h4>
                            <span class="text-[10px] font-mono text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-bold">Defensive Anchor</span>
                        </div>
                        <div class="space-y-2.5">
                            @foreach($topResilient as $item)
                                <div class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-100 flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-extrabold text-slate-900">{{ $item['symbol'] }}</span>
                                            <span class="text-[10px] text-slate-500">{{ $item['sector_name'] }}</span>
                                        </div>
                                        <p class="text-[11px] text-emerald-700 mt-0.5">{{ $item['risk_verdict'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-[10px] text-slate-400 font-mono">Proyeksi NPM</div>
                                        <div class="text-xs font-bold num-tabular text-emerald-700">
                                            {{ $item['projected_npm'] }}% <span class="text-[10px] text-slate-400 font-normal">({{ $item['npm'] }}%)</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 mt-3 italic border-t border-slate-100 pt-2">
                        *Didukung pendapatan valas USD (natural hedge) atau marjin laba tebal dengan rasio utang (DER) sangat konservatif.
                    </p>
                </div>
            </div>

            <!-- 4. Sector-Level Vulnerability Concentration Breakdown -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                            <span>Konsentrasi Risiko Red-Line per 11 Sektor IHSG</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Persentase emiten dalam sektor yang melanggar batas aman solvabilitas/laba</p>
                    </div>
                    <span class="text-[11px] font-mono text-slate-400">Diurutkan: Risiko Tertinggi &rarr; Terendah</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($sectorRiskMap as $sec)
                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex flex-col justify-between gap-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-800 truncate">{{ $sec['sector_name'] }}</span>
                                <span class="text-[10px] font-mono font-bold px-1.5 py-0.5 rounded {{ $sec['red_ratio'] > 40 ? 'bg-rose-100 text-rose-800' : ($sec['red_ratio'] > 0 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                    {{ $sec['red_ratio'] }}% Red
                                </span>
                            </div>
                            <!-- Mini Progress Bar -->
                            <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden flex">
                                <div class="bg-rose-500 h-1.5" style="width: {{ $sec['red_ratio'] }}%"></div>
                                <div class="bg-amber-400 h-1.5" style="width: {{ ($sec['warning_count'] / max(1, $sec['total_companies'])) * 100 }}%"></div>
                                <div class="bg-emerald-500 h-1.5" style="width: {{ ($sec['safe_count'] / max(1, $sec['total_companies'])) * 100 }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-slate-500 font-mono">
                                <span>Red: <strong class="text-rose-600">{{ $sec['red_count'] }}</strong></span>
                                <span>Warn: <strong class="text-amber-600">{{ $sec['warning_count'] }}</strong></span>
                                <span>Safe: <strong class="text-emerald-600">{{ $sec['safe_count'] }}</strong></span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 5. Core Emiten Vulnerability Screening Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <!-- Table Filters Bar -->
                <div class="p-4 sm:p-5 border-b border-slate-200 bg-slate-50/50 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <span>Daftar 49 Emiten Terpindai</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Menampilkan hasil simulasi stres marjin laba dan solvabilitas utang per emiten</p>
                    </div>

                    <!-- Filters & Sort -->
                    <form method="GET" action="{{ route('scanner.index') }}" class="flex flex-wrap items-center gap-2 text-xs">
                        <input type="hidden" name="rate_hike" value="{{ $rateHike }}">
                        <input type="hidden" name="usd_rate" value="{{ $usdRate }}">
                        <input type="hidden" name="cogs_inflation" value="{{ $cogsInflation }}">

                        <!-- Zone Filter -->
                        <select name="zone_filter" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 text-slate-700 font-medium">
                            <option value="all" {{ $zoneFilter === 'all' ? 'selected' : '' }}>Semua Zona ({{ $totalScanned }})</option>
                            <option value="red" {{ $zoneFilter === 'red' ? 'selected' : '' }}>🔴 Hanya Red-Line ({{ $totalRed }})</option>
                            <option value="warning" {{ $zoneFilter === 'warning' ? 'selected' : '' }}>🟡 Warning Zone ({{ $totalWarning }})</option>
                            <option value="safe" {{ $zoneFilter === 'safe' ? 'selected' : '' }}>🟢 Safe Zone ({{ $totalSafe }})</option>
                        </select>

                        <!-- Sector Filter -->
                        <select name="sector_filter" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 text-slate-700 font-medium max-w-[160px]">
                            <option value="all">Semua Sektor</option>
                            @foreach($allSectors as $s)
                                <option value="{{ $s->sector_code }}" {{ $sectorFilter === $s->sector_code ? 'selected' : '' }}>
                                    {{ $s->sector_name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Sort By -->
                        <select name="sort_by" onchange="this.form.submit()" class="bg-white border border-slate-300 rounded-lg px-2.5 py-1.5 text-slate-700 font-medium">
                            <option value="vulnerability_desc" {{ $sortBy === 'vulnerability_desc' ? 'selected' : '' }}>Kerentanan Tertinggi</option>
                            <option value="projected_npm_asc" {{ $sortBy === 'projected_npm_asc' ? 'selected' : '' }}>Proyeksi NPM Terendah</option>
                            <option value="der_desc" {{ $sortBy === 'der_desc' ? 'selected' : '' }}>DER (Utang) Tertinggi</option>
                            <option value="market_cap_desc" {{ $sortBy === 'market_cap_desc' ? 'selected' : '' }}>Market Cap Terbesar</option>
                        </select>
                    </form>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-100/75 text-slate-700 border-b border-slate-200 font-bold text-[11px] uppercase tracking-wider">
                                <th class="py-3 px-4">Emiten & Ticker</th>
                                <th class="py-3 px-4">Sektor</th>
                                <th class="py-3 px-4 text-center">Status Zona</th>
                                <th class="py-3 px-4 text-center">Skor Kerentanan</th>
                                <th class="py-3 px-4 text-right">DER (Utang)</th>
                                <th class="py-3 px-4 text-right">NPM Awal</th>
                                <th class="py-3 px-4 text-right">Proyeksi NPM</th>
                                <th class="py-3 px-4 text-right">Total Shock</th>
                                <th class="py-3 px-4">Diagnosa & Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($filteredCompanies as $c)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col">
                                            <span class="font-extrabold text-slate-900 text-sm">{{ $c['symbol'] }}</span>
                                            <span class="text-[10px] text-slate-500 truncate max-w-[140px]">{{ $c['name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700">
                                        <span class="text-xs font-semibold">{{ $c['sector_name'] }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $c['zone_badge'] }}">
                                            {{ $c['zone_label'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-block font-mono font-extrabold text-xs px-2 py-0.5 rounded {{ $c['vulnerability_score'] >= 70 ? 'bg-rose-100 text-rose-800' : ($c['vulnerability_score'] >= 40 ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">
                                            {{ $c['vulnerability_score'] }}/100
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right num-tabular font-semibold {{ $c['der'] >= 2.0 ? 'text-rose-700 font-bold' : 'text-slate-700' }}">
                                        {{ number_format($c['der'], 2) }}x
                                    </td>
                                    <td class="py-3 px-4 text-right num-tabular text-slate-600 font-medium">
                                        {{ number_format($c['npm'], 1) }}%
                                    </td>
                                    <td class="py-3 px-4 text-right num-tabular font-bold {{ $c['projected_npm'] <= 0 ? 'text-rose-700' : ($c['projected_npm'] <= 4.0 ? 'text-amber-700' : 'text-emerald-700') }}">
                                        {{ number_format($c['projected_npm'], 1) }}%
                                    </td>
                                    <td class="py-3 px-4 text-right num-tabular text-rose-600 font-semibold">
                                        -{{ number_format($c['total_compression'], 1) }}%
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex flex-col gap-0.5 max-w-xs">
                                            <span class="text-xs font-bold {{ $c['zone'] === 'red' ? 'text-rose-900' : ($c['zone'] === 'warning' ? 'text-amber-900' : 'text-emerald-900') }}">
                                                {{ $c['risk_verdict'] }}
                                            </span>
                                            <span class="text-[10px] text-slate-500 font-mono">
                                                Bunga: -{{ $c['interest_shock'] }}% &bull; Kurs: {{ $c['fx_shock'] > 0 ? '-'.$c['fx_shock'] : '+'.abs($c['fx_shock']) }}% &bull; COGS: -{{ $c['cogs_shock'] }}%
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-slate-400 italic">
                                        Tidak ada emiten yang memenuhi kriteria filter terpilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 6. Regulatory Disclaimer -->
            <div class="border-t border-slate-200 pt-4 text-center text-xs text-slate-400 space-y-1">
                <p>
                    <strong>Disclaimer:</strong> Fitur Macro Vulnerability & Red-Line Scanner merupakan simulasi matematis berbasis sensitivitas rasio neraca dan laba rugi emiten. Bukan merupakan rekomendasi jual/beli efek.
                </p>
                <p class="text-[11px] font-mono text-slate-400">
                    Sectors Hackathon 2026 &bull; MacroSectors AI Intelligence Engine
                </p>
            </div>

        </main>
    </div>

</body>
</html>
