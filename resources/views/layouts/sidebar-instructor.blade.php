<nav class="p-4 space-y-4">
    <!-- Main Menu -->
    <div class="mb-8">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider border-l-4 border-[#FF92C2] bg-gradient-to-r from-[#FF92C2]/10 to-transparent rounded-r-lg py-2">
            Menu
        </span>
        <div class="mt-3 space-y-2">
            <a href="{{ route('instructors.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('instructors.dashboard') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-home w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Classes -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider border-l-4 border-[#FF92C2] bg-gradient-to-r from-[#FF92C2]/10 to-transparent rounded-r-lg py-2">
            Classes
        </span>
        <div class="mt-3 space-y-2">
            <a href="{{ route('instructors.sections.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('instructors.sections.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-chalkboard w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>My Classes</span>
            </a>
        </div>
    </div>

    <!-- Subjects -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider border-l-4 border-[#FF92C2] bg-gradient-to-r from-[#FF92C2]/10 to-transparent rounded-r-lg py-2">
            Subjects
        </span>
        <div class="mt-3 space-y-2">
            <a href="{{ route('instructors.subjects.index') }}" 
            class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
            {{ request()->routeIs('instructors.subjects.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-book w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>My Subjects</span>
            </a>
        </div>
    </div>

    <!-- Assessment -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider border-l-4 border-[#FF92C2] bg-gradient-to-r from-[#FF92C2]/10 to-transparent rounded-r-lg py-2">
            Assessment
        </span>
        <div class="mt-3 space-y-2">
            <!-- Task Dropdown -->
            <div x-data="{ taskOpen: {{ request()->routeIs('instructors.tasks.*') || request()->routeIs('instructors.task-submissions.*') || request()->routeIs('instructors.performance-tasks.*') ? 'true' : 'false' }} }">
                <!-- Task Dropdown Toggle -->
                <button @click="taskOpen = !taskOpen"
                        class="w-full flex items-center justify-between gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]
                        {{ request()->routeIs('instructors.tasks.*') || request()->routeIs('instructors.task-submissions.*') || request()->routeIs('instructors.performance-tasks.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 shadow-lg border border-[#FF92C2]/20' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-tasks w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                        <span>Tasks</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="taskOpen ? 'rotate-180' : ''"></i>
                </button>

                <!-- Task Dropdown Content -->
                <div x-show="taskOpen" class="mt-2 ml-4 space-y-2">
                    <!-- Regular Tasks -->
                    <a href="{{ route('instructors.tasks.index') }}" 
                    class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-200 hover:translate-x-1 group
                    {{ request()->routeIs('instructors.tasks.index') ? 'bg-[#FFC8FB]/20 text-[#595758] font-medium border-l-2 border-[#FF92C2]' : 'text-[#595758]/70 dark:text-[#FF92C2]/70 hover:bg-[#FFEEF2]/50' }}">
                        <i class="fas fa-list w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                        <span>All Tasks</span>
                    </a>
                    <!-- Performance Tasks Section -->
                    <div x-data="{ perfTaskOpen: {{ request()->routeIs('instructors.performance-tasks.*') ? 'true' : 'false' }} }" class="border-l border-[#FF92C2]/20">
                        <button @click="perfTaskOpen = !perfTaskOpen"
                                class="w-full flex items-center justify-between gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-200 hover:translate-x-1 group
                                {{ request()->routeIs('instructors.performance-tasks.*') ? 'bg-[#FFC8FB]/20 text-[#595758] font-medium border-l-2 border-[#FF92C2]' : 'text-[#595758]/70 dark:text-[#FF92C2]/70 hover:bg-[#FFEEF2]/50' }}">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-table w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                                <span>Performance Tasks</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform duration-300" :class="perfTaskOpen ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Performance Tasks Dropdown -->
                        <div x-show="perfTaskOpen" class="mt-2 ml-4 space-y-2">
                            <a href="{{ route('instructors.performance-tasks.index') }}" 
                            class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-200 hover:translate-x-1 group
                            {{ request()->routeIs('instructors.performance-tasks.index') ? 'bg-[#FFC8FB]/20 text-[#595758] font-medium border-l-2 border-[#FF92C2]' : 'text-[#595758]/70 dark:text-[#FF92C2]/70 hover:bg-[#FFEEF2]/50' }}">
                                <i class="fas fa-list w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                                <span>All Performance Tasks</span>
                            </a>
                            
                            <a href="{{ route('instructors.performance-tasks.answer-sheets.index') }}" 
                            class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-200 hover:translate-x-1 group
                            {{ request()->routeIs('instructors.performance-tasks.answer-sheets.*') ? 'bg-[#FFC8FB]/20 text-[#595758] font-medium border-l-2 border-[#FF92C2]' : 'text-[#595758]/70 dark:text-[#FF92C2]/70 hover:bg-[#FFEEF2]/50' }}">
                                <i class="fas fa-folder-open w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                                <span>Answer Sheets</span>
                            </a>
                        </div>

                        <a href="{{ route('instructors.task-submissions.index') }}" 
                        class="flex items-center gap-3 px-4 py-2.5 text-sm rounded-lg transition-all duration-200 hover:translate-x-1 group
                        {{ request()->routeIs('instructors.task-submissions.*') ? 'bg-[#FFC8FB]/20 text-[#595758] font-medium border-l-2 border-[#FF92C2]' : 'text-[#595758]/70 dark:text-[#FF92C2]/70 hover:bg-[#FFEEF2]/50' }}">
                            <i class="fas fa-file-alt w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                            <span>Submissions</span>
                        </a>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider border-l-4 border-[#FF92C2] bg-gradient-to-r from-[#FF92C2]/10 to-transparent rounded-r-lg py-2">
            Reports
        </span>
        <div class="mt-3 space-y-2">
            <a href="{{ route('instructors.progress.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('instructors.progress.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-chart-line w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Student Progress</span>
            </a>
        </div>
    </div>
</nav>

<script>
    function togglePerformanceSteps() {
        const dropdown = document.getElementById('performance-steps');
        const icon = document.getElementById('dropdown-icon');
        
        dropdown.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto open if we're on performance/answersheet pages
        const path = window.location.pathname;
        if (path.includes('performance-tasks') || path.includes('answer-sheets')) {
            const dropdown = document.getElementById('performance-steps');
            const icon = document.getElementById('dropdown-icon');
            dropdown.classList.remove('hidden');
            icon.classList.add('rotate-180');
        }
    });
</script>