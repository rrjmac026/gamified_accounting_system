<x-app-layout>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700 dark:text-[#FFC8FB]">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758]">Student</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758]">Task</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758]">Type</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758]">Date</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#FFF6FD] dark:bg-[#595758] divide-y divide-[#FFC8FB]">
                                @forelse ($feedbacks as $feedback)
                                    <tr class="hover:bg-[#FFD9FF] dark:hover:bg-[#6a6869] transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm">{{ $feedback->student->user->name }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $feedback->task->title }}</td>
                                        <td class="py-4 px-6 text-sm capitalize">{{ $feedback->feedback_type }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $feedback->created_at->format('Y-m-d H:i') }}</td>
                                        <td class="py-4 px-6 text-sm space-x-2">
                                            <a href="{{ route('feedback-records.show', $feedback) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('feedback-records.edit', $feedback) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-6 text-sm text-center">No feedback records found</td>
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
