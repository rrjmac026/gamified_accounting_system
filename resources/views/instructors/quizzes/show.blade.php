<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 px-4">
        <h2 class="text-2xl font-bold mb-4">{{ $quiz->title }}</h2>
        <p class="mb-6">{{ $quiz->description }}</p>

        <!-- Import CSV Questions -->
        <form action="{{ route('instructors.quizzes.import', $quiz->task_id) }}" 
              method="POST" enctype="multipart/form-data" 
              class="bg-white p-4 rounded-lg shadow mb-6">
            @csrf
            <label class="block mb-2 font-medium">Upload CSV/XLSX</label>
            <input type="file" name="file" class="mb-4 border rounded p-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Import Questions
            </button>
        </form>

        <!-- List Questions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-semibold mb-4">Questions</h3>
            <div class="space-y-6">
                @foreach($quizzes as $quizItem)
                    <div class="border-b pb-4 last:border-0">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="font-medium text-lg mb-2">
                                    {{ $loop->iteration }}. {{ $quizItem->question_text }}
                                </p>
                                <div class="ml-4 text-gray-600">
                                    <p class="mb-1"><span class="font-medium">Type:</span> 
                                        {{ ucfirst(str_replace('_', ' ', $quizItem->type)) }}
                                    </p>
                                    <p class="mb-1"><span class="font-medium">Points:</span> 
                                        {{ $quizItem->points }}
                                    </p>
                                    <p class="mb-1"><span class="font-medium">Correct Answer:</span> 
                                        {{ $quizItem->correct_answer }}
                                    </p>
                                    @if($quizItem->type === 'multiple_choice')
                                        <div class="mt-2">
                                            <p class="font-medium mb-1">Options:</p>
                                            <ul class="list-disc ml-6">
                                                @if(is_string($quizItem->options))
                                                    @foreach(explode(',', $quizItem->options) as $option)
                                                        <li>{{ trim($option) }}</li>
                                                    @endforeach
                                                @elseif(is_array($quizItem->options))
                                                    @foreach($quizItem->options as $option)
                                                        <li>{{ trim($option) }}</li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="px-3 py-1 rounded-full text-sm {{ $quizItem->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $quizItem->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
