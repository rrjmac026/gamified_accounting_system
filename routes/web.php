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
use App\Http\Controllers\QuizController;

// ============================================================================
// STUDENT CONTROLLERS
// ============================================================================
use App\Http\Controllers\Students\StudentController;
use App\Http\Controllers\Students\TodoController;
use App\Http\Controllers\Students\StudentSubjectController;
use App\Http\Controllers\Students\StudentProgressController;
use App\Http\Controllers\Students\FeedbackController;

use Illuminate\Support\Facades\Route;

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
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// ============================================================================
// PROFILE ROUTES (All Authenticated Users)
// ============================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    
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
    Route::resource('performance-logs', PerformanceLogController::class);
    Route::get('performance-logs/student/{student}', [PerformanceLogController::class, 'getStudentPerformance'])
        ->name('performance-logs.student');
    Route::get('performance-logs/subject/{subject}', [PerformanceLogController::class, 'getSubjectStatistics'])
        ->name('performance-logs.subject');
    
    // Leaderboards
    Route::get('/leaderboards', [LeaderboardController::class, 'index'])->name('leaderboards.index');
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
    Route::post('/quizzes/{taskId}/import', [QuizController::class, 'import'])->name('quizzes.import');
    
    // Task Submissions
    Route::get('/task-submissions', [TaskSubmissionController::class, 'index'])->name('task-submissions.index');
    Route::get('/task-submissions/{taskSubmission}', [TaskSubmissionController::class, 'show'])->name('task-submissions.show');
    Route::post('/task-submissions/{taskSubmission}/grade', [TaskSubmissionController::class, 'grade'])->name('task-submissions.grade');
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
            ->where('status', 'missing|assigned|in_progress|late|submitted|graded')
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