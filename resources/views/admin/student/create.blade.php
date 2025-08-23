<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] backdrop-blur-sm overflow-hidden shadow-lg rounded-xl sm:rounded-2xl p-4 sm:p-8 border border-[#FFC8FB]/50">
                <h2 class="text-xl sm:text-2xl font-bold text-[#FF92C2] mb-4 sm:mb-6">Create New Student</h2>

                <form action="{{ route('admin.student.store') }}" method="POST" class="space-y-4 sm:space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        {{-- Basic Info Fields --}}
                        <div class="sm:col-span-2">
                            {{-- Name --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Full Name</label>
                                <input type="text" name="name" 
                                       value="{{ old('name') }}"
                                       class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                              border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                              text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200
                                              @error('name') border-red-500 @enderror"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="sm:col-span-2">
                            {{-- Email --}}
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Email Address</label>
                                <input type="email" name="email" 
                                       value="{{ old('email') }}"
                                       class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                              border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                              text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200
                                              @error('email') border-red-500 @enderror"
                                       required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Student ID --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Student ID</label>
                            <input type="text" name="id_number" 
                                   value="{{ old('id_number') }}"
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200
                                          @error('id_number') border-red-500 @enderror"
                                   required>
                            @error('id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Course & Year Level --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Course</label>
                                <select name="course_id" required
                                        class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                               border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                               text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200
                                               @error('course_id') border-red-500 @enderror">
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Year Level</label>
                                <select name="year_level" 
                                       class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                              border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                              text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                       required>
                                    <option value="">Select Year Level</option>
                                    @for ($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Assign Subjects - Updated with consistent styling --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Assign Subjects</label>
                            <div class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                        border border-[#FFC8FB] focus-within:border-pink-400 focus-within:ring focus-within:ring-pink-200 dark:focus-within:ring-pink-500
                                        px-4 py-2 transition-all duration-200 max-h-48 overflow-y-auto">
                                @foreach($subjects as $subject)
                                    <label class="flex items-center space-x-3 py-2 hover:bg-[#FFC8FB]/20 dark:hover:bg-[#FFC8FB]/10 rounded cursor-pointer">
                                        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" 
                                               class="text-[#FF92C2] border-[#FFC8FB] rounded focus:ring-[#FF92C2] focus:ring-2">
                                        <span class="text-gray-800 dark:text-black-200">{{ $subject->subject_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <small class="text-gray-600 dark:text-gray-400 block mt-1">Select one or more subjects.</small>
                        </div>

                        {{-- Section & Password --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Section</label>
                                <input type="text" name="section" 
                                       class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                              border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                              text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Password</label>
                                <input type="password" name="password" 
                                       class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                              border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                              text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                       required>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="sm:col-span-2 flex justify-end">
                            <button type="submit" 
                                    class="w-full sm:w-auto px-6 py-2 bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2] hover:from-[#FF5DA2] hover:to-[#FF92C2] 
                                           text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                Create Student
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>