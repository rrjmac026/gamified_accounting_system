<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6 text-gray-700 dark:text-[#FFC8FB]">
                    <!-- Header -->
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h2 class="text-3xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] mb-2">Section: {{ $section->name }}</h2>
                            <p class="text-gray-600 dark:text-[#FFC8FB] opacity-80">Section Code: {{ $section->section_code }}</p>
                        </div>
                        <a href="{{ route('instructors.sections.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Sections
                        </a>
                    </div>

                    <!-- Section Stats -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white dark:bg-[#4a4949] p-6 rounded-xl shadow-sm border border-[#FFC8FB]/30">
                            <div class="flex items-center">
                                <i class="fas fa-users text-[#FF92C2] text-2xl mr-4"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-[#FFC8FB]/80">Total Students</p>
                                    <p class="text-2xl font-bold text-[#FF92C2] dark:text-[#FFC8FB]">{{ $section->students->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Students List -->
                    <div>
                        <h3 class="text-xl font-semibold text-[#595758] dark:text-[#FFC8FB] mb-4">Students</h3>
                        <div class="bg-white dark:bg-[#4a4949] rounded-xl shadow-sm border border-[#FFC8FB]/30 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-[#FFC8FB]/50">
                                    <thead class="bg-gradient-to-r from-[#FFC8FB]/80 to-[#FFC8FB]/60">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#595758]">Name</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#595758]">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-[#595758]">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#FFC8FB]/30">
                                        @forelse($section->students as $student)
                                            <tr class="hover:bg-[#FFF6FD] dark:hover:bg-[#6a6869] transition-colors">
                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-[#FFC8FB]">
                                                    {{ $student->user->name }}
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-[#FFC8FB]">
                                                    {{ $student->user->email }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                        Active
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                                    No students enrolled in this section
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
    </div>
</x-app-layout>