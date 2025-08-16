<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700">
                    
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="mb-4 px-4 py-3 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if (session('error'))
                        <div class="mb-4 px-4 py-3 bg-red-100 border border-red-200 text-red-700 rounded-md">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <strong>Import Failed:</strong><br>
                                    {{ session('error') }}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="mb-4 px-4 py-3 bg-red-100 border border-red-200 text-red-700 rounded-md">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <strong>Validation Errors:</strong>
                                    <ul class="mt-1 ml-4 list-disc">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Import Form --}}
                    <div class="mb-6 p-4 bg-white rounded-lg border border-pink-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Import Students</h3>
                        <form action="{{ route('admin.student.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                                        Select CSV or Excel File
                                    </label>
                                    <input type="file" 
                                           name="file" 
                                           id="file"
                                           accept=".csv,.xlsx,.xls"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                           required>
                                </div>
                                <div class="mt-6">
                                    <button type="submit" 
                                            class="px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-colors duration-200">
                                        Import Students
                                    </button>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Accepted formats: CSV, XLSX, XLS. Expected columns: name, email, course, year_level, section, password
                            </p>
                        </form>
                    </div>

                    {{-- Students Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Name</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Email</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Student ID</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Course</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Year Level</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Section</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Status</th>
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
                                            {{ $student->user->id_number ?? $student->id }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            {{ $student->course ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            {{ $student->year_level ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            {{ $student->section ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            @if($student->user && $student->user->is_active)
                                                <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-block bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-8 px-6 text-sm text-center text-gray-600">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                </svg>
                                                <p class="text-lg font-medium text-gray-900 mb-1">No students found</p>
                                                <p class="text-gray-600">Import a CSV or Excel file to get started</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($students->hasPages())
                        <div class="mt-6">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>