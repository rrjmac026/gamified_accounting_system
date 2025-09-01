<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">
                <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] mb-6">Submit Feedback</h2>

                <form action="{{ route('students.feedback.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Task</label>
                        <select name="task_id" required class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                            <option value="">Select Task</option>
                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}">{{ $task->title }}</option>
                            @endforeach
                        </select>
                        @error('task_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Your Feedback</label>
                        <textarea name="content" rows="4" required 
                                  class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200"
                                  placeholder="Share your thoughts about this task..."></textarea>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Rating (Optional)</label>
                        <div class="flex space-x-4">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="rating" value="{{ $i }}" class="text-[#FF92C2] focus:ring-pink-200">
                                    <span class="text-gray-700 dark:text-[#FFC8FB]">{{ $i }}</span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('students.feedback.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
