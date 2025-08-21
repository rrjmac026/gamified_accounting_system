<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Gamified Accounting System') }}</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            // Check for saved theme preference immediately
            if (localStorage.getItem('theme') === 'dark' || 
                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        </script>
    </head>
    <body class="bg-gradient-to-br from-[#FFE4F3] via-[#FFEEF2] to-[#FFF0F5] dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <!-- Navigation -->
        <nav class="fixed w-full bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border-b border-gray-200/20 dark:border-gray-700/20 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="flex items-center gap-4">
                            <!-- Same GAS Logo as login page -->
                            <div class="w-12 h-12 bg-gradient-to-br from-[#FF92C2] to-[#FFC8FB] rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300 relative">
                                <div class="text-white font-bold text-lg tracking-wide">
                                    <span class="block text-xl leading-none">G</span>
                                    <div class="flex text-xs -mt-0.5">
                                        <span>A</span>
                                        <span class="ml-0.5">S</span>
                                    </div>
                                </div>
                                <div class="absolute -top-1 -right-1 w-4 h-4 bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2] rounded-full flex items-center justify-center shadow-md">
                                    <i class="fas fa-calculator text-white text-xs"></i>
                                </div>
                            </div>
                            <div>
                                <span class="text-2xl font-bold bg-gradient-to-r from-[#FF92C2] to-[#FFC8FB] bg-clip-text text-transparent">GAS</span>
                                <div class="text-sm text-gray-600 dark:text-gray-300 -mt-1">Gamified Accounting</div>
                            </div>
                        </div>
                    </div>                    
                    <!-- Auth Buttons -->
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4">
                            @auth
                                @php
                                    $role = Auth::user()->role ?? null;
                                    $dashboardRoute = match($role) {
                                        'admin' => 'admin.dashboard',
                                        'instructor' => 'instructors.dashboard',
                                        'student' => 'students.dashboard',
                                        default => 'dashboard'
                                    };
                                @endphp
                                <a href="{{ route($dashboardRoute) }}" 
                                   class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#FF92C2] to-[#FFC8FB] text-white hover:from-[#FFC8FB] hover:to-[#FF92C2] transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="px-6 py-2 text-gray-600 dark:text-gray-300 hover:text-[#FF92C2] transition-colors font-medium">
                                    Sign in
                                </a>
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" 
                                       class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#FF92C2] to-[#FFC8FB] text-white hover:from-[#FFC8FB] hover:to-[#FF92C2] transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative pt-32 pb-20 overflow-hidden">
            <!-- Animated Background Elements -->
            <div class="absolute inset-0 z-0">
                <div class="absolute top-20 right-20 w-32 h-32 bg-[#FFC8FB]/30 rounded-full mix-blend-multiply filter blur-xl animate-float"></div>
                <div class="absolute top-40 left-20 w-24 h-24 bg-[#FF92C2]/30 rounded-full mix-blend-multiply filter blur-xl animate-float-delay"></div>
                <div class="absolute bottom-20 right-40 w-20 h-20 bg-[#FFC8FB]/40 rounded-full mix-blend-multiply filter blur-xl animate-float-slow"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <!-- Main Hero Content -->
                    <div class="mb-8">
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border border-[#FFC8FB]/30 mb-6">
                            <span class="w-2 h-2 bg-[#FF92C2] rounded-full animate-pulse mr-2"></span>
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Revolutionary Learning Experience</span>
                        </div>
                        
                        <h1 class="text-5xl md:text-7xl font-bold mb-8 leading-tight">
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#FF92C2] to-[#FFC8FB] block">
                                Master Accounting
                            </span>
                            <span class="text-4xl md:text-5xl text-gray-700 dark:text-gray-200 block mt-2">
                                Through Epic Gameplay
                            </span>
                        </h1>
                        
                        <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-12 max-w-4xl mx-auto leading-relaxed">
                            Transform your learning journey with our gamified accounting system. Earn XP, unlock achievements, and master financial concepts through interactive challenges designed for the modern learner.
                        </p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row justify-center gap-4 md:gap-6 mb-16">
                        <a href="{{ route('login') }}" 
                           class="group px-8 py-4 rounded-xl bg-gradient-to-r from-[#FF92C2] to-[#FFC8FB] text-white text-lg font-semibold hover:from-[#FFC8FB] hover:to-[#FF92C2] transform hover:scale-105 transition-all duration-200 shadow-xl hover:shadow-2xl">
                            <i class="fas fa-rocket mr-2 group-hover:animate-bounce"></i>
                            Start Your Journey
                        </a>
                        <a href="#features" 
                           class="px-8 py-4 rounded-xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-2 border-[#FFC8FB] text-[#FF92C2] text-lg font-semibold hover:bg-[#FFC8FB] hover:text-white transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <i class="fas fa-compass mr-2"></i>
                            Explore Features
                        </a>
                    </div>

                    <!-- Stats Preview -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                        <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg">
                            <div class="text-3xl font-bold text-[#FF92C2] mb-2">10K+</div>
                            <div class="text-gray-600 dark:text-gray-300">Happy Students</div>
                        </div>
                        <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg">
                            <div class="text-3xl font-bold text-[#FF92C2] mb-2">500+</div>
                            <div class="text-gray-600 dark:text-gray-300">Interactive Lessons</div>
                        </div>
                        <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-sm rounded-2xl p-6 border border-white/20 shadow-lg">
                            <div class="text-3xl font-bold text-[#FF92C2] mb-2">98%</div>
                            <div class="text-gray-600 dark:text-gray-300">Success Rate</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-20 bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold bg-gradient-to-r from-[#FF92C2] to-[#FFC8FB] bg-clip-text text-transparent mb-4">
                        Why Choose GAS?
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        Experience the future of accounting education with our innovative features designed to make learning engaging, effective, and enjoyable.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="group bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-white/50 hover:border-[#FFC8FB]/30">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#FF92C2] to-[#FFC8FB] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-gamepad text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Gamified Learning</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Transform complex accounting concepts into exciting quests and challenges. Level up your skills through interactive gameplay that makes learning addictive.
                        </p>
                        <div class="mt-4 flex items-center text-[#FF92C2] font-semibold">
                            <span>Learn More</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="group bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-white/50 hover:border-[#FFC8FB]/30">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#FF92C2] to-[#FFC8FB] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-chart-line text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Smart Analytics</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Track your progress with detailed analytics and personalized insights. Identify strengths and areas for improvement with AI-powered recommendations.
                        </p>
                        <div class="mt-4 flex items-center text-[#FF92C2] font-semibold">
                            <span>Learn More</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="group bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-white/50 hover:border-[#FFC8FB]/30">
                        <div class="w-16 h-16 bg-gradient-to-br from-[#FF92C2] to-[#FFC8FB] rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-trophy text-white text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Achievement System</h3>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            Earn badges, unlock achievements, and compete with peers. Our comprehensive reward system keeps you motivated throughout your learning journey.
                        </p>
                        <div class="mt-4 flex items-center text-[#FF92C2] font-semibold">
                            <span>Learn More</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-20 bg-gradient-to-r from-[#FF92C2]/10 to-[#FFC8FB]/10">
            <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="text-4xl font-bold text-gray-800 dark:text-white mb-6">
                    Ready to Transform Your Learning?
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-300 mb-8">
                    Join thousands of students who have revolutionized their accounting education through gamification.
                </p>
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center px-8 py-4 rounded-xl bg-gradient-to-r from-[#FF92C2] to-[#FFC8FB] text-white text-lg font-semibold hover:from-[#FFC8FB] hover:to-[#FF92C2] transform hover:scale-105 transition-all duration-200 shadow-xl hover:shadow-2xl">
                    <i class="fas fa-play mr-2"></i>
                    Start Playing Now
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm border-t border-gray-200/20 dark:border-gray-700/20 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-6 md:mb-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#FF92C2] to-[#FFC8FB] rounded-xl flex items-center justify-center shadow-lg mr-3">
                            <i class="fas fa-calculator text-white"></i>
                        </div>
                        <div>
                            <span class="text-xl font-bold bg-gradient-to-r from-[#FF92C2] to-[#FFC8FB] bg-clip-text text-transparent">
                                Gamified Accounting System
                            </span>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Making learning fun and effective</div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        © {{ date('Y') }} GAS. All rights reserved. Made with ❤️ for learners.
                    </div>
                </div>
            </div>
        </footer>

        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
            }
            @keyframes float-delay {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-15px); }
            }
            @keyframes float-slow {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            .animate-float { animation: float 6s ease-in-out infinite; }
            .animate-float-delay { animation: float-delay 4s ease-in-out infinite 2s; }
            .animate-float-slow { animation: float-slow 8s ease-in-out infinite 1s; }
        </style>
    </body>
</html>