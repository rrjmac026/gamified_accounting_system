<nav class="p-4 space-y-1.5">
    <!-- Logo Section -->
    <!-- <div class="mb-8 px-2">
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" alt="GAS Logo" class="h-10 w-10">
            <div>
                <h1 class="text-xl font-bold text-[#FF92C2] dark:text-[#FFC8FB]">GAS</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">Admin Dashboard</p>
            </div>
        </div>
    </div> -->

    <!-- Main Menu -->
    <div class="mb-6">
        <span class="px-3 text-xs font-bold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            Menu
        </span>
        <div class="mt-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-th-large w-5 h-5"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- User Management -->
    <div class="mb-6">
        <span class="px-3 text-xs font-bold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            User Management
        </span>
        <div class="mt-3 space-y-1">
            <a href="{{ route('admin.student.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.student.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-user-graduate w-5 h-5"></i>
                <span>Students</span>
            </a>
            <a href="{{ route('admin.instructors.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.instructors.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-chalkboard-teacher w-5 h-5"></i>
                <span>Instructors</span>
            </a>
        </div>
    </div>

    <!-- Academic Management -->
    <div class="mb-6">
        <span class="px-3 text-xs font-bold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            Academic Management
        </span>
        <div class="mt-3 space-y-1">
            <a href="{{ route('admin.subjects.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.subjects.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-book-open w-5 h-5"></i>
                <span>Subjects</span>
            </a>
            <a href="{{ route('admin.xp-transactions.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.xp-transactions.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-medal w-5 h-5"></i>
                <span>XP Management</span>
            </a>
            <a href="{{ route('admin.courses.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.courses.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-graduation-cap w-5 h-5"></i>
                <span>Courses</span>
            </a>
            <a href="{{ route('admin.performance-logs.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.performance-logs.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-chart-bar w-5 h-5"></i>
                <span>Performance Logs</span>
            </a>
            <a href="{{ route('admin.badges.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.badges.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-medal w-5 h-5"></i>
                <span>Badges</span>
            </a>
            <a href="{{ route('admin.leaderboards.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.leaderboards.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-trophy w-5 h-5"></i>
                <span>Leaderboards</span>
            </a>
        </div>
    </div>

    <!-- System Management -->
    <div class="mb-6">
        <span class="px-3 text-xs font-bold text-[#595758] dark:text-[#FFC8FB] uppercase tracking-wider">
            System
        </span>
        <div class="mt-3 space-y-1">
            <a href="{{ route('admin.activity-logs.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.activity-logs.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-history w-5 h-5"></i>
                <span>Activity Logs</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-chart-line w-5 h-5"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-users w-5 h-5"></i>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.feedback-records.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.feedback-records.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-comments w-5 h-5"></i>
                <span>Feedback</span>
            </a>
            <a href="{{ route('evaluations.index') }}" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('evaluations.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-clipboard-check w-5 h-5"></i>
                <span>Evaluation</span>
            </a>
            <a href="#" 
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-[#FFC8FB] to-[#FFE3F5] text-[#FF92C2] shadow-sm' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFF0FA] dark:hover:bg-[#3D3D3D]' }}">
                <i class="fas fa-cog w-5 h-5"></i>
                <span>Settings</span>
            </a>
        </div>
    </div>

    <script>
        function confirmDelete(formId) {
            if (confirm('Are you sure you want to delete this item?')) {
                document.getElementById(formId).submit();
            }
            return false;
        }

        function confirmAction(message, formId) {
            if (confirm(message)) {
                document.getElementById(formId).submit();
            }
            return false;
        }
    </script>
</nav>
