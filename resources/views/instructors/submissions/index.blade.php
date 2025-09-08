<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-xl rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-[#FF92C2]">Task Submissions</h2>
                        <a href="{{ route('instructors.tasks.index') }}" 
                           class="text-[#FF92C2] hover:text-[#ff6fb5]">
                            Back to Tasks
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Student</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Submitted</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Score</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-[#FFC8FB]">
                                @forelse($taskSubmissions as $submission)
                                    <tr class="hover:bg-[#FFF6FD]">
                                        <td class="px-6 py-4">{{ $submission->student->user->name }}</td>
                                        <td class="px-6 py-4">{{ $submission->submitted_at->format('M d, Y H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 py-1 text-xs rounded-full',
                                                'bg-yellow-100 text-yellow-800' => $submission->status === 'pending',
                                                'bg-green-100 text-green-800' => $submission->status === 'graded',
                                                'bg-red-100 text-red-800' => $submission->status === 'late'
                                            ])>
                                                {{ Str::title($submission->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $submission->score ? "{$submission->score} / {$submission->task->max_score}" : 'Not graded' }}
                                        </td>
                                        <td class="px-6 py-4 space-x-2">
                                            <a href="{{ route('instructors.task-submissions.show', $submission) }}" 
                                               class="text-[#FF92C2] hover:text-[#ff6fb5]" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No submissions found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
