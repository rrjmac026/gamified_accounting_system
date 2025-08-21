@section('title', 'Performance Logs')
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700">
                    <h2 class="text-2xl font-bold text-[#FF92C2] mb-6">Performance Logs</h2>

                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Student</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Subject</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Task</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Metric</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Value</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Recorded At</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                                @forelse ($logs as $log)
                                    <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm">{{ $log->student->user->name }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $log->subject->name }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $log->task->title }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $log->performance_metric }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $log->value }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $log->recorded_at->format('M d, Y H:i') }}</td>
                                        <td class="py-4 px-6 text-sm space-x-2">
                                            <a href="{{ route('admin.performance_logs.show', $log) }}" 
                                               class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-4 px-6 text-sm text-center text-gray-500">
                                            No performance logs found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
