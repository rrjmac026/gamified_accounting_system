<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-xl rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-[#FF92C2]">Submission Details</h2>
                        <a href="{{ route('instructors.task-submissions.index', $taskSubmission->task) }}" 
                           class="text-[#FF92C2] hover:text-[#ff6fb5]">
                            Back to Submissions
                        </a>
                    </div>

                    <!-- Student & Task Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600">Student</h3>
                            <p class="text-lg">{{ $taskSubmission->student->user->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600">Task</h3>
                            <p class="text-lg">{{ $taskSubmission->task->title }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600">Submitted At</h3>
                            <p class="text-lg">{{ $taskSubmission->submitted_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600">Status</h3>
                            <span @class([
                                'px-2 py-1 text-sm rounded-full',
                                'bg-yellow-100 text-yellow-800' => $taskSubmission->status === 'pending',
                                'bg-green-100 text-green-800' => $taskSubmission->status === 'graded',
                                'bg-red-100 text-red-800' => $taskSubmission->status === 'late'
                            ])>
                                {{ Str::title($taskSubmission->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Submission Content -->
                    @if($taskSubmission->file_path)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-[#FF92C2] mb-2">Submitted File</h3>
                            <div class="bg-white p-4 rounded-lg border border-[#FFC8FB]">
                                <a href="{{ Storage::url($taskSubmission->file_path) }}" 
                                   class="text-[#FF92C2] hover:text-[#ff6fb5] flex items-center" 
                                   target="_blank">
                                    <i class="fas fa-download mr-2"></i>
                                    Download Submission
                                </a>
                            </div>
                        </div>
                    @endif

                    @if($taskSubmission->submission_data)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-[#FF92C2] mb-2">Answers</h3>
                            <div class="bg-white p-4 rounded-lg border border-[#FFC8FB]">
                                @foreach($taskSubmission->submission_data as $question => $answer)
                                    <div class="mb-4">
                                        <p class="font-medium text-gray-700">{{ $question }}</p>
                                        <p class="text-gray-600 mt-1">{{ $answer }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Grading Section -->
                    @if($taskSubmission->status !== 'graded')
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-[#FF92C2] mb-4">Grade Submission</h3>
                            <form action="{{ route('instructors.task-submissions.grade', $taskSubmission) }}" method="POST">
                                @csrf
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Score</label>
                                        <input type="number" name="score" min="0" max="{{ $taskSubmission->task->max_score }}"
                                               class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200"
                                               required>
                                        <p class="mt-1 text-sm text-gray-500">Maximum score: {{ $taskSubmission->task->max_score }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">XP to Award</label>
                                        <input type="number" name="xp_earned" min="0"
                                               class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200"
                                               value="{{ $taskSubmission->task->xp_reward }}"
                                               required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Feedback</label>
                                        <textarea name="feedback" rows="3" required
                                                  class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200"></textarea>
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="submit" 
                                                class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                                            Submit Grade
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Show Grading Details -->
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-[#FF92C2] mb-2">Grading Details</h3>
                            <div class="bg-white p-4 rounded-lg border border-[#FFC8FB]">
                                <div class="space-y-3">
                                    <p><strong>Score:</strong> {{ $taskSubmission->score }} / {{ $taskSubmission->task->max_score }}</p>
                                    <p><strong>XP Awarded:</strong> {{ $taskSubmission->xp_earned }}</p>
                                    <p><strong>Graded At:</strong> {{ $taskSubmission->graded_at->format('F j, Y g:i A') }}</p>
                                    <p><strong>Feedback:</strong></p>
                                    <p class="text-gray-600">{{ $taskSubmission->feedback }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
