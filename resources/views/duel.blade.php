<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Head-to-Head Sector & Stock Duel - MacroSectors AI</title>
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
<body class="min-h-full flex flex-col sm:flex-row bg-slate-50 text-slate-900" x-data="duelApp()">

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
                <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-sm shadow-xs">
                    ⚔️
                </div>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-tight">Head-to-Head Duel</h2>
                    <span class="text-[11px] text-slate-500">Arena Komparasi Transmisi Makro 2 Sektor / 2 Emiten</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="px-3.5 py-1.5 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all inline-flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Peta Makro</span>
                </a>
                <button type="button" onclick="window.print()" class="px-3.5 py-1.5 text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 rounded-xl transition-all inline-flex items-center gap-1.5 cursor-pointer shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak PDF</span>
                </button>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 max-w-7xl w-full mx-auto p-4 sm:p-8 pt-8 sm:pt-10 pb-16 flex flex-col gap-8">

            <!-- 1. QUICK PRESET BATTLES (One-Click Showcase) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Quick Battle Presets</span>
                        <span class="text-xs text-slate-400">• Pilih duel populer untuk demonstrasi langsung</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2.5 pt-1">
                    @foreach($presetDuels as $key => $preset)
                        <a href="{{ route('duel.index', ['preset' => $key, 'scenario_id' => $activeScenario?->id]) }}" 
                           class="p-3 rounded-xl border text-left transition-all flex flex-col justify-between gap-2 {{ $selectedPreset === $key ? 'bg-amber-50/70 border-amber-300 ring-2 ring-amber-400/20' : 'bg-slate-50/60 border-slate-200 hover:bg-white hover:border-slate-300' }}">
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $preset['mode'] === 'stock' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $preset['mode'] === 'stock' ? 'Saham' : 'Sektor' }}
                                </span>
                                <span class="text-[10px] font-mono font-bold text-slate-700">
                                    {{ $preset['fighter_a'] }} vs {{ $preset['fighter_b'] }}
                                </span>
                            </div>
                            <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-1">{{ $preset['name'] }}</h4>
                            <span class="text-[10px] text-slate-500 line-clamp-1">{{ $preset['scenario_hint'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- 2. DUEL ARENA CONFIGURATION FORM (Pilih Petarung & Skenario) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col gap-6">
                <form action="{{ route('duel.index') }}" method="POST" class="flex flex-col gap-6">
                    @csrf

                    <!-- Mode Toggle (Saham vs Sektor) -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
                        <div>
                            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-0.5">Konfigurasi Pertarungan</span>
                            <h3 class="text-lg font-bold text-slate-900">Pilih Lawan & Skenario Makro</h3>
                        </div>

                        <!-- Mode Switcher Buttons -->
                        <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 self-start sm:self-auto">
                            <button type="button" 
                                    @click="mode = 'stock'"
                                    :class="mode === 'stock' ? 'bg-white text-blue-700 font-bold shadow-2xs' : 'text-slate-600 font-semibold hover:text-slate-900'"
                                    class="px-4 py-2 rounded-lg text-xs transition-all cursor-pointer flex items-center gap-1.5">
                                <span>🏢 Duel Saham (Stock vs Stock)</span>
                            </button>
                            <button type="button" 
                                    @click="mode = 'sector'"
                                    :class="mode === 'sector' ? 'bg-white text-blue-700 font-bold shadow-2xs' : 'text-slate-600 font-semibold hover:text-slate-900'"
                                    class="px-4 py-2 rounded-lg text-xs transition-all cursor-pointer flex items-center gap-1.5">
                                <span>🏛️ Duel Sektor (Sector vs Sector)</span>
                            </button>
                        </div>
                        <input type="hidden" name="mode" :value="mode">
                    </div>

                    <!-- Arena Selection Row: Fighter A vs Fighter B -->
                    <div class="grid grid-cols-1 lg:grid-cols-11 gap-4 items-center">

                        <!-- Fighter A Selector Box (Left 5 Cols) -->
                        <div class="lg:col-span-5 p-4 sm:p-5 rounded-2xl border-2 border-blue-200 bg-blue-50/30 flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-blue-600 text-white shadow-2xs">
                                    PETARUNG A (SUDUT BIRU)
                                </span>
                                <span class="text-xs font-mono font-bold text-blue-700" x-text="mode === 'stock' ? selectedFighterA : selectedSectorA"></span>
                            </div>

                            <!-- Stock Selector A -->
                            <div x-show="mode === 'stock'">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Saham Emiten A:</label>
                                <select name="fighter_a" 
                                        x-model="selectedFighterA"
                                        :disabled="mode !== 'stock'"
                                        class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                                    @foreach($allCompanies as $comp)
                                        <option value="{{ $comp->symbol }}" {{ ($mode === 'stock' && $fighterA['symbol'] === $comp->symbol) ? 'selected' : '' }}>
                                            {{ $comp->symbol }} — {{ $comp->name }} ({{ $comp->sector?->sector_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sector Selector A -->
                            <div x-show="mode === 'sector'" x-cloak>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Sektor IDX A:</label>
                                <select name="fighter_a" 
                                        x-model="selectedSectorA"
                                        :disabled="mode !== 'sector'"
                                        class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                                    @foreach($allSectors as $sec)
                                        <option value="{{ $sec->sector_code }}" {{ ($mode === 'sector' && $fighterA['code'] === $sec->sector_code) ? 'selected' : '' }}>
                                            {{ $sec->sector_name }} ({{ strtoupper(str_replace('idx-', '', $sec->sector_code)) }}) • {{ $sec->companies->count() }} Emiten
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Center VS Emblem (1 Col) -->
                        <div class="lg:col-span-1 flex flex-col items-center justify-center py-2 lg:py-0">
                            <div class="w-12 h-12 rounded-full bg-slate-900 text-white font-extrabold text-sm flex items-center justify-center shadow-md border-2 border-white ring-4 ring-slate-100">
                                VS
                            </div>
                        </div>

                        <!-- Fighter B Selector Box (Right 5 Cols) -->
                        <div class="lg:col-span-5 p-4 sm:p-5 rounded-2xl border-2 border-rose-200 bg-rose-50/30 flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-rose-600 text-white shadow-2xs">
                                    PETARUNG B (SUDUT MERAH)
                                </span>
                                <span class="text-xs font-mono font-bold text-rose-700" x-text="mode === 'stock' ? selectedFighterB : selectedSectorB"></span>
                            </div>

                            <!-- Stock Selector B -->
                            <div x-show="mode === 'stock'">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Saham Emiten B:</label>
                                <select name="fighter_b" 
                                        x-model="selectedFighterB"
                                        :disabled="mode !== 'stock'"
                                        class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all cursor-pointer">
                                    @foreach($allCompanies as $comp)
                                        <option value="{{ $comp->symbol }}" {{ ($mode === 'stock' && $fighterB['symbol'] === $comp->symbol) ? 'selected' : '' }}>
                                            {{ $comp->symbol }} — {{ $comp->name }} ({{ $comp->sector?->sector_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sector Selector B -->
                            <div x-show="mode === 'sector'" x-cloak>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Sektor IDX B:</label>
                                <select name="fighter_b" 
                                        x-model="selectedSectorB"
                                        :disabled="mode !== 'sector'"
                                        class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-bold text-slate-900 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all cursor-pointer">
                                    @foreach($allSectors as $sec)
                                        <option value="{{ $sec->sector_code }}" {{ ($mode === 'sector' && $fighterB['code'] === $sec->sector_code) ? 'selected' : '' }}>
                                            {{ $sec->sector_name }} ({{ strtoupper(str_replace('idx-', '', $sec->sector_code)) }}) • {{ $sec->companies->count() }} Emiten
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Macro Shock Arena Condition (Skenario Makro) -->
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/60 flex flex-col gap-3">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <label class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                                <span>🌪️ Kondisi Krisis / Guncangan Makroekonomi:</span>
                            </label>

                            <!-- Toggle between Preset & Custom Scenario -->
                            <div class="flex items-center gap-2">
                                <button type="button" 
                                        @click="scenarioMode = 'preset'" 
                                        :class="scenarioMode === 'preset' ? 'bg-white text-blue-700 font-bold border border-slate-200 shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                                        class="text-xs px-2.5 py-1 rounded-lg transition-all cursor-pointer">
                                    Pilihan Skenario
                                </button>
                                <span class="text-slate-300">|</span>
                                <button type="button" 
                                        @click="scenarioMode = 'custom'" 
                                        :class="scenarioMode === 'custom' ? 'bg-white text-blue-700 font-bold border border-slate-200 shadow-2xs' : 'text-slate-500 hover:text-slate-800'"
                                        class="text-xs px-2.5 py-1 rounded-lg transition-all cursor-pointer">
                                    Ketik Berita Baru (Custom AI)
                                </button>
                            </div>
                        </div>

                        <!-- Mode A: Dropdown Preset Scenarios -->
                        <div x-show="scenarioMode === 'preset'">
                            <select name="scenario_id" 
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                                @foreach($presetScenarios as $scen)
                                    <option value="{{ $scen->id }}" {{ $activeScenario?->id === $scen->id ? 'selected' : '' }}>
                                        {{ $scen->title }} — {{ Str::limit($scen->description, 80) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mode B: Custom News Input -->
                        <div x-show="scenarioMode === 'custom'" x-cloak class="flex flex-col gap-2">
                            <input type="text" 
                                   name="custom_scenario" 
                                   placeholder="Contoh: Perang dagang memanas, tarif impor tekstil naik 20% dan inflasi pangan melonjak..." 
                                   class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <span class="text-[11px] text-slate-500">Gemini AI akan secara langsung menelaah transmisi berita ini terhadap kedua petarung.</span>
                        </div>
                    </div>

                    <!-- Submit Duel Button -->
                    <div class="flex items-center justify-end">
                        <button type="submit" 
                                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <span class="text-base">⚔️</span>
                            <span>Mulai Duel Head-to-Head &rarr;</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. THE BATTLE ARENA HERO SECTION (Kartu Pertarungan & Pemenang) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col gap-6">

                <!-- Winner Trophy Banner -->
                @if($verdict === 'winner_a')
                    <div class="p-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-xl font-bold">
                                🏆
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-200 block">HASIL PERTANDINGAN</span>
                                <h3 class="text-base sm:text-lg font-extrabold text-white">
                                    PEMENANG: {{ $fighterA['symbol'] }} ({{ $fighterA['name'] }})
                                </h3>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-start sm:self-auto bg-white/10 px-3.5 py-1.5 rounded-xl border border-white/20">
                            <span class="text-xs font-semibold">Keunggulan Resiliensi:</span>
                            <span class="text-sm font-extrabold font-mono text-emerald-300">+{{ $scoreDiff }} Poin</span>
                        </div>
                    </div>
                @elseif($verdict === 'winner_b')
                    <div class="p-4 rounded-xl bg-gradient-to-r from-rose-600 to-pink-700 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center text-xl font-bold">
                                🏆
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-rose-200 block">HASIL PERTANDINGAN</span>
                                <h3 class="text-base sm:text-lg font-extrabold text-white">
                                    PEMENANG: {{ $fighterB['symbol'] }} ({{ $fighterB['name'] }})
                                </h3>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 self-start sm:self-auto bg-white/10 px-3.5 py-1.5 rounded-xl border border-white/20">
                            <span class="text-xs font-semibold">Keunggulan Resiliensi:</span>
                            <span class="text-sm font-extrabold font-mono text-emerald-300">+{{ $scoreDiff }} Poin</span>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-slate-800 text-white flex items-center justify-between gap-4 shadow-md">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-xl font-bold">
                                ⚖️
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">HASIL PERTANDINGAN</span>
                                <h3 class="text-base sm:text-lg font-extrabold text-white">
                                    SKOR IMBANG (DRAW) — Daya Tahan Relatif Setara
                                </h3>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Fighter Cards Arena (Side-by-Side) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">

                    <!-- Fighter A Box (Blue Corner) -->
                    <div class="p-6 rounded-2xl border-2 {{ $verdict === 'winner_a' ? 'border-blue-500 bg-blue-50/40 shadow-sm' : 'border-slate-200 bg-white' }} flex flex-col justify-between gap-6 transition-all">
                        <div class="flex flex-col gap-4">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-extrabold text-lg shadow-2xs font-mono">
                                        {{ substr($fighterA['symbol'], 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 text-lg">{{ $fighterA['symbol'] }}</span>
                                            @if($verdict === 'winner_a')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    WINNER 🏆
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-slate-500 block truncate max-w-[220px]">{{ $fighterA['name'] }}</span>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Skor Makro</span>
                                    <span class="text-2xl font-extrabold num-tabular {{ $fighterA['score'] > 0 ? 'text-emerald-600' : ($fighterA['score'] < 0 ? 'text-rose-600' : 'text-slate-600') }}">
                                        {{ $fighterA['score'] > 0 ? '+' : '' }}{{ $fighterA['score'] }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-xs">
                                <span class="px-2.5 py-1 rounded-md font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    Sektor: {{ $fighterA['sector_name'] }}
                                </span>
                                <span class="px-2.5 py-1 rounded-md font-semibold {{ $fighterA['score'] > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($fighterA['score'] < 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $fighterA['score'] > 0 ? 'Resilien' : ($fighterA['score'] < 0 ? 'Tertekan' : 'Netral') }}
                                </span>
                            </div>
                        </div>

                        <!-- Mini Stats Grid -->
                        <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100 text-center">
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">DER (Utang)</span>
                                <span class="text-xs font-bold text-slate-900 mt-0.5 block num-tabular">{{ $fighterA['der'] }}x</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">NPM (Margin)</span>
                                <span class="text-xs font-bold mt-0.5 block num-tabular {{ $fighterA['npm'] > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $fighterA['npm'] }}%</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">P/E Ratio</span>
                                <span class="text-xs font-bold text-slate-900 mt-0.5 block num-tabular">{{ $fighterA['pe_ratio'] > 0 ? $fighterA['pe_ratio'].'x' : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Fighter B Box (Red Corner) -->
                    <div class="p-6 rounded-2xl border-2 {{ $verdict === 'winner_b' ? 'border-rose-500 bg-rose-50/40 shadow-sm' : 'border-slate-200 bg-white' }} flex flex-col justify-between gap-6 transition-all">
                        <div class="flex flex-col gap-4">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-extrabold text-lg shadow-2xs font-mono">
                                        {{ substr($fighterB['symbol'], 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-900 text-lg">{{ $fighterB['symbol'] }}</span>
                                            @if($verdict === 'winner_b')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    WINNER 🏆
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-slate-500 block truncate max-w-[220px]">{{ $fighterB['name'] }}</span>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Skor Makro</span>
                                    <span class="text-2xl font-extrabold num-tabular {{ $fighterB['score'] > 0 ? 'text-emerald-600' : ($fighterB['score'] < 0 ? 'text-rose-600' : 'text-slate-600') }}">
                                        {{ $fighterB['score'] > 0 ? '+' : '' }}{{ $fighterB['score'] }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-xs">
                                <span class="px-2.5 py-1 rounded-md font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    Sektor: {{ $fighterB['sector_name'] }}
                                </span>
                                <span class="px-2.5 py-1 rounded-md font-semibold {{ $fighterB['score'] > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($fighterB['score'] < 0 ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $fighterB['score'] > 0 ? 'Resilien' : ($fighterB['score'] < 0 ? 'Tertekan' : 'Netral') }}
                                </span>
                            </div>
                        </div>

                        <!-- Mini Stats Grid -->
                        <div class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100 text-center">
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">DER (Utang)</span>
                                <span class="text-xs font-bold text-slate-900 mt-0.5 block num-tabular">{{ $fighterB['der'] }}x</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">NPM (Margin)</span>
                                <span class="text-xs font-bold mt-0.5 block num-tabular {{ $fighterB['npm'] > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $fighterB['npm'] }}%</span>
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/80">
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">P/E Ratio</span>
                                <span class="text-xs font-bold text-slate-900 mt-0.5 block num-tabular">{{ $fighterB['pe_ratio'] > 0 ? $fighterB['pe_ratio'].'x' : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Strategic Pair-Trading Synthesis Box -->
                <div class="p-5 rounded-2xl border border-blue-200 bg-blue-50/40 flex flex-col gap-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-800">💡 Analisis Strategis & Rekomendasi Pair Trading</span>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900">{{ $pairTradingThesis['headline'] }}</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">{{ $pairTradingThesis['thesis'] }}</p>
                    <div class="p-3 bg-white border border-blue-200/80 rounded-xl text-xs font-semibold text-blue-900 flex items-center gap-2 shadow-2xs">
                        <span class="text-sm">🎯</span>
                        <span><strong>Tindakan Portofolio:</strong> {{ $pairTradingThesis['pair_action'] }}</span>
                    </div>
                </div>

            </div>

            <!-- 4. SIDE-BY-SIDE STATS SHOWDOWN (Tabel Komparasi Head-to-Head) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col gap-6">
                <div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-0.5">Showdown Kuantitatif</span>
                    <h3 class="text-lg font-bold text-slate-900">Perbandingan Metrik Fundamental & Sensitivitas</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbandingan langsung rasio kunci antara {{ $fighterA['symbol'] }} dan {{ $fighterB['symbol'] }}.</p>
                </div>

                <div class="border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider select-none">
                                <th class="px-5 py-3.5 w-1/3 text-blue-700 font-bold">
                                    {{ $fighterA['symbol'] }} ({{ $fighterA['name'] }})
                                </th>
                                <th class="px-4 py-3.5 w-1/3 text-center text-slate-600 font-bold">
                                    Metrik Komparasi
                                </th>
                                <th class="px-5 py-3.5 w-1/3 text-right text-rose-700 font-bold">
                                    {{ $fighterB['symbol'] }} ({{ $fighterB['name'] }})
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm num-tabular">

                            <!-- Row 1: Skor Makro -->
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-extrabold text-base {{ $fighterA['score'] > 0 ? 'text-emerald-600' : ($fighterA['score'] < 0 ? 'text-rose-600' : 'text-slate-700') }}">
                                            {{ $fighterA['score'] > 0 ? '+' : '' }}{{ $fighterA['score'] }}
                                        </span>
                                        @if($fighterA['score'] > $fighterB['score'])
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Unggul</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-slate-700 text-xs">
                                    Skor Transmisi Makro
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($fighterB['score'] > $fighterA['score'])
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Unggul</span>
                                        @endif
                                        <span class="font-extrabold text-base {{ $fighterB['score'] > 0 ? 'text-emerald-600' : ($fighterB['score'] < 0 ? 'text-rose-600' : 'text-slate-700') }}">
                                            {{ $fighterB['score'] > 0 ? '+' : '' }}{{ $fighterB['score'] }}
                                        </span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 2: DER (Debt to Equity) -->
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-slate-900">{{ $fighterA['der'] }}x</span>
                                        @if($fighterA['der'] < $fighterB['der'])
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Lebih Aman</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-slate-700 text-xs">
                                    Leverage Utang (DER)
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($fighterB['der'] < $fighterA['der'])
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Lebih Aman</span>
                                        @endif
                                        <span class="font-bold text-slate-900">{{ $fighterB['der'] }}x</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 3: NPM (Net Profit Margin) -->
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold {{ $fighterA['npm'] > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $fighterA['npm'] }}%</span>
                                        @if($fighterA['npm'] > $fighterB['npm'])
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Lebih Tebal</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-slate-700 text-xs">
                                    Marjin Laba Bersih (NPM)
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($fighterB['npm'] > $fighterA['npm'])
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Lebih Tebal</span>
                                        @endif
                                        <span class="font-bold {{ $fighterB['npm'] > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $fighterB['npm'] }}%</span>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 4: Sektor Asal -->
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3.5 font-medium text-slate-700 text-xs">
                                    {{ $fighterA['sector_name'] }}
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-slate-700 text-xs">
                                    Klasifikasi Sektor
                                </td>
                                <td class="px-5 py-3.5 text-right font-medium text-slate-700 text-xs">
                                    {{ $fighterB['sector_name'] }}
                                </td>
                            </tr>

                            <!-- Row 5: P/E Ratio -->
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3.5 font-semibold text-slate-800 text-xs">
                                    {{ $fighterA['pe_ratio'] > 0 ? $fighterA['pe_ratio'].'x' : 'N/A' }}
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-slate-700 text-xs">
                                    Price to Earnings (P/E)
                                </td>
                                <td class="px-5 py-3.5 text-right font-semibold text-slate-800 text-xs">
                                    {{ $fighterB['pe_ratio'] > 0 ? $fighterB['pe_ratio'].'x' : 'N/A' }}
                                </td>
                            </tr>

                            <!-- Row 6: PBV Ratio -->
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3.5 font-semibold text-slate-800 text-xs">
                                    {{ $fighterA['pbv_ratio'] > 0 ? $fighterA['pbv_ratio'].'x' : 'N/A' }}
                                </td>
                                <td class="px-4 py-3.5 text-center font-semibold text-slate-700 text-xs">
                                    Price to Book (PBV)
                                </td>
                                <td class="px-5 py-3.5 text-right font-semibold text-slate-800 text-xs">
                                    {{ $fighterB['pbv_ratio'] > 0 ? $fighterB['pbv_ratio'].'x' : 'N/A' }}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 5. DEKOMPOSISI 3 PILAR TRANSMISI MAKRO (Side-by-Side) -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col gap-6">
                <div>
                    <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-0.5">Dekomposisi Saluran Transmisi</span>
                    <h3 class="text-lg font-bold text-slate-900">Adu Ketahanan 3 Pilar Makro</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Membedah di pilar mana masing-masing petarung unggul atau tertekan saat krisis terjadi.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <!-- Pilar 1: Pendapatan & Penjualan -->
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80">
                                <span class="text-base">📈</span>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Pilar Pendapatan (Revenue)</h4>
                            </div>

                            <!-- Fighter A Note -->
                            <div class="p-3 bg-white rounded-xl border border-slate-200 flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-blue-700 uppercase">{{ $fighterA['symbol'] }}</span>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $fighterA['revenue_note'] }}</p>
                            </div>

                            <!-- Fighter B Note -->
                            <div class="p-3 bg-white rounded-xl border border-slate-200 flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-rose-700 uppercase">{{ $fighterB['symbol'] }}</span>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $fighterB['revenue_note'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pilar 2: Biaya Input & Marjin Operasional -->
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80">
                                <span class="text-base">🏷️</span>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Pilar Beban Input & Biaya</h4>
                            </div>

                            <!-- Fighter A Note -->
                            <div class="p-3 bg-white rounded-xl border border-slate-200 flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-blue-700 uppercase">{{ $fighterA['symbol'] }}</span>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $fighterA['cost_note'] }}</p>
                            </div>

                            <!-- Fighter B Note -->
                            <div class="p-3 bg-white rounded-xl border border-slate-200 flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-rose-700 uppercase">{{ $fighterB['symbol'] }}</span>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $fighterB['cost_note'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pilar 3: Utang & Neraca Keuangan -->
                    <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between gap-4">
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-2 pb-2 border-b border-slate-200/80">
                                <span class="text-base">🏦</span>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Pilar Beban Utang & Neraca</h4>
                            </div>

                            <!-- Fighter A Note -->
                            <div class="p-3 bg-white rounded-xl border border-slate-200 flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-blue-700 uppercase">{{ $fighterA['symbol'] }}</span>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $fighterA['balance_sheet_note'] }}</p>
                            </div>

                            <!-- Fighter B Note -->
                            <div class="p-3 bg-white rounded-xl border border-slate-200 flex flex-col gap-1">
                                <span class="text-[10px] font-bold text-rose-700 uppercase">{{ $fighterB['symbol'] }}</span>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $fighterB['balance_sheet_note'] }}</p>
                            </div>
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
                    Kalkulasi skor duel dan simulasi transmisi makro ditujukan untuk analisis kuantitatif dan riset bursa efek. Hasil analisis bukan merupakan saran investasi resmi atau anjuran jual-beli saham di Bursa Efek Indonesia.
                </p>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-[11px] text-slate-400 pt-3 border-t border-slate-200/60 gap-2">
                    <span>© 2026 MacroSectors AI • Wesley Wilnio • Sectors Hackathon Indonesia 2026</span>
                    <span>Modul Head-to-Head Duel Terhubung ke Sectors API & Google Gemini AI</span>
                </div>
            </footer>
        </main>
    </div>

    <!-- Alpine.js Application State for Duel -->
    <script>
        function duelApp() {
            return {
                mobileMenuOpen: false,
                mode: '{{ $mode }}',
                scenarioMode: 'preset',
                selectedFighterA: '{{ $mode === 'stock' ? $fighterA['symbol'] : 'BBCA' }}',
                selectedFighterB: '{{ $mode === 'stock' ? $fighterB['symbol'] : 'BBRI' }}',
                selectedSectorA: '{{ $mode === 'sector' ? $fighterA['code'] : 'idx-energy' }}',
                selectedSectorB: '{{ $mode === 'sector' ? $fighterB['code'] : 'idx-transportation' }}',
            };
        }
    </script>
</body>
</html>
