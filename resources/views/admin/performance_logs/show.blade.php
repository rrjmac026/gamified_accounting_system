<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700">
                    <h2 class="text-2xl font-bold text-[#FF92C2] mb-6">Performance Log Details</h2>

                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-[#FF92C2] mb-4">Basic Information</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Student:</dt>
                                    <dd class="text-gray-900">{{ $performanceLog->student->user->name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Subject:</dt>
                                    <dd class="text-gray-900">{{ $performanceLog->subject->name }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Task:</dt>
                                    <dd class="text-gray-900">{{ $performanceLog->task->title }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-[#FF92C2] mb-4">Performance Details</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Metric:</dt>
                                    <dd class="text-gray-900">{{ $performanceLog->performance_metric }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Value:</dt>
                                    <dd class="text-gray-900">{{ $performanceLog->value }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Recorded At:</dt>
                                    <dd class="text-gray-900">{{ $performanceLog->recorded_at->format('F j, Y H:i:s') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('admin.performance_logs.index') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
