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
    <body class="bg-gradient-to-br from-[#FFF8FB] to-[#FFE9F5] dark:from-[#2D2D2D] dark:to-[#1D1D1D] min-h-screen">
        <!-- Navigation -->
        <nav class="fixed w-full bg-white/95 dark:bg-[#2D2D2D]/95 backdrop-blur-lg border-b border-[#FFC8FB]/20 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0 flex items-center gap-3">
                            <div class="p-2 bg-[#FFF0FA] dark:bg-[#3D3D3D] rounded-xl">
                                <i class="fas fa-calculator text-[#FF92C2] text-2xl"></i>
                            </div>
                            <div>
                                <span class="text-2xl font-bold text-[#FF92C2]">GAS</span>
                                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Gamified Accounting</span>
                            </div>
                        </div>
                    </div>                    
                    <!-- Auth Buttons -->
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4">
                            <!-- Dark Mode Toggle -->
                            <!-- <button @click="$store.darkMode.toggle()" 
                                    class="p-2 rounded-lg hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D] transition-colors duration-200">
                                <template x-if="$store.darkMode.on">
                                    <i class="fas fa-sun text-[#FF92C2] text-xl"></i>
                                </template>
                                <template x-if="!$store.darkMode.on">
                                    <i class="fas fa-moon text-[#FF92C2] text-xl"></i>
                                </template>
                            </button> -->

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
                                   class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2] text-white hover:from-[#ff6fb5] hover:to-[#ff4b97] transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="px-6 py-3 text-[#FF92C2] hover:text-[#ff6fb5] transition-colors">
                                    Sign in
                                </a>
                                @if (Route::has('login'))
                                    <a href="{{ route('login') }}" 
                                       class="px-6 py-3 rounded-xl bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2] text-white hover:from-[#ff6fb5] hover:to-[#ff4b97] transition-all duration-200 shadow-md hover:shadow-lg">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Hero Section with Animation -->
        <div class="relative pt-32 pb-20 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-[#FF92C2] opacity-5 dark:opacity-10"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-[#FF92C2] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-[#FFC8FB] rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-5xl md:text-7xl font-bold mb-8">
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2]">
                            Learn Accounting
                        </span>
                        <br>
                        <span class="text-4xl md:text-6xl text-gray-700 dark:text-gray-200">
                            Through Gamification
                        </span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 mb-12 max-w-3xl mx-auto leading-relaxed">
                        Experience a revolutionary way to master accounting concepts through interactive challenges, rewards, and real-time progress tracking.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4 md:gap-6">
                        <a href="{{ route('login') }}" 
                           class="px-8 py-4 rounded-xl bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2] text-white text-lg font-semibold hover:from-[#ff6fb5] hover:to-[#ff4b97] transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                            Start Learning Now
                        </a>
                        <a href="#features" 
                           class="px-8 py-4 rounded-xl border-2 border-[#FF92C2] text-[#FF92C2] text-lg font-semibold hover:bg-[#FF92C2] hover:text-white transform hover:scale-105 transition-all duration-200">
                            Explore Features
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-20 bg-white/50 dark:bg-[#2D2D2D]/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center text-[#FF92C2] mb-12">Key Features</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white dark:bg-[#3D3D3D] p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-200">
                        <div class="w-12 h-12 bg-[#FFF0FA] dark:bg-[#4D4D4D] rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-gamepad text-[#FF92C2] text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-[#FF92C2] mb-2">Gamified Learning</h3>
                        <p class="text-gray-600 dark:text-gray-300">Master accounting concepts through interactive challenges and games.</p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="bg-white dark:bg-[#3D3D3D] p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-200">
                        <div class="w-12 h-12 bg-[#FFF0FA] dark:bg-[#4D4D4D] rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-chart-line text-[#FF92C2] text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-[#FF92C2] mb-2">Progress Tracking</h3>
                        <p class="text-gray-600 dark:text-gray-300">Monitor your learning journey with detailed analytics and insights.</p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="bg-white dark:bg-[#3D3D3D] p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-200">
                        <div class="w-12 h-12 bg-[#FFF0FA] dark:bg-[#4D4D4D] rounded-lg flex items-center justify-center mb-4">
                            <i class="fas fa-trophy text-[#FF92C2] text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-[#FF92C2] mb-2">Rewards System</h3>
                        <p class="text-gray-600 dark:text-gray-300">Earn badges and XP as you complete challenges and master concepts.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white/80 dark:bg-[#2D2D2D]/80 border-t border-[#FFC8FB]/20 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center mb-4 md:mb-0">
                        <i class="fas fa-calculator text-[#FF92C2] text-xl mr-2"></i>
                        <span class="text-lg font-semibold text-[#FF92C2]">Gamified Accounting System</span>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        © {{ date('Y') }} GAS. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </body>
</html>
