<x-app-layout>

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
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Name</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Email</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Student ID</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Course</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Year Level</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Subjects</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Tasks</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                                @forelse ($students as $student)
                                    <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            {{ $student->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            {{ $student->user->email ?? 'N/A' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            {{ $student->student_id }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            {{ $student->course ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            {{ $student->year_level ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            {{ $student->subjects_count ?? 0 }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            {{ $student->tasks_count ?? 0 }}
                                            @if ($student->active_tasks_count > 0)
                                                <span class="ml-1 inline-block bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full">
                                                    {{ $student->active_tasks_count }} active
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-4 px-6 text-sm text-center text-gray-600">
                                            No students found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $students->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
