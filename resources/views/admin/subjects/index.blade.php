<x-app-layout>
    
    <div class="flex justify-end px-8 mt-4">
        <a href="{{ route('admin.subjects.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 dark:bg-amber-500 dark:hover:bg-amber-600 rounded-lg shadow-sm hover:shadow transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>Add Subject
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700 dark:text-gray-100">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md dark:bg-green-900 dark:border-green-800 dark:text-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-700">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Code</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Name</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Instructor</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Semester</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Year</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Status</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($subjects as $subject)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-amber-800/50 transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">{{ $subject->subject_code }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">{{ $subject->subject_name }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">{{ $subject->instructor?->user->name ?? 'N/A' }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">{{ $subject->semester }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">{{ $subject->academic_year }}</td>
                                        <td class="py-4 px-6 text-sm">
                                            @if($subject->is_active)
                                                <span class="text-green-600 dark:text-green-400 font-medium">Active</span>
                                            @else
                                                <span class="text-red-600 dark:text-red-400 font-medium">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm space-x-2">
                                            <a href="{{ route('subjects.edit', $subject) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                onclick="return confirmAction('Are you sure you want to delete this subject?', 'delete-subject-{{ $subject->id }}')"
                                                class="text-red-700 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-subject-{{ $subject->id }}" action="{{ route('subjects.destroy', $subject) }}" method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 px-6 text-sm text-center text-gray-600 dark:text-gray-400">
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

    <script>
        function confirmAction(message, formId) {
            if (confirm(message)) {
                document.getElementById(formId).submit();
            }
            return false;
        }
    </script>
</x-app-layout>
