<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Transactions') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-[#D5006D] to-[#A0004F] px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">New Transaction Entry</h3>
                    <p class="text-sm text-pink-100 mt-1">Add one or more transactions to your ledger</p>
                </div>

                <form action="{{ route('instructors.transactions.store') }}" method="POST" class="p-6">
                    @csrf

                    <div id="transactions" class="space-y-4">
                        <div class="transaction bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <div class="flex flex-col sm:flex-row gap-3">
                                <div class="flex-shrink-0">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                                    <input type="date" 
                                           name="transactions[0][date]" 
                                           class="border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm w-full sm:w-auto" 
                                           required>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                    <input type="text" 
                                           name="transactions[0][description]" 
                                           class="border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm w-full" 
                                           placeholder="Enter transaction description" 
                                           required>
                                </div>
                                <div class="flex-shrink-0 self-end">
                                    <button type="button" 
                                            onclick="removeTransaction(this)" 
                                            class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition-colors duration-200 shadow-sm flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Remove</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-200">
                        <button type="button" 
                                onclick="addTransaction()" 
                                class="px-4 py-2 bg-[#D5006D] hover:bg-[#B0005A] text-white rounded-md transition-colors duration-200 shadow-sm flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Another Transaction
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md transition-colors duration-200 shadow-sm font-semibold flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save All Transactions
                        </button>
                        <a href="{{ route('instructors.transactions.index') }}" 
                           class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md transition-colors duration-200 shadow-sm flex items-center justify-center gap-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <div class="mt-4 bg-pink-50 border border-pink-200 rounded-lg p-4">
                <div class="flex gap-2">
                    <svg class="w-5 h-5 text-[#D5006D] flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-[#D5006D] font-medium">Quick Tip</p>
                        <p class="text-sm text-gray-700 mt-1">You can add multiple transactions at once. Use the "Add Another Transaction" button to create batch entries.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let transactionIndex = 1;

        function addTransaction() {
            const container = document.getElementById('transactions');
            const div = document.createElement('div');
            div.classList.add('transaction', 'bg-gray-50', 'p-4', 'rounded-lg', 'border', 'border-gray-200', 'animate-fade-in');
            div.innerHTML = `
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-shrink-0">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                        <input type="date" 
                               name="transactions[${transactionIndex}][date]" 
                               class="border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm w-full sm:w-auto" 
                               required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                        <input type="text" 
                               name="transactions[${transactionIndex}][description]" 
                               class="border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm w-full" 
                               placeholder="Enter transaction description" 
                               required>
                    </div>
                    <div class="flex-shrink-0 self-end">
                        <button type="button" 
                                onclick="removeTransaction(this)" 
                                class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition-colors duration-200 shadow-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="hidden sm:inline">Remove</span>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(div);
            transactionIndex++;
        }

        function removeTransaction(button) {
            const transaction = button.closest('.transaction');
            transaction.style.opacity = '0';
            transaction.style.transform = 'scale(0.95)';
            transaction.style.transition = 'all 0.2s';
            setTimeout(() => transaction.remove(), 200);
        }
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</x-app-layout>