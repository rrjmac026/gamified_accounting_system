<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Performance Task Answer Sheets</h1>

        <table class="min-w-full bg-white rounded shadow">
            <thead class="bg-pink-100 text-gray-700">
                <tr>
                    <th class="p-3 text-left">Title</th>
                    <th class="p-3 text-center">Steps Created</th>
                    <th class="p-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr class="border-b hover:bg-pink-50 transition">
                        <td class="p-3">{{ $task->title }}</td>
                        <td class="p-3 text-center">{{ $task->answer_sheets_count }}/10</td>
                        <td class="p-3 text-right">
                            <a href="{{ route('instructors.performance-tasks.answer-sheets.show', $task->id) }}"
                               class="text-pink-600 hover:underline">Manage</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-gray-500">
                            No performance tasks found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
