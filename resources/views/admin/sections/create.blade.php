<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] backdrop-blur-sm overflow-hidden shadow-lg rounded-lg p-8 border border-[#FFC8FB]/50">
                <h2 class="text-2xl font-bold text-[#FF92C2] mb-6">Create New Section</h2>

                <form action="{{ route('admin.sections.store') }}" method="POST" class="space-y-6">
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
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Section Code</label>
                            <input type="text" name="section_code" value="{{ old('section_code') }}" required
                                   class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Section Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Assign Instructors</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto border border-[#FFC8FB] rounded-lg p-2">
                                @foreach($instructors as $instructor)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="instructors[]" value="{{ $instructor->id }}"
                                               class="rounded border-[#FFC8FB] text-[#FF92C2] focus:ring-pink-200">
                                        <span>{{ $instructor->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Assign Students</label>
                            <div class="space-y-2 max-h-48 overflow-y-auto border border-[#FFC8FB] rounded-lg p-2">
                                <div class="flex justify-between mb-1">
                                    <button type="button" id="select-all" class="text-sm text-[#FF92C2] hover:underline">Select All</button>
                                    <button type="button" id="deselect-all" class="text-sm text-[#FF92C2] hover:underline">Deselect All</button>
                                </div>
                                @foreach($students as $student)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="students[]" value="{{ $student->id }}"
                                               class="student-checkbox rounded border-[#FFC8FB]">
                                        <span>{{ $student->user->name }} ({{ $student->user->email }})</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Course</label>
                            <select name="course_id" required
                                    class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Capacity (Optional)</label>
                            <input type="number" name="capacity" min="1" value="{{ old('capacity') }}"
                                   class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Notes (Optional)</label>
                            <textarea name="notes" rows="3"
                                      class="w-full rounded-lg bg-white border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('admin.sections.index') }}" 
                           class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                            Create Section
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


