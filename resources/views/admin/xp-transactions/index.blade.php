@section('title', 'XP Transactions')
<x-app-layout>
    <div class="flex justify-end px-4 sm:px-8 mt-4">
        <a href="{{ route('admin.xp-transactions.create') }}" 
           class="w-full sm:w-auto inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#FF92C2] hover:bg-[#ff6fb5] rounded-lg shadow-sm hover:shadow transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>Add XP Transaction
        </a>
    </div>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 rounded-lg sm:rounded-2xl">
                <div class="p-4 sm:p-6 text-gray-700">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Student</th>
                                    <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Amount</th>
                                    <th class="hidden sm:table-cell py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Type</th>
                                    <th class="hidden md:table-cell py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Source</th>
                                    <th class="hidden lg:table-cell py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Date</th>
                                    <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                                @forelse ($transactions as $transaction)
                                    <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                        <td class="py-4 px-3 sm:px-6 text-sm">{{ $transaction->student->user->name ?? 'N/A' }}</td>
                                        <td class="py-4 px-3 sm:px-6 text-sm">{{ $transaction->amount }} XP</td>
                                        <td class="hidden sm:table-cell py-4 px-3 sm:px-6 text-sm capitalize">{{ $transaction->type }}</td>
                                        <td class="hidden md:table-cell py-4 px-3 sm:px-6 text-sm capitalize">{{ str_replace('_', ' ', $transaction->source) }}</td>
                                        <td class="hidden lg:table-cell py-4 px-3 sm:px-6 text-sm">{{ $transaction->processed_at->format('M d, Y h:i A') }}</td>
                                        <td class="py-4 px-3 sm:px-6 text-sm space-x-2">
                                            <a href="{{ route('admin.xp-transactions.show', $transaction) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.xp-transactions.edit', $transaction) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-3 sm:px-6 text-sm text-center">No transactions found.</td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
</x-app-layout>
