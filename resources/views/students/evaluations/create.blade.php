<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-xl sm:rounded-2xl">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-[#FF92C2] to-[#ff6fb5] p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold mb-2">Course Evaluation</h2>
                            <p class="text-pink-100">Share your feedback to help improve the learning experience</p>
                        </div>
                        <div class="hidden md:block">
                            <svg class="w-16 h-16 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-8">
                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-red-800">Please correct the following errors:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('evaluations.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- Course and Instructor Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB]">
                                    Select Instructor <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="instructor_id" 
                                            class="w-full rounded-xl bg-white dark:bg-[#4a4949] border-2 border-[#FFC8FB] focus:border-[#FF92C2] focus:ring-2 focus:ring-[#FF92C2]/20 transition-all duration-200 text-gray-900 dark:text-[#FFC8FB] py-3 px-4" 
                                            required>
                                        <option value="">Choose an instructor</option>
                                        @forelse($instructors as $instructor)
                                            <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                                {{ $instructor->user->name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>No instructors available</option>
                                        @endforelse
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-[#FF92C2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('instructor_id')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB]">
                                    Select Course <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="course_id" 
                                            class="w-full rounded-xl bg-white dark:bg-[#4a4949] border-2 border-[#FFC8FB] focus:border-[#FF92C2] focus:ring-2 focus:ring-[#FF92C2]/20 transition-all duration-200 text-gray-900 dark:text-[#FFC8FB] py-3 px-4" 
                                            required>
                                        <option value="">Choose a course</option>
                                        @forelse($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                                {{ $course->name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>No courses available</option>
                                        @endforelse
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none">
                                        <svg class="w-5 h-5 text-[#FF92C2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('course_id')
                                    <p class="text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Evaluation Criteria -->
                        <div class="space-y-6">
                            <div class="border-b border-[#FFC8FB]/30 pb-4">
                                <h3 class="text-xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Evaluation Criteria
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-[#FFC8FB]/80 mt-2">Please rate each aspect on a scale of 1-5 (1 = Poor, 5 = Excellent)</p>
                            </div>

                            @foreach($criteria as $key => $criterion)
                                <div class="bg-white dark:bg-[#4a4949] p-6 rounded-xl border border-[#FFC8FB]/30 shadow-sm">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-[#FFC8FB] mb-4">{{ $criterion }}</label>
                                    <div class="flex flex-wrap gap-4">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-[#FF92C2]/5 p-3 rounded-lg transition-all duration-200">
                                                <input type="radio" 
                                                       name="responses[{{ $key }}]" 
                                                       value="{{ $i }}" 
                                                       class="w-4 h-4 text-[#FF92C2] border-2 border-[#FFC8FB] focus:ring-2 focus:ring-[#FF92C2]/20" 
                                                       {{ old("responses.{$key}") == $i ? 'checked' : '' }}
                                                       required>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-sm font-medium text-gray-600 dark:text-[#FFC8FB]">{{ $i }}</span>
                                                    <div class="flex">
                                                        @for($j = 1; $j <= $i; $j++)
                                                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 24 24">
                                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                            </svg>
                                                        @endfor
                                                        @for($k = $i + 1; $k <= 5; $k++)
                                                            <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 24 24">
                                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                            </svg>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </label>
                                        @endfor
                                    </div>
                                    @error("responses.{$key}")
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <!-- Comments Section -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB]">
                                Additional Comments <span class="text-red-500">*</span>
                            </label>
                            <p class="text-sm text-gray-600 dark:text-[#FFC8FB]/80">Share any specific feedback, suggestions, or experiences</p>
                            <textarea name="comments" 
                                      rows="6" 
                                      class="w-full rounded-xl bg-white dark:bg-[#4a4949] border-2 border-[#FFC8FB] focus:border-[#FF92C2] focus:ring-2 focus:ring-[#FF92C2]/20 transition-all duration-200 text-gray-900 dark:text-[#FFC8FB] p-4" 
                                      placeholder="Please share your detailed feedback about the course and instructor..."
                                      required>{{ old('comments') }}</textarea>
                            @error('comments')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-[#FFC8FB]/30">
                            <a href="{{ route('evaluations.index') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-[#FF92C2] to-[#ff6fb5] hover:from-[#ff6fb5] hover:to-[#ff4da6] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Submit Evaluation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>