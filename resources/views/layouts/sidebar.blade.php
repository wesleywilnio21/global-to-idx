<!-- Modern Sidebar Navigation (SaaS Layout - Compact w-52) -->
<aside class="w-52 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 h-screen sticky top-0 overflow-y-auto z-40 custom-scrollbar">
    <div class="flex flex-col">
        <!-- Brand Header -->
        <div class="p-3.5 border-b border-slate-100 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-xs font-bold text-sm group-hover:scale-105 transition-transform">
                    M
                </div>
                <div>
                    <h1 class="text-xs font-bold text-slate-900 tracking-tight leading-none group-hover:text-blue-600 transition-colors">MacroSectors</h1>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">IDX Intel AI</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Navigation Menu Items -->
        <div class="p-2.5 flex flex-col gap-1">
            <p class="px-2.5 pt-2.5 pb-1 text-[9px] font-bold text-slate-400 uppercase tracking-wider">Modul Analisis</p>

            <!-- 1. Dashboard Makro -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="truncate">Dashboard Makro</span>
                <span class="ml-auto text-[9px] px-1.5 py-0.5 rounded font-mono shrink-0 {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500' }}">11</span>
            </a>

            <!-- 2. Stress-Test Portofolio -->
            <a href="{{ route('portfolio.index') }}" 
               class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('portfolio.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('portfolio.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span class="truncate">Stress-Test Porto</span>
                <span class="ml-auto text-[9px] px-1.5 py-0.5 rounded font-bold uppercase shrink-0 {{ request()->routeIs('portfolio.*') ? 'bg-blue-500 text-white' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">Baru</span>
            </a>

            <p class="px-2.5 pt-3 pb-1 text-[9px] font-bold text-slate-400 uppercase tracking-wider">Aksi & Ekspor</p>

            <!-- 3. Export PDF Report Trigger -->
            <button type="button" 
                    onclick="window.print()" 
                    class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all text-left cursor-pointer">
                <svg class="w-4 h-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span class="truncate">Ekspor PDF</span>
            </button>
        </div>
    </div>

    <!-- Sidebar Bottom System Status Card -->
    <div class="p-3 border-t border-slate-100 flex flex-col gap-2.5">
        <div class="p-2.5 bg-slate-50 border border-slate-200/80 rounded-xl flex flex-col gap-1.5 text-[10px]">
            <div class="flex items-center justify-between text-slate-700 font-bold">
                <span class="flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>DB SQLite</span>
                </span>
                <span class="text-[9px] text-emerald-700 bg-emerald-50 px-1 py-0.5 rounded font-mono font-bold">LOKAL</span>
            </div>
            <p class="text-slate-500 text-[10px] leading-relaxed">
                49 emiten aman. Hemat <strong>0 kredit</strong> API.
            </p>
        </div>

        <div class="flex items-center justify-between text-[10px] text-slate-400 px-0.5 font-medium">
            <span>Gemini 3.1</span>
            <span class="font-mono text-[9px]">v2.2</span>
        </div>
    </div>
</aside>
