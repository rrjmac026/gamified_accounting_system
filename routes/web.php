<?php

use App\Http\Controllers\ProfileController;

// ============================================================================
// ADMIN CONTROLLERS
// ============================================================================
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\XpTransactionController;
use App\Http\Controllers\Admin\StudentContronController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\InstructorManagementController;
use App\Http\Controllers\Admin\EvaluationController;
use App\Http\Controllers\Admin\FeedbackRecordController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StudentManagementController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\PerformanceLogController;
use App\Http\Controllers\Admin\LeaderboardController;
use App\Http\Controllers\Admin\BadgeController;

// ============================================================================
// INSTRUCTOR CONTROLLERS
// ============================================================================
use App\Http\Controllers\Instructors\InstructorController;
use App\Http\Controllers\Instructors\TaskQuestionController;
use App\Http\Controllers\Instructors\TaskController;
use App\Http\Controllers\Instructors\StudentTaskController;
use App\Http\Controllers\Instructors\TaskSubmissionController;
use App\Http\Controllers\Instructors\InstructorSectionController;
use App\Http\Controllers\Instructors\InstructorSubjectController;
use App\Http\Controllers\Instructors\QuizController;
use App\Http\Controllers\Instructors\StudentProgressesController;

// ============================================================================
// STUDENT CONTROLLERS
// ============================================================================
use App\Http\Controllers\Students\StudentController;
use App\Http\Controllers\Students\TodoController;
use App\Http\Controllers\Students\StudentSubjectController;
use App\Http\Controllers\Students\StudentProgressController;
use App\Http\Controllers\Students\FeedbackController;

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;


// ============================================================================
// PUBLIC ROUTES
// ============================================================================
Route::get('/', function () {
    return view('welcome');
});

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate']);
    
    // Password Reset Routes
    Route::get('forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');
        
    Route::get('reset-password/{token}', [PasswordResetController::class, 'show'])
        ->name('password.reset');
    
    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
    
    // Update route name to match view
    Route::get('/two-factor-challenge', [LoginController::class, 'showTwoFactorForm'])
        ->name('two-factor.challenge');
    Route::post('/two-factor-challenge', [LoginController::class, 'twoFactorChallenge'])
        ->name('two-factor.login');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================================
// PROFILE ROUTES (All Authenticated Users)
// ============================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Update password route
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');
    
    // Add 2FA routes
    Route::get('/two-factor', [ProfileController::class, 'showTwoFactorForm'])
        ->name('two-factor.login');
    Route::post('/two-factor', [ProfileController::class, 'verifyTwoFactor'])
        ->name('two-factor.verify');
    Route::post('/profile/two-factor-authentication', [ProfileController::class, 'enableTwoFactor'])
        ->name('profile.enableTwoFactor');
    Route::delete('/profile/two-factor-authentication', [ProfileController::class, 'disableTwoFactor'])
        ->name('profile.disableTwoFactor');
    Route::get('/profile/two-factor', [ProfileController::class, 'showTwoFactorForm'])
     ->name('profile.twoFactorForm');

    Route::get('/profile/badges', [\App\Http\Controllers\ProfileController::class, 'badges'])
    ->name('profile.badges');
});

// ============================================================================
// EVALUATION ROUTES (Mixed Roles)
// ============================================================================

// Routes for students (creating evaluations)
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/evaluations/create', [EvaluationController::class, 'create'])->name('evaluations.create');
    Route::post('/evaluations', [EvaluationController::class, 'store'])->name('evaluations.store');
});

// Routes for instructors & admins (viewing evaluations)
Route::middleware(['auth', 'role:admin,instructor'])->group(function () {
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluations/{evaluation}', [EvaluationController::class, 'show'])->name('evaluations.show');
});

