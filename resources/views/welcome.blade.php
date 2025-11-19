<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Intelligent Medicare System') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-blue-600">
                            {{ config('app.name', 'Intelligent Medicare System') }}
                        </h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6">
                        Intelligent Healthcare
                        <span class="block text-blue-200">Made Simple</span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-blue-100 max-w-3xl mx-auto">
                        Experience the future of healthcare with AI-powered medical assistance, 
                        smart appointment booking, and comprehensive patient care management.
                    </p>
                    <div class="space-x-4">
                        @guest
                            <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                                Get Started
                            </a>
                            <a href="#features" class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                                Learn More
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                                Go to Dashboard
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Comprehensive Healthcare Solutions
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Our intelligent medicare system combines cutting-edge AI technology with 
                        traditional healthcare management to provide superior patient care.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- AI Medical Assistant -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">AI Medical Assistant</h3>
                        <p class="text-gray-600 mb-4">
                            Get instant medical guidance, symptom analysis, and emergency first aid instructions 
                            from our intelligent AI assistant available 24/7.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-2">
                            <li>• Symptom checker and analysis</li>
                            <li>• Emergency response guidance</li>
                            <li>• Medicine recommendations</li>
                            <li>• Doctor suggestions based on symptoms</li>
                        </ul>
                    </div>

                    <!-- Smart Appointment Booking -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 8a4 4 0 11-8 0v-4m4-4h8m-4-4v8m-4 4h8"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Smart Appointment Booking</h3>
                        <p class="text-gray-600 mb-4">
                            Book appointments effortlessly with our AI-powered system that finds the best 
                            available slots and matches you with the right specialists.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-2">
                            <li>• AI-assisted booking process</li>
                            <li>• Real-time availability checking</li>
                            <li>• Automated reminders</li>
                            <li>• Flexible rescheduling options</li>
                        </ul>
                    </div>

                    <!-- Comprehensive Management -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Comprehensive Management</h3>
                        <p class="text-gray-600 mb-4">
                            Complete healthcare management system with patient records, inventory tracking, 
                            and administrative tools for healthcare providers.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-2">
                            <li>• Digital medical records</li>
                            <li>• Inventory management with AI alerts</li>
                            <li>• Staff and department management</li>
                            <li>• Financial reporting and analytics</li>
                        </ul>
                    </div>

                    <!-- Patient Portal -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Patient Portal</h3>
                        <p class="text-gray-600 mb-4">
                            Secure patient portal for accessing medical history, test results, 
                            prescriptions, and communicating with healthcare providers.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-2">
                            <li>• Access medical history</li>
                            <li>• View test results and prescriptions</li>
                            <li>• Secure messaging with doctors</li>
                            <li>• Appointment history and tracking</li>
                        </ul>
                    </div>

                    <!-- Doctor Dashboard -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Doctor Dashboard</h3>
                        <p class="text-gray-600 mb-4">
                            Comprehensive tools for healthcare providers to manage patients, 
                            access medical records, and streamline clinical workflows.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-2">
                            <li>• Patient queue management</li>
                            <li>• Digital prescription writing</li>
                            <li>• Medical history access</li>
                            <li>• Pharmacy inventory integration</li>
                        </ul>
                    </div>

                    <!-- Emergency Support -->
                    <div class="bg-gray-50 rounded-xl p-8 hover:shadow-lg transition-shadow">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Emergency Support</h3>
                        <p class="text-gray-600 mb-4">
                            Immediate emergency response system with AI-powered first aid guidance 
                            and direct connection to emergency services.
                        </p>
                        <ul class="text-sm text-gray-500 space-y-2">
                            <li>• Emergency symptom assessment</li>
                            <li>• First aid instructions</li>
                            <li>• Emergency contact integration</li>
                            <li>• Critical care recommendations</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-blue-600 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">
                    Ready to Transform Your Healthcare Experience?
                </h2>
                <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                    Join thousands of patients and healthcare providers who trust our intelligent medicare system.
                </p>
                @guest
                    <a href="{{ route('register') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                        Start Your Journey Today
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg transition-colors">
                        Access Your Dashboard
                    </a>
                @endguest
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold mb-4">{{ config('app.name', 'Intelligent Medicare System') }}</h3>
                    <p class="text-gray-400 mb-8">
                        Revolutionizing healthcare with artificial intelligence and compassionate care.
                    </p>
                    <div class="border-t border-gray-800 pt-8">
                        <p class="text-gray-400 text-sm">
                            © {{ date('Y') }} Intelligent Medicare System. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>