<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:from-[#4B4B4B] dark:to-[#3B3B3B] 
                        backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">

                <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FF92C2] mb-6">Edit Subject</h2>

                <form action="{{ route('admin.subjects.update', $subject) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Subject Code --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Subject Code</label>
                        <input type="text" name="subject_code" value="{{ old('subject_code', $subject->subject_code) }}"
                               class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                      border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                      text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                               required>
                    </div>

                    {{-- Subject Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Subject Name</label>
                        <input type="text" name="subject_name" value="{{ old('subject_name', $subject->subject_name) }}"
                               class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                      border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                      text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                               required>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                         border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                         text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                  required>{{ old('description', $subject->description) }}</textarea>
                    </div>

                    {{-- Instructor --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Instructor</label>
                        <select name="instructor_id"
                                class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                       border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                       text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                required>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ $instructor->id == $subject->instructor_id ? 'selected' : '' }}>
                                    {{ $instructor->user->name ?? 'Unnamed' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Semester & Academic Year --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Semester</label>
                            <select name="semester" 
                                    class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                        border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                        text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                    required>
                                <option value="1st" {{ old('semester', $subject->semester) == '1st' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2nd" {{ old('semester', $subject->semester) == '2nd' ? 'selected' : '' }}>2nd Semester</option>
                            </select>

                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Academic Year</label>
                            <input type="text" name="academic_year" value="{{ old('academic_year', $subject->academic_year) }}"
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                        </div>
                    </div>

                    {{-- Units --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Units</label>
                        <input type="number" name="units" min="1" max="6" value="{{ old('units', $subject->units) }}"
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
                            <option value="1" {{ $subject->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$subject->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2] hover:from-[#FF5DA2] hover:to-[#FF92C2] 
                                       text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            Update Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
