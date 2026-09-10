<!-- Modern Sidebar Navigation (SaaS Layout) -->
<aside class="w-60 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 h-screen sticky top-0 overflow-y-auto z-40 custom-scrollbar">
    <div class="flex flex-col">
        <!-- Brand Header -->
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-xs font-bold text-base group-hover:scale-105 transition-transform">
                    M
                </div>
                <div>
                    <h1 class="text-sm font-bold text-slate-900 tracking-tight leading-none group-hover:text-blue-600 transition-colors">MacroSectors</h1>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">IDX Intel AI</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Navigation Menu Items -->
        <div class="p-3 flex flex-col gap-1.5">
            <p class="px-3 pt-3 pb-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Modul Analisis</p>

            <!-- 1. Dashboard Makro -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span>Dashboard Makro</span>
                <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded font-mono {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : 'bg-slate-100 text-slate-500' }}">11 Sektor</span>
            </a>

            <!-- 2. Stress-Test Portofolio -->
            <a href="{{ route('portfolio.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('portfolio.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('portfolio.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <span>Stress-Test Portofolio</span>
                <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded font-bold uppercase {{ request()->routeIs('portfolio.*') ? 'bg-blue-500 text-white' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">Fitur</span>
            </a>

            <!-- 3. Head-to-Head Duel -->
            <a href="{{ route('duel.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('duel.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('duel.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span>Head-to-Head Duel</span>
                <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded font-bold uppercase {{ request()->routeIs('duel.*') ? 'bg-blue-500 text-white' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">VS</span>
            </a>

            <!-- 4. Macro Tear-Sheet & PDF Report -->
            <a href="{{ route('report.tear-sheet') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('report.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('report.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>Riset & Tear-Sheet</span>
                <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded font-bold uppercase {{ request()->routeIs('report.*') ? 'bg-blue-500 text-white' : 'bg-purple-50 text-purple-700 border border-purple-200' }}">PDF</span>
            </a>

            <!-- 5. Vulnerability & Red-Line Scanner -->
            <a href="{{ route('scanner.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all {{ request()->routeIs('scanner.*') ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 {{ request()->routeIs('scanner.*') ? 'text-white' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span>Red-Line Scanner</span>
                <span class="ml-auto text-[10px] px-1.5 py-0.5 rounded font-bold uppercase {{ request()->routeIs('scanner.*') ? 'bg-blue-500 text-white' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">EWS</span>
        </div>
    </div>

    <!-- Sidebar Bottom System Status Card -->
    <div class="p-4 border-t border-slate-100 flex flex-col gap-3">
        <div class="p-3 bg-slate-50 border border-slate-200/80 rounded-xl flex flex-col gap-2 text-[11px]">
            <div class="flex items-center justify-between text-slate-700 font-bold">
                <span class="flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Database Status</span>
                </span>
                <span class="text-[10px] text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded font-mono font-bold">LOKAL</span>
            </div>
            <p class="text-slate-500 text-[10px] leading-relaxed">
                49 emiten tersimpan aman di SQLite lokal. Penggunaan fitur simulasi <strong>0 credit</strong>.
            </p>
        </div>

        <div class="flex items-center justify-between text-[11px] text-slate-400 px-1 font-medium">
            <span>AI: Gemini 3.1 Flash</span>
            <span class="font-mono text-[10px]">v2.2-Hackathon</span>
        </div>
    </div>
</aside>
