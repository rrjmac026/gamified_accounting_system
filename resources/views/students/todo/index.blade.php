<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-700">
                    <h2 class="text-2xl font-bold text-[#FF92C2] mb-6">To-Do</h2>

                    @foreach (['assigned', 'in_progress', 'submitted', 'graded'] as $status)
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold text-[#FF92C2] mb-4 capitalize">
                                {{ str_replace('_', ' ', $status) }}
                            </h3>

                            @if(isset($groupedTasks[$status]) && $groupedTasks[$status]->count())
                                <div class="w-full overflow-x-auto">
                                    <table class="min-w-full table-auto text-xs sm:text-sm">
                                        <thead class="bg-[#FFC8FB]">
                                            <tr>
                                                <th class="py-2 sm:py-3 px-3 sm:px-6 text-left font-medium text-pink-900">Task</th>
                                                <th class="py-2 sm:py-3 px-3 sm:px-6 text-left font-medium text-pink-900">Subject</th>
                                                <th class="py-2 sm:py-3 px-3 sm:px-6 text-left font-medium text-pink-900">Due</th>
                                                <th class="py-2 sm:py-3 px-3 sm:px-6 text-left font-medium text-pink-900">Score</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                                            @foreach($groupedTasks[$status] as $task)
                                                <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                                    <td class="px-6 py-4">
                                                        <a href="{{ route('students.tasks.show', $task) }}" 
                                                           class="text-[#FF92C2] font-medium hover:underline">
                                                            {{ $task->title }}
                                                        </a>
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        {{ $task->subject->subject_name ?? '—' }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        {{ $task->due_date ? $task->due_date->format('M d, Y g:i A') : 'No deadline' }}
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        @php
                                                            $submission = $task->submissions->first();
                                                        @endphp

                                                        {{ $submission && $submission->score !== null 
                                                            ? $submission->score . ' / ' . $task->max_score 
                                                            : 'Not graded' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-gray-500">No {{ str_replace('_', ' ', $status) }} tasks.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
