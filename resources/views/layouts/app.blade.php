<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Intelligent Medicare System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">
                                {{ config('app.name', 'IMS') }}
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                    Dashboard
                                </a>
                                
                                @if(auth()->user()->isPatient())
                                    <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                        My Appointments
                                    </a>
                                    <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                        Book Appointment
                                    </a>
                                @endif

                                @if(auth()->user()->isDoctor())
                                    <a href="{{ route('appointments.today') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                        Today's Appointments
                                    </a>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                        Admin Panel
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <div class="ml-3 relative" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                                            {{ Auth::user()->name }}
                                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>
                                </div>

                                <div x-show="open" @click.away="open = false" class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                    <div class="py-1">
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                Log Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-gray-900 mr-4">Log in</a>
                            <a href="{{ route('register') }}" class="text-sm text-gray-700 hover:text-gray-900">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-6">
            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            {{ $slot }}
        </main>

        <!-- AI Chat Widget -->
        @auth
            <div id="ai-chat-widget" class="fixed bottom-4 right-4 z-50">
                <div x-data="aiChat()" class="relative">
                    <!-- Chat Toggle Button -->
                    <button @click="toggleChat()" class="bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg transition-colors">
                        <svg x-show="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <svg x-show="isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>

                    <!-- Chat Window -->
                    <div x-show="isOpen" x-transition class="absolute bottom-16 right-0 w-80 h-96 bg-white rounded-lg shadow-xl border">
                        <div class="flex flex-col h-full">
                            <!-- Chat Header -->
                            <div class="bg-blue-600 text-white p-4 rounded-t-lg">
                                <h3 class="font-semibold">AI Medical Assistant</h3>
                                <p class="text-sm opacity-90">How can I help you today?</p>
                            </div>

                            <!-- Chat Messages -->
                            <div class="flex-1 overflow-y-auto p-4 space-y-3" x-ref="chatMessages">
                                <template x-for="message in messages" :key="message.id">
                                    <div :class="message.role === 'user' ? 'text-right' : 'text-left'">
                                        <div :class="message.role === 'user' ? 'bg-blue-600 text-white ml-8' : 'bg-gray-200 text-gray-800 mr-8'" class="inline-block p-3 rounded-lg text-sm">
                                            <span x-text="message.content"></span>
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="isLoading" class="text-left">
                                    <div class="bg-gray-200 text-gray-800 mr-8 inline-block p-3 rounded-lg text-sm">
                                        <div class="flex space-x-1">
                                            <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce"></div>
                                            <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                            <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chat Input -->
                            <div class="border-t p-4">
                                <form @submit.prevent="sendMessage()">
                                    <div class="flex space-x-2">
                                        <input 
                                            x-model="currentMessage" 
                                            type="text" 
                                            placeholder="Type your message..." 
                                            class="flex-1 border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            :disabled="isLoading"
                                        >
                                        <button 
                                            type="submit" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors"
                                            :disabled="isLoading || !currentMessage.trim()"
                                        >
                                            Send
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function aiChat() {
                    return {
                        isOpen: false,
                        isLoading: false,
                        currentMessage: '',
                        messages: [],
                        sessionId: null,

                        toggleChat() {
                            this.isOpen = !this.isOpen;
                            if (this.isOpen && this.messages.length === 0) {
                                this.initializeChat();
                            }
                        },

                        async initializeChat() {
                            try {
                                const response = await fetch('/api/ai-chat/start', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                });
                                
                                const data = await response.json();
                                this.sessionId = data.session_id;
                                this.addMessage('assistant', data.message);
                            } catch (error) {
                                console.error('Error initializing chat:', error);
                                this.addMessage('assistant', 'Hello! I\'m your AI medical assistant. How can I help you today?');
                            }
                        },

                        async sendMessage() {
                            if (!this.currentMessage.trim() || this.isLoading) return;

                            const message = this.currentMessage.trim();
                            this.addMessage('user', message);
                            this.currentMessage = '';
                            this.isLoading = true;

                            try {
                                const response = await fetch('/api/ai-chat', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        message: message,
                                        session_id: this.sessionId
                                    })
                                });

                                const data = await response.json();
                                
                                if (data.success) {
                                    this.sessionId = data.session_id;
                                    this.addMessage('assistant', data.response);
                                } else {
                                    this.addMessage('assistant', 'I apologize, but I\'m experiencing technical difficulties. Please try again.');
                                }
                            } catch (error) {
                                console.error('Error sending message:', error);
                                this.addMessage('assistant', 'I\'m currently unavailable. Please try again later.');
                            } finally {
                                this.isLoading = false;
                            }
                        },

                        addMessage(role, content) {
                            this.messages.push({
                                id: Date.now(),
                                role: role,
                                content: content,
                                timestamp: new Date()
                            });
                            
                            this.$nextTick(() => {
                                this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
                            });
                        }
                    }
                }
            </script>
        @endauth
    </div>
</body>
</html>