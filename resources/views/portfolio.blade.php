<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stress-Test Portofolio Saham - MacroSectors AI</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .num-tabular { font-variant-numeric: tabular-nums; }
        @media print {
            aside, header, .no-print { display: none !important; }
            main { max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        }
    </style>
</head>
<body class="min-h-full flex flex-col sm:flex-row bg-slate-50 text-slate-900" x-data="{ mobileMenuOpen: false }">

    <!-- Mobile Top Navigation Header -->
    <div class="sm:hidden bg-white border-b border-slate-200 p-4 flex items-center justify-between no-print">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-sm">M</div>
            <span class="font-bold text-slate-900 text-sm">MacroSectors AI</span>
        </a>
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 text-slate-600 hover:text-slate-900">
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
    <div class="hidden sm:block shrink-0 no-print">
        @include('layouts.sidebar')
    </div>

    <!-- Main Workspace Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top App Bar -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30 px-6 py-4 flex items-center justify-between no-print">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-slate-900">Stress-Test Portofolio</h2>
                <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md font-medium border border-slate-200 hidden md:inline">
                    Simulasi Ketahanan Aset
                </span>
            </div>

            <!-- Active Shock Indicator -->
            <div class="flex items-center gap-3">
                <form action="{{ route('portfolio.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="preset" value="{{ $selectedPresetKey }}">
                    <label for="scenario_id" class="text-xs font-semibold text-slate-500 hidden lg:inline">Skenario Makro:</label>
                    <select name="scenario_id" id="scenario_id" onchange="this.form.submit()" 
                            class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                        @foreach($presetScenarios as $scen)
                            <option value="{{ $scen->id }}" {{ $activeScenario?->id === $scen->id ? 'selected' : '' }}>
                                {{ $scen->title }}
                            </option>
                        @endforeach
                    </select>
                </form>

                <a href="{{ route('dashboard') }}" class="px-3.5 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Ke Peta Sektor</span>
                </a>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col gap-8">

            <!-- Active Scenario Brief Banner -->
            <div class="bg-gradient-to-r from-blue-900 to-slate-900 rounded-2xl p-6 text-white shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full bg-blue-500/30 text-blue-200 text-xs font-bold uppercase tracking-wider border border-blue-400/30">Guncangan Makro Aktif</span>
                        <span class="text-xs text-slate-300 capitalize">• {{ str_replace('_', ' ', $activeScenario?->category ?? 'Makro Global') }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ $activeScenario?->title }}</h3>
                    <p class="text-xs text-slate-300 mt-1 max-w-3xl leading-relaxed">{{ $activeScenario?->description }}</p>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    <span class="text-xs text-slate-300">Ganti Skenario di Dropdown Pojok Kanan Atas &uarr;</span>
                </div>
            </div>

            <!-- Quick Template Pickers -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-4 no-print">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Pilih Template Portofolio Instan (Preset):</h3>
                    <span class="text-xs text-slate-400">Atau ubah bobot saham di tabel bawah</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($presetPortfolios as $key => $preset)
                        @php $isActivePreset = $selectedPresetKey === $key; @endphp
                        <a href="{{ route('portfolio.index', ['preset' => $key, 'scenario_id' => $activeScenario?->id]) }}"
                           class="p-4 rounded-xl border transition-all text-left flex flex-col justify-between gap-2 {{ $isActivePreset ? 'border-blue-600 bg-blue-50/40 ring-2 ring-blue-500/20' : 'border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300' }}">
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="font-bold text-sm text-slate-900">{{ $preset['name'] }}</h4>
                                    @if($isActivePreset)
                                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed">{{ $preset['description'] }}</p>
                            </div>
                            <div class="flex items-center gap-1.5 flex-wrap pt-2 border-t border-slate-100 font-mono text-[11px]">
                                @foreach($preset['items'] as $item)
                                    <span class="px-2 py-0.5 rounded bg-slate-100 font-semibold text-slate-700">
                                        {{ $item['symbol'] }} {{ $item['weight'] }}%
                                    </span>
                                @endforeach
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Hero Portfolio Resilience Score Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col gap-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Hasil Evaluasi Ketahanan Portofolio</p>
                        <h2 class="text-2xl font-bold text-slate-900">
                            {{ $presetPortfolios[$selectedPresetKey]['name'] ?? 'Portofolio Kustom' }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-1 max-w-xl leading-relaxed">
                            {{ $verdict }}
                        </p>
                    </div>

                    <!-- Giant Score Metric Badge -->
                    <div class="flex items-center gap-4 bg-slate-50 border border-slate-200 rounded-2xl p-4 shrink-0">
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Skor Ketahanan</span>
                            <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full inline-block mt-1 {{ $statusBadge }}">
                                {{ $portfolioStatus }}
                            </span>
                        </div>
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center font-extrabold text-2xl num-tabular {{ $portfolioScore > 0 ? 'bg-emerald-600 text-white shadow-emerald-200 shadow-md' : ($portfolioScore < 0 ? 'bg-rose-600 text-white shadow-rose-200 shadow-md' : 'bg-slate-700 text-white') }}">
                            {{ $portfolioScore > 0 ? '+' : '' }}{{ $portfolioScore }}
                        </div>
                    </div>
                </div>

                <!-- 3 Fundamental Averages of the Portfolio -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata Terbobot DER (Utang)</span>
                        <p class="text-xl font-bold text-slate-900 mt-2 num-tabular">{{ $weightedDer }}x</p>
                        <span class="text-[11px] text-slate-400 mt-1">
                            {{ $weightedDer > 1.2 ? 'Leverage utang relatif agresif' : 'Struktur utang terkendali aman' }}
                        </span>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata Terbobot NPM (Marjin Laba)</span>
                        <p class="text-xl font-bold text-emerald-600 mt-2 num-tabular">{{ $weightedNpm }}%</p>
                        <span class="text-[11px] text-slate-400 mt-1">Bantalan laba bersih penyerap inflasi</span>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Konsentrasi Saham</span>
                        <p class="text-xl font-bold text-blue-600 mt-2">{{ count($analyzedHoldings) }} Emiten</p>
                        <span class="text-[11px] text-slate-400 mt-1">100% Data Fundamental Terverifikasi</span>
                    </div>
                </div>
            </div>

            <!-- Two-Column Workspace: Holdings Table & AI Rebalancing Intelligence -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- Left Column (2 Cols): Holdings Table -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Rincian Saham & Kontribusi Dampak</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Dekomposisi performa masing-masing saham terhadap skor ketahanan total portofolio.</p>
                        </div>
                    </div>

                    <!-- Interactive Holdings Table -->
                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                                        <th class="px-5 py-3.5">Ticker / Nama</th>
                                        <th class="px-5 py-3.5 text-center">Sektor</th>
                                        <th class="px-5 py-3.5 text-right">Bobot</th>
                                        <th class="px-5 py-3.5 text-right">DER</th>
                                        <th class="px-5 py-3.5 text-right">NPM</th>
                                        <th class="px-5 py-3.5 text-center">Skor Dampak</th>
                                        <th class="px-5 py-3.5 text-right">Kontribusi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm num-tabular">
                                    @foreach($analyzedHoldings as $item)
                                        @php
                                            $comp = $item['company'];
                                            $score = $item['company_score'];
                                            $contrib = $item['weighted_contribution'];
                                        @endphp
                                        <tr class="hover:bg-slate-50/60 transition-colors">
                                            <td class="px-5 py-4">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs font-mono {{ $score > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($score < 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                                        {{ substr($comp->symbol, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-slate-900 block">{{ $comp->symbol }}</span>
                                                        <span class="text-xs text-slate-400 font-sans truncate max-w-[140px] block">{{ $comp->name }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-5 py-4 text-center">
                                                <span class="px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                                    {{ $item['sector_name'] }}
                                                </span>
                                            </td>

                                            <td class="px-5 py-4 text-right font-bold text-slate-900">
                                                {{ $item['weight'] }}%
                                            </td>

                                            <td class="px-5 py-4 text-right font-semibold {{ $item['der'] > 1.2 ? 'text-rose-600' : 'text-slate-700' }}">
                                                {{ $item['der'] }}x
                                            </td>

                                            <td class="px-5 py-4 text-right font-semibold {{ $item['npm'] < 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                                {{ $item['npm'] }}%
                                            </td>

                                            <td class="px-5 py-4 text-center font-bold">
                                                <span class="px-2 py-0.5 rounded-full text-xs {{ $score > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($score < 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700 border border-slate-200') }}">
                                                    {{ $score > 0 ? '+' : '' }}{{ $score }}
                                                </span>
                                            </td>

                                            <td class="px-5 py-4 text-right font-bold {{ $contrib > 0 ? 'text-emerald-600' : ($contrib < 0 ? 'text-rose-600' : 'text-slate-600') }}">
                                                {{ $contrib > 0 ? '+' : '' }}{{ $contrib }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Custom Weight Adjustment Form -->
                    <div class="p-5 bg-slate-50 border border-slate-200 rounded-xl flex flex-col gap-4 no-print">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Sesuaikan Komposisi Portofolio Sendiri:</h4>
                            <span class="text-xs text-slate-400">Pilih saham dari 49 emiten terdaftar</span>
                        </div>

                        <form action="{{ route('portfolio.index') }}" method="POST" class="flex flex-col gap-3">
                            @csrf
                            <input type="hidden" name="scenario_id" value="{{ $activeScenario?->id }}">
                            <input type="hidden" name="preset" value="custom">

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                @for($i = 0; $i < 4; $i++)
                                    @php
                                        $currentHolding = $analyzedHoldings[$i] ?? null;
                                        $currentSymbol = $currentHolding['company']->symbol ?? ($allCompanies[$i]->symbol ?? '');
                                        $currentWeight = $currentHolding['weight'] ?? 25;
                                    @endphp
                                    <div class="p-3 bg-white border border-slate-200 rounded-xl flex flex-col gap-2">
                                        <label class="text-[11px] font-bold text-slate-500 uppercase">Saham #{{ $i + 1 }}</label>
                                        <select name="holdings[{{ $i }}][symbol]" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-bold text-slate-800 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                            @foreach($allCompanies as $comp)
                                                <option value="{{ $comp->symbol }}" {{ $currentSymbol === $comp->symbol ? 'selected' : '' }}>
                                                    {{ $comp->symbol }} - {{ $comp->sector?->sector_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="flex items-center gap-1.5">
                                            <input type="number" name="holdings[{{ $i }}][weight]" value="{{ $currentWeight }}" min="1" max="100" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1.5 text-xs font-semibold text-slate-800 text-right">
                                            <span class="text-xs font-bold text-slate-500">%</span>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <button type="submit" class="self-end px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-all flex items-center gap-2 cursor-pointer mt-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>Hitung Ulang Ketahanan Portofolio</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Column (1 Col): AI Diagnostic & Rebalancing Action Plan -->
                <div class="flex flex-col gap-6">

                    <!-- Shock Absorber & Risk Drag Cards -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-5">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">
                            Diagnostik Saham Ekstrem
                        </h3>

                        <!-- Top Protector -->
                        @if($topProtector)
                            <div class="p-4 rounded-xl bg-emerald-50/60 border border-emerald-200 flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 font-bold text-sm">
                                    🛡️
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-emerald-800 block">Penyelamat Portofolio (Top Protector):</span>
                                    <span class="font-bold text-slate-900 text-sm block mt-0.5">
                                        {{ $topProtector['company']->symbol }} ({{ $topProtector['company']->name }})
                                    </span>
                                    <p class="text-xs text-emerald-700 mt-1 leading-relaxed">
                                        Memberikan sokongan positif sebesar <strong>+{{ $topProtector['weighted_contribution'] }} poin</strong> pada portofolio berkat sektor {{ $topProtector['sector_name'] }}.
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Top Risk Drag -->
                        @if($topRiskDrag)
                            <div class="p-4 rounded-xl bg-rose-50/60 border border-rose-200 flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 font-bold text-sm">
                                    ⚠️
                                </div>
                                <div>
                                    <span class="text-xs font-bold text-rose-800 block">Beban Terberat (Top Risk Drag):</span>
                                    <span class="font-bold text-slate-900 text-sm block mt-0.5">
                                        {{ $topRiskDrag['company']->symbol }} ({{ $topRiskDrag['company']->name }})
                                    </span>
                                    <p class="text-xs text-rose-700 mt-1 leading-relaxed">
                                        Menggerus skor portofolio sebesar <strong>{{ $topRiskDrag['weighted_contribution'] }} poin</strong> akibat rasio DER {{ $topRiskDrag['der'] }}x.
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600">
                                Tidak ada saham yang tertekan secara kritis dalam portofolio ini.
                            </div>
                        @endif
                    </div>

                    <!-- AI Rebalancing Action Plan -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-4">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                            <div class="w-2.5 h-2.5 rounded-full bg-blue-600"></div>
                            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                                Rekomendasi Rebalancing AI
                            </h3>
                        </div>

                        <div class="flex flex-col gap-3">
                            @foreach($recommendations as $rec)
                                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/40 flex flex-col gap-2">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $rec['type'] === 'reduce' ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $rec['type'] === 'reduce' ? 'KURANGI BOBOT' : 'TAMBAH HEDGING' }}
                                        </span>
                                        <h4 class="text-xs font-bold text-slate-900">{{ $rec['title'] }}</h4>
                                    </div>
                                    <p class="text-xs text-slate-600 leading-relaxed">{{ $rec['reason'] }}</p>
                                    <div class="p-2.5 bg-white border border-slate-200 rounded-lg text-xs font-semibold text-blue-700 flex items-center gap-2">
                                        <span>👉</span>
                                        <span>{{ $rec['action'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="pt-6 border-t border-slate-200 text-xs text-slate-500 flex flex-col gap-3">
                <div class="flex items-center gap-2 text-slate-700 font-bold text-xs tracking-wide">
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>LEGAL & INVESTMENT RISK DISCLAIMER</span>
                </div>
                <p class="leading-relaxed text-slate-500 text-xs max-w-4xl">
                    Kalkulasi skor stres portofolio ditujukan sebagai analisis simulasi kuantitatif bursa efek untuk keperluan riset. Seluruh luaran bukan merupakan saran investasi resmi atau rekomendasi transaksi saham dalam yurisdiksi Republik Indonesia.
                </p>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-[11px] text-slate-400 pt-3 border-t border-slate-200/60 gap-2">
                    <span>© 2026 MacroSectors AI • Wesley Wilnio • Sectors Hackathon Indonesia 2026</span>
                    <span>Modul Portofolio Terhubung ke Laporan Fundamental Sectors API</span>
                </div>
            </footer>
        </main>
    </div>
</body>
</html>
