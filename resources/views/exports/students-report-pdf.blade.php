<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Performance Report</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; }
        .stats { margin-bottom: 20px; }
        .stats-item { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Student Performance Report</h1>
        <p>Generated on {{ now()->format('F d, Y') }}</p>
    </div>

    <div class="stats">
        <h2>Summary Statistics</h2>
        @foreach($stats as $key => $value)
            <div class="stats-item">
                <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                {{ is_numeric($value) ? number_format($value, 2) : $value }}
            </div>
        @endforeach
    </div>

    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Total XP</th>
                <th>Tasks Completed</th>
                <th>Performance Rating</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
                <tr>
                    <td>{{ $student->user->name }}</td>
                    <td>{{ number_format($student->total_xp) }}</td>
                    <td>{{ $student->assignedTasks->where('status', 'completed')->count() }}</td>
                    <td>{{ number_format($student->performance_rating, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
