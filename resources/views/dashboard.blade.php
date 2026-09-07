<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacroSectors Terminal — IDX Market Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        .num-tabular {
            font-family: 'Geist Mono', monospace;
            font-variant-numeric: tabular-nums;
        }
        /* Custom thin scrollbar for financial terminal density */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #020617;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 2px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 h-full flex flex-col antialiased selection:bg-emerald-500 selection:text-slate-950 overflow-hidden" 
      x-data="{ 
          selectedSectorCode: '{{ $sectorResults->first()?->sector->sector_code ?? 'IDX-ENERGY' }}',
          sectors: {{ json_encode($sectorResults->mapWithKeys(fn($r) => [$r->sector->sector_code => [
              'code' => $r->sector->sector_code,
              'name' => $r->sector->sector_name,
              'score' => $r->impact_score,
              'status' => $r->resilience_status,
              'reasoning' => $r->reasoning,
              'avg_der' => $r->sector->avg_der,
              'avg_npm' => $r->sector->avg_npm,
              'vulnerable_companies' => $r->vulnerable_companies ?? [],
              'beneficiary_companies' => $r->beneficiary_companies ?? [],
              'companies' => $r->sector->companies
          ]])) }},
          get activeSector() {
              return this.sectors[this.selectedSectorCode] || null;
          }
      }">

    <!-- Institutional Top Ribbon & Ticker -->
    <header class="border-b border-slate-800 bg-slate-950 px-4 py-2 flex items-center justify-between shrink-0 select-none">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded bg-emerald-500 flex items-center justify-center text-slate-950 font-bold text-[11px] num-tabular">
                    M
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-bold text-sm text-slate-100 tracking-tight">MACRO<span class="text-emerald-400">SECTORS</span></span>
                    <span class="text-[10px] num-tabular text-slate-400 tracking-wider">TERMINAL v1.0</span>
                </div>
            </div>

            <div class="h-3 w-px bg-slate-800"></div>

            <!-- Macro Indicators Strip -->
            <div class="hidden md:flex items-center gap-4 text-[11px] num-tabular">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400">USD/IDR:</span>
                    <span class="text-slate-200 font-semibold">16.285</span>
                    <span class="text-rose-400 text-[10px]">+0.42%</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400">BI-RATE:</span>
                    <span class="text-slate-200 font-semibold">6.25%</span>
                    <span class="text-slate-400 text-[10px]">UNCH</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400">BRENT:</span>
                    <span class="text-slate-200 font-semibold">$84.10</span>
                    <span class="text-emerald-400 text-[10px]">+1.15%</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-400">IND10Y:</span>
                    <span class="text-slate-200 font-semibold">6.89%</span>
                    <span class="text-rose-400 text-[10px]">+2 bps</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] num-tabular bg-slate-900 border border-slate-800">
                <span class="w-1.5 h-1.5 rounded-full {{ $isSectorsApiConfigured ? 'bg-emerald-400' : 'bg-emerald-500' }}"></span>
                <span class="text-slate-300">{{ $isSectorsApiConfigured ? 'Sectors API: Connected' : 'Sectors Cache: 11 Sectors Loaded' }}</span>
            </div>

            <form action="{{ route('sectors.sync') }}" method="POST">
                @csrf
                <button type="submit" title="Sync snapshot data from Sectors REST API" 
                        class="px-2.5 py-1 text-[11px] num-tabular font-medium bg-slate-900 hover:bg-slate-800 text-slate-300 border border-slate-700 rounded transition flex items-center gap-1.5">
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Snapshot Sync</span>
                </button>
            </form>
        </div>
    </header>

    <!-- Main Workspace (Split View) -->
    <div class="flex-1 flex overflow-hidden">

        <!-- LEFT WORKSPACE: Command & Macro Narrative Control (38% Width) -->
        <aside class="w-full lg:w-[420px] xl:w-[460px] border-r border-slate-800 flex flex-col bg-slate-950 shrink-0">
            
            <!-- Command Input Box -->
            <div class="p-3.5 border-b border-slate-800 bg-slate-900/40">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-[10px] uppercase font-mono tracking-wider font-semibold text-slate-400 flex items-center gap-1.5">
                        <span class="text-emerald-400">&gt;</span> Macro Disruption Engine
                    </label>
                    <span class="text-[10px] num-tabular text-slate-400">Press Enter to simulate</span>
                </div>

                <form action="{{ route('analyze') }}" method="POST">
                    @csrf
                    <div class="relative">
                        <input type="text" name="custom_scenario" 
                               placeholder="Input headline / what-if scenario..." 
                               class="w-full bg-slate-950 border border-slate-700/80 rounded px-3 py-2 text-xs text-slate-100 placeholder-slate-400 font-mono-num focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                    </div>
                </form>

                <!-- Quick Presets Carousel/List -->
                <div class="mt-3">
                    <span class="text-[10px] uppercase font-mono text-slate-400 block mb-1.5">Benchmarked Scenarios:</span>
                    <div class="space-y-1 max-h-32 overflow-y-auto pr-1">
                        @foreach($presetScenarios as $preset)
                            @php
                                $isActive = $activeScenario && $activeScenario->id === $preset->id;
                            @endphp
                            <a href="{{ route('dashboard', ['scenario_id' => $preset->id]) }}" 
                               class="block px-2.5 py-1.5 rounded text-[11px] transition border {{ $isActive ? 'bg-emerald-950/40 border-emerald-600/80 text-emerald-300 font-medium' : 'bg-slate-900/50 hover:bg-slate-800/80 border-slate-800 text-slate-300' }}">
                                <div class="flex items-center justify-between">
                                    <span class="truncate">{{ $preset->title }}</span>
                                    <span class="text-[9px] uppercase font-mono px-1 py-0.2 rounded bg-slate-800 text-slate-400 shrink-0 ml-1.5">{{ $preset->category }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Macro Synthesis & Executive Brief -->
            <div class="flex-1 p-4 overflow-y-auto space-y-4">
                <div class="border border-slate-800 rounded bg-slate-900/60 p-3.5 space-y-2.5">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                        <span class="text-[10px] font-mono uppercase tracking-wider text-slate-400 font-semibold">Macro Transmit Status</span>
                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-slate-800 text-emerald-400 border border-slate-700">
                            {{ $analysisData['engine_used'] ?? 'MacroSectors AI' }}
                        </span>
                    </div>

                    <div class="space-y-1">
                        <span class="text-[11px] font-mono uppercase text-slate-400">Current Shock Vector:</span>
                        <h2 class="text-xs font-bold text-slate-100 leading-snug">
                            {{ $activeScenario?->title ?? 'Baseline Market Structure' }}
                        </h2>
                    </div>

                    <div class="text-[12px] text-slate-300 leading-relaxed font-sans border-t border-slate-800/60 pt-2">
                        {{ $analysisData['executive_summary'] ?? 'Pilih atau jalankan skenario untuk melihat transmisi dampak ke sektor modal.' }}
                    </div>

                    @if(isset($analysisData['key_takeaway']))
                        <div class="bg-slate-950/80 border-l-2 border-emerald-500 p-2 text-[11px] text-slate-300">
                            <span class="text-emerald-400 font-semibold uppercase text-[10px] font-mono block">Strategic Takeaway:</span>
                            {{ $analysisData['key_takeaway'] }}
                        </div>
                    @endif
                </div>

                <!-- Methodology / Transmission Matrix Helper -->
                <div class="border border-slate-800/80 rounded bg-slate-900/30 p-3 text-[11px] space-y-2 text-slate-400">
                    <span class="text-[10px] font-mono uppercase text-slate-400 font-semibold block">Scoring Scale Architecture:</span>
                    <div class="grid grid-cols-3 gap-1.5 text-center num-tabular text-[10px]">
                        <div class="bg-rose-950/30 border border-rose-900/50 p-1 rounded text-rose-300">
                            -10 to -6<br><span class="text-[9px] text-rose-400">CRITICAL</span>
                        </div>
                        <div class="bg-slate-800/40 border border-slate-700 p-1 rounded text-slate-300">
                            -5 to +5<br><span class="text-[9px] text-slate-400">VULN / RESILIENT</span>
                        </div>
                        <div class="bg-emerald-950/30 border border-emerald-900/50 p-1 rounded text-emerald-300">
                            +6 to +10<br><span class="text-[9px] text-emerald-400">BENEFICIARY</span>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 leading-normal">
                        Skor dikalkulasi dari relasi sensitivitas utang (DER), marjin kotor (NPM), dan arah arus kas valas terhadap variabel makro.
                    </p>
                </div>
            </div>

            <!-- Footer Compliance Notice -->
            <div class="p-3 border-t border-slate-800 text-[10px] text-slate-400 bg-slate-950 shrink-0">
                <span>Instruksi analitik riset pasar modal. Bukan rekomendasi investasi.</span>
            </div>
        </aside>

        <!-- RIGHT WORKSPACE: 11-Sector Resilience Matrix & Docked Inspector (Flex-1) -->
        <main class="flex-1 flex flex-col overflow-hidden bg-slate-950">

            <!-- UPPER HALF: 11-Sector Matrix Table (Density 9) -->
            <div class="h-1/2 border-b border-slate-800 flex flex-col overflow-hidden">
                <div class="px-4 py-2 bg-slate-900/60 border-b border-slate-800 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-slate-300">11-Sector Resilience Matrix</span>
                        <span class="text-[10px] num-tabular text-slate-400">(Sorted by Net Impact Score)</span>
                    </div>
                    <span class="text-[10px] num-tabular text-slate-400">Click any sector to inspect micro exposure</span>
                </div>

                <div class="flex-1 overflow-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-900 text-slate-400 font-mono text-[10px] uppercase sticky top-0 z-10 border-b border-slate-800 select-none">
                            <tr>
                                <th class="px-3 py-2 font-semibold">Sector</th>
                                <th class="px-3 py-2 font-semibold text-center w-24">Impact</th>
                                <th class="px-3 py-2 font-semibold w-28">Status</th>
                                <th class="px-3 py-2 font-semibold text-right w-20">Avg DER</th>
                                <th class="px-3 py-2 font-semibold text-right w-20">Avg NPM</th>
                                <th class="px-3 py-2 font-semibold">Transmission Rationalization</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/80 num-tabular">
                            @foreach($sectorResults as $item)
                                @php
                                    $score = $item->impact_score;
                                    $code = $item->sector->sector_code;
                                    $isPositive = $score > 0;
                                    $isNegative = $score < 0;
                                    $scoreColor = $isPositive ? 'text-emerald-400 bg-emerald-950/50 border-emerald-800' : ($isNegative ? 'text-rose-400 bg-rose-950/50 border-rose-800' : 'text-slate-400 bg-slate-800 border-slate-700');
                                    $statusBadge = $isPositive ? 'text-emerald-300 border-emerald-800/70 bg-emerald-950/30' : ($isNegative ? 'text-rose-300 border-rose-800/70 bg-rose-950/30' : 'text-slate-400 border-slate-700 bg-slate-800/30');
                                @endphp
                                <tr @click="selectedSectorCode = '{{ $code }}'" 
                                    :class="selectedSectorCode === '{{ $code }}' ? 'bg-slate-800/90 border-l-2 border-emerald-400' : 'hover:bg-slate-900/70 border-l-2 border-transparent'"
                                    class="cursor-pointer transition">
                                    
                                    <td class="px-3 py-2 font-sans">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-slate-100 text-[12px]">{{ $item->sector->sector_name }}</span>
                                            <span class="text-[9px] font-mono text-slate-400">{{ $code }}</span>
                                        </div>
                                    </td>

                                    <td class="px-3 py-2 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded border text-[11px] font-bold font-mono {{ $scoreColor }}">
                                            {{ $score > 0 ? '+' : '' }}{{ $score }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-2">
                                        <span class="inline-block px-1.5 py-0.5 rounded border text-[10px] font-mono uppercase {{ $statusBadge }}">
                                            {{ $item->resilience_status }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-2 text-right text-slate-300 text-[11px]">
                                        {{ $item->sector->avg_der }}x
                                    </td>

                                    <td class="px-3 py-2 text-right text-[11px] {{ $item->sector->avg_npm < 0 ? 'text-rose-400' : 'text-emerald-400' }}">
                                        {{ $item->sector->avg_npm }}%
                                    </td>

                                    <td class="px-3 py-2 text-slate-300 text-[11px] font-sans truncate max-w-xs xl:max-w-md">
                                        {{ $item->reasoning }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- LOWER HALF: Docked Micro Company Inspector (Density 9) -->
            <div class="h-1/2 flex flex-col overflow-hidden bg-slate-900/20">
                <div class="px-4 py-2 bg-slate-900/80 border-b border-slate-800 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-mono uppercase text-slate-400">Micro Exposure Inspector:</span>
                        <span class="text-xs font-bold text-slate-100 font-sans" x-text="activeSector?.name + ' (' + activeSector?.code + ')'"></span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-mono uppercase border"
                              :class="activeSector?.score > 0 ? 'bg-emerald-950 text-emerald-400 border-emerald-800' : (activeSector?.score < 0 ? 'bg-rose-950 text-rose-400 border-rose-800' : 'bg-slate-800 text-slate-400 border-slate-700')"
                              x-text="'Net Score: ' + (activeSector?.score > 0 ? '+' : '') + activeSector?.score"></span>
                    </div>
                    <div class="text-[10px] num-tabular text-slate-400 flex items-center gap-3">
                        <span>Avg DER: <strong class="text-slate-200" x-text="activeSector?.avg_der + 'x'"></strong></span>
                        <span>Avg NPM: <strong class="text-slate-200" x-text="activeSector?.avg_npm + '%'"></strong></span>
                        <span>Sectors API Grounded</span>
                    </div>
                </div>

                <!-- Sector Specific Reasoning Callout -->
                <div class="px-4 py-2 bg-slate-950 border-b border-slate-800/80 text-xs text-slate-300 flex items-start gap-2">
                    <span class="text-emerald-400 font-mono text-[11px] mt-0.5">&gt;</span>
                    <p class="leading-relaxed font-sans text-[11px]" x-text="activeSector?.reasoning"></p>
                </div>

                <!-- Companies Table in Selected Sector -->
                <div class="flex-1 overflow-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead class="bg-slate-900/90 text-slate-400 font-mono text-[10px] uppercase sticky top-0 z-10 border-b border-slate-800 select-none">
                            <tr>
                                <th class="px-3 py-1.5 font-semibold w-24">Ticker</th>
                                <th class="px-3 py-1.5 font-semibold">Emiten Name</th>
                                <th class="px-3 py-1.5 font-semibold text-right w-32">Market Cap</th>
                                <th class="px-3 py-1.5 font-semibold text-right w-24">DER (Utang)</th>
                                <th class="px-3 py-1.5 font-semibold text-right w-24">NPM (Margin)</th>
                                <th class="px-3 py-1.5 font-semibold text-right w-20">P/E</th>
                                <th class="px-3 py-1.5 font-semibold text-right w-20">PBV</th>
                                <th class="px-3 py-1.5 font-semibold w-28 text-center">Exposure Tag</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60 num-tabular">
                            <template x-for="comp in activeSector?.companies" :key="comp.id">
                                <tr class="hover:bg-slate-800/50 transition">
                                    <td class="px-3 py-1.5 font-bold text-slate-100 text-[12px]" x-text="comp.symbol"></td>
                                    <td class="px-3 py-1.5 text-slate-300 font-sans text-[11px] truncate max-w-xs" x-text="comp.name"></td>
                                    <td class="px-3 py-1.5 text-right text-slate-200 text-[11px]" x-text="(comp.market_cap / 1000000000000).toFixed(1) + ' T'"></td>
                                    <td class="px-3 py-1.5 text-right text-[11px]" 
                                        :class="comp.der > 1.5 ? 'text-rose-400 font-semibold' : 'text-slate-300'"
                                        x-text="comp.der ? comp.der + 'x' : '-'"></td>
                                    <td class="px-3 py-1.5 text-right text-[11px]" 
                                        :class="comp.npm < 0 ? 'text-rose-400 font-semibold' : 'text-emerald-400 font-semibold'"
                                        x-text="comp.npm ? comp.npm + '%' : '-'"></td>
                                    <td class="px-3 py-1.5 text-right text-slate-400 text-[11px]" x-text="comp.pe_ratio ? comp.pe_ratio + 'x' : '-'"></td>
                                    <td class="px-3 py-1.5 text-right text-slate-400 text-[11px]" x-text="comp.pbv_ratio ? comp.pbv_ratio + 'x' : '-'"></td>
                                    <td class="px-3 py-1.5 text-center">
                                        <template x-if="activeSector?.vulnerable_companies?.includes(comp.symbol)">
                                            <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-mono font-semibold bg-rose-950 text-rose-300 border border-rose-800">HIGH EXPOSURE</span>
                                        </template>
                                        <template x-if="activeSector?.beneficiary_companies?.includes(comp.symbol)">
                                            <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-mono font-semibold bg-emerald-950 text-emerald-300 border border-emerald-800">RESILIENT</span>
                                        </template>
                                        <template x-if="!activeSector?.vulnerable_companies?.includes(comp.symbol) && !activeSector?.beneficiary_companies?.includes(comp.symbol)">
                                            <span class="text-slate-400 text-[10px] font-mono">-</span>
                                        </template>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

</body>
</html>
