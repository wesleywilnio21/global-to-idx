<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kilas Balik Krisis Historis — MacroSectors AI</title>
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
        @media print {
            aside, header, .no-print { display: none !important; }
            main { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
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
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-600 hover:text-slate-900 cursor-pointer" aria-label="Buka Menu">
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
                <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center font-bold shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span>Kilas Balik Krisis Historis</span>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 border border-blue-200 font-bold uppercase tracking-wider">EMPIRICAL VALIDATION</span>
                    </h2>
                    <p class="text-xs text-slate-500">Validasi Empiris Model Transmisi Makro AI Terhadap 3 Krisis Nyata Bursa Efek Indonesia</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Audit Empiris BEI Terverifikasi</span>
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

            <!-- 1. CRISIS SWITCHER DECK (3 Interactive Cards) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <span>Pilih Skenario Krisis Historis</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">Evaluasi performa model transmisi makro AI melawan realita empiris 11 sektor BEI</p>
                    </div>
                    <span class="text-xs text-slate-400 font-mono bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200/80">
                        Periode Aktif: {{ $crisisData['period'] }}
                    </span>
                </div>

                <!-- 3 Crisis Switcher Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($crises as $key => $item)
                        @php
                            $isActive = ($selectedCrisisKey === $key);
                        @endphp
                        <a href="{{ route('backtest.index', ['crisis' => $key]) }}"
                           class="p-4 rounded-xl border transition-all flex flex-col justify-between gap-3 {{ $isActive ? 'bg-blue-50/70 border-blue-400 ring-2 ring-blue-500/20 shadow-xs' : 'bg-white hover:bg-slate-50 border-slate-200 hover:border-slate-300 shadow-2xs' }}">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-mono px-2 py-0.5 rounded-md font-bold uppercase tracking-wider {{ $isActive ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ $item['tag'] }}
                                </span>
                                <span class="text-xs font-mono font-bold {{ $isActive ? 'text-blue-700' : 'text-slate-400' }}">
                                    {{ $item['year'] }}
                                </span>
                            </div>

                            <div>
                                <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                    <span>{{ $item['title'] }}</span>
                                    @if($isActive)
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    @endif
                                </h4>
                                <p class="text-xs text-slate-500 mt-1.5 line-clamp-2 leading-relaxed">
                                    {{ $item['summary'] }}
                                </p>
                            </div>

                            <div class="pt-2 border-t {{ $isActive ? 'border-blue-200/60' : 'border-slate-100' }} flex items-center justify-between text-[11px]">
                                <span class="font-mono text-slate-400">{{ $item['period'] }}</span>
                                <span class="font-semibold {{ $isActive ? 'text-blue-700' : 'text-slate-500' }}">
                                    {{ $isActive ? 'Sedang Ditinjau →' : 'Pilih Skenario' }}
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- 2. MACRO CONTEXT DECK (4-Stat Cards) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-4">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            <span>Kondisi Makro Ekonomi Saat Krisis (Backdrop Historis)</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Indikator guncangan moneter, valas, dan aktivitas ekonomi riil pada saat {{ $crisisData['title'] }}</p>
                    </div>
                    <span class="text-xs font-mono font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                        {{ $crisisData['year'] }}
                    </span>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                    <!-- Stat 1: Kurs USD/IDR -->
                    <div class="p-4 sm:p-5 rounded-xl border border-slate-200 bg-slate-50/60 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kurs USD / IDR</span>
                            <span class="w-6 h-6 rounded-lg bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center text-xs font-bold">FX</span>
                        </div>
                        <p class="text-lg sm:text-xl font-extrabold text-slate-900 num-tabular mt-2">
                            {{ $crisisData['usd_fx'] }}
                        </p>
                        <span class="text-[11px] text-slate-400 mt-1 font-medium">Volatilitas Valas & Arus Modal</span>
                    </div>

                    <!-- Stat 2: BI-Rate Shock -->
                    <div class="p-4 sm:p-5 rounded-xl border border-slate-200 bg-slate-50/60 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">BI-Rate Shock</span>
                            <span class="w-6 h-6 rounded-lg bg-blue-50 border border-blue-200 text-blue-600 flex items-center justify-center text-xs font-bold">%</span>
                        </div>
                        <p class="text-lg sm:text-xl font-extrabold text-slate-900 num-tabular mt-2">
                            {{ $crisisData['bi_rate'] }}
                        </p>
                        <span class="text-[11px] text-slate-400 mt-1 font-medium">Respon Kebijakan Moneter</span>
                    </div>

                    <!-- Stat 3: Inflasi / PDB -->
                    <div class="p-4 sm:p-5 rounded-xl border border-slate-200 bg-slate-50/60 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Inflasi & Pertumbuhan PDB</span>
                            <span class="w-6 h-6 rounded-lg bg-purple-50 border border-purple-200 text-purple-600 flex items-center justify-center text-xs font-bold">GDP</span>
                        </div>
                        <p class="text-lg sm:text-xl font-extrabold text-slate-900 num-tabular mt-2">
                            {{ $crisisData['inflation_gdp'] }}
                        </p>
                        <span class="text-[11px] text-slate-400 mt-1 font-medium">Dinamika Sektor Riil & Domestik</span>
                    </div>

                    <!-- Stat 4: IHSG Drawdown -->
                    <div class="p-4 sm:p-5 rounded-xl border border-rose-200 bg-rose-50/40 flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-rose-800 uppercase tracking-wider">Drawdown Maksimal IHSG</span>
                            <span class="w-6 h-6 rounded-lg bg-rose-100 border border-rose-200 text-rose-600 flex items-center justify-center text-xs font-bold">IHSG</span>
                        </div>
                        <p class="text-lg sm:text-xl font-extrabold text-rose-600 num-tabular mt-2">
                            {{ $crisisData['ihsg_drawdown'] }}
                        </p>
                        <span class="text-[11px] text-rose-700/80 mt-1 font-medium">Koreksi Puncak ke Palung Pasar</span>
                    </div>
                </div>
            </div>

            <!-- 3. MODEL VALIDATION SCORECARD (3 KPI Cards) -->
            @php
                $matchCount = count(array_filter($sectorComparison, fn($s) => (bool) ($s['directional_match'] ?? false)));
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
                <!-- Card 1: Directional Accuracy -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between gap-4">
                    <div>
                        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Akurasi Arah Prediksi Model</span>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200 font-bold">
                                {{ $matchCount }}/11 Sektor Tepat
                            </span>
                        </div>
                        <div class="flex items-baseline gap-3 mt-1">
                            <span class="text-4xl font-black text-slate-900 num-tabular">{{ $directionalAccuracy }}%</span>
                            <span class="text-xs font-bold text-emerald-600">Directional Match</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-2.5 leading-relaxed">
                            Polaritas dampak prediksi model MacroSectors (+ / -) selaras dengan pergerakan riil sektor BEI saat krisis berlangsung.
                        </p>
                    </div>

                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div class="h-2 rounded-full bg-emerald-500 transition-all" style="width: {{ $directionalAccuracy }}%"></div>
                    </div>
                </div>

                <!-- Card 2: Top Sektor Defensif -->
                <div class="bg-white rounded-2xl border border-emerald-200 p-6 shadow-sm flex flex-col justify-between gap-4">
                    <div>
                        <div class="flex items-center justify-between border-b border-emerald-100 pb-3 mb-3">
                            <span class="text-xs font-bold text-emerald-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>Top Sektor Defensif</span>
                            </span>
                            <span class="text-[10px] font-mono text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md font-bold border border-emerald-200">
                                Paling Tangguh
                            </span>
                        </div>

                        <div class="space-y-2.5">
                            @foreach($topDefensive as $def)
                                <div class="p-2.5 rounded-lg bg-emerald-50/50 border border-emerald-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 block">{{ $def['sector_name'] }}</span>
                                        <span class="text-[11px] text-slate-400 font-mono">Prediksi: {{ $def['predicted_impact'] > 0 ? '+' : '' }}{{ $def['predicted_impact'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-black num-tabular font-mono {{ $def['actual_return'] >= 0 ? 'text-emerald-600' : 'text-slate-700' }}">
                                            {{ $def['actual_return'] > 0 ? '+' : '' }}{{ $def['actual_return'] }}%
                                        </span>
                                        <span class="block text-[10px] text-emerald-700 font-semibold">{{ $def['accuracy_status'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-400 italic">
                        *Sektor dengan elastisitas permintaan kokoh atau pendapatan natural hedge valas.
                    </p>
                </div>

                <!-- Card 3: Top Sektor Rentan -->
                <div class="bg-white rounded-2xl border border-rose-200 p-6 shadow-sm flex flex-col justify-between gap-4">
                    <div>
                        <div class="flex items-center justify-between border-b border-rose-100 pb-3 mb-3">
                            <span class="text-xs font-bold text-rose-900 uppercase tracking-wider flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                                <span>Top Sektor Rentan</span>
                            </span>
                            <span class="text-[10px] font-mono text-rose-700 bg-rose-50 px-2 py-0.5 rounded-md font-bold border border-rose-200">
                                Paling Tertekan
                            </span>
                        </div>

                        <div class="space-y-2.5">
                            @foreach($topVulnerable as $vuln)
                                <div class="p-2.5 rounded-lg bg-rose-50/50 border border-rose-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-xs font-bold text-slate-900 block">{{ $vuln['sector_name'] }}</span>
                                        <span class="text-[11px] text-slate-400 font-mono">Prediksi: {{ $vuln['predicted_impact'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-black text-rose-600 num-tabular font-mono">
                                            {{ $vuln['actual_return'] }}%
                                        </span>
                                        <span class="block text-[10px] text-rose-700 font-semibold">{{ $vuln['accuracy_status'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-400 italic">
                        *Sektor dengan leverage operasional/utang tinggi atau permintaan siklikal sensitif.
                    </p>
                </div>
            </div>

            <!-- 4. COMPARATIVE 11-SECTOR MATRIX (Side-by-Side Dual Visual Meters) -->
            @php
                $sectorMetaMap = [
                    'IDX-HEALTHCARE' => ['short' => 'KS', 'color' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
                    'IDX-CONSUMER-NON-CYCLICALS' => ['short' => 'KP', 'color' => 'bg-teal-100 text-teal-800 border-teal-200'],
                    'IDX-TECHNOLOGY' => ['short' => 'TK', 'color' => 'bg-indigo-100 text-indigo-800 border-indigo-200'],
                    'IDX-INFRASTRUCTURES' => ['short' => 'IF', 'color' => 'bg-cyan-100 text-cyan-800 border-cyan-200'],
                    'IDX-BASIC-MATERIALS' => ['short' => 'BB', 'color' => 'bg-amber-100 text-amber-800 border-amber-200'],
                    'IDX-ENERGY' => ['short' => 'EN', 'color' => 'bg-orange-100 text-orange-800 border-orange-200'],
                    'IDX-FINANCIALS' => ['short' => 'KU', 'color' => 'bg-blue-100 text-blue-800 border-blue-200'],
                    'IDX-INDUSTRIALS' => ['short' => 'PI', 'color' => 'bg-slate-200 text-slate-800 border-slate-300'],
                    'IDX-CONSUMER-CYCLICALS' => ['short' => 'KN', 'color' => 'bg-violet-100 text-violet-800 border-violet-200'],
                    'IDX-PROPERTIES' => ['short' => 'PR', 'color' => 'bg-rose-100 text-rose-800 border-rose-200'],
                    'IDX-TRANSPORTATION' => ['short' => 'TL', 'color' => 'bg-sky-100 text-sky-800 border-sky-200'],
                ];
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
                            <span>Komparasi Empiris: Realita Pasar BEI vs Prediksi Model AI</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Evaluasi mendalam 11 sektor: Nilai persentase return riil bursa efek vs estimasi transmisi model MacroSectors
                        </p>
                    </div>

                    <!-- Visual Legend -->
                    <div class="flex items-center gap-4 text-xs shrink-0 flex-wrap">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-xs bg-rose-500"></span>
                            <span class="w-3 h-3 rounded-xs bg-emerald-500 -ml-2"></span>
                            <span class="text-slate-600 font-medium">Realita Historis (%)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-3 rounded-xs bg-blue-500"></span>
                            <span class="text-slate-600 font-medium">Prediksi Model AI</span>
                        </div>
                    </div>
                </div>

                <!-- 11 Sectors Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider select-none">
                                <th class="px-6 py-4 w-72">Sektor & Driver Ekonomi</th>
                                <th class="px-5 py-4 text-right w-36">Realita Historis</th>
                                <th class="px-5 py-4 text-right w-36">Prediksi Model</th>
                                <th class="px-6 py-4">Visual Komparasi (Dual-Meter)</th>
                                <th class="px-6 py-4 text-center w-44">Status Presisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm num-tabular">
                            @foreach($sectorComparison as $sec)
                                @php
                                    $actual = (float) $sec['actual_return'];
                                    $predicted = (float) $sec['predicted_impact'];
                                    $meta = $sectorMetaMap[$sec['sector_code']] ?? ['short' => 'SX', 'color' => 'bg-slate-100 text-slate-700 border-slate-200'];
                                    $status = $sec['accuracy_status'];

                                    // Bar widths scaled proportionally (max 50% scale factor)
                                    $actualWidth = min(100, max(8, abs($actual) * 2));
                                    $predictedWidth = min(100, max(8, abs($predicted) * 2));
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <!-- Column 1: Sektor & Driver -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-start gap-3">
                                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs font-mono shrink-0 shadow-2xs border {{ $meta['color'] }}">
                                                {{ $meta['short'] }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="font-extrabold text-slate-900 text-sm">{{ $sec['sector_name'] }}</span>
                                                    <span class="text-[10px] font-mono text-slate-400">({{ $sec['sector_code'] }})</span>
                                                </div>
                                                <p class="text-xs text-slate-500 font-sans mt-1 leading-relaxed max-w-md">
                                                    {{ $sec['key_driver'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Column 2: Realita Historis -->
                                    <td class="px-5 py-4 text-right align-top">
                                        <span class="text-base font-black {{ $actual >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $actual > 0 ? '+' : '' }}{{ number_format($actual, 1) }}%
                                        </span>
                                        <span class="block text-[10px] text-slate-400 font-sans mt-0.5">Return Riil BEI</span>
                                    </td>

                                    <!-- Column 3: Prediksi Model -->
                                    <td class="px-5 py-4 text-right align-top">
                                        <span class="text-base font-black {{ $predicted >= 0 ? 'text-blue-600' : 'text-slate-700' }}">
                                            {{ $predicted > 0 ? '+' : '' }}{{ number_format($predicted, 1) }}
                                        </span>
                                        <span class="block text-[10px] text-slate-400 font-sans mt-0.5">Skor Transmisi</span>
                                    </td>

                                    <!-- Column 4: Dual Visual Progress Meter -->
                                    <td class="px-6 py-4 align-top">
                                        <div class="space-y-2.5 max-w-md">
                                            <!-- Meter 1: Realita Historis -->
                                            <div>
                                                <div class="flex items-center justify-between text-[11px] mb-1">
                                                    <span class="text-slate-500 font-sans font-medium">Realita BEI</span>
                                                    <span class="font-bold {{ $actual >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                        {{ $actual > 0 ? '+' : '' }}{{ number_format($actual, 1) }}%
                                                    </span>
                                                </div>
                                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                                    <div class="h-2 rounded-full {{ $actual >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }} transition-all"
                                                         style="width: {{ $actualWidth }}%"></div>
                                                </div>
                                            </div>

                                            <!-- Meter 2: Prediksi Model -->
                                            <div>
                                                <div class="flex items-center justify-between text-[11px] mb-1">
                                                    <span class="text-slate-500 font-sans font-medium">Prediksi AI</span>
                                                    <span class="font-bold {{ $predicted >= 0 ? 'text-blue-600' : 'text-slate-700' }}">
                                                        {{ $predicted > 0 ? '+' : '' }}{{ number_format($predicted, 1) }}
                                                    </span>
                                                </div>
                                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                                    <div class="h-2 rounded-full {{ $predicted >= 0 ? 'bg-blue-500' : 'bg-slate-600' }} transition-all"
                                                         style="width: {{ $predictedWidth }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Column 5: Status Presisi Badge -->
                                    <td class="px-6 py-4 text-center align-top">
                                        <div class="flex flex-col items-center gap-1.5">
                                            @if($status === 'Bullseye (Presisi)')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span>{{ $status }}</span>
                                                </span>
                                            @elseif($status === 'Konsisten')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                                    <span>{{ $status }}</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                    <span>{{ $status }}</span>
                                                </span>
                                            @endif

                                            @if($sec['directional_match'])
                                                <span class="text-[10px] font-mono text-emerald-600 font-semibold">✓ Arah Selaras</span>
                                            @else
                                                <span class="text-[10px] font-mono text-amber-600 font-semibold">⚠ Deviasi Siklikal</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 5. RETROSPECTIVE AI POST-MORTEM CARD -->
            <div class="bg-white rounded-2xl border border-blue-200 p-6 sm:p-8 shadow-sm flex flex-col gap-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-5">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider border border-blue-200">
                                Retrospeksi &amp; Pelajaran Strategis AI
                            </span>
                            <span class="text-xs text-slate-400">• Post-Mortem Analysis</span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900 mt-1">
                            Pelajaran Krusial bagi Investor Modern Saat Menghadapi Krisis Serupa
                        </h3>
                    </div>

                    <div class="flex items-center gap-2 self-start sm:self-auto shrink-0">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 shadow-2xs">
                            <span>✨</span>
                            <span>Gemini 3.1 Flash Analysis</span>
                        </span>
                    </div>
                </div>

                <!-- Main Narrative Box -->
                <div class="p-5 sm:p-6 bg-slate-50/80 rounded-xl border border-slate-200 text-slate-700 text-xs sm:text-sm leading-relaxed font-normal">
                    <p class="leading-relaxed">
                        {{ $crisisData['ai_post_mortem'] }}
                    </p>
                </div>

                <!-- 3 Strategic Takeaway Columns -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col gap-2">
                        <div class="flex items-center gap-2 text-slate-900 font-bold text-xs">
                            <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs">1</span>
                            <span>Bantalan Defensif Alami</span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Sektor non-siklikal dan kebutuhan primer membuktikan daya tahan arus kas tertinggi saat volatilitas makro meningkat secara tiba-tiba.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col gap-2">
                        <div class="flex items-center gap-2 text-slate-900 font-bold text-xs">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs">2</span>
                            <span>Disiplin Utang &amp; Solvabilitas</span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Rasio utang valas (DER Valas) dan beban bunga menjadi pembeda utama antara emiten yang mampu bertahan vs yang terancam de-rating ekstrem.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col gap-2">
                        <div class="flex items-center gap-2 text-slate-900 font-bold text-xs">
                            <span class="w-6 h-6 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-xs">3</span>
                            <span>Likuiditas untuk Rebound</span>
                        </div>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Aset defensif bukan hanya perlindungan nilai, melainkan penyedia likuiditas likuid untuk rotasi ke saham siklikal di dasar palung pasar.
                        </p>
                    </div>
                </div>
            </div>

            <!-- 6. REGULATORY & RISK DISCLAIMER -->
            <footer class="pt-6 border-t border-slate-200 text-xs text-slate-500 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-slate-700 font-bold text-xs tracking-wide">
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>LEGAL &amp; INVESTMENT RISK DISCLAIMER</span>
                </div>
                <p class="leading-relaxed text-slate-500 text-xs max-w-4xl">
                    Validasi empiris kilas balik krisis historis ditujukan untuk analisis kuantitatif dan evaluasi metodologi transmisi makro model MacroSectors AI. Data historis masa lalu tidak menjamin kinerja masa depan. Seluruh luaran bukan merupakan saran investasi resmi atau rekomendasi beli/jual instrumen keuangan di Bursa Efek Indonesia.
                </p>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-[11px] text-slate-400 pt-3 border-t border-slate-200/60 gap-2">
                    <span>© 2026 MacroSectors AI • Wesley Wilnio • Sectors Hackathon Indonesia 2026</span>
                    <span>Modul Validasi Empiris 11 Sektor Resmi Bursa Efek Indonesia</span>
                </div>
            </footer>
        </main>
    </div>
</body>
</html>
