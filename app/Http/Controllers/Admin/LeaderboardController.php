<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'nullable|in:weekly,monthly,semester,overall'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $periodType = $request->get('period', 'overall'); // default overall

        $query = Student::with('xpTransactions');

        if ($periodType !== 'overall') {
            $periodStart = now()->startOf($periodType);
            $periodEnd   = now()->endOf($periodType);

            $query->whereHas('xpTransactions', function ($q) use ($periodStart, $periodEnd) {
                $q->whereBetween('processed_at', [$periodStart, $periodEnd]);
            });
        }

        $students = $query->get()->map(function ($student) use ($periodType) {
            return [
                'student_id'      => $student->id,
                'name'            => $student->user->name,
                'total_xp'        => $student->xpTransactions
                                        ->when($periodType !== 'overall', function ($txs) use ($periodType) {
                                            return $txs->where('processed_at', '>=', now()->startOf($periodType));
                                        })
                                        ->sum('amount'),
                'tasks_completed' => $student->xpTransactions
                                        ->where('type', 'earned')
                                        ->count(),
            ];
        });

        // Sort by XP and rank
        $ranked = $students->sortByDesc('total_xp')->values()->map(function ($data, $index) {
            $data['rank_position'] = $index + 1;
            return $data;
        });

        return view('admin.leaderboards.index', compact('ranked', 'periodType'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'rank_position' => 'required|integer|min:1',
            'total_xp' => 'required|integer|min:0',
            'total_score' => 'required|numeric|min:0',
            'tasks_completed' => 'required|integer|min:0',
            'period_type' => 'required|in:weekly,monthly,semester,overall',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $leaderboard = Leaderboard::create($validator->validated());
            
            DB::commit();
            
            return redirect()->route('admin.leaderboards.show', $leaderboard)
                ->with('success', 'Leaderboard entry created successfully');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to create leaderboard entry: ' . $e->getMessage());
        }
    }

    public function show(Leaderboard $leaderboard)
    {
        return view('admin.leaderboards.show', compact('leaderboard'));
    }
}
