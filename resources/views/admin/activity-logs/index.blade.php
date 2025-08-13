<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700 dark:text-gray-100">

                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md dark:bg-green-900 dark:border-green-800 dark:text-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-amber-50 dark:bg-amber-900/20 border-b border-amber-200 dark:border-amber-700">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">User</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Action</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">IP Address</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">User Agent</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Date & Time</th>
                                    <th class="py-3 px-6 text-sm font-medium text-amber-900 dark:text-amber-100">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($logs as $log)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-amber-800/50 transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $log->user->name ?? 'Unknown' }}<br>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user->email ?? '' }}</span>
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200 capitalize">
                                            {{ $log->action }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $log->ip_address ?? '-' }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200 break-words max-w-xs">
                                            {{ Str::limit($log->user_agent, 80) }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            {{ $log->created_at->format('M d, Y h:i A') }}
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700 dark:text-gray-200">
                                            <a href="{{ route('admin.activity-logs.show', $log->id) }}" 
                                               class="px-3 py-1 bg-amber-500 text-white rounded-md hover:bg-amber-600 transition">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-6 text-sm text-center text-gray-600 dark:text-gray-400">
                                            No activity logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
