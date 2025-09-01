<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Task Details Card -->
            <div class="bg-[#FFF0FA] overflow-hidden shadow-xl rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-[#FF92C2]">Task Details</h2>
                        <div class="flex gap-3">
                            <a href="{{ route('instructors.tasks.edit', $task) }}" 
                               class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                                Edit Task
                            </a>
                            <a href="{{ route('instructors.tasks.index') }}" 
                               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-semibold text-[#FF92C2] mb-1">Title</h3>
                            <p class="text-gray-700">{{ $task->title }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-[#FF92C2] mb-1">Type</h3>
                            <p class="text-gray-700 capitalize">{{ $task->type }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-[#FF92C2] mb-1">Subject</h3>
                            <p class="text-gray-700">{{ $task->subject->subject_name }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-[#FF92C2] mb-1">Due Date</h3>
                            <p class="text-gray-700">{{ $task->due_date->format('F j, Y g:i A') }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <h3 class="text-sm font-semibold text-[#FF92C2] mb-1">Description</h3>
                            <p class="text-gray-700">{{ $task->description }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <h3 class="text-sm font-semibold text-[#FF92C2] mb-1">Instructions</h3>
                            <p class="text-gray-700 whitespace-pre-line">{{ $task->instructions }}</p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-[#FF92C2] mb-1">Settings</h3>
                            <div class="space-y-2">
                                <p class="text-gray-700">Difficulty Level: {{ $task->difficulty_level }}</p>
                                <p class="text-gray-700">Max Score: {{ $task->max_score }}</p>
                                <p class="text-gray-700">XP Reward: {{ $task->xp_reward }}</p>
                                <p class="text-gray-700">Retry Limit: {{ $task->retry_limit }}</p>
                                @if($task->late_penalty)
                                    <p class="text-gray-700">Late Penalty: {{ $task->late_penalty }}%</p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-[#FF92C2] mb-1">Status</h3>
                            <div class="flex flex-wrap gap-3">
                                <span class="px-3 py-1 text-sm rounded-full {{ $task->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $task->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($task->auto_grade)
                                    <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">Auto Grade</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                        <!-- Assigned Students Section -->
            <div class="bg-[#FFF0FA] overflow-hidden shadow-xl rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-[#FF92C2]">Assigned Students ({{ $task->students->count() }})</h3>
                        <a href="{{ route('instructors.tasks.assign-students', $task) }}" 
                        class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                        Assign Students
                        </a>
                    </div>

                    @if($task->students->isEmpty())
                        <p class="text-gray-500 text-center py-4">No students assigned yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg border border-[#FFC8FB]">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Name</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Email</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Status</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Score</th>
                                        <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">XP Earned</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($task->students as $student)
                                        <tr class="border-t border-gray-200">
                                            <td class="px-4 py-2">{{ $student->user->name }}</td>
                                            <td class="px-4 py-2">{{ $student->user->email }}</td>
                                            <td class="px-4 py-2">{{ Str::title($student->pivot->status) }}</td>
                                            <td class="px-4 py-2">{{ $student->pivot->score ?? 'N/A' }}</td>
                                            <td class="px-4 py-2">{{ $student->pivot->xp_earned ?? 0 }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Questions Section -->
            <div class="bg-[#FFF0FA] overflow-hidden shadow-xl rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-[#FF92C2]">Questions ({{ $task->questions->count() }})</h3>
                        <button onclick="showAddQuestionModal()" 
                               class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                            Add Question
                        </button>
                    </div>

                    @if($task->questions->isEmpty())
                        <p class="text-gray-500 text-center py-4">No questions added yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($task->questions->sortBy('order_index') as $question)
                                <div class="bg-white p-4 rounded-lg border border-[#FFC8FB]">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-xs bg-gray-100 px-2 py-1 rounded">Order: {{ $question->order_index }}</span>
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $question->points }} pts</span>
                                                <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded capitalize">{{ str_replace('_', ' ', $question->question_type) }}</span>
                                            </div>
                                            <p class="font-medium text-gray-800 mb-2">{{ $question->description }}</p>
                                            
                                            @if($question->options && is_array($question->options) && count($question->options) > 0)
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-600 mb-1">Options:</p>
                                                    <ul class="text-sm text-gray-600 list-disc list-inside">
                                                        @foreach($question->options as $option)
                                                            @if($option)
                                                                <li>{{ $option }}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            
                                            <div class="mt-2">
                                                <p class="text-sm text-green-600">
                                                    <strong>Correct Answer:</strong> {{ $question->correct_answer }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            <a href="{{ route('instructors.tasks.edit-question', [$task, $question]) }}" 
                                               class="text-[#FF92C2] hover:text-[#ff6fb5]" title="Edit Question">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                                                                        <form action="{{ route('instructors.tasks.delete-question', [$task, $question]) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" title="Delete Question"
                                                        onclick="return confirm('Are you sure you want to delete this question?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div> <!-- end of flex gap-2 ml-4 -->
                                    </div> <!-- end of flex justify-between items-start -->
                                </div> <!-- end of question card -->
                            @endforeach
                        </div> <!-- end of space-y-4 -->
                    @endif
                </div> <!-- end of p-6 -->
            </div> <!-- end of Questions Section -->
        </div> <!-- end of max-w-7xl -->
    </div> <!-- end of py-6 -->
</x-app-layout>
