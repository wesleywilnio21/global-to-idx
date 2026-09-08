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
<body class="min-h-full flex flex-col sm:flex-row bg-slate-50 text-slate-900" x-data="portfolioApp()">

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
        <div class="w-60 h-full bg-white shadow-xl" @click.stop>
            @include('layouts.sidebar')
        </div>
    </div>

    <!-- Desktop Persistent Sidebar -->
    <div class="hidden sm:block shrink-0 sticky top-0 h-screen z-40 no-print">
        @include('layouts.sidebar')
    </div>

    <!-- Main Workspace Content -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top App Bar -->
        <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30 shadow-xs px-6 py-4 sm:py-4.5 flex items-center justify-between no-print">
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-bold text-slate-900">Stress-Test Portofolio</h2>
                <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md font-medium border border-slate-200 hidden md:inline">
                    Simulasi Ketahanan Aset Personal
                </span>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="px-3.5 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Lihat Peta 11 Sektor</span>
                </a>
            </div>
        </header>

        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-10 pb-16 flex flex-col gap-8">

            <!-- 1. Guncangan Makro Control Panel (Preset + Ketik Skenario Kustom) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wider border border-blue-200">Guncangan Makro yang Diuji</span>
                            <span class="text-xs text-slate-400 capitalize">• {{ str_replace('_', ' ', $activeScenario?->category ?? 'Makro Global') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mt-1">{{ $activeScenario?->title }}</h3>
                        <p class="text-xs text-slate-500 mt-1 max-w-3xl leading-relaxed">{{ $activeScenario?->description }}</p>
                    </div>

                    <!-- Mode Switcher -->
                    <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl border border-slate-200 self-start sm:self-auto shrink-0">
                        <button type="button" 
                                @click="scenarioMode = 'preset'" 
                                :class="scenarioMode === 'preset' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer">
                            Pilih Preset
                        </button>
                        <button type="button" 
                                @click="scenarioMode = 'custom'" 
                                :class="scenarioMode === 'custom' ? 'bg-white text-blue-600 shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all cursor-pointer flex items-center gap-1">
                            <span>Ketik Berita Baru</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        </button>
                    </div>
                </div>

                <!-- Mode 1: Preset Dropdown / Quick Links -->
                <div x-show="scenarioMode === 'preset'" class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider shrink-0">Ganti Skenario Preset:</label>
                    <div class="flex-1 flex flex-wrap gap-2">
                        @foreach($presetScenarios as $scen)
                            <a href="{{ route('portfolio.index', ['scenario_id' => $scen->id, 'preset' => $selectedPresetKey]) }}"
                               class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all border {{ $activeScenario?->id === $scen->id ? 'bg-blue-600 text-white border-blue-600 shadow-2xs' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200' }}">
                                {{ $scen->title }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Mode 2: Custom News Input Box with Gemini AI -->
                <div x-show="scenarioMode === 'custom'" x-cloak class="pt-1">
                    <form action="{{ route('portfolio.index') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <div class="relative flex-1">
                            <input type="text" name="custom_scenario" 
                                   placeholder="Ketik berita atau guncangan makro baru (contoh: 'Kenaikan PPN 12% dan lonjakan inflasi pangan domestik')..."
                                   class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>
                        <button type="submit" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs rounded-xl shadow-xs transition-all shrink-0 flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span>Uji dengan AI</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- 2. Form Komposisi Portofolio (DITARUH DI PALING ATAS!) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-sm flex flex-col gap-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Komposisi Saham Portofolio Anda</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            Tentukan saham yang Anda miliki beserta bobot alokasi persentasenya. Anda bebas memasukkan <strong>2 saham, 3 saham, atau lebih</strong>.
                        </p>
                    </div>

                    <!-- Quick Preset Pills -->
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Template Instan:</span>
                        <button type="button" 
                                @click="setPreset([
                                    { symbol: 'BBCA', weight: 40 },
                                    { symbol: 'BMRI', weight: 30 },
                                    { symbol: 'ASII', weight: 30 }
                                ])"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 border border-slate-200 transition-all cursor-pointer">
                            🏦 Blue-Chips (3 Saham)
                        </button>
                        <button type="button" 
                                @click="setPreset([
                                    { symbol: 'ADRO', weight: 40 },
                                    { symbol: 'MEDC', weight: 35 },
                                    { symbol: 'PTBA', weight: 25 }
                                ])"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 border border-slate-200 transition-all cursor-pointer">
                            ⚡ Energi USD (3 Saham)
                        </button>
                        <button type="button" 
                                @click="setPreset([
                                    { symbol: 'BBCA', weight: 60 },
                                    { symbol: 'TLKM', weight: 40 }
                                ])"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-blue-50 hover:text-blue-700 hover:border-blue-200 border border-slate-200 transition-all cursor-pointer">
                            🎯 Portofolio 2 Saham
                        </button>
                    </div>
                </div>

                <!-- Dynamic Form Table -->
                <form action="{{ route('portfolio.index') }}" method="POST" class="flex flex-col gap-5">
                    @csrf
                    <input type="hidden" name="scenario_id" value="{{ $activeScenario?->id }}">
                    <input type="hidden" name="preset" value="custom">

                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider select-none">
                                    <th class="px-4 py-3.5 w-16 text-center">No</th>
                                    <th class="px-4 py-3.5">Pilih Saham Emiten (49 Emiten Terverifikasi)</th>
                                    <th class="px-4 py-3.5 w-44 text-right">Bobot Alokasi (%)</th>
                                    <th class="px-4 py-3.5 w-20 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                <template x-for="(item, index) in holdings" :key="index">
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <!-- Row Number -->
                                        <td class="px-4 py-3 text-center font-bold text-slate-400 text-xs" x-text="index + 1"></td>

                                        <!-- Stock Dropdown -->
                                        <td class="px-4 py-3">
                                            <div class="relative">
                                                <select :name="'holdings[' + index + '][symbol]'" 
                                                        x-model="item.symbol"
                                                        class="w-full bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                                    @foreach($allCompanies as $comp)
                                                        <option value="{{ $comp->symbol }}">
                                                            {{ $comp->symbol }} — {{ $comp->name }} ({{ $comp->sector?->sector_name }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>

                                        <!-- Weight Input -->
                                        <td class="px-4 py-3">
                                            <div class="flex items-center gap-2 justify-end">
                                                <input type="number" 
                                                       :name="'holdings[' + index + '][weight]'" 
                                                       x-model.number="item.weight"
                                                       min="1" 
                                                       max="100" 
                                                       step="1"
                                                       class="w-28 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl px-3 py-2.5 text-xs font-bold text-slate-800 text-right focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all num-tabular">
                                                <span class="text-xs font-bold text-slate-400">%</span>
                                            </div>
                                        </td>

                                        <!-- Delete Button -->
                                        <td class="px-4 py-3 text-center">
                                            <button type="button" 
                                                    @click="removeHolding(index)" 
                                                    :disabled="holdings.length <= 1"
                                                    :class="holdings.length <= 1 ? 'opacity-30 cursor-not-allowed text-slate-300' : 'text-slate-400 hover:text-rose-600 hover:bg-rose-50 cursor-pointer'"
                                                    class="p-2 rounded-lg transition-colors"
                                                    title="Hapus Saham Ini">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Action Bar: Add Row & Submit -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                        <div class="flex items-center gap-3">
                            <button type="button" 
                                    @click="addHolding()" 
                                    class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-all inline-flex items-center gap-2 cursor-pointer shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>+ Tambah Baris Saham</span>
                            </button>

                            <!-- Live Weight Indicator -->
                            <div class="flex items-center gap-2 text-xs font-semibold">
                                <span class="text-slate-500">Total Bobot:</span>
                                <span class="px-2.5 py-1 rounded-lg num-tabular font-mono"
                                      :class="totalWeight === 100 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'"
                                      x-text="totalWeight + '%'">
                                </span>
                                <template x-if="totalWeight !== 100">
                                    <span class="text-[11px] text-amber-600 font-normal hidden lg:inline">(Otomatis dinormalisasi ke 100%)</span>
                                </template>
                            </div>
                        </div>

                        <!-- Submit Test Button -->
                        <button type="submit" 
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Jalankan Stress-Test Portofolio Ini &rarr;</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. HASIL EVALUASI KETAHANAN PORTOFOLIO (Hero Card) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col gap-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Hasil Evaluasi Ketahanan Portofolio</p>
                        <h2 class="text-2xl font-bold text-slate-900">
                            {{ $presetPortfolios[$selectedPresetKey]['name'] ?? 'Portofolio Kustom Anda' }}
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
                            {{ $weightedDer > 1.2 ? 'Leverage utang relatif tinggi terhadap bunga' : 'Struktur utang terkendali aman' }}
                        </span>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata Terbobot NPM (Marjin Laba)</span>
                        <p class="text-xl font-bold text-emerald-600 mt-2 num-tabular">{{ $weightedNpm }}%</p>
                        <span class="text-[11px] text-slate-400 mt-1">Bantalan laba bersih penyerap inflasi</span>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Konsentrasi Saham</span>
                        <p class="text-xl font-bold text-blue-600 mt-2">{{ count($analyzedHoldings) }} Saham</p>
                        <span class="text-[11px] text-slate-400 mt-1">100% Data Riil Laporan Sectors API</span>
                    </div>
                </div>
            </div>

            <!-- 4. Two-Column Breakdown: Holdings Analytics & AI Rebalancing Plan -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- Left Column (2 Cols): Holdings Analytics Table -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col gap-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Kontribusi Dampak Tiap Saham</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Dekomposisi skor individual dan kontribusi berbobot masing-masing saham.</p>
                        </div>
                    </div>

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
                                        <th class="px-5 py-3.5 text-center">Skor Saham</th>
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
                </div>

                <!-- Right Column (1 Col): AI Diagnostic & Rebalancing Plan -->
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
                                        Memberikan bantalan positif sebesar <strong>+{{ $topProtector['weighted_contribution'] }} poin</strong> pada portofolio berkat daya tahan sektor {{ $topProtector['sector_name'] }}.
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

    <!-- Alpine.js Application State for Portfolio -->
    <script>
        function portfolioApp() {
            return {
                mobileMenuOpen: false,
                scenarioMode: 'preset',
                holdings: @json(array_map(fn($h) => [
                    'symbol' => $h['company']->symbol ?? $h['symbol'],
                    'weight' => $h['weight']
                ], $analyzedHoldings)),
                addHolding() {
                    if (this.holdings.length < 10) {
                        this.holdings.push({ symbol: 'BBCA', weight: 10 });
                    }
                },
                removeHolding(index) {
                    if (this.holdings.length > 1) {
                        this.holdings.splice(index, 1);
                    }
                },
                get totalWeight() {
                    return this.holdings.reduce((sum, h) => sum + (parseFloat(h.weight) || 0), 0);
                },
                setPreset(items) {
                    this.holdings = items.map(i => ({ symbol: i.symbol, weight: i.weight }));
                }
            };
        }
    </script>
</body>
</html>
