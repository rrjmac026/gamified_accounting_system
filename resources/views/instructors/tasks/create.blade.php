<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] backdrop-blur-sm overflow-hidden shadow-lg rounded-lg p-8 border border-[#FFC8FB]/50">
                <h2 class="text-2xl font-bold text-[#FF92C2] mb-6">Create New Task</h2>

                <form action="{{ route('instructors.tasks.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" required
                                   class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>
                        

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Type</label>
                            <select name="type" required class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                                <option value="">Select Type</option>
                                @foreach(['assignment', 'exercise', 'quiz', 'project'] as $type)
                                    <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Attachment</label>
                            <input type="file" name="attachment"
                                class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Subject</label>
                            <select name="subject_id" required class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->subject_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Due Date</label>
                            <input type="datetime-local" name="due_date" value="{{ old('due_date') }}" required
                                   class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Section</label>
                            <select name="section_id" required
                                    class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                                <option value="">Select Section</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }} ({{ $section->section_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Status</label>
                            <select name="status" class="w-full rounded-lg border-[#FFC8FB]">
                                <option value="assigned" selected>Assigned</option>
                                <option value="in_progress">In Progress</option>
                            </select>
                        </div>


                        <!-- Task Settings -->
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Retry Limit</label>
                            <input type="number" name="retry_limit" value="{{ old('retry_limit', 1) }}" min="1" required
                                   class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Late Penalty (%)</label>
                            <input type="number" name="late_penalty" value="{{ old('late_penalty', 0) }}" min="0" max="100"
                                   class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Maximum Score</label>
                            <input type="number" name="max_score" value="{{ old('max_score', 100) }}" min="0" required
                                   class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">XP Reward</label>
                            <input type="number" name="xp_reward" value="{{ old('xp_reward', 0) }}" min="0" required
                                   class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>
                    </div>

                    <!-- Description and Instructions -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Description</label>
                            <textarea name="description" rows="3" required
                                    class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Instructions</label>
                            <textarea name="instructions" rows="4" required
                                    class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">{{ old('instructions') }}</textarea>
                        </div>
                    </div>

                    <!-- Task Options -->
                    <div class="flex items-center gap-6">
                        <label class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                class="rounded border-[#FFC8FB] text-[#FF92C2] focus:ring-pink-200">
                            <span class="ml-2 text-sm text-gray-700">Active</span>
                        </label>

                        <label class="flex items-center">
                            <input type="hidden" name="auto_grade" value="0">
                            <input type="checkbox" name="auto_grade" value="1" {{ old('auto_grade') ? 'checked' : '' }}
                                class="rounded border-[#FFC8FB] text-[#FF92C2] focus:ring-pink-200">
                            <span class="ml-2 text-sm text-gray-700">Auto Grade</span>
                        </label>

                        <!-- Fixed: Remove reference to $task since it doesn't exist in create view -->
                        <label class="flex items-center">
                            <input type="hidden" name="allow_late_submission" value="0">
                            <input type="checkbox" name="allow_late_submission" value="1" 
                                {{ old('allow_late_submission') ? 'checked' : '' }}
                                class="rounded border-[#FFC8FB] text-[#FF92C2] focus:ring-pink-200">
                            <span class="ml-2 text-sm text-gray-700">Allow late submissions</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('instructors.tasks.index') }}" 
                           class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                            Create Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    document.getElementById('select-all').addEventListener('click', function() {
        document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = true);
    });

    document.getElementById('deselect-all').addEventListener('click', function() {
        document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
    });
</script>
</x-app-layout>