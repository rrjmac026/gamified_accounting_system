<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">
                <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] mb-6">Create New User</h2>

                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">ID Number</label>
                            <input type="text" name="id_number" value="{{ old('id_number') }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Role</label>
                            <select name="role" id="role" onchange="toggleRoleFields()" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" required>
                                <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                                <option value="instructor" {{ old('role') == 'instructor' ? 'selected' : '' }}>Instructor</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <!-- Student Fields -->
                        <div id="studentFields" class="col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Course</label>
                                <input type="text" name="course" value="{{ old('course') }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Year Level</label>
                                <input type="number" name="year_level" value="{{ old('year_level') }}" min="1" max="5" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Section</label>
                                <input type="text" name="section" value="{{ old('section') }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                            </div>
                        </div>

                        <!-- Instructor Fields -->
                        <div id="instructorFields" class="col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Employee ID</label>
                                <input type="text" name="employee_id" value="{{ old('employee_id') }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Department</label>
                                <input type="text" name="department" value="{{ old('department') }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Specialization</label>
                                <input type="text" name="specialization" value="{{ old('specialization') }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Password</label>
                            <input type="password" name="password" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" required>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleRoleFields() {
            const role = document.getElementById('role').value;
            const studentFields = document.getElementById('studentFields');
            const instructorFields = document.getElementById('instructorFields');

            // Hide all role-specific fields first
            studentFields.style.display = 'none';
            instructorFields.style.display = 'none';

            // Show fields based on selected role
            if (role === 'student') {
                studentFields.style.display = 'grid';
            } else if (role === 'instructor') {
                instructorFields.style.display = 'grid';
            }

            // Update required attributes
            const studentInputs = studentFields.querySelectorAll('input');
            const instructorInputs = instructorFields.querySelectorAll('input');

            studentInputs.forEach(input => input.required = (role === 'student'));
            instructorInputs.forEach(input => input.required = (role === 'instructor'));
        }

        // Call on page load to set initial state
        document.addEventListener('DOMContentLoaded', function() {
            toggleRoleFields();
        });
    </script>
</x-app-layout>
