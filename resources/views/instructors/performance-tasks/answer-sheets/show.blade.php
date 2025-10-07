<x-app-layout>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">
            {{ $task->title }} – Answer Sheets
        </h1>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @for ($i = 1; $i <= 10; $i++)
                @php
                    $sheet = $answerSheets->firstWhere('step', $i);
                @endphp
                <a href="{{ route('instructors.performance-tasks.answer-sheets.edit', [$task->id, $i]) }}"
                   class="block p-4 bg-white rounded shadow hover:bg-pink-50 transition">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold">Step {{ $i }}</span>
                        @if ($sheet)
                            <span class="text-green-600 text-sm">✅</span>
                        @else
                            <span class="text-gray-400 text-sm">❌</span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $sheet ? 'Answer sheet saved' : 'Not yet created' }}
                    </p>
                </a>
            @endfor
        </div>
    </div>
</x-app-layout>
