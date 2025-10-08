<x-app-layout>
    <div class="py-8 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <a href="{{ route('instructors.performance-tasks.answer-sheets.index') }}" 
               class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-3">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to tasks
            </a>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $task->title }}</h1>
            <p class="text-sm text-gray-500 mt-1">Configure answer sheets for each step</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @for ($i = 1; $i <= 10; $i++)
                @php
                    $sheet = $answerSheets->firstWhere('step', $i);
                @endphp
                <a href="{{ route('instructors.performance-tasks.answer-sheets.edit', [$task->id, $i]) }}"
                   class="group block">
                    <div class="relative bg-white rounded-lg border-2 {{ $sheet ? 'border-green-500' : 'border-gray-200' }} p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900">Step {{ $i }}</span>
                            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $sheet ? 'bg-green-100' : 'bg-gray-100' }}">
                                @if ($sheet)
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <p class="text-xs {{ $sheet ? 'text-green-600' : 'text-gray-500' }}">
                            {{ $sheet ? 'Configured' : 'Not configured' }}
                        </p>
                        <div class="absolute inset-0 rounded-lg ring-2 ring-blue-500 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    </div>
                </a>
            @endfor
        </div>

        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-blue-900">Configure Each Step</h3>
                    <p class="text-sm text-blue-700 mt-1">Click on any step to set up the answer sheet and grading criteria.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>