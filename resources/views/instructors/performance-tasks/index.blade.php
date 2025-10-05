<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Performance Tasks
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Create New Task -->
            <div class="mb-4 flex justify-end">
                <a href="{{ route('instructors.performance-tasks.create') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">
                    + Create New Task
                </a>
            </div>

            <!-- Task Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($tasks->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Step</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Description</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">XP</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Attempts</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Submissions</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Created</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tasks as $task)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $task->title }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        Step {{ $task->step }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ Str::limit($task->description, 40) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $task->xp_reward }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ $task->max_attempts }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ $task->submissions->count() }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ $task->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('instructors.performance-tasks.edit', $task->id) }}"
                                           class="text-blue-600 hover:text-blue-800">Edit</a>
                                        <form action="{{ route('instructors.performance-tasks.destroy', $task) }}"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this task?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="p-6 text-center text-gray-500">
                        <p class="mb-4">No performance tasks created yet.</p>
                        <a href="{{ route('instructors.performance-tasks.create') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">
                            Create Your First Task
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
