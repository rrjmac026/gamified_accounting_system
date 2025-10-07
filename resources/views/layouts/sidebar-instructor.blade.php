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
            <a href="{{ route('instructors.tasks.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('instructors.tasks.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-tasks w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Task</span>
            </a>
            
            <a href="{{ route('instructors.task-submissions.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('instructors.task-submissions.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-file-alt w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Task Submissions</span>
            </a>

            <!-- Performance Task Dropdown -->
            <div class="mb-6">
                <div class="mt-3 space-y-2">
                    <!-- Dropdown Toggle -->
                    <button onclick="togglePerformanceSteps()" 
                            class="w-full flex items-center justify-between gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]
                            {{ request()->is('instructors/performance-tasks*') || request()->is('instructors/answer-sheets*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 shadow-lg border border-[#FF92C2]/20' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-table w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                            <span>Performance Tasks</span>
                        </div>
                        <i id="dropdown-icon" class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>

                    <!-- Dropdown Content -->
                    <div id="performance-steps" class="ml-4 space-y-1 hidden">
                        <!-- Main Performance Task Index -->
                        <a href="{{ route('instructors.performance-tasks.index') }}" 
                        class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-all duration-300 hover:bg-[#FFEEF2]
                        {{ request()->routeIs('instructors.performance-tasks.index') ? 'bg-[#FFC8FB]/20 text-[#595758] font-medium' : 'text-[#595758]/70 dark:text-[#FF92C2]/70' }}">
                            <span class="w-6 h-6 rounded-full bg-[#FF92C2]/20 flex items-center justify-center text-xs font-bold">
                                <i class="fas fa-list text-xs"></i>
                            </span>
                            <span>All Tasks</span>
                        </a>

                        <!-- Answer Sheets Section -->
                        <div class="pl-4 mt-2 space-y-2">
                            <span class="text-xs uppercase tracking-wide text-[#595758]/70 dark:text-[#FFC8FB]/70 font-semibold">Answer Sheets</span>

                            <!-- Dropdown to Choose Performance Task -->
                            <a href="{{ route('instructors.performance-tasks.answer-sheets.index') }}" 
                            class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-all duration-300 hover:bg-[#FFEEF2]
                            {{ request()->routeIs('instructors.performance-tasks.index') ? 'bg-[#FFC8FB]/20 text-[#595758] font-medium' : 'text-[#595758]/70 dark:text-[#FF92C2]/70' }}">
                                <span class="w-6 h-6 rounded-full bg-[#FF92C2]/20 flex items-center justify-center text-xs font-bold">
                                    <i class="fas fa-list text-xs"></i>
                                </span>
                                <span>All Tasks</span>
                            </a>

                            <!-- Steps 1–10 for selected task -->
                            @php
                                $selectedTaskId = request('task_id');
                            @endphp

                            @if($selectedTaskId)
                                @for($i = 1; $i <= 10; $i++)
                                    <a href="{{ route('instructors.answer-sheets.edit', ['task' => $selectedTaskId, 'step' => $i]) }}" 
                                    class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-all duration-300 hover:bg-[#FFEEF2]
                                    {{ request()->routeIs('instructors.answer-sheets.edit') && request()->route('step') == $i ? 'bg-[#FFC8FB]/20 text-[#595758] font-medium' : 'text-[#595758]/70 dark:text-[#FF92C2]/70' }}">
                                        <span class="w-6 h-6 rounded-full bg-[#FF92C2]/20 flex items-center justify-center text-xs font-bold">{{ $i }}</span>
                                        <span>
                                            @switch($i)
                                                @case(1) Journal Entries @break
                                                @case(2) General Ledger @break
                                                @case(3) Trial Balance @break
                                                @case(4) Adjusting Entries @break
                                                @case(5) Adjusted Trial Balance @break
                                                @case(6) Income Statement @break
                                                @case(7) Statement of Changes @break
                                                @case(8) Balance Sheet @break
                                                @case(9) Closing Entries @break
                                                @case(10) Post-Closing Trial Balance @break
                                            @endswitch
                                        </span>
                                    </a>
                                @endfor
                            @endif
                        </div>
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
        // auto open if we’re on performance/answersheet pages
        const path = window.location.pathname;
        if (path.includes('performance-tasks') || path.includes('answer-sheets')) {
            const dropdown = document.getElementById('performance-steps');
            const icon = document.getElementById('dropdown-icon');
            dropdown.classList.remove('hidden');
            icon.classList.add('rotate-180');
        }

        // Auto submit when selecting a Performance Task
        const selector = document.getElementById('taskSelector');
        if (selector) {
            selector.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
</script>