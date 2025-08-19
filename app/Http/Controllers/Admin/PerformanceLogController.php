<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
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

        return view('admin.performance_logs.index', compact('logs'));
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

        PerformanceLog::create($validated);

        return redirect()->route('admin.performance_logs.index')
            ->with('success', 'Performance log created successfully');
    }

    public function show(PerformanceLog $performanceLog)
    {
        $performanceLog->load(['student', 'subject', 'task']);
        return view('admin.performance_logs.show', compact('performanceLog'));
    }

    public function update(Request $request, PerformanceLog $performanceLog)
    {
        $validated = $request->validate([
            'performance_metric' => 'sometimes|string',
            'value' => 'sometimes|numeric|min:0',
            'recorded_at' => 'sometimes|date'
        ]);

        $performanceLog->update($validated);

        return redirect()->route('admin.performance_logs.show', $performanceLog->id)
            ->with('success', 'Performance log updated successfully');
    }

    public function destroy(PerformanceLog $performanceLog)
    {
        $performanceLog->delete();

        return redirect()->route('admin.performance_logs.index')
            ->with('success', 'Performance log deleted successfully');
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

        return view('admin.performance_logs.student_performance', compact('performance', 'logs'));
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

        return view('admin.performance_logs.subject_statistics', compact('statistics', 'logs'));
    }
}
