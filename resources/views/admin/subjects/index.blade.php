@section('title', 'Subjects Management')
<x-app-layout>
    <div class="flex justify-end px-4 sm:px-8 mt-4">
        <a href="{{ route('admin.subjects.create') }}" 
           class="w-full sm:w-auto inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#FF92C2] hover:bg-[#ff6fb5] rounded-lg shadow-sm hover:shadow transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>Add Subject
        </a>
    </div>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-700">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-[#FFC8FB]">
                                <thead class="bg-[#FFC8FB]">
                                    <tr>
                                        <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-pink-900">Code</th>
                                        <th class="hidden sm:table-cell py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-pink-900">Name</th>
                                        <th class="hidden md:table-cell py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-pink-900">Units</th>
                                        <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-pink-900">Semester</th>
                                        <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-pink-900">Year</th>
                                        <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-pink-900">Status</th>
                                        <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-pink-900">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                                    @forelse($subjects as $subject)
                                        <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                            <td class="py-4 px-3 sm:px-6 text-sm text-gray-700">{{ $subject->subject_code }}</td>
                                            <td class="hidden sm:table-cell py-4 px-3 sm:px-6 text-sm text-gray-700">{{ $subject->subject_name }}</td>
                                            <td class="hidden md:table-cell py-4 px-3 sm:px-6 text-sm text-gray-700">{{ $subject->units }}</td>
                                            <td class="py-4 px-3 sm:px-6 text-sm text-gray-700">{{ $subject->semester }}</td>
                                            <td class="py-4 px-3 sm:px-6 text-sm text-gray-700">{{ $subject->academic_year }}</td>
                                            <td class="py-4 px-3 sm:px-6 text-sm">
                                                @if($subject->is_active)
                                                    <span class="text-green-600 font-medium">Active</span>
                                                @else
                                                    <span class="text-red-600 font-medium">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-3 sm:px-6 text-sm space-x-2">
                                                <a href="{{ route('admin.subjects.show', $subject->id) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.subjects.edit', $subject) }}" 
                                                   class="text-[#FF6FB5] hover:text-[#e8559d]">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                    onclick="return confirmAction('Are you sure you want to delete this subject?', 'delete-subject-{{ $subject->id }}')"
                                                    class="text-red-500 hover:text-red-700 transition-colors duration-150">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <form id="delete-subject-{{ $subject->id }}" action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-4 px-3 sm:px-6 text-sm text-center text-gray-600">
                                                No subjects found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
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
