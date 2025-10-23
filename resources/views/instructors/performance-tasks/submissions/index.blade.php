<x-app-layout>
    <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">All Performance Task Submissions</h2>
            <p class="text-sm text-gray-500 mt-1">Overview of all student submissions across all tasks</p>
        </div>

        <!-- Task Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($tasks as $task)
                @php
                    $stats = $taskStats[$task->id];
                @endphp
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <!-- Task Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    {{ $task->title }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    {{ Str::limit($task->description, 80) }}
                                </p>
                            </div>
                        </div>

                        <!-- Submission Stats -->
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Total Submissions:</span>
                                <span class="font-semibold text-gray-900">{{ $stats['total_submissions'] }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Students Submitted:</span>
                                <span class="font-semibold text-gray-900">{{ $stats['unique_students'] }}</span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Completed Steps:</span>
                                <span class="font-semibold text-green-600">{{ $stats['completed_steps'] }}</span>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @if($stats['total_submissions'] > 0)
                            <div class="mb-4">
                                <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                    <span>Progress</span>
                                    <span>{{ number_format($stats['progress_percent'], 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full transition-all" 
                                         style="width: {{ $stats['progress_percent'] }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <a href="{{ route('instructors.performance-tasks.submissions.show', $task->id) }}"
                           class="block w-full text-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            View Student Submissions
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <i class="fas fa-tasks text-2xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Performance Tasks</h3>
                        <p class="text-gray-500 mb-4">You haven't created any performance tasks yet.</p>
                        <a href="{{ route('instructors.performance-tasks.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Create Performance Task
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>