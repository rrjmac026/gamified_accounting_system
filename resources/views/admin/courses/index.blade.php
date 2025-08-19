<x-app-layout>
    <div class="flex justify-end px-8 mt-4">
        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#FF92C2] hover:bg-[#ff6fb5] rounded-lg shadow-sm hover:shadow transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>Add Course
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Course Code</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Name</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Department</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Duration</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Students</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Status</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                                @forelse ($courses as $course)
                                    <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm text-gray-700">{{ $course->course_code }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-700">{{ $course->course_name }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-700">{{ $course->department }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-700">{{ $course->duration_years }} years</td>
                                        <td class="py-4 px-6 text-sm text-gray-700">{{ $course->students_count ?? 0 }}</td>
                                        <td class="py-4 px-6 text-sm">
                                            @if($course->is_active)
                                                <span class="text-green-600 font-medium">Active</span>
                                            @else
                                                <span class="text-red-600 font-medium">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm space-x-2">
                                            <a href="{{ route('admin.courses.show', $course) }}" class="text-[#FF6FB5] hover:text-[#e8559d]">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.courses.edit', $course) }}" class="text-[#FF6FB5] hover:text-[#e8559d]">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                onclick="return confirmAction('Are you sure you want to delete this course?', 'delete-course-{{ $course->id }}')"
                                                class="text-red-500 hover:text-red-700 transition-colors duration-150">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-course-{{ $course->id }}" action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 px-6 text-sm text-center text-gray-600">
                                            No courses found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($courses->hasPages())
                        <div class="mt-4">
                            {{ $courses->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmAction(message, formId) {
            if (confirm(message)) {
                document.getElementById(formId).submit();
            }
            return false;
        }
    </script>
</x-app-layout>
