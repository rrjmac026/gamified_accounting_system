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

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Assign Instructors</label>
                            <div class="bg-white rounded-lg border border-[#FFC8FB] overflow-hidden">
                                <!-- Search Bar -->
                                <div class="p-4 border-b border-[#FFC8FB]">
                                    <div class="relative">
                                        <input type="text" id="instructor-search" 
                                               placeholder="Search instructors..." 
                                               class="w-full pl-10 pr-4 py-2 rounded-lg border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selection Controls -->
                                <div class="px-4 py-2 bg-gray-50 border-b border-[#FFC8FB] flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="select-all-instructors" 
                                                class="text-sm text-[#FF92C2] hover:text-[#ff6fb5] flex items-center gap-1">
                                            Select All
                                        </button>
                                        <button type="button" id="deselect-all-instructors" 
                                                class="text-sm text-[#FF92C2] hover:text-[#ff6fb5] flex items-center gap-1">
                                            Deselect All
                                        </button>
                                    </div>
                                    <span class="text-xs text-gray-500" id="instructor-counter">
                                        0 selected
                                    </span>
                                </div>

                                <!-- Instructors List -->
                                <div class="max-h-64 overflow-y-auto p-2" id="instructors-container">
                                    @foreach($instructors as $instructor)
                                        <div class="instructor-item p-3 hover:bg-pink-50 rounded-lg transition-colors mb-2">
                                            <label class="flex items-center justify-between gap-4">
                                                <div class="flex items-center gap-3">
                                                    <input type="checkbox" name="instructors[]" value="{{ $instructor->id }}"
                                                           class="instructor-checkbox rounded border-[#FFC8FB] text-[#FF92C2] focus:ring-pink-200">
                                                    <div>
                                                        <span class="font-medium block">{{ $instructor->user->name }}</span>
                                                        <span class="text-sm text-gray-500">{{ $instructor->department }}</span>
                                                    </div>
                                                </div>
                                                <button type="button" 
                                                        class="view-subjects text-sm text-[#FF92C2] hover:text-[#ff6fb5]"
                                                        data-subjects='@json($instructor->subjects)'>
                                                    <i class="fas fa-book"></i> View Subjects
                                                </button>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-[#FF92C2] mb-1">Assign Students</label>
                            <div class="bg-white rounded-lg border border-[#FFC8FB] overflow-hidden">
                                <!-- Search Bar -->
                                <div class="p-4 border-b border-[#FFC8FB]">
                                    <div class="relative">
                                        <input type="text" id="student-search" 
                                               placeholder="Search students by name or email..." 
                                               class="w-full pl-10 pr-4 py-2 rounded-lg border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selection Controls -->
                                <div class="px-4 py-2 bg-gray-50 border-b border-[#FFC8FB] flex justify-between items-center">
                                    <div class="flex items-center gap-2">
                                        <button type="button" id="select-all-students" 
                                                class="text-sm text-[#FF92C2] hover:text-[#ff6fb5] flex items-center gap-1"> Select All
                                        </button>
                                        <button type="button" id="deselect-all-students" 
                                                class="text-sm text-[#FF92C2] hover:text-[#ff6fb5] flex items-center gap-1">Deselect All
                                        </button>
                                    </div>
                                    <span class="text-xs text-gray-500" id="student-counter">
                                        0 selected
                                    </span>
                                </div>

                                <!-- Students List -->
                                <div class="max-h-64 overflow-y-auto p-2" id="students-container">
                                    @foreach($students as $student)
                                        <div class="student-item p-3 hover:bg-pink-50 rounded-lg transition-colors mb-2">
                                            <label class="flex items-center gap-3">
                                                <input type="checkbox" name="students[]" value="{{ $student->id }}"
                                                       class="student-checkbox rounded border-[#FFC8FB] text-[#FF92C2] focus:ring-pink-200">
                                                <div>
                                                    <span class="font-medium block">{{ $student->user->name }}</span>
                                                    <span class="text-sm text-gray-500">{{ $student->user->email }}</span>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
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
        // Search functionality for instructors
        document.getElementById('instructor-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const instructors = document.querySelectorAll('.instructor-item');
            
            instructors.forEach(item => {
                const name = item.querySelector('.font-medium').textContent.toLowerCase();
                const department = item.querySelector('.text-gray-500').textContent.toLowerCase();
                item.style.display = name.includes(searchTerm) || department.includes(searchTerm) ? '' : 'none';
            });
        });

        // Search functionality for students
        document.getElementById('student-search').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const students = document.querySelectorAll('.student-item');
            
            students.forEach(item => {
                const name = item.querySelector('.font-medium').textContent.toLowerCase();
                const email = item.querySelector('.text-gray-500').textContent.toLowerCase();
                item.style.display = name.includes(searchTerm) || email.includes(searchTerm) ? '' : 'none';
            });
        });

        // Selection counters update
        function updateCounters() {
            document.getElementById('instructor-counter').textContent = 
                document.querySelectorAll('.instructor-checkbox:checked').length + ' selected';
            document.getElementById('student-counter').textContent = 
                document.querySelectorAll('.student-checkbox:checked').length + ' selected';
        }

        // Add event listeners for checkboxes
        document.querySelectorAll('.instructor-checkbox, .student-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateCounters);
        });

        // Select/Deselect all functionality
        document.getElementById('select-all-instructors').addEventListener('click', () => {
            document.querySelectorAll('.instructor-checkbox:not(:disabled)').forEach(cb => cb.checked = true);
            updateCounters();
        });

        document.getElementById('deselect-all-instructors').addEventListener('click', () => {
            document.querySelectorAll('.instructor-checkbox:not(:disabled)').forEach(cb => cb.checked = false);
            updateCounters();
        });

        document.getElementById('select-all-students').addEventListener('click', () => {
            document.querySelectorAll('.student-checkbox:not(:disabled)').forEach(cb => cb.checked = true);
            updateCounters();
        });

        document.getElementById('deselect-all-students').addEventListener('click', () => {
            document.querySelectorAll('.student-checkbox:not(:disabled)').forEach(cb => cb.checked = false);
            updateCounters();
        });


        // View subjects modal functionality
        document.querySelectorAll('.view-subjects').forEach(button => {
            button.addEventListener('click', function() {
                const subjects = JSON.parse(this.dataset.subjects);
                let subjectsList = subjects.map(s => `<li class="py-1">${s.subject_name}</li>`).join('');
                
                // Create modal
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center';
                modal.innerHTML = `
                    <div class="bg-white p-4 rounded-lg shadow-xl max-w-md w-full mx-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-[#FF92C2]">Assigned Subjects</h3>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <ul class="list-disc list-inside text-gray-600">
                            ${subjects.length ? subjectsList : '<li class="py-1">No subjects assigned</li>'}
                        </ul>
                    </div>
                `;
                
                document.body.appendChild(modal);
                modal.querySelector('button').onclick = () => modal.remove();
                modal.onclick = e => {
                    if (e.target === modal) modal.remove();
                };
            });
        });

        // Initialize counters
        updateCounters();
    </script>
</x-app-layout>


