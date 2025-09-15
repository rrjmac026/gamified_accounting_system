<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6 text-gray-700 dark:text-[#FFC8FB]">
                    <!-- Header Section with Create Button -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                        <div>
                            <h2 class="text-3xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] mb-2">Course Evaluations</h2>
                            <p class="text-gray-600 dark:text-[#FFC8FB] opacity-80">Manage and view all course evaluation submissions</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('evaluations.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-[#FF92C2] hover:bg-[#ff6fb5] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                New Evaluation
                            </a>
                        </div>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-400 rounded-lg shadow-sm">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <p class="text-green-800 font-medium">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white dark:bg-[#4a4949] p-6 rounded-xl shadow-sm border border-[#FFC8FB]/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-[#FFC8FB]/80">Total Evaluations</p>
                                    <p class="text-3xl font-bold text-[#FF92C2] dark:text-[#FFC8FB]">{{ $evaluations->total() }}</p>
                                </div>
                                <div class="bg-[#FF92C2]/10 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-[#FF92C2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#4a4949] p-6 rounded-xl shadow-sm border border-[#FFC8FB]/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-[#FFC8FB]/80">This Month</p>
                                    <p class="text-3xl font-bold text-[#FF92C2] dark:text-[#FFC8FB]">{{ $evaluations->where('submitted_at', '>=', now()->startOfMonth())->count() }}</p>
                                </div>
                                <div class="bg-[#FF92C2]/10 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-[#FF92C2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-[#4a4949] p-6 rounded-xl shadow-sm border border-[#FFC8FB]/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-[#FFC8FB]/80">Average Rating</p>
                                    <p class="text-3xl font-bold text-[#FF92C2] dark:text-[#FFC8FB]">4.2</p>
                                </div>
                                <div class="bg-[#FF92C2]/10 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-[#FF92C2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="bg-white dark:bg-[#4a4949] rounded-xl shadow-sm border border-[#FFC8FB]/30 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-[#FFC8FB]/50">
                                <thead class="bg-gradient-to-r from-[#FFC8FB]/80 to-[#FFC8FB]/60">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#595758] uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#595758] uppercase tracking-wider">Instructor</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#595758] uppercase tracking-wider">Course</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-[#595758] uppercase tracking-wider">Date Submitted</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#FFC8FB]/30">
                                    @forelse($evaluations as $evaluation)
                                        <tr class="hover:bg-[#FFF6FD] dark:hover:bg-[#6a6869] transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-10 h-10 bg-[#FF92C2] rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                        {{ substr($evaluation->student->user->name, 0, 2) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-[#FFC8FB]">{{ $evaluation->student->user->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <p class="text-sm text-gray-900 dark:text-[#FFC8FB]">{{ $evaluation->instructor->user->name }}</p>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-3 py-1 text-xs font-medium bg-[#FF92C2]/10 text-[#FF92C2] rounded-full">
                                                    {{ $evaluation->course->course_name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-[#FFC8FB]">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $evaluation->submitted_at->format('M d, Y H:i') }}
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <h3 class="text-lg font-medium text-gray-500 dark:text-[#FFC8FB] mb-2">No evaluations found</h3>
                                                    <p class="text-gray-400 dark:text-[#FFC8FB]/70 mb-4">Get started by creating your first evaluation</p>
                                                    <a href="{{ route('evaluations.create') }}" 
                                                       class="inline-flex items-center px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5] transition-colors">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                        Create Evaluation
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    @if($evaluations->hasPages())
                        <div class="mt-6 flex justify-center">
                            <div class="bg-white dark:bg-[#4a4949] rounded-lg shadow-sm border border-[#FFC8FB]/30 p-1">
                                {{ $evaluations->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>