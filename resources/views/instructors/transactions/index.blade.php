<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Transactions') }}
            </h2>
            <a href="{{ route('instructors.transactions.create') }}" 
               class="px-4 py-2 bg-[#D5006D] hover:bg-[#B0005A] text-white rounded-lg transition-colors duration-200 shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Transaction
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($transactions->isEmpty())
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No transactions yet</h3>
                    <p class="text-gray-600 mb-6">Get started by creating your first transaction</p>
                    <a href="{{ route('instructors.transactions.create') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-[#D5006D] hover:bg-[#B0005A] text-white rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create First Transaction
                    </a>
                </div>
            @else
                <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-[#D5006D] to-[#A0004F] px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Transaction Ledger</h3>
                        <p class="text-sm text-pink-100 mt-1">Complete overview of all transactions and account balances</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                        Date
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                        Description
                                    </th>
                                    @foreach($accounts as $account)
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200 last:border-r-0">
                                            {{ $account->name }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transactions as $transaction)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                            {{ $transaction->date->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 border-r border-gray-200">
                                            {{ $transaction->description }}
                                        </td>
                                        @foreach($accounts as $account)
                                            @php
                                                $entry = $transaction->entries->firstWhere('account_id', $account->id);
                                                $debit = $entry?->debit;
                                                $credit = $entry?->credit;
                                            @endphp
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-right border-r border-gray-200 last:border-r-0">
                                                @if($debit)
                                                    <span class="text-green-600 font-medium">{{ number_format($debit, 2) }}</span>
                                                @elseif($credit)
                                                    <span class="text-red-600 font-medium">({{ number_format($credit, 2) }})</span>
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gradient-to-r from-gray-100 to-gray-200">
                                <tr class="font-semibold">
                                    <td colspan="2" class="px-4 py-4 text-sm text-gray-900 border-t-2 border-gray-300">
                                        Ending Balances
                                    </td>
                                    @foreach($accounts as $account)
                                        <td class="px-4 py-4 text-sm text-right border-t-2 border-gray-300">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $balances[$account->id] >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $balances[$account->id] >= 0 ? '' : '-' }}{{ number_format(abs($balances[$account->id]), 2) }}
                                            </span>
                                        </td>
                                    @endforeach
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                                <span class="text-gray-700">Debit</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-red-600 rounded-full"></div>
                                <span class="text-gray-700">Credit (in parentheses)</span>
                            </div>
                            <div class="ml-auto text-gray-600">
                                Total Transactions: <span class="font-semibold text-gray-900">{{ $transactions->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
