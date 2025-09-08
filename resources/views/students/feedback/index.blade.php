<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FFC8FB]">My Feedback Records</h2>
                        <a href="{{ route('students.feedback.create') }}" 
                           class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                            Submit New Feedback
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md dark:bg-green-900 dark:border-green-800 dark:text-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-[#FFC8FB] dark:bg-[#595758] border-b border-[#FFC8FB] dark:border-[#6a6869]">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Task</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Rating</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Type</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Date</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Status</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#FFC8FB] dark:divide-[#6a6869]">
                                @forelse($feedbacks as $feedback)
                                    <tr class="hover:bg-[#FFF6FD] dark:hover:bg-[#6a6869] transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $feedback->task->title }}
                                        </td>
                                        <td class="py-4 px-6 text-sm">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-[#FF92C2]' : 'text-gray-300 dark:text-gray-600' }}"></i>
                                                @endfor
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200 capitalize">
                                            {{ $feedback->feedback_type }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $feedback->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="py-4 px-6 text-sm">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $feedback->is_read ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                                                {{ $feedback->is_read ? 'Read' : 'Unread' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-sm">
                                            <a href="{{ route('students.feedback.show', $feedback) }}" 
                                               class="text-[#FF92C2] hover:text-[#ff6fb5] dark:hover:text-[#FFC8FB]">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-6 text-sm text-center text-gray-600 dark:text-gray-400">
                                            No feedback records found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $feedbacks->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
