<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FFC8FB]">Feedback Details</h2>
                    <a href="{{ route('students.feedback.index') }}" 
                       class="text-[#FF92C2] hover:text-[#ff6fb5]">
                        Back to List
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Task</h3>
                        <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $feedback->task->title }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Type</h3>
                        <p class="text-gray-700 dark:text-[#FFC8FB] capitalize">{{ $feedback->feedback_type }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Generated At</h3>
                        <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $feedback->generated_at->format('F j, Y g:i A') }}</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-2">Feedback</h3>
                        <div class="bg-white dark:bg-[#4a4949] p-4 rounded-lg">
                            <p class="text-gray-700 dark:text-[#FFC8FB] whitespace-pre-line">{{ $feedback->feedback_text }}</p>
                        </div>
                    </div>

                    @if($feedback->recommendations)
                        <div>
                            <h3 class="text-lg font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-2">Recommendations</h3>
                            <div class="bg-white dark:bg-[#4a4949] p-4 rounded-lg">
                                <ul class="list-disc list-inside space-y-2">
                                    @foreach($feedback->recommendations as $recommendation)
                                        <li class="text-gray-700 dark:text-[#FFC8FB]">{{ $recommendation }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
