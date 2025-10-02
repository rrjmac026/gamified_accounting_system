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
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tasks List -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($tasks as $task)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                {{ $task->title }}
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                {{ Str::limit($task->description, 100) }}
                            </p>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">XP Reward:</span>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full font-semibold">
                                        {{ $task->xp_reward }} XP
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Max Attempts:</span>
                                    <span class="font-medium text-gray-900">{{ $task->max_attempts }}</span>
                                </div>
                                @php
                                    $myAttempts = $task->submissions()
                                        ->where('student_id', auth()->id())
                                        ->count();
                                @endphp
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Your Attempts:</span>
                                    <span class="font-medium {{ $myAttempts >= $task->max_attempts ? 'text-red-600' : 'text-blue-600' }}">
                                        {{ $myAttempts }} / {{ $task->max_attempts }}
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('students.performance-tasks.show', $task->id) }}" 
                               class="block w-full text-center px-4 py-2 {{ $myAttempts >= $task->max_attempts ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700' }} text-white rounded shadow transition-colors duration-200">
                                {{ $myAttempts >= $task->max_attempts ? 'No Attempts Left' : 'Start Task' }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-lg shadow p-8 text-center">
                        <p class="text-gray-500 text-lg">No performance tasks available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>