{{-- Premium AI Chat Widget - APEX CAR Showroom --}}
<div x-data="apexChatWidget()" x-init="init()" class="relative">
    <!-- Floating Circular Button -->
    <button 
        id="apex-chat-button"
        @click="toggleChat()" 
        class="fixed bottom-6 right-6 z-50 w-16 h-16 rounded-full bg-gradient-to-tr from-amber-700 to-amber-600 flex items-center justify-center shadow-[0_0_20px_rgba(217,119,6,0.4)] hover:shadow-[0_0_25px_rgba(217,119,6,0.6)] hover:scale-105 transition-all duration-300 focus:outline-none group active:scale-95"
        aria-label="Contacter l'assistant virtuel"
    >
        <!-- Avatar Image -->
        <div class="w-14 h-14 rounded-full overflow-hidden border border-white/10 relative bg-zinc-900">
            <img 
                src="{{ asset('images/apex_ai_avatar.png') }}" 
                alt="AI Assistant Avatar" 
                class="w-full h-full object-cover no-invert"
            >
        </div>

        <!-- Glowing Active Indicator -->
        <span class="absolute top-0 right-0 block h-4 w-4 rounded-full ring-2 ring-zinc-950 bg-green-500 animate-pulse"></span>
    </button>

    <!-- Chat Box Window -->
    <div 
        id="apex-chat-box"
        x-show="open" 
        x-cloak
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        class="fixed bottom-24 right-6 w-96 max-w-[calc(100vw-2rem)] h-[520px] z-50 flex flex-col glass border border-white/10 overflow-hidden shadow-2xl"
    >
        <!-- Header -->
        <div class="h-16 px-4 bg-zinc-950/70 border-b border-white/5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full overflow-hidden border border-amber-500/20 bg-zinc-900">
                    <img 
                        src="{{ asset('images/apex_ai_avatar.png') }}" 
                        alt="AI Avatar" 
                        class="w-full h-full object-cover no-invert"
                    >
                </div>
                <div>
                    <h3 class="text-xs font-black text-white leading-tight">APEX CAR Assistant</h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider" x-text="selectedLanguage === 'ar' ? 'متصل' : (selectedLanguage === 'en' ? 'Online' : 'En ligne')">En ligne</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <!-- Clear Chat / Reset Language Action -->
                <button 
                    @click="clearChat()" 
                    class="p-2 text-gray-500 hover:text-amber-500 rounded-lg hover:bg-white/5 transition-all"
                    title="Reset conversation"
                    x-show="!needsLanguageSelection"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
                
                <!-- Close Window Button -->
                <button 
                    @click="open = false" 
                    class="p-2 text-gray-500 hover:text-white rounded-lg hover:bg-white/5 transition-all"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Language Selection Screen -->
        <div 
            x-show="needsLanguageSelection" 
            class="flex-1 flex flex-col items-center justify-center p-6 text-center space-y-6"
            x-cloak
        >
            <div class="w-16 h-16 rounded-full overflow-hidden border border-amber-500/20 shadow-lg shadow-amber-500/10 mb-2 bg-zinc-900">
                <img src="{{ asset('images/apex_ai_avatar.png') }}" class="w-full h-full object-cover" alt="AI Logo">
            </div>
            <div>
                <h3 class="text-sm font-black text-white uppercase tracking-wider">APEX CAR Assistant</h3>
                <p class="text-xs text-gray-400 mt-1">Please select your language / Choisissez votre langue</p>
                <p class="text-[10px] text-gray-500">اختر لغة للتحدث مع المساعد الافتراضي</p>
            </div>
            
            <div class="w-full space-y-2.5 pt-2">
                <button @click="selectLanguage('fr')" class="w-full flex items-center justify-between px-5 py-3.5 bg-white/5 border border-white/5 hover:border-amber-600/30 rounded-xl text-left hover:bg-amber-600/10 transition-all group">
                    <span class="text-xs font-bold text-gray-300 group-hover:text-white">🇫🇷 Français</span>
                    <span class="text-xs text-gray-500 group-hover:text-amber-500">&rarr;</span>
                </button>
                <button @click="selectLanguage('en')" class="w-full flex items-center justify-between px-5 py-3.5 bg-white/5 border border-white/5 hover:border-amber-600/30 rounded-xl text-left hover:bg-amber-600/10 transition-all group">
                    <span class="text-xs font-bold text-gray-300 group-hover:text-white">🇬🇧 English</span>
                    <span class="text-xs text-gray-500 group-hover:text-amber-500">&rarr;</span>
                </button>
                <button @click="selectLanguage('ar')" class="w-full flex items-center justify-between px-5 py-3.5 bg-white/5 border border-white/5 hover:border-amber-600/30 rounded-xl hover:bg-amber-600/10 transition-all group" dir="rtl">
                    <span class="text-xs font-bold text-gray-300 group-hover:text-white">🇲🇦 العربية</span>
                    <span class="text-xs text-gray-500 group-hover:text-amber-500">&larr;</span>
                </button>
            </div>
        </div>

        <!-- Chat Conversation (Messages and Input) -->
        <div x-show="!needsLanguageSelection" class="flex-1 flex flex-col min-h-0" x-cloak>
            <!-- Messages Area (RTL supported dynamically) -->
            <div 
                x-ref="messagesContainer" 
                class="flex-1 overflow-y-auto p-4 space-y-4 chat-scroll"
                :dir="selectedLanguage === 'ar' ? 'rtl' : 'ltr'"
            >
                <template x-for="(msg, index) in messages" :key="index">
                    <div 
                        :class="msg.sender === 'user' ? 'justify-end' : 'justify-start'" 
                        class="flex w-full"
                    >
                        <!-- Message Bubble -->
                        <div 
                            :class="msg.sender === 'user' 
                                ? 'bg-gradient-to-r from-amber-600 to-amber-800 text-white rounded-2xl rounded-tr-none shadow-lg shadow-amber-600/10' 
                                : 'bg-zinc-800/80 border border-white/5 text-gray-100 rounded-2xl rounded-tl-none'"
                            class="p-3 max-w-[85%] text-xs leading-relaxed select-text"
                        >
                            <div x-html="formatMessage(msg.text)" class="chat-message-content"></div>
                            <div 
                                :class="msg.sender === 'user' ? 'text-white/60 text-right' : 'text-gray-500'" 
                                class="text-[9px] mt-1 font-medium select-none"
                                x-text="msg.timestamp"
                            ></div>
                        </div>
                    </div>
                </template>

                <!-- Typing Indicator -->
                <div x-show="isLoading" class="flex justify-start w-full" x-cloak>
                    <div class="bg-zinc-800/80 border border-white/5 p-3.5 rounded-2xl rounded-tl-none flex items-center gap-1 max-w-[85%] w-fit">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-bounce" style="animation-delay: 0.3s"></span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Suggestion Chips -->
            <div class="px-4 pb-2 flex flex-wrap gap-1.5 bg-transparent" :dir="selectedLanguage === 'ar' ? 'rtl' : 'ltr'">
                <!-- English chips -->
                <template x-if="selectedLanguage === 'en'">
                    <div class="flex flex-wrap gap-1.5">
                        <button @click="sendSuggestion('What cars do you have?')" class="chat-chip">🚘 Stock</button>
                        <button @click="sendSuggestion('Used cars')" class="chat-chip">🛠️ Used</button>
                        <button @click="sendSuggestion('How to book?')" class="chat-chip">💰 Reservation</button>
                        <button @click="sendSuggestion('What is your phone number?')" class="chat-chip">📞 Contact</button>
                    </div>
                </template>
                <!-- French chips -->
                <template x-if="selectedLanguage === 'fr'">
                    <div class="flex flex-wrap gap-1.5">
                        <button @click="sendSuggestion('Quelles voitures avez-vous ?')" class="chat-chip">🚘 Stock</button>
                        <button @click="sendSuggestion('Voitures d\'occasion')" class="chat-chip">🛠️ Occasion</button>
                        <button @click="sendSuggestion('Comment réserver ?')" class="chat-chip">💰 Réservation</button>
                        <button @click="sendSuggestion('Quel est votre numéro ?')" class="chat-chip">📞 Contact</button>
                    </div>
                </template>
                <!-- Arabic chips -->
                <template x-if="selectedLanguage === 'ar'">
                    <div class="flex flex-wrap gap-1.5">
                        <button @click="sendSuggestion('ما هي السيارات المتوفرة؟')" class="chat-chip">🚘 المعرض</button>
                        <button @click="sendSuggestion('سيارات مستعملة')" class="chat-chip">🛠️ مستعملة</button>
                        <button @click="sendSuggestion('كيف يمكنني الحجز؟')" class="chat-chip">💰 الحجز</button>
                        <button @click="sendSuggestion('ما هو رقم هاتفكم؟')" class="chat-chip">📞 اتصل بنا</button>
                    </div>
                </template>
            </div>

            <!-- Input Box -->
            <form @submit.prevent="sendMessage()" class="p-3 bg-zinc-950/70 border-t border-white/5 flex gap-2 items-center" :dir="selectedLanguage === 'ar' ? 'rtl' : 'ltr'">
                <input 
                    type="text" 
                    x-model="newMessage"
                    :placeholder="getInputPlaceholder()" 
                    class="flex-1 bg-zinc-900 border border-white/5 focus:border-amber-600/50 text-white text-xs rounded-xl px-4 py-2.5 focus:outline-none transition-all"
                    :disabled="isLoading"
                >
                <button 
                    type="submit" 
                    class="w-9 h-9 rounded-xl bg-amber-600 hover:bg-amber-500 flex items-center justify-center text-white transition-all active:scale-95 shrink-0 disabled:opacity-50"
                    :disabled="!newMessage.trim() || isLoading"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" :class="selectedLanguage === 'ar' ? 'rotate-180' : ''">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Styles for Custom Scrollbar, Positioning, and Chips -->
    <style>
        #apex-chat-button {
            position: fixed !important;
            bottom: 24px !important;
            right: 24px !important;
            width: 64px !important;
            height: 64px !important;
            border-radius: 9999px !important;
            z-index: 99999 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            background: linear-gradient(135deg, #b45309, #d97706) !important;
            box-shadow: 0 0 20px rgba(217, 119, 6, 0.4) !important;
            transition: all 0.3s ease !important;
            cursor: pointer !important;
            border: none !important;
            padding: 0 !important;
        }
        #apex-chat-button:hover {
            transform: scale(1.05) !important;
            box-shadow: 0 0 25px rgba(217, 119, 6, 0.6) !important;
        }
        #apex-chat-box {
            position: fixed !important;
            bottom: 96px !important;
            right: 24px !important;
            width: 384px !important;
            max-width: calc(100vw - 32px) !important;
            height: 520px !important;
            z-index: 99999 !important;
            display: flex;
            flex-direction: column !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
            border-radius: 16px !important;
            overflow: hidden !important;
            background: rgba(24, 24, 27, 0.95) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            backdrop-filter: blur(12px) !important;
        }

        .chat-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .chat-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.01);
        }
        .chat-scroll::-webkit-scrollbar-thumb {
            background: rgba(217, 119, 6, 0.3);
            border-radius: 2px;
        }
        .chat-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(217, 119, 6, 0.5);
        }
        
        .chat-chip {
            font-size: 10px;
            font-weight: 700;
            padding: 5px 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            color: #d1d5db;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .chat-chip:hover {
            background: rgba(217, 119, 6, 0.15);
            border-color: rgba(217, 119, 6, 0.3);
            color: white;
            transform: translateY(-1px);
        }
        
        .chat-message-content a {
            color: #f59e0b;
            font-weight: 700;
            text-decoration: none;
            transition: border-bottom 0.15s ease;
            border-bottom: 1px dashed transparent;
        }
        .chat-message-content a:hover {
            border-bottom-color: #f59e0b;
        }
        
        .chat-message-content p {
            margin-bottom: 0.5rem;
        }
        .chat-message-content p:last-child {
            margin-bottom: 0;
        }
        
        [x-cloak] { display: none !important; }
    </style>

    <!-- Alpine.js Widget logic -->
    <script>
        function apexChatWidget() {
            return {
                open: false,
                messages: [],
                newMessage: '',
                isLoading: false,
                needsLanguageSelection: true,
                selectedLanguage: '',

                init() {
                    // Load chat history (determines if language has already been selected)
                    this.fetchHistory();
                },

                toggleChat() {
                    this.open = !this.open;
                    if (this.open) {
                        this.$nextTick(() => this.scrollToBottom());
                    }
                },

                fetchHistory() {
                    fetch('/chat/history')
                        .then(res => res.json())
                        .then(data => {
                            this.needsLanguageSelection = data.needs_language_selection;
                            this.selectedLanguage = data.lang || '';
                            this.messages = data.history || [];
                            this.$nextTick(() => this.scrollToBottom());
                        })
                        .catch(err => console.error('Error fetching chat history:', err));
                },

                selectLanguage(lang) {
                    this.isLoading = true;
                    fetch('/chat/start', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ lang: lang })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.selectedLanguage = data.lang;
                        this.messages = data.history;
                        this.needsLanguageSelection = false;
                        this.isLoading = false;
                        this.$nextTick(() => this.scrollToBottom());
                    })
                    .catch(err => {
                        console.error('Error starting chat:', err);
                        this.isLoading = false;
                    });
                },

                sendMessage() {
                    const text = this.newMessage.trim();
                    if (!text) return;

                    this.newMessage = '';
                    this.isLoading = true;

                    const now = new Date();
                    const timestamp = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
                    this.messages.push({
                        sender: 'user',
                        text: text,
                        timestamp: timestamp
                    });
                    this.$nextTick(() => this.scrollToBottom());

                    fetch('/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ message: text })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.messages = data.history;
                        this.isLoading = false;
                        this.$nextTick(() => this.scrollToBottom());
                    })
                    .catch(err => {
                        console.error('Error sending message:', err);
                        this.isLoading = false;
                        this.messages.push({
                            sender: 'ai',
                            text: this.getErrorMessage(),
                            timestamp: timestamp
                        });
                        this.$nextTick(() => this.scrollToBottom());
                    });
                },

                getErrorMessage() {
                    if (this.selectedLanguage === 'en') return 'Sorry, I encountered an error. Please try again.';
                    if (this.selectedLanguage === 'ar') return 'عذراً، حدث خطأ ما. يرجى المحاولة مرة أخرى.';
                    return 'Désolé, j\'ai rencontré un problème. Veuillez réessayer.';
                },

                getInputPlaceholder() {
                    if (this.selectedLanguage === 'en') return 'Type a message...';
                    if (this.selectedLanguage === 'ar') return 'اكتب رسالة...';
                    return 'Écrivez un message...';
                },

                sendSuggestion(text) {
                    this.newMessage = text;
                    this.sendMessage();
                },

                clearChat() {
                    const confirmMsg = this.getClearConfirmMessage();
                    if (confirm(confirmMsg)) {
                        fetch('/chat/clear', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.needsLanguageSelection = true;
                            this.selectedLanguage = '';
                            this.messages = [];
                        })
                        .catch(err => console.error('Error resetting chat:', err));
                    }
                },

                getClearConfirmMessage() {
                    if (this.selectedLanguage === 'en') return 'Do you want to reset the chat conversation?';
                    if (this.selectedLanguage === 'ar') return 'هل تريد إعادة ضبط محادثة المساعد الافتراضي؟';
                    return 'Voulez-vous réinitialiser la discussion ?';
                },

                formatMessage(text) {
                    // Simple markdown bold conversion **text** -> <strong>text</strong>
                    let formatted = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    // Simple markdown italic conversion *text* -> <em>text</em>
                    formatted = formatted.replace(/\*(.*?)\*/g, '<em>$1</em>');
                    return formatted;
                },

                scrollToBottom() {
                    const container = this.$refs.messagesContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }
            };
        }
    </script>
</div>
