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
            
            <!-- Performance Tasks Dropdown -->
            @php
                $performanceTasks = auth()->user()->student->performanceTasks ?? collect();
            @endphp

            <div x-data="{ openPerformance: {{ request()->routeIs('students.performance-tasks.*') ? 'true' : 'false' }} }" class="space-y-1">
                <!-- Parent link (click to expand) -->
                <button @click="openPerformance = !openPerformance"
                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
                    {{ request()->routeIs('students.performance-tasks.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                    <span class="flex items-center gap-3">
                        <i class="fas fa-table w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                        <span>Performance Tasks</span>
                        @if($performanceTasks->count() > 0)
                            <span class="ml-2 px-2 py-0.5 text-xs bg-[#FF92C2] text-white rounded-full">
                                {{ $performanceTasks->count() }}
                            </span>
                        @endif
                    </span>
                    <div class="flex items-center gap-2">
                        <i :class="openPerformance ? 'fas fa-chevron-up transition-all duration-300 text-[#FF92C2] transform rotate-180' : 'fas fa-chevron-down transition-all duration-300 group-hover:text-[#FF92C2] transform rotate-0'"></i>
                    </div>
                </button>

                <!-- Dropdown items -->
                <div x-show="openPerformance" x-cloak 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
                     class="ml-6 space-y-1 bg-gradient-to-br from-[#FFEEF2]/50 to-[#FFF0F5]/50 backdrop-blur-sm rounded-xl p-3 border border-[#FF92C2]/10 shadow-lg">
                    
                    @forelse($performanceTasks as $pTask)
                        <a href="{{ route('students.performance-tasks.show', $pTask->id) }}"
                            class="flex items-center justify-between px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                            {{ request()->route('task') && request()->route('task')->id == $pTask->id ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-[#FF92C2]' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                            <div class="flex items-center gap-3">
                                <span class="w-2 h-2 bg-[#FF92C2] rounded-full"></span>
                                <span>{{ $pTask->title }}</span>
                            </div>
                            @php
                                $attemptsUsed = $pTask->submissions()->where('student_id', auth()->id())->count();
                                $status = $attemptsUsed >= $pTask->max_attempts ? 'completed' : 'available';
                            @endphp
                            <span class="px-2 py-0.5 text-xs rounded-full {{ $status == 'completed' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-600' }}">
                                {{ $attemptsUsed }}/{{ $pTask->max_attempts }}
                            </span>
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center py-6 text-center">
                            <i class="fas fa-clipboard-list text-[#FF92C2] text-2xl mb-2 opacity-50"></i>
                            <p class="text-sm text-gray-500">No performance tasks available yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

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
                        class="flex items-center justify-between px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='missing' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-red-400' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-3 {{ request('status')=='missing' ? 'animate-pulse' : '' }}"></span>
                            <span>Missing</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full {{ request('status')=='missing' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ auth()->user()->student->tasks()->wherePivot('status', 'missing')->count() }}
                        </span>
                    </a>
                    <a href="{{ route('students.todo.index', ['status' => 'assigned']) }}"
                        class="flex items-center justify-between px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='assigned' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-blue-400' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-3 {{ request('status')=='assigned' ? 'animate-pulse' : '' }}"></span>
                            <span>Assigned</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full {{ request('status')=='assigned' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ auth()->user()->student->tasks()->wherePivot('status', 'assigned')->count() }}
                        </span>
                    </a>
                    <a href="{{ route('students.todo.index', ['status' => 'late']) }}"
                        class="flex items-center justify-between px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='late' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-red-600' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-red-600 rounded-full mr-3 {{ request('status')=='late' ? 'animate-pulse' : '' }}"></span>
                            <span>Late</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full {{ request('status')=='late' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ auth()->user()->student->tasks()->wherePivot('status', 'late')->count() }}
                        </span>
                    </a>
                    <a href="{{ route('students.todo.index', ['status' => 'submitted']) }}"
                        class="flex items-center justify-between px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='submitted' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-green-400' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3 {{ request('status')=='submitted' ? 'animate-pulse' : '' }}"></span>
                            <span>Submitted</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full {{ request('status')=='submitted' ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ auth()->user()->student->tasks()->wherePivot('status', 'submitted')->count() }}
                        </span>
                    </a>
                    <a href="{{ route('students.todo.index', ['status' => 'graded']) }}"
                        class="flex items-center justify-between px-3 py-2.5 text-sm rounded-lg transition-all duration-200 hover:bg-white/60 hover:shadow-sm hover:translate-x-1 relative group
                        {{ request('status')=='graded' ? 'font-semibold text-[#FF92C2] bg-white/40 border-l-2 border-purple-400' : 'text-[#595758] hover:text-[#FF92C2]' }}">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-purple-500 rounded-full mr-3 {{ request('status')=='graded' ? 'animate-pulse' : '' }}"></span>
                            <span>Graded</span>
                        </div>
                        <span class="px-2 py-0.5 text-xs rounded-full {{ request('status')=='graded' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' }}">
                            {{ auth()->user()->student->tasks()->wherePivot('status', 'graded')->count() }}
                        </span>
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
            <a href="{{ route('students.evaluations.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('students.evaluation.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-clipboard-check w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Evaluation</span>
            </a>
        </div>
    </div>
</nav>