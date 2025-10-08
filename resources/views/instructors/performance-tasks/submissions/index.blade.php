<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">{{ $task->title }}</h2>
            <p class="text-sm text-gray-500 mt-1">Student Submissions</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50">
                                Student
                            </th>
                            @for($i=1; $i<=10; $i++)
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    {{ $i }}
                                </th>
                            @endfor
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                Score
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                Attempts
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($submissions as $studentId => $steps)
                            @php
                                $student = $steps->first()->student;
                                $totalScore = $steps->sum('score');
                                $totalAttempts = $steps->sum('attempts');
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white hover:bg-gray-50">
                                    {{ $student->name }}
                                </td>
                                @for($i=1; $i<=10; $i++)
                                    @php
                                        $step = $steps->firstWhere('step', $i);
                                        $status = $step->status ?? 'in-progress';
                                    @endphp
                                    <td class="px-3 py-4 text-center">
                                        @if($status === 'correct')
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600 text-xs">✓</span>
                                        @elseif($status === 'wrong')
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs">✕</span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-100 text-gray-400 text-xs">−</span>
                                        @endif
                                    </td>
                                @endfor
                                <td class="px-4 py-4 text-center text-sm font-medium text-gray-900">
                                    {{ $totalScore }}
                                </td>
                                <td class="px-4 py-4 text-center text-sm text-gray-600">
                                    {{ $totalAttempts }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('instructors.performance-tasks.submissions.show', ['task' => $task->id, 'student' => $student->id]) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>