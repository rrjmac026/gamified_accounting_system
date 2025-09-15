<!-- Feedback Create Page -->
<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl p-8 border border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mb-6">Submit Feedback</h2>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-4 p-4 rounded-md bg-green-50 border border-green-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('students.feedback.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Hidden Student ID field -->
                    <input type="hidden" name="student_id" value="{{ Auth::user()->student->id }}">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Task</label>
                        <select name="task_id" required class="w-full rounded-lg bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-200">
                            <option value="">Select Task</option>
                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}" {{ old('task_id') == $task->id ? 'selected' : '' }}>
                                    {{ $task->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('task_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Feedback Type</label>
                        <select name="feedback_type" required class="w-full rounded-lg bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-200">
                            <option value="">Select Type</option>
                            <option value="general" {{ old('feedback_type') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="improvement" {{ old('feedback_type') == 'improvement' ? 'selected' : '' }}>Improvement</option>
                            <option value="question" {{ old('feedback_type') == 'question' ? 'selected' : '' }}>Question</option>
                        </select>
                        @error('feedback_type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Feedback Text</label>
                        <textarea name="feedback_text" rows="4" required 
                                  class="w-full rounded-lg bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-200"
                                  placeholder="Share your thoughts about this task...">{{ old('feedback_text') }}</textarea>
                        @error('feedback_text')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Recommendations</label>
                        <textarea name="recommendations" rows="3" required 
                                  class="w-full rounded-lg bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 transition-colors duration-200"
                                  placeholder="What suggestions do you have for improvement?">{{ old('recommendations') }}</textarea>
                        @error('recommendations')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hidden fields for metadata -->
                    <input type="hidden" name="generated_at" value="{{ now() }}">
                    <input type="hidden" name="is_read" value="0">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Rating (Optional)</label>
                        <div class="flex space-x-6">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="flex items-center space-x-2 cursor-pointer">
                                    <input type="radio" name="rating" value="{{ $i }}" class="text-indigo-600 focus:ring-indigo-500 focus:ring-2">
                                    <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $i }}</span>
                                    <div class="flex">
                                        @for($j = 1; $j <= $i; $j++)
                                            <i class="fas fa-star text-amber-400 text-xs"></i>
                                        @endfor
                                    </div>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4 pt-4">
                        <a href="{{ route('students.feedback.index') }}" 
                           class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200 font-medium">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 font-medium">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>