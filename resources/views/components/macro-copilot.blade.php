<style>
    [x-cloak] { display: none !important; }
</style>

<div x-data="macroCopilotDrawer()"
     @keydown.window.ctrl.k.prevent="toggle()"
     @keydown.window.meta.k.prevent="toggle()"
     @keydown.window.escape="close()"
     class="relative font-sans text-slate-100">

    <!-- Floating Trigger Pill Button -->
    <button @click="toggle()"
            type="button"
            class="fixed bottom-6 right-6 z-40 flex items-center gap-2 px-4 py-3 bg-slate-900/90 hover:bg-slate-800 text-slate-100 rounded-full border border-indigo-500/40 hover:border-indigo-400 backdrop-blur shadow-xl shadow-indigo-950/30 transition-all duration-200 cursor-pointer group focus:outline-none focus:ring-2 focus:ring-indigo-500/50"
            aria-label="Tanya Macro Copilot">
        <span class="text-base group-hover:scale-110 transition-transform">✨</span>
        <span class="text-xs font-semibold tracking-wide">Tanya Macro Copilot</span>
        <kbd class="hidden sm:inline-block px-1.5 py-0.5 ml-2 text-[10px] font-mono text-slate-400 bg-slate-800 rounded border border-slate-700">Ctrl+K</kbd>
    </button>

    <!-- Slide-Over Drawer Backdrop -->
    <div x-show="isOpen"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         x-cloak
         class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs"></div>

    <!-- Slide-Over Drawer Panel -->
    <div x-show="isOpen"
         x-transition:enter="transform transition ease-out duration-300"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         @click.stop
         x-cloak
         class="fixed inset-y-0 right-0 z-50 w-full max-w-lg bg-slate-900 border-l border-slate-800 shadow-2xl flex flex-col">

        <!-- Header -->
        <div class="p-4 border-b border-slate-800 flex items-center justify-between bg-slate-900/90 backdrop-blur shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-600/30 border border-indigo-500/50 flex items-center justify-center text-indigo-400 font-bold text-sm">
                    ✨
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-bold tracking-tight text-white">Macro Copilot AI</h2>
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-950/60 text-emerald-400 border border-emerald-800/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span>● Ready (Gemini 3.1)</span>
                        </span>
                    </div>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span x-text="pageContext" class="text-[11px] text-slate-400 font-medium"></span>
                    </div>
                </div>
            </div>

            <!-- Actions: Clear History & Close -->
            <div class="flex items-center gap-1">
                <button @click="clearHistory()"
                        type="button"
                        title="Bersihkan Percakapan"
                        class="p-2 text-slate-400 hover:text-rose-400 hover:bg-slate-800 rounded-lg transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
                <button @click="close()"
                        type="button"
                        title="Tutup (Esc)"
                        class="p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-colors cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Chat Stream Body -->
        <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-4 custom-scrollbar">

            <!-- Welcome Greeting & Quick Prompt Chips -->
            <div class="space-y-3">
                <div class="p-3.5 bg-slate-950/70 border border-slate-800/80 rounded-xl space-y-1.5">
                    <div class="flex items-center gap-1.5 text-indigo-400 text-xs font-semibold">
                        <span>✨</span>
                        <span>Intelligence Transmisi Makro-Sektoral</span>
                    </div>
                    <p class="text-slate-300 text-xs leading-relaxed">
                        Selamat datang di <strong>Macro Copilot</strong>. Asisten analitik transmisi makroekonomi ke sektor & emiten BEI. Tanyakan skenario suku bunga, pelemahan rupiah, volatilitas komoditas, atau risiko solvabilitas konstituen indeks.
                    </p>
                </div>

                <!-- Quick Prompt Chips -->
                <div class="space-y-1.5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pertanyaan Cepat</p>
                    <div class="flex flex-wrap gap-1.5">
                        <button @click="askPrompt('Sektor apa paling kebal jika USD tembus Rp16.800?')"
                                type="button"
                                class="text-left text-[11px] px-2.5 py-1.5 rounded-lg bg-slate-800/90 hover:bg-indigo-950/50 text-slate-200 hover:text-indigo-200 border border-slate-700/70 hover:border-indigo-500/50 transition-all cursor-pointer">
                            💡 Kebal Kurs USD &gt; Rp16.800?
                        </button>
                        <button @click="askPrompt('Kenapa BBCA lebih defensif dari BBRI saat suku bunga naik?')"
                                type="button"
                                class="text-left text-[11px] px-2.5 py-1.5 rounded-lg bg-slate-800/90 hover:bg-indigo-950/50 text-slate-200 hover:text-indigo-200 border border-slate-700/70 hover:border-indigo-500/50 transition-all cursor-pointer">
                            🛡️ BBCA vs BBRI Sensitivitas Suku Bunga
                        </button>
                        <button @click="askPrompt('Emiten mana yang terancam Red-Line hari ini?')"
                                type="button"
                                class="text-left text-[11px] px-2.5 py-1.5 rounded-lg bg-slate-800/90 hover:bg-indigo-950/50 text-slate-200 hover:text-indigo-200 border border-slate-700/70 hover:border-indigo-500/50 transition-all cursor-pointer">
                            🚨 Emiten Terancam Red-Line
                        </button>
                        <button @click="askPrompt('Strategi alokasi dan rotasi sektor terbaik semester ini?')"
                                type="button"
                                class="text-left text-[11px] px-2.5 py-1.5 rounded-lg bg-slate-800/90 hover:bg-indigo-950/50 text-slate-200 hover:text-indigo-200 border border-slate-700/70 hover:border-indigo-500/50 transition-all cursor-pointer">
                            🔄 Strategi Alokasi Sektoral
                        </button>
                    </div>
                </div>
            </div>

            <!-- Chat Message Timeline -->
            <template x-for="(msg, index) in messages" :key="index">
                <div class="flex flex-col gap-1.5" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                    <!-- User Message Bubble -->
                    <template x-if="msg.role === 'user'">
                        <div class="max-w-[85%] bg-blue-600/20 border border-blue-500/30 text-blue-100 rounded-2xl rounded-tr-xs px-3.5 py-2.5 text-xs leading-relaxed whitespace-pre-wrap shadow-xs">
                            <span x-text="msg.content"></span>
                        </div>
                    </template>

                    <!-- Assistant Message Bubble -->
                    <template x-if="msg.role === 'assistant'">
                        <div class="max-w-[92%] bg-slate-950 border border-slate-800 text-slate-200 rounded-2xl rounded-tl-xs p-3.5 text-xs leading-relaxed shadow-md space-y-2.5">
                            <div x-html="formatMarkdown(msg.content)" class="space-y-1"></div>

                            <!-- Engine Badge -->
                            <div class="flex items-center justify-between pt-2 border-t border-slate-800/80 text-[10px] text-slate-400 font-mono">
                                <span class="flex items-center gap-1.5 text-slate-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    <span x-text="msg.engine || 'MacroSectors Engine'"></span>
                                </span>
                            </div>

                            <!-- Suggested Followups -->
                            <template x-if="msg.followups && msg.followups.length > 0">
                                <div class="pt-2 border-t border-slate-800/60 space-y-1.5">
                                    <p class="text-[10px] font-semibold text-slate-400">Saran Analisis Lanjutan:</p>
                                    <div class="flex flex-col gap-1">
                                        <template x-for="(fup, fIdx) in msg.followups" :key="fIdx">
                                            <button @click="askPrompt(fup)"
                                                    type="button"
                                                    class="text-left text-[11px] px-2.5 py-1.5 rounded-md bg-slate-900 hover:bg-slate-855 text-indigo-300 hover:text-indigo-200 border border-slate-800 hover:border-indigo-500/40 transition-colors flex items-center gap-1.5 cursor-pointer">
                                                <span class="text-indigo-400 text-xs">↳</span>
                                                <span x-text="fup"></span>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Loading Typing Indicator -->
            <div x-show="isLoading" class="flex items-center gap-2 p-3 bg-slate-950 border border-slate-800 rounded-2xl rounded-tl-xs text-xs text-slate-400 max-w-[150px]">
                <span class="text-[11px] font-medium">Menganalisis</span>
                <div class="flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 150ms"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 300ms"></span>
                </div>
            </div>

        </div>

        <!-- Footer Input Bar -->
        <div class="p-3.5 bg-slate-900 border-t border-slate-800 flex flex-col gap-2 shrink-0">
            <form @submit.prevent="sendMessage()" class="flex items-end gap-2">
                <div class="relative flex-1">
                    <textarea x-model="inputMessage"
                              @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                              :disabled="isLoading"
                              rows="2"
                              placeholder="Tanyakan transmisi makro, sektor, atau emiten..."
                              class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 rounded-xl px-3 py-2 text-xs text-slate-100 placeholder-slate-500 focus:outline-none resize-none custom-scrollbar disabled:opacity-50"></textarea>
                </div>
                <button type="submit"
                        :disabled="isLoading || !inputMessage.trim()"
                        class="h-10 px-3.5 bg-indigo-600 hover:bg-indigo-500 disabled:bg-slate-800 text-white disabled:text-slate-600 rounded-xl font-semibold text-xs transition-colors flex items-center justify-center shrink-0 cursor-pointer disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>
            <div class="flex items-center justify-between text-[10px] text-slate-500 px-1">
                <span>Didukung Google Gemini &amp; Model Transmisi Deterministik BEI</span>
                <span class="font-mono">Shift+Enter baris baru</span>
            </div>
        </div>

    </div>
