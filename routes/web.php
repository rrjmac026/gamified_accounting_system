<?php

use App\Http\Controllers\ProfileController;

//Admin Controllers
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\XpTransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\StudentContronController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SectionController;

use App\Http\Controllers\Admin\InstructorManagementController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\Admin\FeedbackRecordController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StudentManagementController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\PerformanceLogController;
use App\Http\Controllers\Admin\LeaderboardController;
use App\Http\Controllers\Admin\BadgeController;
use App\Http\Controllers\Students\FeedbackController;
//instructor Controllers
use App\Http\Controllers\Instructors\InstructorController;
use App\Http\Controllers\Instructors\TaskQuestionController;
use App\Http\Controllers\Instructors\TaskController;
use App\Http\Controllers\Instructors\StudentTaskController;

//Student Controllers
use App\Http\Controllers\Students\StudentController;


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

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

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/students/import', [StudentManagementController::class, 'import'])->name('student.import');
    // Route::get('/students', [AdminController::class, 'students'])->name('student.index');
    Route::resource('/student', StudentManagementController::class);
    // Route::post('/students', [StudentManagementController::class, 'store'])->name('student.store');

    Route::resource('/courses', CourseController::class);
    Route::resource('badges', BadgeController::class);

    Route::get('/students/create', [StudentManagementController::class, 'create'])->name('student.create');
    Route::resource('/activity-logs',ActivityLogController::class);
    
    // Route::get('/instructors', [AdminController::class, 'instructors'])->name('instructors.index');
    // Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects.index');
    Route::resource('/subjects',SubjectController::class);
    Route::resource('/instructors', InstructorManagementController::class);
    Route::resource('users', UserController::class);
    Route::resource('/xp-transactions', XpTransactionController::class);
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::resource('performance-logs', PerformanceLogController::class);
    Route::get('performance-logs/student/{student}', [PerformanceLogController::class, 'getStudentPerformance'])
        ->name('performance-logs.student');
    Route::get('performance-logs/subject/{subject}', [PerformanceLogController::class, 'getSubjectStatistics'])
        ->name('performance-logs.subject');

    // Add these new routes
    Route::get('/leaderboards', [LeaderboardController::class, 'index'])->name('leaderboards.index');
    Route::get('/leaderboards/{leaderboard}', [LeaderboardController::class, 'show'])->name('leaderboards.show');

    // Reports routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/students', [ReportController::class, 'generateStudentReport'])->name('reports.students');
    Route::get('/reports/students/export', [ReportController::class, 'exportStudentReport'])->name('reports.students.export');
    Route::get('/reports/instructors', [ReportController::class, 'generateInstructorReport'])->name('reports.instructors');
    Route::get('/reports/tasks', [ReportController::class, 'generateTaskReport'])->name('reports.tasks');
    Route::get('/reports/activities', [ReportController::class, 'generateActivityReport'])->name('reports.activities');
    Route::get('/reports/evaluations', [ReportController::class, 'generateEvaluationReport'])->name('reports.evaluations');
    // Route::resource('/xp', [StudentController::class, 'index'])->name('xp-transactions');
    Route::get('/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluations/{evaluation}', [EvaluationController::class, 'show'])->name('evaluations.show');
    Route::delete('/evaluations/{evaluation}', [EvaluationController::class, 'destroy'])->name('evaluations.destroy');
    Route::resource('feedback-records', FeedbackRecordController::class);
    Route::resource('sections', SectionController::class);
});

Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructors.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [InstructorController::class, 'dashboard'])
            ->name('dashboard');

        // Task submissions
        Route::get('/task-submissions', [TaskSubmissionController::class, 'index'])
            ->name('task-submissions.index');
        Route::get('/task-submissions/{taskSubmission}/edit', [TaskSubmissionController::class, 'edit'])
            ->name('task-submissions.edit');
        Route::put('/task-submissions/{taskSubmission}', [TaskSubmissionController::class, 'update'])
            ->name('task-submissions.update');
        Route::post('/task-submissions/{taskSubmission}/grade', [TaskSubmissionController::class, 'grade'])
            ->name('task-submissions.grade');
        
             // CSV Upload/Download routes
        Route::post('tasks/csv-upload', [TaskController::class, 'csvUpload'])->name('tasks.csv-upload');
        Route::get('tasks/download-csv-template', [TaskController::class, 'downloadCsvTemplate'])->name('tasks.download-csv-template');
        
        Route::post('/tasks/{task}/assign-students', [TaskController::class, 'assignToStudent'])
        ->name('tasks.assign-students');
        Route::get('/tasks/{task}/assign-students', [TaskController::class, 'showAssignStudentsForm'])
        ->name('tasks.assign-students-form');
       
        Route::resource('tasks', TaskController::class);
       
        // Question management routes
        Route::post('tasks/{task}/add-question', [TaskController::class, 'addQuestion'])->name('tasks.add-question');
        Route::get('tasks/{task}/questions/{question}/edit', [TaskController::class, 'editQuestion'])->name('tasks.edit-question');
        Route::put('tasks/{task}/questions/{question}', [TaskController::class, 'updateQuestion'])->name('tasks.update-question');
        Route::delete('tasks/{task}/questions/{question}', [TaskController::class, 'deleteQuestion'])->name('tasks.delete-question');
        
        // Student assignment routes
        Route::post('tasks/{task}/assign-student', [TaskController::class, 'assignToStudent'])->name('tasks.assign-student');
        Route::post('tasks/bulk-assign', [TaskController::class, 'bulkAssign'])->name('tasks.bulk-assign');
        Route::get('tasks/student-tasks', [TaskController::class, 'studentTasks'])->name('tasks.student-tasks');
        Route::get('tasks/{task}/students/{student}', [TaskController::class, 'showStudentTask'])->name('tasks.show-student-task');
        Route::get('tasks/{task}/students/{student}/grade', [TaskController::class, 'gradeStudentForm'])->name('tasks.grade-student-form');
        Route::put('tasks/{task}/students/{student}/grade', [TaskController::class, 'gradeStudent'])->name('tasks.grade-student');
    });

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/progress', [StudentController::class, 'viewProgress'])->name('progress');
        Route::get('/assignments', [StudentController::class, 'viewAssignments'])->name('assignments');
        // Route::get('/xp', [StudentController::class, 'viewXp'])->name('xp');
        Route::get('/task-submissions', [TaskSubmissionController::class, 'index'])
            ->name('task-submissions.index');
        Route::get('/task-submissions/{taskSubmission}/edit', [TaskSubmissionController::class, 'edit'])
            ->name('task-submissions.edit');
        Route::put('/task-submissions/{taskSubmission}', [TaskSubmissionController::class, 'update'])
            ->name('task-submissions.update');
        Route::post('/task-submissions/{taskSubmission}/grade', [TaskSubmissionController::class, 'grade'])
            ->name('task-submissions.grade');


        Route::resource('feedback', FeedbackController::class)->only(['create', 'store', 'index', 'show']);
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// Route::post('/admin/backup-now', [DataBackupController::class, 'backupNow'])
//     ->name('admin.backup.now')
//     ->middleware(['auth', 'is_admin']);


