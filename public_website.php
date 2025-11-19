<?php
session_start();

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $isLoggedIn ? $_SESSION['user_name'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intelligent Medicare - Your Healthcare Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50" x-data="hospitalWebsite()">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-2xl">🏥</span>
                        <span class="ml-2 text-xl font-bold text-blue-600">Intelligent Medicare</span>
                    </div>
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <a href="#home" class="nav-link text-gray-900 hover:text-blue-600 px-3 py-2 text-sm font-medium relative group">
                            Home
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="#services" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium relative group">
                            Services
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="#doctors" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium relative group">
                            Our Doctors
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="#departments" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium relative group">
                            Departments
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                        <a href="#contact" class="nav-link text-gray-700 hover:text-blue-600 px-3 py-2 text-sm font-medium relative group">
                            Contact
                            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if ($isLoggedIn): ?>
                        <span class="text-sm text-gray-700">Welcome, <?= htmlspecialchars($userName) ?></span>
                        <a href="pages/dashboard.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            My Dashboard
                        </a>
                        <a href="pages/login.php?action=logout" class="text-gray-700 hover:text-red-600 text-sm">Logout</a>
                    <?php else: ?>
                        <a href="pages/login.php" class="text-gray-700 hover:text-blue-600 text-sm font-medium">Login</a>
                        <a href="#register" @click.prevent="showRegisterModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="relative text-white overflow-hidden" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 z-0 opacity-10">
            <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="medical-pattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                        <circle cx="25" cy="25" r="2" fill="white"/>
                        <circle cx="75" cy="75" r="2" fill="white"/>
                        <path d="M50 30 L50 70 M30 50 L70 50" stroke="white" stroke-width="3"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#medical-pattern)"/>
            </svg>
        </div>
        
        <!-- Floating Medical Icons -->
        <div class="absolute inset-0 z-0 overflow-hidden">
            <div class="absolute top-20 left-10 text-6xl opacity-10 animate-pulse">🏥</div>
            <div class="absolute top-40 right-20 text-5xl opacity-10 animate-pulse" style="animation-delay: 0.5s">💊</div>
            <div class="absolute bottom-32 left-1/4 text-4xl opacity-10 animate-pulse" style="animation-delay: 1s">⚕️</div>
            <div class="absolute bottom-20 right-1/3 text-5xl opacity-10 animate-pulse" style="animation-delay: 1.5s">🩺</div>
            <div class="absolute top-1/3 right-10 text-4xl opacity-10 animate-pulse" style="animation-delay: 2s">💉</div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-5xl font-bold mb-6">Your Health, Our Priority</h1>
                    <p class="text-xl text-blue-100 mb-8">
                        Experience world-class healthcare with AI-powered medical assistance, 
                        expert doctors, and comprehensive care services.
                    </p>
                    <div class="flex space-x-4">
                        <?php if ($isLoggedIn): ?>
                            <a href="pages/dashboard.php" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg">
                                Book Appointment
                            </a>
                        <?php else: ?>
                            <button @click="showRegisterModal = true" class="bg-white text-blue-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold text-lg">
                                Book Appointment
                            </button>
                        <?php endif; ?>
                        <button @click="openAIChat()" class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg">
                            Talk to AI Assistant
                        </button>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-8">
                        <h3 class="text-2xl font-bold mb-4">Quick Stats</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span>Expert Doctors</span>
                                <span class="text-3xl font-bold">50+</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Happy Patients</span>
                                <span class="text-3xl font-bold">10K+</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Departments</span>
                                <span class="text-3xl font-bold">15+</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span>Years of Service</span>
                                <span class="text-3xl font-bold">25+</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
                <p class="text-xl text-gray-600">Comprehensive healthcare solutions for you and your family</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="text-4xl mb-4">🤖</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">AI Medical Assistant</h3>
                    <p class="text-gray-600 mb-4">
                        24/7 AI-powered chatbot for instant medical guidance, symptom analysis, and emergency support.
                    </p>
                    <button @click="openAIChat()" class="text-blue-600 hover:text-blue-800 font-medium">
                        Try Now →
                    </button>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="text-4xl mb-4">📅</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Online Appointments</h3>
                    <p class="text-gray-600 mb-4">
                        Book appointments online with our expert doctors. Choose your preferred time slot.
                    </p>
                    <?php if ($isLoggedIn): ?>
                        <a href="pages/dashboard.php" class="text-green-600 hover:text-green-800 font-medium">
                            Book Now →
                        </a>
                    <?php else: ?>
                        <button @click="showRegisterModal = true" class="text-green-600 hover:text-green-800 font-medium">
                            Register to Book →
                        </button>
                    <?php endif; ?>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="text-4xl mb-4">🏥</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Emergency Care</h3>
                    <p class="text-gray-600 mb-4">
                        24/7 emergency services with immediate AI-guided first aid and expert medical care.
                    </p>
                    <button @click="openEmergencyChat()" class="text-purple-600 hover:text-purple-800 font-medium">
                        Emergency Help →
                    </button>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="text-4xl mb-4">💊</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pharmacy Services</h3>
                    <p class="text-gray-600 mb-4">
                        In-house pharmacy with wide range of medicines and home delivery options.
                    </p>
                    <a href="#contact" class="text-yellow-600 hover:text-yellow-800 font-medium">
                        Learn More →
                    </a>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="text-4xl mb-4">🔬</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Diagnostic Services</h3>
                    <p class="text-gray-600 mb-4">
                        Advanced diagnostic facilities with latest technology and quick results.
                    </p>
                    <a href="#contact" class="text-red-600 hover:text-red-800 font-medium">
                        View Services →
                    </a>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-8 hover:shadow-xl transition-shadow">
                    <div class="text-4xl mb-4">📱</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Telemedicine</h3>
                    <p class="text-gray-600 mb-4">
                        Consult with doctors from the comfort of your home via video calls.
                    </p>
                    <a href="#contact" class="text-indigo-600 hover:text-indigo-800 font-medium">
                        Coming Soon →
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctors Section -->
    <section id="doctors" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Expert Doctors</h2>
                <p class="text-xl text-gray-600">Experienced healthcare professionals dedicated to your wellbeing</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8" x-show="!doctorsLoading">
                <template x-for="doctor in doctors" :key="doctor.id">
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-32"></div>
                        <div class="p-6 -mt-16">
                            <div class="w-24 h-24 bg-white rounded-full mx-auto mb-4 flex items-center justify-center text-4xl shadow-lg">
                                👨‍⚕️
                            </div>
                            <h3 class="text-xl font-bold text-center text-gray-900 mb-2" x-text="'Dr. ' + doctor.name"></h3>
                            <p class="text-center text-blue-600 font-medium mb-2" x-text="doctor.specialization"></p>
                            <p class="text-center text-gray-600 text-sm mb-4" x-text="doctor.department"></p>
                            <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                                <span x-text="doctor.experience_years + ' years exp.'"></span>
                                <span class="font-semibold text-gray-900">$<span x-text="doctor.fee"></span></span>
                            </div>
                            <?php if ($isLoggedIn): ?>
                                <a href="pages/dashboard.php" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 rounded-lg font-medium">
                                    Book Appointment
                                </a>
                            <?php else: ?>
                                <button @click="showRegisterModal = true" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium">
                                    Register to Book
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="doctorsLoading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
        </div>
    </section>

    <!-- Continue in next part... -->
    
    <script>
        // JavaScript will be added in next file
    </script>
</body>
</html>
    
<!-- Departments Section -->
    <section id="departments" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Departments</h2>
                <p class="text-xl text-gray-600">Specialized care across multiple medical disciplines</p>
            </div>

            <div class="grid md:grid-cols-4 gap-6">
                <template x-for="dept in departments" :key="dept.id">
                    <div class="bg-gray-50 rounded-lg p-6 hover:bg-blue-50 hover:shadow-lg transition-all cursor-pointer">
                        <div class="text-3xl mb-3">🏥</div>
                        <h3 class="font-bold text-gray-900 mb-2" x-text="dept.name"></h3>
                        <p class="text-sm text-gray-600" x-text="dept.description"></p>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Contact Us</h2>
                <p class="text-xl text-gray-600">Get in touch with us for any queries</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Get In Touch</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="text-2xl mr-4">📍</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Address</h4>
                                <p class="text-gray-600">123 Healthcare Avenue, Medical District, City 12345</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="text-2xl mr-4">📞</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Phone</h4>
                                <p class="text-gray-600">+1 (555) 123-4567</p>
                                <p class="text-gray-600">Emergency: +1 (555) 911-0000</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="text-2xl mr-4">✉️</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Email</h4>
                                <p class="text-gray-600">info@intelligentmedicare.com</p>
                                <p class="text-gray-600">support@intelligentmedicare.com</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="text-2xl mr-4">🕐</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Working Hours</h4>
                                <p class="text-gray-600">Mon - Fri: 8:00 AM - 8:00 PM</p>
                                <p class="text-gray-600">Sat - Sun: 9:00 AM - 5:00 PM</p>
                                <p class="text-red-600 font-medium">Emergency: 24/7</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h3>
                    <form @submit.prevent="submitContact()" class="space-y-4">
                        <div>
                            <input type="text" x-model="contactForm.name" required
                                   placeholder="Your Name"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <input type="email" x-model="contactForm.email" required
                                   placeholder="Your Email"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <input type="tel" x-model="contactForm.phone"
                                   placeholder="Your Phone"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <textarea x-model="contactForm.message" rows="4" required
                                      placeholder="Your Message"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">🏥 Intelligent Medicare</h3>
                    <p class="text-gray-400">Your trusted healthcare partner providing world-class medical services with AI-powered assistance.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#home" class="hover:text-white">Home</a></li>
                        <li><a href="#services" class="hover:text-white">Services</a></li>
                        <li><a href="#doctors" class="hover:text-white">Doctors</a></li>
                        <li><a href="#departments" class="hover:text-white">Departments</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Services</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">AI Medical Assistant</a></li>
                        <li><a href="#" class="hover:text-white">Online Appointments</a></li>
                        <li><a href="#" class="hover:text-white">Emergency Care</a></li>
                        <li><a href="#" class="hover:text-white">Pharmacy</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>📞 +1 (555) 123-4567</li>
                        <li>✉️ info@intelligentmedicare.com</li>
                        <li>📍 123 Healthcare Avenue</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>© <?= date('Y') ?> Intelligent Medicare System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Registration Modal -->
    <div x-show="showRegisterModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div @click.away="showRegisterModal = false" 
             class="bg-white rounded-xl max-w-md w-full p-8 max-h-screen overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
                <button @click="showRegisterModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="register()" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" x-model="registerForm.name" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" x-model="registerForm.email" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="tel" x-model="registerForm.phone" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" x-model="registerForm.password" required minlength="6"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                    <input type="date" x-model="registerForm.dob"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                    <select x-model="registerForm.gender"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div x-show="registerMessage" class="p-3 rounded-lg"
                     :class="registerSuccess ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                     x-text="registerMessage">
                </div>

                <button type="submit" :disabled="registerLoading"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg disabled:opacity-50">
                    <span x-show="!registerLoading">Create Account</span>
                    <span x-show="registerLoading">Creating...</span>
                </button>

                <p class="text-center text-sm text-gray-600">
                    Already have an account? 
                    <a href="pages/login.php" class="text-blue-600 hover:text-blue-800 font-medium">Login here</a>
                </p>
            </form>
        </div>
    </div>

    <!-- AI Chat Widget -->
    <div x-show="showAIChat" 
         x-transition
         class="fixed bottom-4 right-4 w-96 h-[600px] bg-white rounded-xl shadow-2xl z-50 flex flex-col">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-4 rounded-t-xl flex justify-between items-center">
            <div>
                <h3 class="font-bold">🤖 AI Medical Assistant</h3>
                <p class="text-xs text-blue-100">24/7 Healthcare Support</p>
            </div>
            <button @click="showAIChat = false" class="text-white hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3" x-ref="chatMessages">
            <template x-for="msg in chatMessages" :key="msg.id">
                <div :class="msg.role === 'user' ? 'text-right' : 'text-left'">
                    <div :class="msg.role === 'user' ? 'bg-blue-600 text-white ml-12' : 'bg-gray-200 text-gray-900 mr-12'"
                         class="inline-block p-3 rounded-lg text-sm whitespace-pre-line">
                        <span x-text="msg.content"></span>
                    </div>
                </div>
            </template>

            <div x-show="chatLoading" class="text-left">
                <div class="bg-gray-200 text-gray-900 mr-12 inline-block p-3 rounded-lg">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t p-4">
            <form @submit.prevent="sendChatMessage()" class="flex space-x-2">
                <input type="text" x-model="chatInput" 
                       placeholder="Type your message..."
                       :disabled="chatLoading"
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" :disabled="chatLoading || !chatInput.trim()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg disabled:opacity-50">
                    Send
                </button>
            </form>
        </div>
    </div>

    <!-- Floating AI Chat Button -->
    <button x-show="!showAIChat" @click="openAIChat()"
            class="fixed bottom-4 right-4 bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-2xl z-40 animate-pulse">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
    </button>

    <script>
        function hospitalWebsite() {
            return {
                showRegisterModal: false,
                showAIChat: false,
                doctors: [],
                departments: [],
                doctorsLoading: true,
                chatMessages: [],
                chatInput: '',
                chatLoading: false,
                registerForm: {
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    dob: '',
                    gender: ''
                },
                registerLoading: false,
                registerMessage: '',
                registerSuccess: false,
                contactForm: {
                    name: '',
                    email: '',
                    phone: '',
                    message: ''
                },

                init() {
                    this.loadDoctors();
                    this.loadDepartments();
                },

                async loadDoctors() {
                    try {
                        const response = await fetch('api/doctors.php?action=get_all');
                        const data = await response.json();
                        if (data.success) {
                            this.doctors = data.doctors.slice(0, 6); // Show first 6
                        }
                    } catch (error) {
                        console.error('Error loading doctors:', error);
                    }
                    this.doctorsLoading = false;
                },

                async loadDepartments() {
                    // Load from database or use static data
                    this.departments = [
                        { id: 1, name: 'Cardiology', description: 'Heart & cardiovascular care' },
                        { id: 2, name: 'Neurology', description: 'Brain & nervous system' },
                        { id: 3, name: 'Orthopedics', description: 'Bone & joint care' },
                        { id: 4, name: 'Pediatrics', description: 'Child healthcare' },
                        { id: 5, name: 'General Medicine', description: 'Primary healthcare' },
                        { id: 6, name: 'Emergency', description: '24/7 emergency services' },
                        { id: 7, name: 'Dermatology', description: 'Skin care' },
                        { id: 8, name: 'ENT', description: 'Ear, Nose & Throat' }
                    ];
                },

                openAIChat() {
                    this.showAIChat = true;
                    if (this.chatMessages.length === 0) {
                        this.chatMessages.push({
                            id: Date.now(),
                            role: 'assistant',
                            content: 'Hello! I\'m your AI medical assistant. How can I help you today?\n\nI can help with:\n• Symptom analysis\n• Doctor recommendations\n• Emergency guidance\n• Appointment booking\n• General health questions'
                        });
                    }
                },

                openEmergencyChat() {
                    this.showAIChat = true;
                    this.chatMessages = [{
                        id: Date.now(),
                        role: 'assistant',
                        content: '🚨 EMERGENCY MODE ACTIVATED\n\nPlease describe your emergency situation. I\'ll provide immediate guidance.\n\nFor life-threatening emergencies, please call 911 immediately!'
                    }];
                },

                async sendChatMessage() {
                    if (!this.chatInput.trim()) return;

                    const userMessage = this.chatInput.trim();
                    this.chatMessages.push({
                        id: Date.now(),
                        role: 'user',
                        content: userMessage
                    });

                    this.chatInput = '';
                    this.chatLoading = true;

                    try {
                        const response = await fetch('api/ai_chat.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ message: userMessage })
                        });

                        const data = await response.json();
                        
                        this.chatMessages.push({
                            id: Date.now(),
                            role: 'assistant',
                            content: data.response || 'I apologize, but I\'m having trouble responding right now. Please try again or contact our support team.'
                        });
                    } catch (error) {
                        this.chatMessages.push({
                            id: Date.now(),
                            role: 'assistant',
                            content: 'I\'m currently experiencing technical difficulties. Please try again later or contact our support team at +1 (555) 123-4567.'
                        });
                    }

                    this.chatLoading = false;
                    this.$nextTick(() => {
                        this.$refs.chatMessages.scrollTop = this.$refs.chatMessages.scrollHeight;
                    });
                },

                async register() {
                    this.registerLoading = true;
                    this.registerMessage = '';

                    try {
                        const formData = new FormData();
                        formData.append('action', 'register');
                        Object.keys(this.registerForm).forEach(key => {
                            formData.append(key, this.registerForm[key]);
                        });

                        const response = await fetch('api/register.php', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.registerSuccess = true;
                            this.registerMessage = 'Registration successful! Redirecting to login...';
                            setTimeout(() => {
                                window.location.href = 'pages/login.php';
                            }, 2000);
                        } else {
                            this.registerSuccess = false;
                            this.registerMessage = data.error || 'Registration failed. Please try again.';
                        }
                    } catch (error) {
                        this.registerSuccess = false;
                        this.registerMessage = 'An error occurred. Please try again.';
                    }

                    this.registerLoading = false;
                },

                submitContact() {
                    alert('Thank you for contacting us! We will get back to you soon.');
                    this.contactForm = { name: '', email: '', phone: '', message: '' };
                }
            }
        }
    </script>
</body>
</html>