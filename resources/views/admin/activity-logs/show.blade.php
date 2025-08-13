<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700 dark:text-gray-100">

                    {{-- Title --}}
                    <h2 class="text-2xl font-semibold mb-4 text-amber-900 dark:text-amber-100">
                        Activity Log Details
                    </h2>

                    {{-- Flash Message --}}
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md dark:bg-green-900 dark:border-green-800 dark:text-green-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Details Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">ID</th>
                                    <td class="py-3 px-6 text-sm">{{ $log->id }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">User</th>
                                    <td class="py-3 px-6 text-sm">
                                        {{ $log->user->name ?? 'N/A' }} 
                                        <span class="text-gray-500 text-xs">({{ $log->user->email ?? 'N/A' }})</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Action</th>
                                    <td class="py-3 px-6 text-sm capitalize">{{ $log->action }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">IP Address</th>
                                    <td class="py-3 px-6 text-sm">{{ $log->ip_address }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">User Agent</th>
                                    <td class="py-3 px-6 text-sm break-all">{{ $log>'user_agent' }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Created At</th>
                                    <td class="py-3 px-6 text-sm">{{ $log->created_at->format('F j, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-amber-900 dark:text-amber-100">Updated At</th>
                                    <td class="py-3 px-6 text-sm">{{ $log->updated_at->format('F j, Y g:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Back Button --}}
                    <div class="mt-6">
                        <a href="{{ route('admin.activity-logs.index') }}" 
                           class="inline-block px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-md shadow-sm transition">
                            ← Back to Activity log
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
