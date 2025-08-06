<?php

// ==========================================
// INSTRUCTOR CONTROLLER
// ==========================================

namespace App\Http\Controllers;

use App\Models\Instructor;
use App\Models\User;
use App\Models\Subject;
use App\Models\Task;
use App\Models\Student;
use App\Models\ActivityLog;
use App\Http\Requests\Instructor\StoreInstructorRequest;
use App\Http\Requests\Instructor\UpdateInstructorRequest;
use App\Http\Requests\Instructor\AssignSubjectRequest;
use App\Http\Requests\Instructor\InstructorFilterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class InstructorController extends Controller
{
    /**
     * Display a listing of instructors with filtering and pagination
     */
    public function index(InstructorFilterRequest $request)
    {
        $query = Instructor::with(['user', 'subjects', 'tasks'])
                          ->whereHas('user', function ($q) {
                              $q->where('is_active', true);
                          });

        // Apply filters
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        if ($request->filled('specialization')) {
            $query->where('specialization', 'LIKE', "%{$request->specialization}%");
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function (Builder $q) use ($search) {
                $q->where('employee_id', 'LIKE', "%{$search}%")
                  ->orWhere('department', 'LIKE', "%{$search}%")
                  ->orWhere('specialization', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('full_name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('has_subjects')) {
            if ($request->has_subjects === 'yes') {
                $query->has('subjects');
            } else {
                $query->doesntHave('subjects');
            }
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'full_name' || $sortBy === 'email') {
            $query->join('users', 'instructors.user_id', '=', 'users.id')
                  ->orderBy("users.{$sortBy}", $sortOrder)
                  ->select('instructors.*');
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $instructors = $query->paginate($request->per_page ?? 15);

        // Add statistics for each instructor
        $instructors->getCollection()->transform(function ($instructor) {
            $instructor->stats = [
                'total_subjects' => $instructor->subjects->count(),
                'total_tasks' => $instructor->tasks->count(),
                'active_tasks' => $instructor->tasks->where('is_active', true)->count(),
                'total_students' => $instructor->students()->count(),
            ];
            return $instructor;
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $instructors,
                'message' => 'Instructors retrieved successfully'
            ]);
        }

        return view('admin.instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new instructor
     */
    public function create()
    {
        $departments = Instructor::select('department')
                                ->distinct()
                                ->whereNotNull('department')
                                ->pluck('department')
                                ->toArray();

        return view('admin.instructors.create', compact('departments'));
    }

    /**
     * Store a newly created instructor in storage
     */
    public function store(StoreInstructorRequest $request)
    {
        try {
            // Create the user first
            $user = User::create([
                'id_number' => $request->id_number,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'instructor',
                'is_active' => $request->boolean('is_active', true),
                'email_verified_at' => now(),
            ]);

            // Create the instructor profile
            $instructor = Instructor::create([
                'user_id' => $user->id,
                'employee_id' => $request->employee_id,
                'department' => $request->department,
                'specialization' => $request->specialization,
            ]);

            // Log the activity
            $this->logActivity('instructor_created', $instructor->id, [
                'instructor_data' => $instructor->toArray(),
                'user_data' => $user->only(['id', 'full_name', 'email']),
                'created_by' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $instructor->load(['user', 'subjects']),
                    'message' => 'Instructor created successfully'
                ], 201);
            }

            return redirect()->route('admin.instructors.index')
                           ->with('success', 'Instructor created successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create instructor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                           ->with('error', 'Failed to create instructor: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified instructor
     */
    public function show($id)
    {
        $instructor = Instructor::with([
            'user',
            'subjects' => function($query) {
                $query->withCount('students')
                     ->with(['students' => function($q) {
                         $q->take(5);
                     }]);
            },
            'tasks' => function($query) {
                $query->latest()->take(10);
            }
        ])->findOrFail($id);

        // Get detailed statistics
        $stats = [
            'total_subjects' => $instructor->subjects->count(),
            'active_subjects' => $instructor->subjects->where('is_active', true)->count(),
            'total_tasks' => $instructor->tasks->count(),
            'active_tasks' => $instructor->tasks->where('is_active', true)->count(),
            'completed_tasks' => $instructor->tasks->whereNotNull('graded_at')->count(),
            'total_students' => $instructor->students()->count(),
            'recent_activity' => $instructor->tasks()->latest()->first()?->created_at,
            'join_date' => $instructor->created_at,
            'last_login' => $instructor->user->last_login_at,
        ];

        // Get subject performance data
        $subjectStats = $instructor->subjects->map(function($subject) {
            return [
                'id' => $subject->id,
                'name' => $subject->subject_name,
                'code' => $subject->subject_code,
                'students_count' => $subject->students_count,
                'tasks_count' => $subject->tasks->count(),
                'is_active' => $subject->is_active,
            ];
        });

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => array_merge($instructor->toArray(), [
                    'stats' => $stats,
                    'subject_stats' => $subjectStats
                ]),
                'message' => 'Instructor details retrieved successfully'
            ]);
        }

        return view('admin.instructors.show', compact('instructor', 'stats', 'subjectStats'));
    }

    /**
     * Show the form for editing the specified instructor
     */
    public function edit($id)
    {
        $instructor = Instructor::with('user')->findOrFail($id);
        
        $departments = Instructor::select('department')
                                ->distinct()
                                ->whereNotNull('department')
                                ->pluck('department')
                                ->toArray();

        return view('admin.instructors.edit', compact('instructor', 'departments'));
    }

    /**
     * Update the specified instructor in storage
     */
    public function update(UpdateInstructorRequest $request, $id)
    {
        $instructor = Instructor::with('user')->findOrFail($id);

        try {
            $originalData = $instructor->toArray();
            $originalUserData = $instructor->user->toArray();

            // Update user data
            $userData = [
                'id_number' => $request->id_number,
                'full_name' => $request->full_name,
                'email' => $request->email,
                'is_active' => $request->boolean('is_active', true),
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $instructor->user->update($userData);

            // Update instructor data
            $instructor->update([
                'employee_id' => $request->employee_id,
                'department' => $request->department,
                'specialization' => $request->specialization,
            ]);

            // Log the activity
            $this->logActivity('instructor_updated', $instructor->id, [
                'original_instructor_data' => $originalData,
                'original_user_data' => $originalUserData,
                'updated_instructor_data' => $instructor->fresh()->toArray(),
                'updated_user_data' => $instructor->user->fresh()->toArray(),
                'updated_by' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $instructor->fresh()->load(['user', 'subjects']),
                    'message' => 'Instructor updated successfully'
                ]);
            }

            return redirect()->route('admin.instructors.show', $instructor)
                           ->with('success', 'Instructor updated successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update instructor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                           ->with('error', 'Failed to update instructor: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified instructor from storage
     */
    public function destroy(Request $request, $id)
    {
        $instructor = Instructor::with('user')->findOrFail($id);

        // Check if instructor has active subjects or tasks
        $hasActiveSubjects = $instructor->subjects()->where('is_active', true)->exists();
        $hasActiveTasks = $instructor->tasks()->where('is_active', true)->exists();

        if ($hasActiveSubjects || $hasActiveTasks) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete instructor with active subjects or tasks. Please deactivate them first.'
                ], 400);
            }
            return redirect()->back()
                           ->with('error', 'Cannot delete instructor with active subjects or tasks.');
        }

        try {
            $instructorData = $instructor->toArray();
            $userData = $instructor->user->toArray();

            // Delete instructor and associated user
            $instructor->user->delete(); // This will cascade delete the instructor

            // Log the activity
            $this->logActivity('instructor_deleted', null, [
                'deleted_instructor_data' => $instructorData,
                'deleted_user_data' => $userData,
                'deleted_by' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Instructor deleted successfully'
                ]);
            }

            return redirect()->route('admin.instructors.index')
                           ->with('success', 'Instructor deleted successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete instructor: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                           ->with('error', 'Failed to delete instructor: ' . $e->getMessage());
        }
    }

    /**
     * Assign subjects to instructor
     */
    public function assignSubjects(AssignSubjectRequest $request, $id)
    {
        $instructor = Instructor::findOrFail($id);

        try {
            $subjectIds = $request->subject_ids;
            
            // Get the subjects that will be assigned
            $subjects = Subject::whereIn('id', $subjectIds)->get();
            
            // Update subjects to assign this instructor
            Subject::whereIn('id', $subjectIds)->update([
                'instructor_id' => $instructor->id
            ]);

            // Log the activity
            $this->logActivity('subjects_assigned', $instructor->id, [
                'instructor_id' => $instructor->id,
                'subject_ids' => $subjectIds,
                'subjects' => $subjects->pluck('subject_name')->toArray(),
                'assigned_by' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $instructor->fresh()->load('subjects'),
                    'message' => 'Subjects assigned successfully'
                ]);
            }

            return redirect()->back()
                           ->with('success', 'Subjects assigned successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to assign subjects: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                           ->with('error', 'Failed to assign subjects: ' . $e->getMessage());
        }
    }

    /**
     * Remove subject from instructor
     */
    public function removeSubject(Request $request, $instructorId, $subjectId)
    {
        $instructor = Instructor::findOrFail($instructorId);
        $subject = Subject::where('id', $subjectId)
                         ->where('instructor_id', $instructorId)
                         ->firstOrFail();

        // Check if subject has active tasks
        if ($subject->tasks()->where('is_active', true)->exists()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot remove subject with active tasks'
                ], 400);
            }
            return redirect()->back()
                           ->with('error', 'Cannot remove subject with active tasks');
        }

        try {
            // Remove instructor from subject
            $subject->update(['instructor_id' => null]);

            // Log the activity
            $this->logActivity('subject_removed', $instructor->id, [
                'instructor_id' => $instructor->id,
                'subject_id' => $subject->id,
                'subject_name' => $subject->subject_name,
                'removed_by' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Subject removed from instructor successfully'
                ]);
            }

            return redirect()->back()
                           ->with('success', 'Subject removed successfully');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to remove subject: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()
                           ->with('error', 'Failed to remove subject: ' . $e->getMessage());
        }
    }

    /**
     * Get instructor dashboard data
     */
    public function dashboard($id = null)
    {
        // If no ID provided, use current user (for instructor's own dashboard)
        if (!$id && Auth::user()->role === 'instructor') {
            $instructor = Auth::user()->instructor;
        } else {
            $instructor = Instructor::findOrFail($id);
        }

        $dashboardData = [
            'overview' => [
                'total_subjects' => $instructor->subjects()->count(),
                'active_subjects' => $instructor->subjects()->where('is_active', true)->count(),
                'total_tasks' => $instructor->tasks()->count(),
                'pending_tasks' => $instructor->tasks()->where('status', 'pending')->count(),
                'total_students' => $instructor->students()->count(),
            ],
            'recent_tasks' => $instructor->tasks()
                                      ->with(['subject', 'submissions'])
                                      ->latest()
                                      ->take(5)
                                      ->get()
                                      ->map(function($task) {
                                          return [
                                              'id' => $task->id,
                                              'title' => $task->title,
                                              'subject' => $task->subject->subject_name,
                                              'type' => $task->type,
                                              'due_date' => $task->due_date,
                                              'submissions_count' => $task->submissions->count(),
                                              'created_at' => $task->created_at,
                                          ];
                                      }),
            'subject_performance' => $instructor->subjects()
                                             ->withCount(['students', 'tasks'])
                                             ->get()
                                             ->map(function($subject) {
                                                 return [
                                                     'id' => $subject->id,
                                                     'name' => $subject->subject_name,
                                                     'code' => $subject->subject_code,
                                                     'students_count' => $subject->students_count,
                                                     'tasks_count' => $subject->tasks_count,
                                                     'is_active' => $subject->is_active,
                                                 ];
                                             }),
            'monthly_activity' => $this->getMonthlyActivity($instructor),
        ];

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $dashboardData,
                'message' => 'Dashboard data retrieved successfully'
            ]);
        }

        return view('instructor.dashboard', compact('dashboardData', 'instructor'));
    }

    /**
     * Get instructor statistics
     */
    public function statistics()
    {
        $stats = [
            'total_instructors' => Instructor::count(),
            'active_instructors' => Instructor::whereHas('user', function($q) {
                $q->where('is_active', true);
            })->count(),
            'instructors_with_subjects' => Instructor::has('subjects')->count(),
            'instructors_without_subjects' => Instructor::doesntHave('subjects')->count(),
            'departments' => Instructor::select('department')
                                     ->distinct()
                                     ->whereNotNull('department')
                                     ->pluck('department')
                                     ->toArray(),
            'department_distribution' => Instructor::select('department')
                                                  ->selectRaw('COUNT(*) as count')
                                                  ->whereNotNull('department')
                                                  ->groupBy('department')
                                                  ->get()
                                                  ->pluck('count', 'department'),
            'recent_joins' => Instructor::where('created_at', '>=', now()->subDays(30))->count(),
            'total_subjects_taught' => Subject::whereNotNull('instructor_id')->count(),
            'total_tasks_created' => Task::whereNotNull('instructor_id')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Instructor statistics retrieved successfully'
        ]);
    }

    /**
     * Search instructors
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2|max:100',
            'department' => 'nullable|string|max:100',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $query = Instructor::with(['user', 'subjects'])
                          ->where(function (Builder $q) use ($request) {
                              $search = $request->query;
                              $q->where('employee_id', 'LIKE', "%{$search}%")
                                ->orWhere('department', 'LIKE', "%{$search}%")
                                ->orWhere('specialization', 'LIKE', "%{$search}%")
                                ->orWhereHas('user', function ($userQuery) use ($search) {
                                    $userQuery->where('full_name', 'LIKE', "%{$search}%")
                                             ->orWhere('email', 'LIKE', "%{$search}%");
                                });
                          });

        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        $instructors = $query->limit($request->get('limit', 10))->get();

        return response()->json([
            'success' => true,
            'data' => $instructors,
            'message' => 'Search completed successfully'
        ]);
    }

    /**
     * Get monthly activity data for instructor
     */
    private function getMonthlyActivity($instructor)
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'tasks_created' => $instructor->tasks()
                                            ->whereYear('created_at', $date->year)
                                            ->whereMonth('created_at', $date->month)
                                            ->count(),
                'students_engaged' => $instructor->students()
                                               ->whereHas('submissions', function($q) use ($date) {
                                                   $q->whereYear('created_at', $date->year)
                                                     ->whereMonth('created_at', $date->month);
                                               })
                                               ->count(),
            ];
        }
        return $months;
    }

    /**
     * Log instructor activity
     */
    private function logActivity($action, $instructorId = null, $details = [])
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => 'Instructor',
            'model_id' => $instructorId,
            'details' => array_merge($details, [
                'timestamp' => now()->toISOString(),
            ]),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'performed_at' => now(),
        ]);
    }
}
