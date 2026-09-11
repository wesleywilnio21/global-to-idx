<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Institutional Macro Tear-Sheet — {{ $activeScenario?->title ?? 'Market Assessment' }}</title>
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
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 12mm 10mm 12mm;
            }
            body {
                background-color: #ffffff !important;
                color: #0f172a !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .page-sheet {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                margin: 0 !important;
            }
            .avoid-break {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen">

    <!-- Top Action Control Bar (Hidden when printing) -->
    <header class="no-print bg-white border-b border-slate-200 sticky top-0 z-50 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="p-2 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-all" title="Kembali ke Dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                        <span>Institutional Macro Tear-Sheet</span>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200 font-bold uppercase">A4 PDF Brief</span>
                    </h1>
                    <p class="text-[11px] text-slate-500">Lembar Riset Sensitivitas Makro 11 Sektor IHSG</p>
                </div>
            </div>

            <!-- Scenario Switcher Dropdown & Print Trigger -->
            <div class="flex items-center gap-2.5">
                <form method="GET" action="{{ route('report.tear-sheet') }}" class="flex items-center gap-2">
                    <select name="scenario_id" onchange="this.form.submit()" class="text-xs bg-slate-50 border border-slate-300 rounded-lg px-2.5 py-1.5 font-medium text-slate-700 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                        <optgroup label="Skenario Makro Bawaan">
                            @foreach($presetScenarios as $sc)
                                <option value="{{ $sc->id }}" {{ $activeScenario?->id === $sc->id ? 'selected' : '' }}>
                                    {{ $sc->title }}
                                </option>
                            @endforeach
                        </optgroup>
                        @if($recentCustomScenarios->isNotEmpty())
                            <optgroup label="Skenario AI Kustom Terakhir">
                                @foreach($recentCustomScenarios as $sc)
                                    <option value="{{ $sc->id }}" {{ $activeScenario?->id === $sc->id ? 'selected' : '' }}>
                                        {{ $sc->title }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endif
                    </select>
                </form>

                <button onclick="window.print()" class="px-4 py-1.5 text-xs font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Cetak / Simpan PDF</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Document Page Container (Optimized for A4 Print) -->
    <div class="max-w-5xl mx-auto sm:my-8 px-4 sm:px-0">
        <main class="page-sheet bg-white border border-slate-200 sm:rounded-2xl sm:shadow-md p-6 sm:p-10 flex flex-col gap-6">

            <!-- 1. Formal Institutional Header & Metadata -->
            <div class="border-b-2 border-slate-900 pb-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-6 h-6 rounded bg-slate-950 text-white font-bold text-xs flex items-center justify-center font-mono">MS</span>
                            <span class="text-xs font-black tracking-widest text-slate-900 uppercase">MacroSectors AI Intelligence</span>
                            <span class="text-[10px] text-slate-400 font-mono">| GLOBAL-TO-LOCAL TRANSMISSION ENGINE</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                            INSTITUTIONAL MACRO RESEARCH BRIEF
                        </h1>
                        <p class="text-xs font-semibold text-slate-600 mt-0.5">
                            Sensitivitas 11 Sektor Bursa Efek Indonesia terhadap Disrupsi Ekonomi Makro
                        </p>
                    </div>

                    <div class="text-right text-[11px] text-slate-500 font-mono space-y-0.5 shrink-0">
                        <div><span class="text-slate-400">DOC ID:</span> <strong class="text-slate-800">{{ $reportId }}</strong></div>
                        <div><span class="text-slate-400">TANGGAL:</span> <span class="text-slate-700 font-semibold">{{ $generatedAt }}</span></div>
                        <div><span class="text-slate-400">CAKUPAN:</span> <span class="text-slate-700 font-semibold">11 Sektor IDX (IHSG)</span></div>
                        <div><span class="text-slate-400">KLASIFIKASI:</span> <span class="text-blue-700 font-semibold">Equity Research / Non-Rated</span></div>
                    </div>
                </div>

                <!-- Scenario Headline Box -->
                <div class="mt-4 p-3.5 bg-slate-50 border border-slate-200 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-0.5">
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 bg-blue-100 text-blue-800 rounded">
                                Skenario Dinilai
                            </span>
                            <h2 class="text-sm font-bold text-slate-900">{{ $activeScenario?->title }}</h2>
                        </div>
                        <p class="text-xs text-slate-600">{{ $activeScenario?->description }}</p>
                    </div>

                    <div class="flex items-center gap-3 shrink-0 border-t sm:border-t-0 sm:border-l border-slate-200 pt-2 sm:pt-0 sm:pl-4">
                        <div class="text-right">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Market Stance</div>
                            <div class="text-xs font-extrabold px-2.5 py-0.5 rounded border mt-0.5 {{ $marketStance['badge'] }}">
                                {{ $marketStance['label'] }}
                            </div>
                        </div>
                        <div class="text-right pl-2 border-l border-slate-200">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Avg Impact</div>
                            <div class="text-base font-extrabold num-tabular {{ $avgMarketScore > 0 ? 'text-emerald-700' : ($avgMarketScore < 0 ? 'text-rose-700' : 'text-slate-700') }}">
                                {{ $avgMarketScore > 0 ? '+' : '' }}{{ number_format($avgMarketScore, 1) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Executive Narrative Brief & Strategic Takeaways -->
            <div class="avoid-break grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2 p-4 bg-white border border-slate-200 rounded-xl">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5 mb-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Executive Transmission Summary</span>
                    </h3>
                    <p class="text-xs text-slate-700 leading-relaxed">
                        {{ $narrativeSummary['executive_summary'] }}
                    </p>
                    <p class="text-xs text-slate-500 mt-2 italic">
                        {{ $marketStance['description'] }}
                    </p>
                </div>

                <div class="p-4 bg-slate-900 text-white rounded-xl flex flex-col justify-between">
                    <div>
                        <div class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Core Strategy Action</span>
                        </div>
                        <p class="text-xs text-slate-200 leading-snug font-medium">
                            {{ $narrativeSummary['key_takeaway'] }}
                        </p>
                    </div>

                    <div class="pt-3 mt-3 border-t border-slate-800 flex items-center justify-between text-[10px] font-mono text-slate-400">
                        <span>Resilient: <strong class="text-emerald-400">{{ $resilientCount }}</strong></span>
                        <span>Neutral: <strong class="text-slate-300">{{ $neutralCount }}</strong></span>
                        <span>Vulnerable: <strong class="text-rose-400">{{ $vulnerableCount }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- 3. Tactical Asset Allocation Recommendation (OW / Neutral / UW) -->
            <div class="avoid-break">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        <span>Rekomendasi Alokasi Taktis Sektor (Institutional Weighting)</span>
                    </h3>
                    <span class="text-[10px] text-slate-400 font-mono">Rebalancing Guideline</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Overweight -->
                    <div class="p-3.5 bg-emerald-50/70 border border-emerald-200 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-emerald-900 flex items-center gap-1.5 uppercase">
                                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                Overweight (OW)
                            </span>
                            <span class="text-[10px] font-mono font-bold bg-emerald-200/60 text-emerald-800 px-1.5 py-0.5 rounded">Skor &ge; +3</span>
                        </div>
                        @if($tacticalAllocation['overweight']->isNotEmpty())
                            <ul class="space-y-1">
                                @foreach($tacticalAllocation['overweight'] as $item)
                                    <li class="text-xs flex items-center justify-between text-slate-700">
                                        <span class="font-semibold">{{ $item->sector->sector_name }}</span>
                                        <span class="num-tabular font-bold text-emerald-700">+{{ $item->impact_score }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-xs text-slate-500 italic">Tidak ada sektor dengan eksposur positif agresif.</p>
                        @endif
                    </div>

                    <!-- Neutral -->
                    <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-800 flex items-center gap-1.5 uppercase">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                Neutral / Market Weight
                            </span>
                            <span class="text-[10px] font-mono font-bold bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded">-2 s.d +2</span>
                        </div>
                        @if($tacticalAllocation['neutral']->isNotEmpty())
                            <ul class="space-y-1">
                                @foreach($tacticalAllocation['neutral'] as $item)
                                    <li class="text-xs flex items-center justify-between text-slate-700">
                                        <span>{{ $item->sector->sector_name }}</span>
                                        <span class="num-tabular font-semibold text-slate-600">{{ $item->impact_score > 0 ? '+' : '' }}{{ $item->impact_score }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-xs text-slate-500 italic">Tidak ada sektor pada rentang netral.</p>
                        @endif
                    </div>

                    <!-- Underweight -->
                    <div class="p-3.5 bg-rose-50/70 border border-rose-200 rounded-xl">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-rose-900 flex items-center gap-1.5 uppercase">
                                <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                                Underweight (UW)
                            </span>
                            <span class="text-[10px] font-mono font-bold bg-rose-200/60 text-rose-800 px-1.5 py-0.5 rounded">Skor &le; -3</span>
                        </div>
                        @if($tacticalAllocation['underweight']->isNotEmpty())
                            <ul class="space-y-1">
                                @foreach($tacticalAllocation['underweight'] as $item)
                                    <li class="text-xs flex items-center justify-between text-slate-700">
                                        <span class="font-semibold">{{ $item->sector->sector_name }}</span>
                                        <span class="num-tabular font-bold text-rose-700">{{ $item->impact_score }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-xs text-slate-500 italic">Tidak ada sektor dengan risiko penurunan tajam.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 4. The Core 11-Sector Sensitivity & Fundamental Matrix -->
            <div class="avoid-break">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span>Matriks Sensitivitas Transmisi 11 Sektor IHSG</span>
                    </h3>
                    <span class="text-[10px] text-slate-400 font-mono">Data Sumber: Sectors API v2 & AI Reasoning</span>
                </div>

                <div class="overflow-x-auto border border-slate-200 rounded-xl">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-slate-100 text-slate-700 border-b border-slate-200 font-bold text-[11px] uppercase tracking-wider">
                                <th class="py-2.5 px-3">Sektor (IDX)</th>
                                <th class="py-2.5 px-3 text-center">Skor Resiliensi</th>
                                <th class="py-2.5 px-3 text-center">Status</th>
                                <th class="py-2.5 px-3 text-right">Rata DER</th>
                                <th class="py-2.5 px-3 text-right">Rata NPM</th>
                                <th class="py-2.5 px-3">Transmisi & Rasional AI</th>
                                <th class="py-2.5 px-3">Emiten Diuntungkan vs Rentan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($sortedSectors as $res)
                                @php
                                    $score = $res->impact_score;
                                    $scoreColor = match(true) {
                                        $score >= 6 => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                                        $score >= 1 => 'text-teal-700 bg-teal-50 border-teal-200',
                                        $score === 0 => 'text-slate-600 bg-slate-50 border-slate-200',
                                        $score >= -5 => 'text-amber-700 bg-amber-50 border-amber-200',
                                        default => 'text-rose-700 bg-rose-50 border-rose-200',
                                    };
                                    $statusBadge = match($res->resilience_status) {
                                        'Resilient' => 'bg-emerald-100 text-emerald-800',
                                        'Neutral' => 'bg-slate-100 text-slate-700',
                                        'Vulnerable' => 'bg-amber-100 text-amber-800',
                                        'Critical' => 'bg-rose-100 text-rose-800',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                    $der = $res->sector->avg_der ?? 0;
                                    $npm = $res->sector->avg_npm ?? 0;
                                    $beneficiaries = is_array($res->beneficiary_companies) ? $res->beneficiary_companies : [];
                                    $vulnerables = is_array($res->vulnerable_companies) ? $res->vulnerable_companies : [];
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-2.5 px-3 font-semibold text-slate-900">
                                        <div class="flex flex-col">
                                            <span>{{ $res->sector->sector_name }}</span>
                                            <span class="text-[10px] font-mono text-slate-400 uppercase">{{ $res->sector->sector_code }}</span>
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="inline-block px-2 py-0.5 rounded font-mono font-bold text-xs border {{ $scoreColor }}">
                                            {{ $score > 0 ? '+' : '' }}{{ $score }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $statusBadge }}">
                                            {{ $res->resilience_status }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right num-tabular font-medium text-slate-700">
                                        {{ number_format($der, 2) }}x
                                    </td>
                                    <td class="py-2.5 px-3 text-right num-tabular font-medium text-slate-700">
                                        {{ number_format($npm, 1) }}%
                                    </td>
                                    <td class="py-2.5 px-3 text-slate-600 text-[11px] max-w-xs leading-tight">
                                        {{ Str::limit($res->reasoning, 120) }}
                                    </td>
                                    <td class="py-2.5 px-3">
                                        <div class="flex flex-col gap-1 text-[10px] font-mono">
                                            @if(!empty($beneficiaries))
                                                <div class="flex items-center gap-1 text-emerald-700 font-semibold">
                                                    <span class="text-emerald-500 font-sans">▲</span>
                                                    <span>{{ implode(', ', array_slice($beneficiaries, 0, 3)) }}</span>
                                                </div>
                                            @endif
                                            @if(!empty($vulnerables))
                                                <div class="flex items-center gap-1 text-rose-700 font-semibold">
                                                    <span class="text-rose-500 font-sans">▼</span>
                                                    <span>{{ implode(', ', array_slice($vulnerables, 0, 3)) }}</span>
                                                </div>
                                            @endif
                                            @if(empty($beneficiaries) && empty($vulnerables))
                                                <span class="text-slate-400 italic font-sans">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-slate-400 italic">Belum ada data analisis untuk skenario ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 5. 3-Pillar Macro Transmission Decomposition Summary -->
            <div class="avoid-break grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="text-[11px] font-bold text-slate-900 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                        1. Pilar Pendapatan (Revenue)
                    </div>
                    <p class="text-[11px] text-slate-600 leading-normal">
                        Menguji elastisitas harga dan daya beli konsumen. Emiten primer dan eksportir komoditas membukukan ketahanan pendapatan paling solid terhadap guncangan makro.
                    </p>
                </div>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="text-[11px] font-bold text-slate-900 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        2. Pilar Struktur Biaya & Marjin (NPM)
                    </div>
                    <p class="text-[11px] text-slate-600 leading-normal">
                        Tingkat NPM rata-rata menjadi bantalan penyerap lonjakan biaya energi atau pelemahan kurs. Emiten dengan marjin tipis (&lt;5%) paling cepat tertekan ke zona rugi.
                    </p>
                </div>

                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                    <div class="text-[11px] font-bold text-slate-900 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                        3. Pilar Neraca & Solvabilitas (DER)
                    </div>
                    <p class="text-[11px] text-slate-600 leading-normal">
                        Rasio leverage utang menentukan sensitivitas terhadap kenaikan suku bunga acuan. Sektor dengan DER tinggi (&gt;1.5x) menanggung lonjakan beban bunga kredit bank.
                    </p>
                </div>
            </div>

            <!-- 6. Regulatory Disclaimer & Research Sign-Off -->
            <div class="border-t border-slate-200 pt-4 mt-2 text-[10px] text-slate-400 space-y-1">
                <div class="flex items-center justify-between text-slate-600 font-semibold uppercase tracking-wider">
                    <span>MacroSectors AI Quantitative Intelligence Unit</span>
                    <span>Equity Research Disclaimer & Compliance</span>
                </div>
                <p class="leading-relaxed">
                    <strong>Pernyataan Kepatuhan & Edukasi Riset:</strong> Dokumen Macro Research Tear-Sheet ini diterbitkan oleh MacroSectors AI semata-mata sebagai instrumen simulasi kuantitatif, analisis intelijen pasar modal, dan tujuan edukasi akademik. Dokumen ini <strong>bukan merupakan rekomendasi investasi langsung, penawaran jual/beli efek, atau nasihat keuangan personal</strong> berlisensi. Kinerja masa lalu dan proyeksi model makro-ke-mikro tidak menjamin hasil investasi aktual di masa mendatang. Setiap keputusan investasi merupakan tanggung jawab independen pemodal.
                </p>
                <div class="pt-1 flex items-center justify-between text-slate-400 font-mono text-[9px]">
                    <span>Powered by Sectors REST API v2 & AI Structured Reasoning Engine</span>
                    <span>Halaman 1 / 1 &bull; End of Briefing</span>
                </div>
            </div>

        </main>
    </div>

    <div class="no-print print:hidden">
        <x-macro-copilot />
    </div>
</body>
</html>
