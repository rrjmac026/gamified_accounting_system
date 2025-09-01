<nav class="p-4 space-y-1.5">
    <!-- Main Menu -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            Menu
        </span>
        <div class="mt-2 space-y-1">
            <a href="{{ route('students.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('students.dashboard') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-home w-5 h-5"></i>
                <span>Dashboard</span>
            </a>
            <a href="#" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('students.modules') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-book w-5 h-5"></i>
                <span>Learning Modules</span>
            </a>
        </div>
    </div>

    <!-- Assessment -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            Assessment
        </span>
        <div class="mt-2 space-y-1">
            <a href="#" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('students.quizzes') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-tasks w-5 h-5"></i>
                <span>Quizzes</span>
            </a>
            <a href="#" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('students.exercises') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-pencil-alt w-5 h-5"></i>
                <span>Exercises</span>
            </a>
        </div>
    </div>

    <!-- Progress -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            Progress
        </span>
        <div class="mt-2 space-y-1">
            <a href="#" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('students.progress') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-chart-line w-5 h-5"></i>
                <span>My Progress</span>
            </a>
            <a href="#" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('students.achievements') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-trophy w-5 h-5"></i>
                <span>Achievements</span>
            </a>
        </div>
    </div>

    <!-- Feedback and Evaluation -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            Progress
        </span>
        <div class="mt-2 space-y-1">
            <a href="{{ route('students.feedback.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('students.progress') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-chart-line w-5 h-5"></i>
                <span>Feedback</span>
            </a>
            <a href="#" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('students.achievements') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-trophy w-5 h-5"></i>
                <span>Evaluation</span>
            </a>
        </div>
    </div>
</nav>

