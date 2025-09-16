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
        </div>
        <div class="mt-3 space-y-2">
            <a href="{{ route('instructors.task-submissions.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('instructors.task-submissions.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-file-alt w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Submissions</span>
            </a>
        </div>
        <div class="mt-3 space-y-2">
            <a href="{{ route('instructors.quizzes.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('instructors.quizzes.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-question-circle w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Quizzes</span>
            </a>
        </div>
    </div>

    <!-- Reports -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider border-l-4 border-[#FF92C2] bg-gradient-to-r from-[#FF92C2]/10 to-transparent rounded-r-lg py-2">
            Reports
        </span>
        <div class="mt-3 space-y-2">
            <a href="#" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-300 hover:scale-[0.98] group relative overflow-hidden
               {{ request()->routeIs('instructors.reports.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FF92C2]/30 text-[#595758] shadow-lg border border-[#FF92C2]/20' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-gradient-to-r hover:from-[#FFEEF2] hover:to-[#FFF0F5]' }}">
                <i class="fas fa-chart-line w-5 h-5 transition-transform duration-300 group-hover:scale-110"></i>
                <span>Student Progress</span>
            </a>
        </div>
    </div>
</nav>