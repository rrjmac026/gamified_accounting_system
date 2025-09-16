<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6 text-gray-700 dark:text-[#FFC8FB]">
                    <!-- Header -->
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-[#FF92C2] dark:text-[#FFC8FB]">My Sections</h2>
                        <p class="text-gray-600 dark:text-[#FFC8FB] opacity-80">View and manage your assigned sections</p>
                    </div>

                    <!-- Sections Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($sections as $section)
                            <div class="bg-white dark:bg-[#4a4949] rounded-xl shadow-sm border border-[#FFC8FB]/30 overflow-hidden hover:shadow-md transition-shadow duration-300">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-xl font-semibold text-[#595758] dark:text-[#FFC8FB]">
                                            {{ $section->name }}
                                        </h3>
                                        <span class="px-3 py-1 text-xs font-medium bg-[#FF92C2]/10 text-[#FF92C2] rounded-full">
                                            {{ $section->section_code }}
                                        </span>
                                    </div>

                                    <div class="space-y-3">
                                        <div class="flex items-center text-gray-600 dark:text-[#FFC8FB]">
                                            <i class="fas fa-users w-5 h-5 mr-2"></i>
                                            <span>{{ $section->students->count() }} Students</span>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <a href="{{ route('instructors.sections.show', $section->id) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-[#FF92C2] hover:bg-[#ff6fb5] text-white rounded-lg transition-colors duration-200">
                                            <i class="fas fa-eye mr-2"></i>
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="text-center py-12">
                                    <i class="fas fa-chalkboard text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-500 dark:text-[#FFC8FB] mb-2">No Sections Assigned</h3>
                                    <p class="text-gray-400 dark:text-[#FFC8FB]/70">You haven't been assigned to any sections yet.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
