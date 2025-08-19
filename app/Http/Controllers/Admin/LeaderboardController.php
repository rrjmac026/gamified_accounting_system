<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Student;
use App\Models\Subject;
use App\Http\Requests\LeaderboardRequest;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
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


    public function show(Leaderboard $leaderboard)
    {
        return view('admin.leaderboards.show', compact('leaderboard'));
    }

}
