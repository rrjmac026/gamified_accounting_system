<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Student Overview -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">{{ $student->user->name }}</h2>
                            <p class="text-gray-600">{{ $student->student_id }}</p>
                            <p class="text-gray-600">{{ $student->course->name }} - {{ $student->sections->first()?->name }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-blue-600">Level {{ $metrics['level'] }}</div>
                            <p class="text-gray-600">Rank #{{ $metrics['class_rank'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                @foreach([
                    'completion_rate' => ['Task Completion', '%'],
                    'average_score' => ['Average Score', '%'],
                    'total_xp' => ['Total XP', ''],
                    'badges_earned' => ['Badges Earned', '']
                ] as $key => [$label, $suffix])
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-sm font-medium text-gray-500">{{ $label }}</h3>
                        <p class="text-2xl font-bold text-gray-800">
                            @if($key === 'completion_rate')
                                {{ $metrics['completed_tasks'] }} / {{ $metrics['total_tasks'] }}
                            @else
                                {{ $metrics[$key] }}{{ $suffix }}
                            @endif
                        </p>
                    </div>
                @endforeach
            </div>


            <!-- Tasks & Submissions -->
            <div class="bg-white shadow-xl sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tasks & Submissions</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Task</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($student->tasks as $task)
                                    <tr>
                                        <td class="px-6 py-4">{{ $task->title }}</td>
                                        <td class="px-6 py-4">{{ $task->subject->subject_name }}</td>
                                        <td class="px-6 py-4">
                                            @if($task->pivot->score !== null)
                                                {{ $task->pivot->score }}/{{ $task->max_score }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full
                                                {{ $task->pivot->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($task->pivot->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                    'bg-red-100 text-red-800') }}">
                                                {{ Str::title($task->pivot->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($task->pivot->submitted_at)
                                                <span class="{{ $task->pivot->was_late ? 'text-red-600' : 'text-gray-600' }}">
                                                    {{ Carbon\Carbon::parse($task->pivot->submitted_at)->format('M d, Y g:ia') }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- XP Progress Chart -->
            <div class="bg-white shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">XP Progress</h3>
                    <div class="h-64">
                        <canvas id="xpChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const xpData = @json($xpProgress);
        const ctx = document.getElementById('xpChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: xpData.map(d => d.date),
                datasets: [{
                    label: 'XP Earned',
                    data: xpData.map(d => d.xp),
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