// ============================================================================
// ADMIN ROUTES
// ============================================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Student Management
    Route::post('/students/import', [StudentManagementController::class, 'import'])->name('student.import');
    Route::get('/students/create', [StudentManagementController::class, 'create'])->name('student.create');
    Route::resource('/student', StudentManagementController::class);
    
    // Course & Subject Management
    Route::resource('/courses', CourseController::class);
    Route::resource('/subjects', SubjectController::class);
    Route::resource('sections', SectionController::class);

    Route::get('admin/sections/{section}/subjects', [SectionController::class, 'manageSubjects'])
     ->name('sections.subjects');

    Route::post('admin/sections/{section}/subjects', [SectionController::class, 'updateSubjects'])
        ->name('sections.subjects.update');
    
    // Instructor Management
    Route::resource('/instructors', InstructorManagementController::class);
    
    // User Management
    Route::resource('users', UserController::class);
    
    // Gamification & Progress
    Route::resource('badges', BadgeController::class);
    Route::resource('/xp-transactions', XpTransactionController::class);
    
    // Performance Logs Routes
    Route::get('/performance-logs', [PerformanceLogController::class, 'index'])
        ->name('performance-logs.index');
    Route::get('/performance-logs/{performanceLog}', [PerformanceLogController::class, 'show'])
        ->name('performance-logs.show');
    Route::delete('/performance-logs/{performanceLog}', [PerformanceLogController::class, 'destroy'])
        ->name('performance-logs.destroy');
    Route::get('/performance-logs/student/{student}', [PerformanceLogController::class, 'getStudentPerformance'])
        ->name('performance-logs.student');
    Route::get('/performance-logs/subject/{subject}', [PerformanceLogController::class, 'getSubjectStatistics'])
        ->name('performance-logs.subject');

    // Leaderboards
    Route::get('/leaderboards', [LeaderboardController::class, 'index'])->name('leaderboards.index');
    Route::get('/leaderboards/export', [LeaderboardController::class, 'export'])->name('leaderboards.export'); 
    Route::get('/leaderboards/{leaderboard}', [LeaderboardController::class, 'show'])->name('leaderboards.show');
    
    // Activity Logs
    Route::resource('/activity-logs', ActivityLogController::class);
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/students', [ReportController::class, 'generateStudentReport'])->name('reports.students');
    Route::get('/reports/students/export', [ReportController::class, 'exportStudentReport'])->name('reports.students.export');
    Route::get('/reports/instructors', [ReportController::class, 'generateInstructorReport'])->name('reports.instructors');
    Route::get('/reports/tasks', [ReportController::class, 'generateTaskReport'])->name('reports.tasks');
    Route::get('/reports/activities', [ReportController::class, 'generateActivityReport'])->name('reports.activities');
    Route::get('/reports/evaluations', [ReportController::class, 'generateEvaluationReport'])->name('reports.evaluations');
    
    // Evaluations (Admin View)
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluations/{evaluation}', [EvaluationController::class, 'show'])->name('evaluations.show');
    Route::delete('/evaluations/{evaluation}', [EvaluationController::class, 'destroy'])->name('evaluations.destroy');
    
    // Feedback Records
    Route::resource('feedback-records', FeedbackRecordController::class);
    
    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    
    // Subject instructor assignment routes
    Route::post('/subjects/{subject}/assign-instructors', [SubjectController::class, 'assignInstructors'])
        ->name('subjects.assignInstructors');
    Route::get('/subjects/{subject}/assign-instructors', [SubjectController::class, 'showAssignInstructorsForm'])
        ->name('subjects.showAssignInstructorsForm');
});

// ============================================================================
// INSTRUCTOR ROUTES
// ============================================================================
Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructors.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
    
    // Section Management
    Route::get('/sections', [InstructorSectionController::class, 'index'])->name('sections.index');
    Route::get('/sections/{section}', [InstructorSectionController::class, 'show'])->name('sections.show');
    
    // Subject Management
    Route::get('subjects', [InstructorSubjectController::class, 'index'])->name('subjects.index');
    Route::get('subjects/{subject}', [InstructorSubjectController::class, 'show'])->name('subjects.show');
    
    // Task Management
    Route::resource('tasks', TaskController::class);
    Route::post('/tasks/{task}/assign-students', [TaskController::class, 'assignToStudent'])->name('tasks.assign-students');
    Route::get('/tasks/{task}/assign-students', [TaskController::class, 'showAssignStudentsForm'])->name('tasks.assign-students-form');
    Route::post('/instructors/tasks/{task}/sync-students', [TaskController::class, 'syncStudentsToTask'])
    ->name('tasks.sync-students');
    Route::post('/instructors/tasks/sync-all', [TaskController::class, 'syncAllStudentsToTasks'])
    ->name('tasks.sync-all');
    
    // Question Management for Tasks
    Route::post('tasks/{task}/add-question', [TaskController::class, 'addQuestion'])->name('tasks.add-question');
    Route::get('tasks/{task}/questions/{question}/edit', [TaskController::class, 'editQuestion'])->name('tasks.edit-question');
    Route::put('tasks/{task}/questions/{question}', [TaskController::class, 'updateQuestion'])->name('tasks.update-question');
    Route::delete('tasks/{task}/questions/{question}', [TaskController::class, 'deleteQuestion'])->name('tasks.delete-question');
    
    // Student Assignment for Tasks
    Route::post('tasks/{task}/assign-student', [TaskController::class, 'assignToStudent'])->name('tasks.assign-student');
    Route::post('tasks/bulk-assign', [TaskController::class, 'bulkAssign'])->name('tasks.bulk-assign');
    Route::get('tasks/student-tasks', [TaskController::class, 'studentTasks'])->name('tasks.student-tasks');
    Route::get('tasks/{task}/students/{student}', [TaskController::class, 'showStudentTask'])->name('tasks.show-student-task');
    Route::get('tasks/{task}/students/{student}/grade', [TaskController::class, 'gradeStudentForm'])->name('tasks.grade-student-form');
    Route::put('tasks/{task}/students/{student}/grade', [TaskController::class, 'gradeStudent'])->name('tasks.grade-student');
    
    // Quiz Management
    Route::resource('/quizzes', QuizController::class);
    Route::get('/quizzes/create/{taskId}', [QuizController::class, 'create'])->name('quizzes.create');
    Route::post('/quizzes/{taskId}/import', [QuizController::class, 'import'])->name('quizzes.import');
    Route::get('/quizzes/{quiz}/template/download', [QuizController::class, 'downloadTemplate'])
        ->name('quizzes.downloadTemplate');
    Route::post('/quizzes/template/preview', [QuizController::class, 'previewTemplate'])
        ->name('quizzes.previewTemplate');
    
    // Task Submissions
    Route::get('/task-submissions', [TaskSubmissionController::class, 'index'])->name('task-submissions.index');
    Route::get('/task-submissions/{taskSubmission}', [TaskSubmissionController::class, 'show'])->name('task-submissions.show');
    Route::post('/task-submissions/{taskSubmission}/grade', [TaskSubmissionController::class, 'grade'])->name('task-submissions.grade');
});

