<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacroSectors AI — Indonesia Market Intelligence</title>
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
      x-data="macroDashboard()">

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
        <div class="w-52 h-full bg-white shadow-xl" @click.stop>
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
                <h2 class="text-lg font-bold text-slate-900">Peta Transmisi Makro</h2>
                <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md font-medium border border-slate-200 hidden md:inline">
                    11 Sektor Resmi Bursa Efek Indonesia
                </span>
            </div>

            <!-- Header Status & Sync Button -->
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold {{ $isSectorsApiConfigured ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                    <span class="w-2 h-2 rounded-full {{ $isSectorsApiConfigured ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                    <span>{{ $isSectorsApiConfigured ? 'Sectors API: Live' : 'Sectors Cache: 11 Sektor' }}</span>
                </div>

                <form action="{{ route('sectors.sync') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-3.5 py-1.5 text-xs font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl shadow-xs transition-all flex items-center gap-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Sync Snapshot</span>
                    </button>
                </form>
            </div>
        </header>

        @if(session('success') || session('info'))
            <div class="max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="p-4 bg-white border border-slate-200 text-sm text-slate-700 rounded-2xl shadow-xs flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>{{ session('success') ?? session('info') }}</span>
                    </div>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">SUKSES</span>
                </div>
            </div>
        @endif

        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-10 pb-16 flex flex-col gap-8 sm:gap-10">

        <!-- 4 Stat Cards Grid (Unified 4-column on desktop & split-screen) -->
        @php
            $resilientCount = $sectorResults->where('impact_score', '>', 0)->count();
            $vulnerableCount = $sectorResults->where('impact_score', '<', 0)->count();
            $neutralCount = $sectorResults->where('impact_score', 0)->count();
        @endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6">
            <!-- StatCard 1 -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:border-slate-300 transition-all flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Cakupan Sektor</p>
                    <p class="text-2xl font-bold text-slate-900">11 Sektor</p>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium truncate">100% Klasifikasi Resmi</p>
                </div>
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-blue-50 flex items-center justify-center shrink-0 border border-blue-200 text-blue-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>

            <!-- StatCard 2 -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:border-slate-300 transition-all flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sektor Tangguh</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $resilientCount }} Sektor</p>
                    <p class="text-[11px] text-emerald-600 mt-1 font-medium truncate">Skor Transmisi Positif (+)</p>
                </div>
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-200 text-emerald-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>

            <!-- StatCard 3 -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:border-slate-300 transition-all flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sektor Rentan</p>
                    <p class="text-2xl font-bold text-rose-600">{{ $vulnerableCount }} Sektor</p>
                    <p class="text-[11px] text-rose-600 mt-1 font-medium truncate">Skor Transmisi Negatif (-)</p>
                </div>
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-rose-50 flex items-center justify-center shrink-0 border border-rose-200 text-rose-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                    </svg>
                </div>
            </div>

            <!-- StatCard 4 -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:border-slate-300 transition-all flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Driver Makro</p>
                    <p class="text-xl sm:text-2xl font-bold text-slate-900 truncate max-w-[130px] capitalize">
                        {{ $activeScenario ? str_replace('_', ' ', $activeScenario->category) : 'Netral' }}
                    </p>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium truncate">
                        {{ $activeScenario ? 'Kategori Transmisi Aktif' : 'Tanpa Guncangan Makro' }}
                    </p>
                </div>
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-amber-50 flex items-center justify-center shrink-0 border border-amber-200 text-amber-600">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Scenario Control Panel (POSCafe Clean Structured Card) -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Simulasi Skenario Makroekonomi</h2>
                    <p class="text-xs text-slate-500 mt-1">Pilih preset skenario ekonomi global atau ketik berita kustom untuk mensimulasikan transmisi dampaknya ke bursa Indonesia.</p>
                </div>
                <div class="flex items-center gap-2">
                    <div class="px-3.5 py-1.5 {{ $activeScenario ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-slate-100 text-slate-700 border-slate-200' }} text-xs font-semibold rounded-full border">
                        Aktif: {{ $activeScenario ? $activeScenario->title : 'Kondisi Netral (Baseline)' }}
                    </div>
                    @if($activeScenario)
                        <a href="{{ route('dashboard', ['clear' => 1]) }}" 
                           title="Reset ke Kondisi Netral"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-600 hover:text-rose-600 bg-slate-100 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 rounded-full transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Clear Skenario</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Presets Grid -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pilihan Skenario Cepat (Preset):</p>
                    @if($activeScenario)
                        <a href="{{ route('dashboard', ['clear' => 1]) }}" class="text-xs font-medium text-slate-500 hover:text-rose-600 transition-colors inline-flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Reset Skenario</span>
                        </a>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2.5">
                    <!-- Neutral / Baseline Option Button -->
                    <a href="{{ route('dashboard', ['clear' => 1]) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold transition-all border {{ ! $activeScenario ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-white hover:bg-slate-50 text-slate-700 border-slate-200' }}">
                        <svg class="w-3.5 h-3.5 {{ ! $activeScenario ? 'text-slate-300' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Kondisi Netral (Default)</span>
                    </a>

                    @foreach($presetScenarios as $preset)
                        @php
                            $isActive = $activeScenario && $activeScenario->id === $preset->id;
                        @endphp
                        <a href="{{ route('dashboard', ['scenario_id' => $preset->id]) }}"
                           class="px-4 py-2.5 rounded-xl text-xs font-semibold transition-all border {{ $isActive ? 'bg-blue-600 text-white border-blue-600 shadow-sm' : 'bg-white hover:bg-slate-50 text-slate-700 border-slate-200' }}">
                            {{ $preset->title }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Custom Input Box -->
            <form action="{{ route('analyze') }}" method="POST" class="pt-2 flex flex-col sm:flex-row gap-3">
                @csrf
                <div class="relative flex-1">
                    <input type="text" name="custom_scenario" 
                           placeholder="Ketik berita atau skenario baru (contoh: 'Kenaikan tarif PPN 12% dan lonjakan inflasi pangan domestik')..."
                           class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-2xs">
                </div>
                <button type="submit" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-sm transition-all shrink-0 flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span>Analisis Dampak</span>
                </button>
            </form>
        </div>

        <!-- Executive Intelligence Narrative Card -->
        @if($analysisData)
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-3 h-3 rounded-full bg-blue-600"></div>
                        <h3 class="font-bold text-slate-900 text-base">Executive Macro Intelligence Brief</h3>
                    </div>
                    <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-full font-medium border border-slate-200">
                        {{ $analysisData['engine_used'] }}
                    </span>
                </div>
                <p class="text-sm text-slate-700 leading-relaxed font-normal">
                    {{ $analysisData['executive_summary'] }}
                </p>
                <div class="bg-blue-50/70 border border-blue-100 rounded-xl p-4 flex items-start gap-3.5 text-sm text-slate-800">
                    <span class="font-bold text-blue-700 shrink-0 uppercase tracking-wider text-xs mt-0.5">Rekomendasi Kunci:</span>
                    <span class="leading-relaxed">{{ $analysisData['key_takeaway'] }}</span>
                </div>
            </div>
        @endif

        <!-- 11-Sector Resilience Matrix (POSCafe Clean Structured Table) -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Matriks Ketahanan 11 Sektor IHSG</h3>
                    <p class="text-xs text-slate-500 mt-1">Peta transmisi makroekonomi ke sektor riil Bursa Efek Indonesia. Klik baris sektor untuk memeriksa rincian emiten.</p>
                </div>
                <!-- Legend -->
                <div class="flex items-center gap-4 text-xs font-semibold text-slate-600">
                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Tangguh (+1 s/d +10)</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                        <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                        <span>Netral (0)</span>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span>Rentan (-1 s/d -10)</span>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider select-none">
                            <th class="px-6 py-4">Sektor IHSG</th>
                            <th class="px-6 py-4 text-center">Evaluasi Ketahanan</th>
                            <th class="px-6 py-4 text-right">Rata-rata DER</th>
                            <th class="px-6 py-4 text-right">Rata-rata NPM</th>
                            <th class="px-6 py-4 text-center">Sampel Emiten</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($sectorResults as $item)
                            @php
                                $score = $item->impact_score;
                                $code = $item->sector->sector_code;
                                $isPositive = $score > 0;
                                $isNegative = $score < 0;
                                $scoreBadge = $isPositive ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($isNegative ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200');
                                $gaugePercent = abs($score) * 5; // 50% max from center
                                $sampleCompanies = $item->sector->companies->take(3);
                            @endphp
                            <tr @click="selectedSectorCode = '{{ $code }}'"
                                :class="selectedSectorCode === '{{ $code }}' ? 'bg-blue-50/60 font-semibold' : 'hover:bg-slate-50/60'"
                                class="cursor-pointer transition-colors group">
                                
                                <!-- Sector Name & Code -->
                                <td class="px-6 py-4 relative">
                                    <div x-show="selectedSectorCode === '{{ $code }}'" class="absolute left-0 top-0 bottom-0 w-1 bg-blue-600"></div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 {{ $isPositive ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : ($isNegative ? 'bg-rose-50 text-rose-600 border border-rose-200' : 'bg-slate-100 text-slate-600 border border-slate-200') }}">
                                            {{ substr($item->sector->sector_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 text-sm block group-hover:text-blue-600 transition-colors">{{ $item->sector->sector_name }}</span>
                                            <span class="text-xs text-slate-400 font-mono">{{ $code }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Combined Score, Status & Diverging Gauge (No Awkward Gaps) -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold num-tabular {{ $scoreBadge }}">
                                                {{ $score > 0 ? '+' : '' }}{{ $score }}
                                            </span>
                                            <span class="text-xs font-semibold {{ $isPositive ? 'text-emerald-700' : ($isNegative ? 'text-rose-700' : 'text-slate-600') }}">
                                                {{ $item->resilience_status }}
                                            </span>
                                        </div>
                                        <!-- Inline Diverging Gauge -->
                                        <div class="w-24 h-1.5 bg-slate-100 rounded-full relative overflow-hidden mt-1.5 border border-slate-200">
                                            <div class="absolute left-1/2 top-0 bottom-0 w-0.5 bg-slate-300"></div>
                                            @if($isNegative)
                                                <div class="absolute right-1/2 top-0 bottom-0 bg-rose-500 rounded-l-full" style="width: {{ $gaugePercent }}%"></div>
                                            @elseif($isPositive)
                                                <div class="absolute left-1/2 top-0 bottom-0 bg-emerald-500 rounded-r-full" style="width: {{ $gaugePercent }}%"></div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Avg DER -->
                                <td class="px-6 py-4 text-right num-tabular font-semibold text-slate-700">
                                    {{ $item->sector->avg_der }}x
                                </td>

                                <!-- Avg NPM -->
                                <td class="px-6 py-4 text-right num-tabular font-semibold {{ $item->sector->avg_npm < 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ $item->sector->avg_npm }}%
                                </td>

                                <!-- Sample Companies -->
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        @foreach($sampleCompanies as $comp)
                                            <span class="px-2 py-0.5 rounded-md text-[11px] font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                {{ $comp->symbol }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <!-- Action -->
                                <td class="px-6 py-4 text-right font-medium">
                                    <span :class="selectedSectorCode === '{{ $code }}' ? 'text-blue-600 font-bold' : 'text-slate-400 group-hover:text-blue-600'" class="text-xs transition-colors whitespace-nowrap">
                                        <span x-show="selectedSectorCode === '{{ $code }}'">Terpilih &bull;</span>
                                        <span x-show="selectedSectorCode !== '{{ $code }}'">Periksa &rarr;</span>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Micro Exposure Inspector (POSCafe Clean Detail Card) -->
        <div id="micro-inspector" class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Pemeriksaan Mikro:</span>
                        <h3 class="text-xl font-bold text-slate-900" x-text="activeSector ? activeSector.name : ''"></h3>
                        <span class="text-xs text-slate-400 font-mono" x-text="activeSector ? '(' + activeSector.code + ')' : ''"></span>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Daftar emiten unggulan dalam sektor terpilih beserta metrik neraca fundamental.</p>
                </div>

                <div class="flex items-center gap-3 text-xs flex-wrap">
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold num-tabular"
                          :class="activeSector?.score > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (activeSector?.score < 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200')"
                          x-text="'Skor Sektor: ' + (activeSector?.score > 0 ? '+' : '') + activeSector?.score">
                    </span>
                    <span class="text-slate-500">Rata-rata DER: <strong class="text-slate-800 font-semibold num-tabular" x-text="activeSector?.avg_der + 'x'"></strong></span>
                    <span class="text-slate-500">Rata-rata NPM: <strong class="text-slate-800 font-semibold num-tabular" x-text="activeSector?.avg_npm + '%'"></strong></span>
                </div>
            </div>

            <!-- Economic Transmission Rationalization -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 text-sm text-slate-700 leading-relaxed flex items-start gap-3.5">
                <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 shrink-0"></div>
                <div>
                    <span class="text-xs font-bold text-slate-900 uppercase tracking-wider block mb-1">Rasionalisasi Transmisi Ekonomi:</span>
                    <p x-text="activeSector?.reasoning" class="leading-relaxed"></p>
                </div>
            </div>

            <!-- Collapsible Score Breakdown Dropdown (Ringkas, Tidak Bikin Scroll Panjang) -->
            <div x-data="{ openBreakdown: false }" class="border border-slate-200 rounded-xl overflow-hidden bg-white">
                <button type="button" 
                        @click="openBreakdown = !openBreakdown" 
                        class="w-full px-5 py-3.5 flex items-center justify-between bg-slate-50/80 hover:bg-slate-100/90 transition-colors text-left select-none cursor-pointer">
                    <div class="flex items-center gap-2.5">
                        <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-900">Rincian Dekomposisi Skor 3 Pilar Transmisi</span>
                            <span class="text-[11px] text-slate-500 hidden sm:inline ml-1.5">(Dampak Pendapatan, Sensitivitas Biaya & Neraca)</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-slate-500">
                        <span class="text-xs font-medium" x-text="openBreakdown ? 'Tutup Rincian' : 'Buka Rincian (+/-)'"></span>
                        <svg class="w-4 h-4 transform transition-transform duration-200" :class="openBreakdown ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </button>

                <!-- Dropdown Content -->
                <div x-show="openBreakdown" x-cloak class="p-5 border-t border-slate-100 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Pilar 1: Pendapatan -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between gap-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">📈</span>
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">1. Pendapatan (Omset)</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-xs font-bold font-mono"
                                      :class="(activeSector?.score_breakdown?.revenue_impact ?? 0) > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ((activeSector?.score_breakdown?.revenue_impact ?? 0) < 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200')"
                                      x-text="((activeSector?.score_breakdown?.revenue_impact ?? 0) > 0 ? '+' : '') + (activeSector?.score_breakdown?.revenue_impact ?? 0)">
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed" x-text="activeSector?.score_breakdown?.revenue_note || 'Dampak netral terhadap pendapatan.'"></p>
                        </div>

                        <!-- Pilar 2: Biaya & Pasok -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between gap-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">📦</span>
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">2. Biaya & Pasok</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-xs font-bold font-mono"
                                      :class="(activeSector?.score_breakdown?.cost_impact ?? 0) > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ((activeSector?.score_breakdown?.cost_impact ?? 0) < 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200')"
                                      x-text="((activeSector?.score_breakdown?.cost_impact ?? 0) > 0 ? '+' : '') + (activeSector?.score_breakdown?.cost_impact ?? 0)">
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed" x-text="activeSector?.score_breakdown?.cost_note || 'Beban operasional & rantai pasok stabil.'"></p>
                        </div>

                        <!-- Pilar 3: Neraca (DER/NPM) -->
                        <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between gap-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-base">⚖️</span>
                                    <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">3. Neraca (DER & NPM)</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md text-xs font-bold font-mono"
                                      :class="(activeSector?.score_breakdown?.balance_sheet_impact ?? 0) > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ((activeSector?.score_breakdown?.balance_sheet_impact ?? 0) < 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200')"
                                      x-text="((activeSector?.score_breakdown?.balance_sheet_impact ?? 0) > 0 ? '+' : '') + (activeSector?.score_breakdown?.balance_sheet_impact ?? 0)">
                                </span>
                            </div>
                            <p class="text-xs text-slate-600 leading-relaxed" x-text="activeSector?.score_breakdown?.balance_sheet_note || 'Struktur permodalan dan solvabilitas seimbang.'"></p>
                        </div>
                    </div>

                    <!-- Formula Sum Bar -->
                    <div class="mt-6 pt-4 border-t border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                        <div class="flex items-center gap-2 flex-wrap font-mono bg-slate-50 border border-slate-200/60 px-3.5 py-2 rounded-xl">
                            <span class="font-sans text-slate-700 font-bold">Formula Total:</span>
                            <span class="font-semibold text-slate-700" x-text="'(' + ((activeSector?.score_breakdown?.revenue_impact ?? 0) > 0 ? '+' : '') + (activeSector?.score_breakdown?.revenue_impact ?? 0) + ' Pendapatan)'"></span>
                            <span class="text-slate-400 font-bold">+</span>
                            <span class="font-semibold text-slate-700" x-text="'(' + ((activeSector?.score_breakdown?.cost_impact ?? 0) > 0 ? '+' : '') + (activeSector?.score_breakdown?.cost_impact ?? 0) + ' Biaya)'"></span>
                            <span class="text-slate-400 font-bold">+</span>
                            <span class="font-semibold text-slate-700" x-text="'(' + ((activeSector?.score_breakdown?.balance_sheet_impact ?? 0) > 0 ? '+' : '') + (activeSector?.score_breakdown?.balance_sheet_impact ?? 0) + ' Neraca)'"></span>
                            <span class="text-slate-400 font-bold">=</span>
                            <span class="px-2.5 py-0.5 rounded-lg font-bold shadow-2xs"
                                  :class="activeSector?.score > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (activeSector?.score < 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200')"
                                  x-text="'Skor Total ' + (activeSector?.score > 0 ? '+' : '') + (activeSector?.score ?? 0)">
                            </span>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium self-end sm:self-auto">Skala -10 (Kritis) s.d +10 (Sangat Diuntungkan)</span>
                    </div>
                </div>
            </div>

            <!-- Companies List Table -->
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider select-none">
                                <th class="px-6 py-3.5 w-28">Ticker</th>
                                <th class="px-6 py-3.5">Nama Perusahaan</th>
                                <th class="px-6 py-3.5 text-right w-40">Kapitalisasi Pasar</th>
                                <th class="px-6 py-3.5 text-right w-32">DER (Utang)</th>
                                <th class="px-6 py-3.5 text-right w-32">NPM (Margin)</th>
                                <th class="px-6 py-3.5 text-right w-24">P/E</th>
                                <th class="px-6 py-3.5 text-right w-24">PBV</th>
                                <th class="px-6 py-3.5 text-center w-36">Status Eksposur</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm num-tabular">
                            <template x-for="comp in activeSector?.companies" :key="comp.id">
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-slate-900 text-sm" x-text="comp.symbol"></td>
                                    <td class="px-6 py-4 text-slate-800 font-medium font-sans text-sm" x-text="comp.name"></td>
                                    <td class="px-6 py-4 text-right text-slate-800 text-xs font-semibold" x-text="(comp.market_cap / 1000000000000).toFixed(1) + ' T'"></td>
                                    <td class="px-6 py-4 text-right text-xs font-semibold" 
                                        :class="comp.der > 1.5 ? 'text-rose-600' : 'text-slate-700'"
                                        x-text="comp.der ? comp.der + 'x' : '-'"></td>
                                    <td class="px-6 py-4 text-right text-xs font-semibold" 
                                        :class="comp.npm < 0 ? 'text-rose-600' : 'text-emerald-600'"
                                        x-text="comp.npm ? comp.npm + '%' : '-'"></td>
                                    <td class="px-6 py-4 text-right text-slate-500 text-xs" x-text="comp.pe_ratio ? comp.pe_ratio + 'x' : '-'"></td>
                                    <td class="px-6 py-4 text-right text-slate-500 text-xs" x-text="comp.pbv_ratio ? comp.pbv_ratio + 'x' : '-'"></td>
                                    <td class="px-6 py-4 text-center font-sans">
                                        <template x-if="activeSector?.vulnerable_companies?.includes(comp.symbol)">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">Rentan</span>
                                        </template>
                                        <template x-if="activeSector?.beneficiary_companies?.includes(comp.symbol)">
                                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Tangguh</span>
                                        </template>
                                        <template x-if="!activeSector?.vulnerable_companies?.includes(comp.symbol) && !activeSector?.beneficiary_companies?.includes(comp.symbol)">
                                            <span class="text-slate-400 text-xs font-mono">-</span>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Regulatory & Risk Disclaimer Footer -->
        <footer class="pt-6 border-t border-slate-200 text-xs text-slate-500 flex flex-col gap-3">
            <div class="flex items-center gap-2 text-slate-700 font-bold text-xs tracking-wide">
                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>LEGAL & INVESTMENT RISK DISCLAIMER</span>
            </div>
            <p class="leading-relaxed text-slate-500 text-xs max-w-4xl">
                Platform <strong>MacroSectors AI</strong> dirancang sebagai instrumen simulasi analitik kuantitatif pasar modal untuk riset. Seluruh luaran, skor dampak, dan sintesis naratif bukan merupakan anjuran transaksi efek, rekomendasi jual/beli saham, atau nasihat keuangan legal dalam yurisdiksi Republik Indonesia.
            </p>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-[11px] text-slate-400 pt-3 border-t border-slate-200/60 gap-2">
                <span>© 2026 MacroSectors AI • Author: Wesley Wilnio • Sectors Hackathon Indonesia 2026</span>
                <span>Powered by Sectors REST API & Fundamental Ratios Cache</span>
            </div>
        </footer>

    </main>
    </div>

    <!-- Alpine.js Logic -->
    <script>
        function macroDashboard() {
            return {
                mobileMenuOpen: false,
                selectedSectorCode: '{{ $sectorResults->first()?->sector->sector_code ?? 'IDX-ENERGY' }}',
                sectors: {!! $sectorsJson !!},
                get activeSector() {
                    return this.sectors[this.selectedSectorCode] || null;
                }
            };
        }
    </script>
</body>
</html>
