<nav class="p-4 space-y-1.5">
    <!-- Main Menu -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            Menu
        </span>
        <div class="mt-2 space-y-1">
            <a href="{{ route('instructors.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('instructor.dashboard') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-home w-5 h-5"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Classes -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            Classes
        </span>
        <div class="mt-2 space-y-1">
            <a href="{{ route('instructors.tasks.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('instructor.tasks.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-chalkboard w-5 h-5"></i>
                <span>My Classes</span>
            </a>
        </div>
    </div>

    <!-- Assessment -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            Assessment
        </span>
        <div class="mt-2 space-y-1">
            <a href="{{ route('instructors.tasks.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('instructor.tasks.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-tasks w-5 h-5"></i>
                <span>Task</span>
            </a>
        </div>
        <div class="mt-2 space-y-1">
            <a href="{{ route('instructors.task-submissions.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('instructors.task-submissions.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-file-alt w-5 h-5"></i>
                <span>Submissions</span>
            </a>
        </div>
    </div>

    <!-- Reports -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            Reports
        </span>
        <div class="mt-2 space-y-1">
            <a href="{{ route('instructors.task-submissions.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('instructor.task-submissions.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-chart-line w-5 h-5"></i>
                <span>Student Progress</span>
            </a>
        </div>
    </div>
</nav>
