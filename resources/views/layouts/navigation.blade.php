<div x-data="{ darkMode: $store.darkMode.on, open: false }">
    <nav class="fixed top-0 left-0 right-0 z-50 border-b shadow-lg bg-[#FFEEF2]">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left Side -->
                <div class="flex items-center gap-4">
                    <!-- Toggle Button -->
                    <button @click="$store.sidebar.toggle()" 
                        class="p-2.5 rounded-xl transition-all duration-200">
                        <i class="fas fa-bars-staggered text-xl"></i>
                    </button>

                    <!-- Brand -->
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl text-[#FF92C2]">
                            <i class="fas fa-calculator text-xl"></i>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-[#595758]">
                            GAS<span> System</span>
                        </span>
                    </div>
                </div>

                <!-- Right Side - Profile Menu Only -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-all duration-200">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center">
                            <span class="text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs opacity-75"></i>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         :class="$store.darkMode.on ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-100'"
                         class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg py-2 border">
                        
                        <!-- Profile -->
                         <a href="{{ route('profile.edit') }}" 
                           :class="$store.darkMode.on ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100'"
                           class="flex items-center px-4 py-2 text-sm transition-colors duration-200">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        
                        <!-- Dark Mode Toggle -->
                        <button @click="$store.darkMode.toggle()"
                                :class="$store.darkMode.on ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100'"
                                class="flex items-center w-full px-4 py-2 text-sm transition-colors duration-200">
                            <template x-if="$store.darkMode.on">
                                <i class="fas fa-sun mr-2 text-amber-400"></i>
                            </template>
                            <template x-if="!$store.darkMode.on">
                                <i class="fas fa-moon mr-2"></i>
                            </template>
                            <span x-text="$store.darkMode.on ? 'Light Mode' : 'Dark Mode'"></span>
                        </button>

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" 
                                    :class="$store.darkMode.on ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100'"
                                    class="flex items-center w-full px-4 py-2 text-sm transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>