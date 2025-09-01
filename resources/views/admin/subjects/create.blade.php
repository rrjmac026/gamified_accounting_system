<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] backdrop-blur-sm overflow-hidden shadow-lg rounded-xl sm:rounded-2xl p-4 sm:p-8 border border-[#FFC8FB]/50">
                <h2 class="text-xl sm:text-2xl font-bold text-[#FF92C2] mb-4 sm:mb-6">Create New Subject</h2>

                <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-4 sm:space-y-6">
                    @csrf

                    {{-- Subject Code --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Subject Code</label>
                            <input type="text" name="subject_code" 
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                        </div>

                        {{-- Subject Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Subject Name</label>
                            <input type="text" name="subject_name" 
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                         border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                         text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                  required></textarea>
                    </div>


                    {{-- Multiple Instructors Selection --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">
                            Assign Instructors
                        </label>
                        <div class="max-h-48 overflow-y-auto p-4 border border-[#FFC8FB] rounded-lg">
                            <div class="flex justify-between mb-2">
                                <button type="button" id="select-all-instructors" 
                                        class="text-sm text-[#FF92C2] hover:text-[#ff6fb5]">
                                    Select All
                                </button>
                                <button type="button" id="deselect-all-instructors" 
                                        class="text-sm text-[#FF92C2] hover:text-[#ff6fb5]">
                                    Deselect All
                                </button>
                            </div>
                            @foreach($instructors as $instructor)
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" name="instructor_ids[]" 
                                           value="{{ $instructor->id }}"
                                           class="instructor-checkbox rounded border-[#FFC8FB] text-[#FF92C2] focus:ring-[#FF92C2]">
                                    <span>{{ $instructor->user->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Semester & Academic Year --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Semester</label>
                            <select name="semester" 
                                    class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                        border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                        text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                    required>
                                <option value="">-- Select Semester --</option>
                                <option value="1st" {{ old('semester') == '1st' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd" {{ old('semester') == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                            </select>

                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Academic Year</label>
                            <input type="text" name="academic_year" 
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                        </div>
                    </div>

                    {{-- Units --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Units</label>
                        <input type="number" name="units" min="1" max="6" 
                               class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                      border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                      text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                               required>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Status</label>
                        <select name="is_active" 
                                class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                       border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                       text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    {{-- Submit --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <button type="submit" 
                                class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2] hover:from-[#FF5DA2] hover:to-[#FF92C2] 
                                       text-white text-sm sm:text-base font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            Create Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all-instructors').addEventListener('click', function() {
            document.querySelectorAll('.instructor-checkbox').forEach(cb => cb.checked = true);
        });

        document.getElementById('deselect-all-instructors').addEventListener('click', function() {
            document.querySelectorAll('.instructor-checkbox').forEach(cb => cb.checked = false);
        });
    </script>
</x-app-layout>
