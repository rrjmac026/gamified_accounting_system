<x-app-layout>
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
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Name</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Email</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Employee ID</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Department</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Specialization</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Subjects</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Tasks</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Students</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($instructors as $instructor)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-amber-800/50 transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $instructor->user->full_name ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $instructor->user->email ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $instructor->employee_id }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $instructor->department ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $instructor->specialization ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $instructor->stats['total_subjects'] ?? 0 }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $instructor->stats['total_tasks'] ?? 0 }}
                                            @if ($instructor->stats['active_tasks'] > 0)
                                                <span class="ml-1 inline-block bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full dark:bg-green-900 dark:text-green-200">
                                                    {{ $instructor->stats['active_tasks'] }} active
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $instructor->stats['total_students'] ?? 0 }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200 space-x-2">
                                            <a href="{{ route('admin.instructors.show', $instructor->id) }}" class="text-indigo-600 hover:underline">View</a>
                                            <a href="{{ route('admin.instructors.edit', $instructor->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                                            <form action="{{ route('admin.instructors.destroy', $instructor->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-4 px-6 text-sm text-center text-gray-600 dark:text-gray-400">
                                            No instructors found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $instructors->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
