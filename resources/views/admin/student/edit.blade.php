<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:from-[#4B4B4B] dark:to-[#3B3B3B] 
                        backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">
                
                <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FF92C2] mb-6">Edit Student</h2>

                <form action="{{ route('admin.student.update', $student) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Course Selection --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Course</label>
                        <select name="course_id" required
                                class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                       border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                       text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200
                                       @error('course_id') border-red-500 @enderror">
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ $student->course_id == $course->id ? 'selected' : '' }}>
                                    {{ $course->course_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Year Level & Section --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Year Level</label>
                            <select name="year_level" 
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                                @for ($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}" {{ $student->year_level == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Section</label>
                            <input type="text" name="section" value="{{ $student->section }}"
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                        </div>
                    </div>

                    {{-- Subjects - Updated with consistent styling and new hover color --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Assign Subjects</label>
                        <div class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                    border border-[#FFC8FB] focus-within:border-pink-400 focus-within:ring focus-within:ring-pink-200 dark:focus-within:ring-pink-500
                                    px-4 py-2 transition-all duration-200 max-h-48 overflow-y-auto">
                            @foreach($subjects as $subject)
                                <label class="flex items-center space-x-3 py-2 hover:bg-[#FFC8FB]/20 dark:hover:bg-[#FFC8FB]/10 rounded cursor-pointer">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" 
                                           {{ $student->subjects->contains($subject->id) ? 'checked' : '' }}
                                           class="text-[#FF92C2] border-[#FFC8FB] rounded focus:ring-[#FF92C2] focus:ring-2">
                                    <span class="text-gray-800 dark:text-black-200">{{ $subject->subject_name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.student.index', $student) }}" 
                           class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</a>
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2] hover:from-[#FF5DA2] hover:to-[#FF92C2] 
                                       text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>