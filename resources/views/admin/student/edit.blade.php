<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] backdrop-blur-sm overflow-hidden shadow-lg rounded-xl sm:rounded-2xl p-4 sm:p-8 border border-[#FFC8FB]/50">
                <h2 class="text-xl sm:text-2xl font-bold text-[#FF92C2] mb-4 sm:mb-6">Edit Student</h2>

                {{-- Add error message display at the top of the form --}}
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.student.update', $student) }}" method="POST" class="space-y-4 sm:space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Basic Info --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $student->user->first_name) }}"
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200
                                          @error('first_name') border-red-500 @enderror"
                                   required>
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $student->user->last_name) }}"
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200
                                          @error('last_name') border-red-500 @enderror"
                                   required>
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $student->user->email) }}"
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Fields --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">
                                Change Password <span class="text-gray-500 font-normal">(leave blank to keep current password)</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <input type="password" name="password" 
                                           placeholder="New Password"
                                           class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                                  border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                                  text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200">
                                </div>
                                <div>
                                    <input type="password" name="password_confirmation" 
                                           placeholder="Confirm New Password"
                                           class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                                  border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                                  text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200">
                                </div>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Student Number</label>
                            <input type="text" name="student_number" value="{{ old('student_number', $student->student_number) }}"
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                            @error('student_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Course Selection --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Course</label>
                            <select name="course_id" required
                                    class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200">
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id', $student->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Year Level & Section --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Year Level</label>
                            <select name="year_level" 
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   >
                                @for ($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}" {{ $student->year_level == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Section</label>
                                
                                {{-- Search Input --}}
                                <div class="mb-2">
                                    <input type="text" id="section-search" placeholder="Search sections..."
                                        class="w-full rounded-lg border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200
                                                text-gray-800 px-3 py-2 text-sm transition-all duration-200" />
                                </div>

                                {{-- Hidden input to store the selected value --}}
                                <input type="hidden" name="section" id="selected-section" value="{{ old('section', $student->section) }}">

                                {{-- Sections List --}}
                                <div id="section-list"
                                    class="w-full rounded-lg shadow-sm bg-white border border-[#FFC8FB] 
                                            text-gray-800 px-4 py-2 transition-all duration-200 max-h-48 overflow-y-auto">
                                    @foreach($sections as $section)
                                        <label class="flex items-center space-x-3 py-2 hover:bg-[#FFC8FB]/20 rounded cursor-pointer section-item">
                                            <input type="radio" name="section_radio" value="{{ $section->name }}" 
                                                {{ old('section', $student->section) == $section->name ? 'checked' : '' }}
                                                class="text-[#FF92C2] border-[#FFC8FB] focus:ring-[#FF92C2] focus:ring-2"
                                                onchange="updateSelectedSection(this.value)">
                                            <span class="text-gray-800">{{ $section->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('section')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                    {{-- Subjects Selection --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Assign Subjects</label>

                        {{-- Search Input --}}
                        <div class="mb-2">
                            <input type="text" id="subject-search" placeholder="Search subjects..."
                                class="w-full rounded-lg border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200
                                        text-gray-800 px-3 py-2 text-sm transition-all duration-200" />
                        </div>

                        {{-- Subjects List --}}
                        <div id="subject-list"
                            class="w-full rounded-lg shadow-sm bg-white border border-[#FFC8FB] 
                                    text-gray-800 px-4 py-2 transition-all duration-200 max-h-48 overflow-y-auto">
                            @foreach($subjects as $subject)
                                <label class="flex items-center space-x-3 py-2 hover:bg-[#FFC8FB]/20 rounded cursor-pointer subject-item">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" 
                                        {{ (is_array(old('subjects', $student->subjects->pluck('id')->toArray())) && in_array($subject->id, old('subjects', $student->subjects->pluck('id')->toArray()))) ? 'checked' : '' }}
                                        class="text-[#FF92C2] border-[#FFC8FB] rounded focus:ring-[#FF92C2] focus:ring-2">
                                    <span class="text-gray-800">{{ $subject->subject_name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('subjects')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="sm:col-span-2 flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.student.index') }}" 
                           class="w-full sm:w-auto px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 text-center">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto px-6 py-2 bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2] hover:from-[#FF5DA2] hover:to-[#FF92C2] 
                                       text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    // Section search functionality
    document.getElementById('section-search').addEventListener('keyup', function () {
        let query = this.value.toLowerCase();
        document.querySelectorAll('#section-list .section-item').forEach(function (item) {
            let text = item.innerText.toLowerCase();
            item.style.display = text.includes(query) ? '' : 'none';
        });
    });

    // Update hidden input when section is selected
    function updateSelectedSection(value) {
        document.getElementById('selected-section').value = value;
    }

    // Subject search functionality (keep existing)
    document.getElementById('subject-search').addEventListener('keyup', function () {
        let query = this.value.toLowerCase();
        document.querySelectorAll('#subject-list .subject-item').forEach(function (item) {
            let text = item.innerText.toLowerCase();
            item.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
</x-app-layout>