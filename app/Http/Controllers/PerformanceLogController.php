<?php

namespace App\Http\Controllers;

use App\Models\PerformanceLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PerformanceLogController extends Controller
{
    public function index(Request $request)
    {
        $query = PerformanceLog::with(['student', 'subject', 'task']);

        // Apply filters
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('date_from')) {
            $query->where('recorded_at', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->where('recorded_at', '<=', Carbon::parse($request->date_to));
        }

        $logs = $query->latest('recorded_at')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $logs,
            'message' => 'Performance logs retrieved successfully'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'task_id' => 'required|exists:tasks,id',
            'performance_metric' => 'required|string',
            'value' => 'required|numeric|min:0',
            'recorded_at' => 'required|date'
        ]);

        $log = PerformanceLog::create($validated);

        return response()->json([
            'success' => true,
            'data' => $log,
            'message' => 'Performance log created successfully'
        ], 201);
    }

    public function show(PerformanceLog $performanceLog)
    {
        return response()->json([
            'success' => true,
            'data' => $performanceLog->load(['student', 'subject', 'task']),
            'message' => 'Performance log retrieved successfully'
        ]);
    }

    public function update(Request $request, PerformanceLog $performanceLog)
    {
        $validated = $request->validate([
            'performance_metric' => 'sometimes|string',
            'value' => 'sometimes|numeric|min:0',
            'recorded_at' => 'sometimes|date'
        ]);

        $performanceLog->update($validated);

        return response()->json([
            'success' => true,
            'data' => $performanceLog,
            'message' => 'Performance log updated successfully'
        ]);
    }

    public function destroy(PerformanceLog $performanceLog)
    {
        $performanceLog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Performance log deleted successfully'
        ]);
    }

    public function getStudentPerformance($studentId)
    {
        $logs = PerformanceLog::where('student_id', $studentId)
            ->with(['subject', 'task'])
            ->latest('recorded_at')
            ->get();

        $performance = [
            'overall_average' => $logs->avg('value'),
            'by_subject' => $logs->groupBy('subject_id')
                ->map(fn($group) => [
                    'subject_name' => $group->first()->subject->name,
                    'average' => $group->avg('value'),
                    'logs_count' => $group->count()
                ]),
            'recent_logs' => $logs->take(5)
        ];

        return response()->json([
            'success' => true,
            'data' => $performance,
            'message' => 'Student performance retrieved successfully'
        ]);
    }

    public function getSubjectStatistics($subjectId)
    {
        $logs = PerformanceLog::where('subject_id', $subjectId)
            ->with(['student'])
            ->get();

        $statistics = [
            'class_average' => $logs->avg('value'),
            'highest_score' => $logs->max('value'),
            'lowest_score' => $logs->min('value'),
            'total_logs' => $logs->count(),
            'performance_distribution' => $logs->groupBy('student_id')
                ->map(fn($group) => [
                    'student_name' => $group->first()->student->user->full_name,
                    'average' => $group->avg('value')
                ])
        ];

        return response()->json([
            'success' => true,
            'data' => $statistics,
            'message' => 'Subject statistics retrieved successfully'
        ]);
    }
}
