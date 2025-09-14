<x-app-layout>
    <div class="max-w-6xl mx-auto py-6 px-4">
        <!-- Page Heading -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Quizzes</h2>

            <!-- Upload CSV Button -->
            <form action="{{ route('instructors.quizzes.import', $taskId ?? 1) }}" 
                  method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file" accept=".csv,.xlsx" 
                       class="border rounded px-2 py-1 text-sm">
                <button type="submit" 
                        class="bg-pink-500 hover:bg-pink-600 text-white text-sm px-4 py-2 rounded-lg shadow">
                    Upload CSV
                </button>
            </form>
        </div>

        <!-- Quiz Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full text-sm text-left border-collapse">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Question</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Points</th>
                        <th class="px-4 py-3">Created At</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $quiz->question_text }}</td>
                            <td class="px-4 py-3 capitalize">{{ str_replace('_', ' ', $quiz->type) }}</td>
                            <td class="px-4 py-3">{{ $quiz->points }}</td>
                            <td class="px-4 py-3">{{ $quiz->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('instructors.quizzes.show', $quiz->id) }}" 
                                   class="text-blue-600 hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                No quizzes found. Upload a CSV to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