</div>

<script>
    function macroCopilotDrawer() {
        return {
            isOpen: false,
            isLoading: false,
            inputMessage: '',
            messages: [],
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            pageContext: '📍 Transmisi Makro',

            init() {
                this.updatePageContext();
            },

            updatePageContext() {
                const path = window.location.pathname;
                if (path.includes('/scanner')) {
                    this.pageContext = '📍 Solvency Scanner';
                } else if (path.includes('/portfolio')) {
                    this.pageContext = '📍 Portofolio Stress-Test';
                } else if (path.includes('/backtest')) {
                    this.pageContext = '📍 Kilas Balik Krisis';
                } else if (path.includes('/duel')) {
                    this.pageContext = '📍 Head-to-Head Duel';
                } else if (path.includes('/report')) {
                    this.pageContext = '📍 Riset & Tear-Sheet';
                } else {
                    this.pageContext = '📍 Transmisi Makro';
                }
            },

            toggle() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.updatePageContext();
                    this.scrollToBottom();
                }
            },

            close() {
                this.isOpen = false;
            },

            clearHistory() {
                this.messages = [];
            },

            askPrompt(promptText) {
                this.inputMessage = promptText;
                this.sendMessage();
            },

            formatMarkdown(text) {
                if (!text) return '';
                let escaped = text
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;');
                escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong class="font-semibold text-white">$1</strong>');
                escaped = escaped.replace(/`([^`]+)`/g, '<code class="px-1 py-0.5 bg-slate-800 text-indigo-300 rounded font-mono text-[11px]">$1</code>');
                escaped = escaped.replace(/(?:^|\n)[*-]\s+(.+)/g, '<div class="flex items-start gap-1.5 my-1"><span class="text-indigo-400 mt-0.5 shrink-0">•</span><span>$1</span></div>');
                escaped = escaped.replace(/\n/g, '<br>');
                return escaped;
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    if (this.$refs.chatContainer) {
                        this.$refs.chatContainer.scrollTop = this.$refs.chatContainer.scrollHeight;
                    }
                });
            },

            async sendMessage() {
                const text = this.inputMessage.trim();
                if (!text || this.isLoading) return;

                this.messages.push({
                    role: 'user',
                    content: text
                });
                this.inputMessage = '';
                this.isLoading = true;
                this.scrollToBottom();

                try {
                    const response = await fetch('{{ route('copilot.ask') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({
                            message: text,
                            page: window.location.pathname,
                            history: this.messages.slice(-6).map(m => ({ role: m.role, content: m.content }))
                        })
                    });

                    const resData = await response.json();
                    if (resData.success && resData.data) {
                        this.messages.push({
                            role: 'assistant',
                            content: resData.data.answer,
                            engine: resData.data.engine || 'MacroSectors Engine',
                            followups: resData.data.suggested_followups || []
                        });
                    } else {
                        this.messages.push({
                            role: 'assistant',
                            content: 'Terjadi kendala saat memproses analisis makro. Silakan coba sesaat lagi.',
                            engine: 'System Notice',
                            followups: []
                        });
                    }
                } catch (err) {
                    this.messages.push({
                        role: 'assistant',
                        content: 'Gagal terhubung ke Macro Copilot Service. Silakan periksa koneksi atau coba kembali.',
                        engine: 'Network Notice',
                        followups: []
                    });
                } finally {
                    this.isLoading = false;
                    this.scrollToBottom();
                }
            }
        };
    }
</script>
