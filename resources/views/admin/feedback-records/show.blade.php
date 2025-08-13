<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">
                <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] mb-6">Feedback Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Student</label>
                        <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $feedbackRecord->student->user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Task</label>
                        <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $feedbackRecord->task->title }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Feedback Type</label>
                        <p class="text-gray-700 dark:text-[#FFC8FB] capitalize">{{ $feedbackRecord->feedback_type }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Date Created</label>
                        <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $feedbackRecord->created_at->format('F j, Y g:i A') }}</p>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Feedback Content</label>
                        <p class="text-gray-700 dark:text-[#FFC8FB] whitespace-pre-line">{{ $feedbackRecord->content }}</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-6">
                    <a href="{{ route('feedback-records.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Back</a>
                    <a href="{{ route('feedback-records.edit', $feedbackRecord) }}" class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">Edit</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
