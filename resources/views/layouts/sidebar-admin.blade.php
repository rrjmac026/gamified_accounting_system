<nav class="p-4 space-y-1.5">
    <!-- Main Menu -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FF92C2] uppercase tracking-wider">
            Menu
        </span>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.dashboard') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-home w-5 h-5"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- User Management -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FF92C2] uppercase tracking-wider">
            User Management
        </span>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.student.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.student.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-users w-5 h-5"></i>
                <span>Students</span>
            </a>
            <a href="{{ route('admin.instructors.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.instructors.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-chalkboard-teacher w-5 h-5"></i>
                <span>Instructors</span>
            </a>
        </div>
    </div>

    <!-- Academic Management -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FF92C2] uppercase tracking-wider">
            Academic Management
        </span>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.subjects.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.subjects.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-book w-5 h-5"></i>
                <span>Subjects</span>
            </a>
            <a href="#" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.modules.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-cube w-5 h-5"></i>
                <span>Modules</span>
            </a>
            <a href="{{ route('admin.xp-transactions.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.modules.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-cube w-5 h-5"></i>
                <span>XP</span>
            </a>
            
        </div>
    </div>

    <!-- System Management -->
    <div class="mb-6">
        <span class="px-3 text-xs font-semibold text-[#595758] dark:text-[#FF92C2] uppercase tracking-wider">
            System
        </span>
        <div class="mt-2 space-y-1">
            <a href="#" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.settings') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-cog w-5 h-5"></i>
                <span>Settings</span>
            </a>
            <a href="{{ route('admin.activity-logs.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.reports') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-chart-bar w-5 h-5"></i>
                <span>Activity Logs</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.reports') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-chart-bar w-5 h-5"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('admin.users.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.modules.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-cube w-5 h-5"></i>
                <span>Users</span>
            </a>
            <a href="{{ route('admin.feedback-records.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.modules.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-cube w-5 h-5"></i>
                <span>Feedback</span>
            </a>
            <a href="{{ route('evaluations.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 
               {{ request()->routeIs('admin.modules.*') ? 'bg-[#FFC8FB] text-[#595758]' : 'text-[#595758] dark:text-[#FF92C2] hover:bg-[#FFEEF2]' }}">
                <i class="fas fa-cube w-5 h-5"></i>
                <span>Evaluation</span>
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
