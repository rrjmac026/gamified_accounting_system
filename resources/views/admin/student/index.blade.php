@section('title', 'Student Management')
<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Add Student & Import Section --}}
            <div class="flex flex-col sm:flex-row justify-between items-center mb-4 sm:mb-6 gap-4">
                <h2 class="text-lg sm:text-xl font-semibold text-[#FF92C2]">Student Management</h2>
                <div class="w-full sm:w-auto">
                    <a href="{{ route('admin.student.create') }}" 
                       class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-plus mr-2"></i>
                        Add Student
                    </a>
                </div>
            </div>
            

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
                    <div class="mb-4 sm:mb-6 p-4 bg-white rounded-lg border border-pink-200">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3">Import Students</h3>
                        <form action="{{ route('admin.student.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                <div class="w-full">
                                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                                        Select CSV File
                                    </label>
                                    <input type="file" name="file" id="file" accept=".csv,.xlsx,.xls"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent"
                                           required>
                                </div>
                                <button type="submit" 
                                        class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-colors duration-200">
                                    Import Students
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Accepted formats: CSV, XLSX, XLS. Expected columns: name, email, course, year_level, section, password
                            </p>
                        </form>
                    </div>
                    {{-- Search Bar --}}
                    <div class="mb-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <form action="{{ route('admin.student.index') }}" method="GET" class="flex w-full sm:w-1/2">
                            <div class="relative flex-grow">
                                <input type="text" 
                                    name="search" 
                                    value="{{ request('search') }}" 
                                    placeholder="Search students by name, email, or student ID..."
                                    class="w-full pl-10 pr-4 py-2 border border-[#FFC8FB] rounded-l-lg focus:ring-2 focus:ring-[#FF92C2] focus:border-[#FF92C2] text-sm sm:text-base">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                            <button type="submit" 
                                    class="px-4 py-2 bg-[#FF92C2] text-white rounded-r-lg hover:bg-[#ff6fb5] focus:outline-none focus:ring-2 focus:ring-[#FF92C2]">
                                Search
                            </button>
                        </form>

                        {{-- Optional: Reset button --}}
                        @if(request('search'))
                            <a href="{{ route('admin.student.index') }}" 
                            class="text-sm text-[#FF92C2] hover:text-[#ff6fb5]">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        @endif
                    </div>

                    {{-- Students Table --}}
                    <div class="overflow-x-auto relative sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-700">
                            <thead class="bg-[#FFC8FB] text-xs uppercase">
                                <tr>
                                    <th scope="col" class="py-3 px-4">
                                        <div class="flex items-center">
                                            Name/Email
                                        </div>
                                    </th>
                                    <th scope="col" class="hidden md:table-cell py-3 px-4">
                                        Student ID
                                    </th>
                                    <th scope="col" class="hidden sm:table-cell py-3 px-4">
                                        Course
                                    </th>
                                    <th scope="col" class="hidden lg:table-cell py-3 px-4">
                                        Year/Section
                                    </th>
                                    <th scope="col" class="py-3 px-4">
                                        Status
                                    </th>
                                    <th scope="col" class="py-3 px-4">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($students as $student)
                                    <tr class="bg-white border-b border-[#FFC8FB] hover:bg-[#FFD9FF]">
                                        <td class="py-3 px-4 font-medium">
                                            {{ $student->user->name ?? 'N/A' }}
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $student->user->email ?? 'N/A' }}
                                            </div>
                                        </td>
                                        <td class="hidden md:table-cell py-3 px-4">
                                            {{ $student->user->student_number ?? $student->student_number }}
                                        </td>
                                        <td class="hidden sm:table-cell py-3 px-4">
                                            {{ $student->course->course_name ?? '-' }}
                                        </td>
                                        <td class="hidden lg:table-cell py-3 px-4">
                                            <span class="whitespace-nowrap">
                                                Year {{ $student->year_level ?? '-' }}
                                            </span>
                                            <span class="block text-sm text-gray-500">
                                                Section {{ $student->section ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($student->user && $student->user->is_active)
                                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                                    Active
                                                </span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex flex-col sm:flex-row gap-2">
                                                <a href="{{ route('admin.student.show', $student->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="sm:hidden ml-1">View</span>
                                                </a>
                                                <a href="{{ route('admin.student.edit', $student->id) }}" 
                                                   class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="sm:hidden ml-1">Edit</span>
                                                </a>
                                                <button type="button" 
                                                        onclick="return confirmAction('Are you sure?', 'delete-student-{{ $student->id }}')"
                                                        class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="sm:hidden ml-1">Delete</span>
                                                </button>
                                                <form id="delete-student-{{ $student->id }}" 
                                                      action="{{ route('admin.student.destroy', $student->id) }}" 
                                                      method="POST" 
                                                      class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-4 text-center text-gray-500">
                                            No students found.
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