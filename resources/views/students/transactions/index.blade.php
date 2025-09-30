<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if($transactions->isEmpty())
                <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                    <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No transactions available</h3>
                    <p class="text-gray-600 text-base">Please wait for your instructor to provide transactions.</p>
                </div>
            @else
                <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-[#D5006D] to-[#A0004F] px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Available Transactions</h3>
                        <p class="text-sm text-pink-100 mt-1">Select a transaction to provide your accounting entries</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                        Description
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transactions as $transaction)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $transaction->date->format('M d, Y') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 border-r border-gray-200">
                                            {{ $transaction->description }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-center">
                                            <a href="{{ route('students.transactions.create', $transaction->id) }}" 
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-[#D5006D] hover:bg-[#B0005A] text-white rounded-lg transition-colors duration-200 shadow-sm font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Answer
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2 text-gray-600">
                                <svg class="w-5 h-5 text-[#D5006D]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Click "Answer" to provide journal entries for each transaction</span>
                            </div>
                            <div class="text-gray-600">
                                Total Transactions: <span class="font-semibold text-gray-900">{{ $transactions->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>