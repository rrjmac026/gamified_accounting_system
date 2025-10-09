<x-app-layout>
    <style>
        .star-display {
            display: inline-flex;
            gap: 4px;
        }

        .star-display svg {
            width: 32px;
            height: 32px;
            transition: all 0.2s ease;
        }

        .star-display .filled {
            fill: #FF6B35;
        }

        .star-display .empty {
            fill: #E0E0E0;
        }

        .dark .star-display .empty {
            fill: #666;
        }

        .rating-label {
            display: inline-block;
            margin-left: 12px;
            padding: 4px 12px;
            background: #FF92C2;
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        @media (max-width: 640px) {
            .star-display svg {
                width: 28px;
                height: 28px;
            }
        }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-xl sm:rounded-2xl">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-[#FF92C2] to-[#ff6fb5] p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-3xl font-bold mb-2">Evaluation Details</h2>
                            <p class="text-pink-100">Review submitted feedback</p>
                        </div>
                        <div class="hidden md:block">
                            <svg class="w-16 h-16 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <!-- Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white dark:bg-[#4a4949] p-4 rounded-xl border border-[#FFC8FB]/30">
                            <h3 class="text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Student
                            </h3>
                            <p class="text-gray-700 dark:text-[#FFC8FB] font-medium">{{ $evaluation->student->user->name }}</p>
                        </div>
                        
                        <div class="bg-white dark:bg-[#4a4949] p-4 rounded-xl border border-[#FFC8FB]/30">
                            <h3 class="text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Instructor
                            </h3>
                            <p class="text-gray-700 dark:text-[#FFC8FB] font-medium">{{ $evaluation->instructor->user->name }}</p>
                        </div>

                        <div class="bg-white dark:bg-[#4a4949] p-4 rounded-xl border border-[#FFC8FB]/30">
                            <h3 class="text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Course
                            </h3>
                            <p class="text-gray-700 dark:text-[#FFC8FB] font-medium">{{ $evaluation->course->course_name }}</p>
                        </div>

                        <div class="bg-white dark:bg-[#4a4949] p-4 rounded-xl border border-[#FFC8FB]/30">
                            <h3 class="text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-2 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Submitted At
                            </h3>
                            <p class="text-gray-700 dark:text-[#FFC8FB] font-medium">{{ $evaluation->submitted_at->format('F j, Y g:i A') }}</p>
                        </div>
                    </div>

                    <!-- Evaluation Responses -->
                    <div class="space-y-6 mb-8">
                        <div class="border-b border-[#FFC8FB]/30 pb-4">
                            <h3 class="text-xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Evaluation Responses
                            </h3>
                        </div>

                        <div class="space-y-4">
                            @php
                                $ratingLabels = [
                                    1 => 'Poor',
                                    2 => 'Fair',
                                    3 => 'Good',
                                    4 => 'Very Good',
                                    5 => 'Excellent'
                                ];
                            @endphp

                            @foreach($evaluation->responses as $criterion => $rating)
                                <div class="bg-white dark:bg-[#4a4949] p-6 rounded-xl border border-[#FFC8FB]/30 shadow-sm">
                                    <h4 class="font-medium text-gray-700 dark:text-[#FFC8FB] mb-4">{{ $criterion }}</h4>
                                    
                                    <div class="flex items-center flex-wrap gap-2">
                                        <div class="star-display">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg viewBox="0 0 24 24" class="{{ $i <= $rating ? 'filled' : 'empty' }}">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="rating-label">{{ $ratingLabels[$rating] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="space-y-4">
                        <div class="border-b border-[#FFC8FB]/30 pb-4">
                            <h3 class="text-xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                </svg>
                                Additional Comments
                            </h3>
                        </div>
                        
                        <div class="bg-white dark:bg-[#4a4949] p-6 rounded-xl border border-[#FFC8FB]/30 shadow-sm">
                            <p class="text-gray-700 dark:text-[#FFC8FB] whitespace-pre-line leading-relaxed">{{ $evaluation->comments }}</p>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="flex justify-end mt-8 pt-6 border-t border-[#FFC8FB]/30">
                        <a href="{{ route('evaluations.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-[#FF92C2] to-[#ff6fb5] hover:from-[#ff6fb5] hover:to-[#ff4da6] text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>