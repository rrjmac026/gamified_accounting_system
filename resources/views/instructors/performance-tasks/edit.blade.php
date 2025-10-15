<x-app-layout>
    <div class="py-6 sm:py-12 bg-[#FAF3F3] min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('instructors.tasks.index') }}" 
                   class="inline-flex items-center text-sm text-[#D5006D] hover:text-[#FF6F91] transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Tasks
                </a>
            </div>

            <!-- Edit Task Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg border border-[#FF9AAB]/40">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-[#D5006D]">Edit Performance Task</h2>
                        <p class="text-sm text-gray-600 mt-1">Update the performance task details</p>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-[#FF9AAB]/20 border border-[#FF9AAB] text-[#D5006D] rounded-lg">
                            <h4 class="font-semibold mb-2">Please fix the following errors:</h4>
                            <ul class="list-disc list-inside space-y-1 text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('instructors.performance-tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Task Title <span class="text-[#D5006D]">*</span>
                                </label>
                                <input type="text" 
                                       name="title" 
                                       id="title" 
                                       value="{{ old('title', $task->title) }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D5006D] focus:border-transparent"
                                       required>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description" 
                                          id="description" 
                                          rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D5006D] focus:border-transparent">{{ old('description', $task->description) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Provide additional details about this performance task</p>
                            </div>

                            <!-- Subject and Section Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Subject -->
                                <div>
                                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Subject <span class="text-[#D5006D]">*</span>
                                    </label>
                                    <select name="subject_id" 
                                            id="subject_id" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D5006D] focus:border-transparent"
                                            required>
                                        <option value="">Select Subject</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" 
                                                    {{ old('subject_id', $task->subject_id) == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->subject_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Section -->
                                <div>
                                    <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Section <span class="text-[#D5006D]">*</span>
                                    </label>
                                    <select name="section_id" 
                                            id="section_id" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D5006D] focus:border-transparent"
                                            required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" 
                                                    {{ old('section_id', $task->section_id) == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- XP Reward and Max Attempts Row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- XP Reward -->
                                <div>
                                    <label for="xp_reward" class="block text-sm font-medium text-gray-700 mb-2">
                                        XP Reward <span class="text-[#D5006D]">*</span>
                                    </label>
                                    <input type="number" 
                                           name="xp_reward" 
                                           id="xp_reward" 
                                           value="{{ old('xp_reward', $task->xp_reward) }}"
                                           min="0"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D5006D] focus:border-transparent"
                                           required>
                                    <p class="text-xs text-gray-500 mt-1">Experience points awarded upon completion</p>
                                </div>

                                <!-- Max Attempts -->
                                <div>
                                    <label for="max_attempts" class="block text-sm font-medium text-gray-700 mb-2">
                                        Maximum Attempts <span class="text-[#D5006D]">*</span>
                                    </label>
                                    <input type="number" 
                                           name="max_attempts" 
                                           id="max_attempts" 
                                           value="{{ old('max_attempts', $task->max_attempts) }}"
                                           min="1"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#D5006D] focus:border-transparent"
                                           required>
                                    <p class="text-xs text-gray-500 mt-1">Number of times students can attempt this task</p>
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="bg-[#FF9AAB]/20 border border-[#FF9AAB]/50 rounded-lg p-4">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-[#D5006D] mt-0.5 mr-3"></i>
                                    <div class="text-sm text-[#D5006D]">
                                        <p class="font-semibold mb-1">About Performance Tasks</p>
                                        <p>Performance tasks are designed to assess students' practical application of knowledge. Students will receive notifications when the task is updated.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end gap-4 pt-4 border-t border-[#FF9AAB]/40">
                                <a href="{{ route('instructors.tasks.index') }}" 
                                   class="px-6 py-2 text-sm font-medium text-gray-700 bg-[#FAF3F3] hover:bg-[#FF9AAB]/30 rounded-lg transition">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 text-sm font-medium text-white bg-[#D5006D] hover:bg-[#FF6F91] rounded-lg shadow-sm transition">
                                    <i class="fas fa-save mr-2"></i>Update Performance Task
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Task Info -->
            <div class="mt-6 bg-white rounded-lg shadow p-6 border border-[#FF9AAB]/30">
                <h3 class="text-lg font-semibold text-[#D5006D] mb-4">Current Task Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">Created:</span>
                        <span class="font-medium ml-2">{{ $task->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-medium ml-2">{{ $task->updated_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Current Section:</span>
                        <span class="font-medium ml-2">{{ $task->section->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Students Assigned:</span>
                        <span class="font-medium ml-2">{{ $task->students->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