// Student Progress Routes (Instructor Side)
Route::middleware(['auth', 'role:instructor'])->group(function () {
    Route::get('/instructor/progress', [StudentProgressesController::class, 'index'])
        ->name('instructors.progress.index');
    Route::get('/instructor/progress/{student}', [StudentProgressesController::class, 'show'])
        ->name('instructors.progress.show');
});

// ============================================================================
// STUDENT ROUTES
// ============================================================================
Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
    
    // Dashboard & Main Views
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/assignments', [StudentController::class, 'viewAssignments'])->name('assignments');
    
    // Progress & Achievements
    Route::get('/progress', [StudentProgressController::class, 'progress'])->name('progress');
    Route::get('/achievements', [StudentProgressController::class, 'achievements'])->name('achievements');
    
    // Task Management
    Route::get('tasks', [\App\Http\Controllers\Students\TaskController::class, 'index'])->name('tasks.index');
    Route::get('tasks/{task}', [\App\Http\Controllers\Students\TaskController::class, 'show'])->name('tasks.show');
    Route::post('tasks/{task}/submit', [\App\Http\Controllers\Students\TaskController::class, 'submit'])->name('tasks.submit');
    
    // Todo Management
    Route::prefix('todo')->group(function () {
        Route::get('/{status?}', [TodoController::class, 'index'])
            ->where('status', 'missing|assigned|late|submitted|graded')
            ->name('todo.index');
    });
    
    // Subject Management
    Route::get('/subjects', [\App\Http\Controllers\Students\StudentSubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/{id}', [\App\Http\Controllers\Students\StudentSubjectController::class, 'show'])->name('subjects.show');
    
    // Quiz Submissions
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submitAnswer'])->name('quizzes.submit');
    
    // Feedback Management
    Route::resource('feedback', FeedbackController::class)->only(['create', 'store', 'index', 'show']);
    
    // Evaluation Management (Student View)
    Route::get('/evaluations/create', [EvaluationController::class, 'create'])->name('evaluations.create');
    Route::post('/evaluations', [EvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('/my-evaluations', [EvaluationController::class, 'myEvaluations'])->name('evaluations.index');
    
    
    //Hide the Leaderboard name
    Route::patch('/profile/leaderboard-privacy', [ProfileController::class, 'updateLeaderboardPrivacy'])
         ->name('updateLeaderboardPrivacy');
});

// ============================================================================
// DUPLICATE LOGOUT ROUTE
// ============================================================================
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================================
// COMMENTED OUT ROUTES (For Future Reference)
// ============================================================================
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/students', [AdminController::class, 'students'])->name('student.index');
// Route::post('/students', [StudentManagementController::class, 'store'])->name('student.store');

// Route::get('/instructors', [AdminController::class, 'instructors'])->name('instructors.index');
// Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects.index');

// Route::resource('/xp', [StudentController::class, 'index'])->name('xp-transactions');
// Route::get('/xp', [StudentController::class, 'viewXp'])->name('xp');

// Route::post('/admin/backup-now', [DataBackupController::class, 'backupNow'])
//     ->name('admin.backup.now')
//     ->middleware(['auth', 'is_admin']);

// Add this temporarily for testing
// Route::get('/test-task-route', function() {
//     dd('Route is accessible');
// });