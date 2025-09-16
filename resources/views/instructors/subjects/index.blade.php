<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-6 text-gray-700">
                    <h2 class="text-3xl font-bold text-[#FF92C2] mb-6">My Subjects</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($subjects as $subject)
                            <div class="bg-white rounded-xl shadow-sm border border-[#FFC8FB]/30 overflow-hidden hover:shadow-md transition-shadow duration-300">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-xl font-semibold text-[#595758]">
                                            {{ $subject->subject_code }}
                                        </h3>
                                        <span class="px-3 py-1 text-xs font-medium bg-[#FF92C2]/10 text-[#FF92C2] rounded-full">
                                            {{ $subject->semester }} Semester
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 mb-4">{{ $subject->subject_name }}</p>

                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-users w-5 h-5 mr-2"></i>
                                            <span>{{ $subject->students->count() }} Students</span>
                                        </div>
                                    </div>

                                    <div class="flex justify-end">
                                        <a href="{{ route('instructors.subjects.show', $subject->id) }}" 
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
                                    <i class="fas fa-book text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Subjects Assigned</h3>
                                    <p class="text-gray-500">You haven't been assigned to any subjects yet.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>