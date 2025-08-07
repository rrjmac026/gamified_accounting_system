<div x-data class="h-full">
    <!-- Backdrop -->
    <div x-show="$store.sidebar.isOpen" x-cloak
        class="fixed inset-0 z-40 backdrop-blur-sm lg:hidden"
        @click="$store.sidebar.toggle()">
    </div>

    <!-- Sidebar -->
    <aside x-show="$store.sidebar.isOpen" x-cloak
        x-transition:enter="transform transition-transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition-transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed top-16 left-0 h-[calc(100vh-4rem)] w-72 border-r shadow-xl z-40 flex flex-col">
        
        <!-- Main content wrapper with scrolling -->
        <div class="flex-1 overflow-y-auto">
            @if(auth()->user()->role === 'student')
                @include('layouts.sidebar-student')
            @elseif(auth()->user()->role === 'instructor')
                @include('layouts.sidebar-instructor')
            @else
                @include('layouts.sidebar-admin')
            @endif
        </div>

        <!-- Footer (not affected by scroll) -->
        <div class="p-4 flex-shrink-0">
            <div class="p-4 rounded-xl shadow-lg bg-[#FFEEF2]">
                <div class="flex items-center justify-center gap-2 text-[#FF92C2]">
                    <i class="fas fa-calculator"></i>
                    <span class="text-sm font-medium">GAS v1.0</span>
                </div>
            </div>
        </div>
    </aside>
</div>