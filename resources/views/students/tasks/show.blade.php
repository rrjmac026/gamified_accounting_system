<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg border border-pink-100">
                <div class="p-4 sm:p-6 text-gray-700">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-pink-600">Task Details</h2>
                        <a href="{{ route('students.tasks.index') }}" 
                           class="text-pink-600 hover:text-pink-700 font-medium">
                            Back to Tasks
                        </a>
                    </div>

                    <!-- Task Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="text-sm font-semibold text-pink-600 mb-1">Subject</h3>
                            <p class="text-gray-700">{{ $task->subject->subject_name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-pink-600 mb-1">Type</h3>
                            <p class="text-gray-700 capitalize">{{ $task->type }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-pink-600 mb-1">Due Date</h3>
                            <p class="text-gray-700">{{ $task->due_date->format('F j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-pink-600 mb-1">Status</h3>
                            <span @class([
                                'px-2 py-1 text-xs rounded-full font-medium',
                                'bg-yellow-100 text-yellow-800' => $studentTask->pivot->status === 'assigned',
                                'bg-blue-100 text-blue-800' => $studentTask->pivot->status === 'in_progress',
                                'bg-green-100 text-green-800' => $studentTask->pivot->status === 'submitted',
                                'bg-purple-100 text-purple-800' => $studentTask->pivot->status === 'graded',
                                'bg-red-100 text-red-800' => $studentTask->pivot->status === 'overdue'
                            ])>
                                {{ $submission ? ucfirst($submission->status) : 'Not submitted' }}
                            </span>
                        </div>
                    </div>

                    <!-- Task Content -->
                    @if ($task->attachment)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-pink-600 mb-2">
                                Attachment
                            </h3>
                            <div class="flex items-center justify-between bg-pink-50 border border-pink-200 rounded-lg p-4 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-pink-100">
                                        <i class="fas fa-paperclip text-pink-600"></i>
                                    </div>
                                    <span class="text-gray-700 text-sm truncate max-w-[200px]">
                                        {{ basename($task->attachment) }}
                                    </span>
                                </div>
                                <a href="{{ asset('storage/' . $task->attachment) }}" 
                                target="_blank"
                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg
                                        bg-pink-600 text-white hover:bg-pink-700 transition-all">
                                    <i class="fas fa-download mr-1"></i> Download
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-6 mb-8">
                        <div>
                            <h3 class="text-lg font-semibold text-pink-600 mb-2">Description</h3>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-gray-700">{{ $task->description }}</p>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-pink-600 mb-2">Instructions</h3>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-gray-700 whitespace-pre-line">{{ $task->instructions }}</p>
                            </div>
                        </div>

                        @if($task->questions->isNotEmpty())
                            <div>
                                <h3 class="text-lg font-semibold text-pink-600 mb-2">Questions</h3>
                                <div class="space-y-4">
                                    @foreach($task->questions as $question)
                                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                            <p class="font-medium text-gray-900 mb-2">
                                                {{ $question->description }}
                                            </p>
                                            @if($question->options)
                                                <div class="space-y-2">
                                                    @foreach($question->options as $option)
                                                        <label class="flex items-center">
                                                            <input type="radio" name="answers[{{ $question->id }}]" 
                                                                   value="{{ $option }}"
                                                                   class="text-pink-600 focus:ring-pink-500">
                                                            <span class="ml-2 text-gray-700">{{ $option }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Submission Form -->
                    @if(!in_array($studentTask->pivot->status, ['submitted', 'graded']))
                        <form action="{{ route('students.tasks.submit', $task) }}" method="POST" 
                              enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            
                            @if($task->type !== 'quiz')
                                <div>
                                    <label class="block text-sm font-semibold text-pink-600 mb-1">
                                        Upload File (if required)
                                    </label>
                                    <input type="file" name="file" 
                                           class="w-full text-gray-700 border border-gray-300 rounded-md p-2">
                                </div>
                            @endif

                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium transition-colors">
                                    Submit Task
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Show submission details if already submitted -->
                        <div class="bg-pink-50 rounded-lg p-4 border border-pink-200">
                            <h3 class="text-lg font-semibold text-pink-600 mb-2">Submission Details</h3>
                            <div class="space-y-2">
                                <p class="text-gray-700">
                                    <span class="font-medium">Submitted:</span> {{ isset($studentTask->pivot->submitted_at) ? \Carbon\Carbon::parse($studentTask->pivot->submitted_at)->format('F j, Y g:i A') : 'N/A' }}
                                </p>
                                @if($studentTask->pivot->status === 'graded')
                                    <p class="text-gray-700">
                                        <span class="font-medium">Score:</span> {{ $studentTask->pivot->score }} / {{ $task->max_score }}
                                    </p>
                                    <p class="text-gray-700">
                                        <span class="font-medium">XP Earned:</span> {{ $studentTask->pivot->xp_earned }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 bg-gray-50 p-6 rounded-lg shadow border border-gray-200">
                        <h3 class="text-lg font-semibold text-pink-600 mb-3">Instructor Feedback</h3>

                        @if($submission && $submission->status === 'graded')
                            <div class="space-y-2">
                                <p class="text-gray-700">
                                    <span class="font-medium">Score:</span> {{ $submission->score }}
                                </p>
                                <p class="text-gray-700">
                                    <span class="font-medium">XP Earned:</span> {{ $submission->xp_earned }}
                                </p>
                                <p class="text-gray-700">
                                    <span class="font-medium">Feedback:</span> {{ $submission->feedback }}
                                </p>
                            </div>
                        @else
                            <p class="text-gray-500">
                                No feedback yet. Complete and submit your task first.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>