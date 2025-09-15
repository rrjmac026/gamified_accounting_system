<nav class="p-4 space-y-4">
    <!-- Main Menu -->
    <div class="mb-8">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider border-l-4 border-[#FF92C2] bg-gradient-to-r from-[#FF92C2]/10 to-transparent rounded-r-lg py-2">
            Menu
        </span>
        <div class="mt-3 space-y-2">
            <a href="{{ route('students.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('students.dashboard') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-home w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Dashboard</span>
                @if(request()->routeIs('students.dashboard'))
                    <div class="ml-auto w-2 h-2 bg-[#FF92C2] rounded-full animate-pulse"></div>
                @endif
            </a>
            <a href="{{ route('students.subjects.index') }}" 
            class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
            {{ request()->routeIs('students.subjects.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-book w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Subjects</span>
            </a>
        </div>
    </div>

    <!-- Assessment -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider border-l-4 border-[#FF92C2] bg-gradient-to-r from-[#FF92C2]/10 to-transparent rounded-r-lg py-2">
            Assessment
        </span>
        <div class="mt-3 space-y-2">
            <a href="{{ route('students.tasks.index') }}" 
            class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
            {{ request()->routeIs('students.tasks.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-tasks w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Tasks</span>
            </a>
            {{-- Sidebar To-Do Dropdown --}}
            <div x-data="{ open: {{ request()->routeIs('students.todo.*') ? 'true' : 'false' }} }" class="space-y-1">
                <!-- Parent link (click to expand) -->
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
                    {{ request()->routeIs('students.todo.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                    <span class="flex items-center gap-3">
                        <i class="fas fa-list-check w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                        <span>To-Do</span>
                    </span>
                    <div class="flex items-center gap-2">
                        <i :class="open ? 'fas fa-chevron-up transition-all duration-300 text-[#FF92C2] transform rotate-180' : 'fas fa-chevron-down transition-all duration-300 group-hover:text-[#FF92C2] transform rotate-0'"></i>
                    </div>
                </button>

                <!-- Enhanced Dropdown items -->
                <div x-show="open" x-cloak 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
                     class="ml-6 space-y-1 bg-gradient-to-br from-[#FFEEF2]/50 to-[#FFF0F5]/50 backdrop-blur-sm rounded-xl p-3 border border-[#FF92C2]/10 shadow-lg">
                    <a href="{{ route('students.todo.index', ['status' => 'missing']) }}"
                        class="flex items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='missing' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-red-400' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <span class="w-2 h-2 bg-red-500 rounded-full mr-3 {{ request('status')=='missing' ? 'animate-pulse' : '' }}"></span>
                        <span>Missing</span>
                        <div class="absolute left-0 top-0 h-full w-1 bg-red-400 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-200 rounded-r"></div>
                    </a>
                    <a href="{{ route('students.todo.index', ['status' => 'assigned']) }}"
                        class="flex items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='assigned' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-blue-400' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3 {{ request('status')=='assigned' ? 'animate-pulse' : '' }}"></span>
                        <span>Assigned</span>
                        <div class="absolute left-0 top-0 h-full w-1 bg-blue-400 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-200 rounded-r"></div>
                    </a>
                    <a href="{{ route('students.todo.index', ['status' => 'in_progress']) }}"
                        class="flex items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='in_progress' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-yellow-400' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-3 {{ request('status')=='in_progress' ? 'animate-pulse' : '' }}"></span>
                        <span>In Progress</span>
                        <div class="absolute left-0 top-0 h-full w-1 bg-yellow-400 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-200 rounded-r"></div>
                    </a>
                    <a href="{{ route('students.todo.index', ['status' => 'late']) }}"
                        class="flex items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='late' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-red-600' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <span class="w-2 h-2 bg-red-600 rounded-full mr-3 {{ request('status')=='late' ? 'animate-pulse' : '' }}"></span>
                        <span>Late</span>
                        <div class="absolute left-0 top-0 h-full w-1 bg-red-600 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-200 rounded-r"></div>
                    </a>
                    <a href="{{ route('students.todo.index', ['status' => 'submitted']) }}"
                        class="flex items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='submitted' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-green-400' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-3 {{ request('status')=='submitted' ? 'animate-pulse' : '' }}"></span>
                        <span>Submitted</span>
                        <div class="absolute left-0 top-0 h-full w-1 bg-green-400 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-200 rounded-r"></div>
                    </a>
                    <a href="{{ route('students.todo.index', ['status' => 'graded']) }}"
                        class="flex items-center px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='graded' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-purple-400' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-3 {{ request('status')=='graded' ? 'animate-pulse' : '' }}"></span>
                        <span>Graded</span>
                        <div class="absolute left-0 top-0 h-full w-1 bg-purple-400 transform -translate-x-full group-hover:translate-x-0 transition-transform duration-200 rounded-r"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider border-l-4 border-[#FF92C2] bg-gradient-to-r from-[#FF92C2]/10 to-transparent rounded-r-lg py-2">
            Progress
        </span>
        <div class="mt-3 space-y-2">
            <a href="{{ route('students.progress') }}" 
            class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
            {{ request()->routeIs('students.progress') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-chart-line w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>My Progress</span>
            </a>
            <a href="{{ route('students.achievements') }}" 
            class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
            {{ request()->routeIs('students.achievements') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-trophy w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Achievements</span>
            </a>
        </div>
    </div>


    <!-- Feedback and Evaluation -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider border-l-4 border-[#FF92C2] bg-gradient-to-r from-[#FF92C2]/10 to-transparent rounded-r-lg py-2">
            Feedback & Evaluation
        </span>
        <div class="mt-3 space-y-2">
            <a href="{{ route('students.feedback.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('students.feedback.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-comments w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Feedback</span>
            </a>
            <a href="#" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('students.evaluation.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-clipboard-check w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Evaluation</span>
            </a>
        </div>
    </div>
</nav>