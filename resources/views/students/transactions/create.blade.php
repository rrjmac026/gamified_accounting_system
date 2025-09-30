<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('students.transactions.index') }}" 
               class="text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Answer Transaction') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                <div class="bg-gradient-to-r from-[#D5006D] to-[#A0004F] px-6 py-5">
                    <h3 class="text-xl font-semibold text-white mb-1">{{ $transaction->description }}</h3>
                    <div class="flex items-center gap-2 text-pink-100 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $transaction->date->format('F d, Y') }}</span>
                    </div>
                </div>

                <div class="p-6">
                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-2 border-green-500 rounded-lg p-4">
                            <div class="flex gap-2">
                                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-green-800 font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Error Message --}}
                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border-2 border-red-500 rounded-lg p-4">
                            <div class="flex gap-2">
                                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-red-800 font-medium">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('students.transactions.store', $transaction->id) }}" method="POST">
                        @csrf

                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex gap-2">
                                <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-800 font-medium">Instructions</p>
                                    <p class="text-sm text-blue-700 mt-1">Select accounts and enter debit/credit amounts. Click "+ Add Row" to add more accounts. Total debits must equal total credits!</p>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table id="transactionTable" class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                            Account
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                            Debit
                                        </th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider border-r border-gray-200">
                                            Credit
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($studentEntries->isNotEmpty())
                                        {{-- Show previous student answers --}}
                                        @foreach($studentEntries as $index => $entry)
                                            <tr class="transaction-row hover:bg-gray-50 transition-colors duration-150" data-entry-index="{{ $index }}">
                                                <td class="px-6 py-4 text-sm border-r border-gray-200">
                                                    <select name="entries[{{ $index }}][account_id]" class="w-full border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm px-3 py-2" required>
                                                        <option value="">-- Select Account --</option>
                                                        @foreach($accounts as $account)
                                                            <option value="{{ $account->id }}" {{ $entry->account_id == $account->id ? 'selected' : '' }}>
                                                                {{ $account->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-right border-r border-gray-200">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           name="entries[{{ $index }}][debit]" 
                                                           class="debit-input w-full text-right border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm px-3 py-2" 
                                                           value="{{ $entry->debit }}"
                                                           placeholder="0.00">
                                                </td>
                                                <td class="px-6 py-4 text-sm text-right border-r border-gray-200">
                                                    <input type="number" 
                                                           step="0.01" 
                                                           name="entries[{{ $index }}][credit]" 
                                                           class="credit-input w-full text-right border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm px-3 py-2" 
                                                           value="{{ $entry->credit }}"
                                                           placeholder="0.00">
                                                </td>
                                                <td class="px-6 py-4 text-sm text-center">
                                                    <button type="button" class="remove-row text-red-600 hover:text-red-800 font-medium transition-colors duration-150 flex items-center justify-center gap-1 mx-auto">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Remove
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        {{-- Default empty row --}}
                                        <tr class="transaction-row hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 text-sm border-r border-gray-200">
                                                <select name="entries[0][account_id]" class="w-full border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm px-3 py-2" required>
                                                    <option value="">-- Select Account --</option>
                                                    @foreach($accounts as $account)
                                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right border-r border-gray-200">
                                                <input type="number" 
                                                       step="0.01" 
                                                       name="entries[0][debit]" 
                                                       class="debit-input w-full text-right border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm px-3 py-2" 
                                                       value="0"
                                                       placeholder="0.00">
                                            </td>
                                            <td class="px-6 py-4 text-sm text-right border-r border-gray-200">
                                                <input type="number" 
                                                       step="0.01" 
                                                       name="entries[0][credit]" 
                                                       class="credit-input w-full text-right border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm px-3 py-2" 
                                                       value="0"
                                                       placeholder="0.00">
                                            </td>
                                            <td class="px-6 py-4 text-sm text-center">
                                                <button type="button" class="remove-row text-red-600 hover:text-red-800 font-medium transition-colors duration-150 flex items-center justify-center gap-1 mx-auto">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Remove
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot class="bg-gradient-to-r from-gray-100 to-gray-200 font-semibold">
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900 border-t-2 border-gray-300">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                Totals
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right border-t-2 border-gray-300">
                                            <span id="totalDebit" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                                0.00
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-right border-t-2 border-gray-300">
                                            <span id="totalCredit" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                                                0.00
                                            </span>
                                        </td>
                                        <td class="border-t-2 border-gray-300"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <button type="button" id="addRowBtn" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 shadow-sm font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Row
                        </button>

                        <div id="balanceMessage" class="mt-4 hidden"></div>

                        <div class="mt-6 flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('students.transactions.index') }}" 
                               class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors duration-200 shadow-sm flex items-center justify-center gap-2 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-[#D5006D] hover:bg-[#B0005A] text-white rounded-lg transition-colors duration-200 shadow-sm font-semibold flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Answer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const correctEntries = @json($correctEntries ?? []);
            let rowIndex = {{ $studentEntries->count() > 0 ? $studentEntries->count() : 1 }};
            const addRowBtn = document.getElementById('addRowBtn');
            const tableBody = document.querySelector('#transactionTable tbody');
            const totalDebitEl = document.getElementById('totalDebit');
            const totalCreditEl = document.getElementById('totalCredit');
            const balanceMessageEl = document.getElementById('balanceMessage');

            // Account options HTML for new rows
            const accountOptions = `
                <option value="">-- Select Account --</option>
                @foreach($accounts as $account)
                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                @endforeach
            `;

            // Function to color individual cells based on correctness
            function colorCells() {
                document.querySelectorAll('.transaction-row').forEach(row => {
                    const accountSelect = row.querySelector('select[name*="account_id"]');
                    const debitInput = row.querySelector('.debit-input');
                    const creditInput = row.querySelector('.credit-input');
                    
                    if (!accountSelect || !debitInput || !creditInput) return;

                    const accountId = parseInt(accountSelect.value);
                    const debit = parseFloat(debitInput.value) || 0;
                    const credit = parseFloat(creditInput.value) || 0;

                    // Skip empty rows
                    if (debit === 0 && credit === 0 && !accountId) {
                        accountSelect.classList.remove('bg-red-100', 'bg-green-100', 'border-red-500', 'border-green-500');
                        debitInput.classList.remove('bg-red-100', 'bg-green-100', 'border-red-500', 'border-green-500');
                        creditInput.classList.remove('bg-red-100', 'bg-green-100', 'border-red-500', 'border-green-500');
                        return;
                    }

                    // Find matching correct entry by account
                    const correctEntry = correctEntries.find(correct => correct.account_id === accountId);

                    // Color account select
                    accountSelect.classList.remove('bg-red-100', 'bg-green-100', 'border-red-500', 'border-green-500');
                    if (accountId) {
                        if (correctEntry) {
                            accountSelect.classList.add('bg-green-100', 'border-green-500');
                        } else {
                            accountSelect.classList.add('bg-red-100', 'border-red-500');
                        }
                    }

                    // Color debit input
                    debitInput.classList.remove('bg-red-100', 'bg-green-100', 'border-red-500', 'border-green-500');
                    if (debit > 0) {
                        if (correctEntry && Math.abs(correctEntry.debit - debit) < 0.01) {
                            debitInput.classList.add('bg-green-100', 'border-green-500');
                        } else {
                            debitInput.classList.add('bg-red-100', 'border-red-500');
                        }
                    }

                    // Color credit input
                    creditInput.classList.remove('bg-red-100', 'bg-green-100', 'border-red-500', 'border-green-500');
                    if (credit > 0) {
                        if (correctEntry && Math.abs(correctEntry.credit - credit) < 0.01) {
                            creditInput.classList.add('bg-green-100', 'border-green-500');
                        } else {
                            creditInput.classList.add('bg-red-100', 'border-red-500');
                        }
                    }
                });
            }

            // Add new row
            addRowBtn.addEventListener('click', () => {
                const newRow = document.createElement('tr');
                newRow.classList.add('transaction-row', 'hover:bg-gray-50', 'transition-colors', 'duration-150');
                newRow.innerHTML = `
                    <td class="px-6 py-4 text-sm border-r border-gray-200">
                        <select name="entries[${rowIndex}][account_id]" class="w-full border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm px-3 py-2" required>
                            ${accountOptions}
                        </select>
                    </td>
                    <td class="px-6 py-4 text-sm text-right border-r border-gray-200">
                        <input type="number" 
                               step="0.01" 
                               name="entries[${rowIndex}][debit]" 
                               class="debit-input w-full text-right border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm px-3 py-2" 
                               value="0"
                               placeholder="0.00">
                    </td>
                    <td class="px-6 py-4 text-sm text-right border-r border-gray-200">
                        <input type="number" 
                               step="0.01" 
                               name="entries[${rowIndex}][credit]" 
                               class="credit-input w-full text-right border-gray-300 focus:border-[#D5006D] focus:ring-[#D5006D] rounded-md shadow-sm px-3 py-2" 
                               value="0"
                               placeholder="0.00">
                    </td>
                    <td class="px-6 py-4 text-sm text-center">
                        <button type="button" class="remove-row text-red-600 hover:text-red-800 font-medium transition-colors duration-150 flex items-center justify-center gap-1 mx-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Remove
                        </button>
                    </td>
                `;
                tableBody.appendChild(newRow);
                rowIndex++;

                // Attach event listeners to new inputs
                const newDebitInput = newRow.querySelector('.debit-input');
                const newCreditInput = newRow.querySelector('.credit-input');
                const newAccountSelect = newRow.querySelector('select[name*="account_id"]');
                
                newDebitInput.addEventListener('input', () => {
                    calculateTotals();
                    colorCells();
                });
                newCreditInput.addEventListener('input', () => {
                    calculateTotals();
                    colorCells();
                });
                newAccountSelect.addEventListener('change', colorCells);

                calculateTotals();
            });

            // Remove row
            tableBody.addEventListener('click', function(e) {
                if (e.target.closest('.remove-row')) {
                    const rows = tableBody.querySelectorAll('.transaction-row');
                    if (rows.length > 1) {
                        e.target.closest('tr').remove();
                        calculateTotals();
                        colorCells();
                    } else {
                        alert('You must have at least one entry row.');
                    }
                }
            });

            // Calculate totals
            function calculateTotals() {
                const debitInputs = document.querySelectorAll('.debit-input');
                const creditInputs = document.querySelectorAll('.credit-input');
                
                let totalDebit = 0;
                let totalCredit = 0;

                debitInputs.forEach(input => {
                    totalDebit += parseFloat(input.value) || 0;
                });

                creditInputs.forEach(input => {
                    totalCredit += parseFloat(input.value) || 0;
                });

                totalDebitEl.textContent = totalDebit.toFixed(2);
                totalCreditEl.textContent = totalCredit.toFixed(2);

                // Check if balanced
                if (totalDebit > 0 || totalCredit > 0) {
                    const difference = Math.abs(totalDebit - totalCredit);
                    
                    if (difference < 0.01) {
                        balanceMessageEl.className = 'mt-4 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-2';
                        balanceMessageEl.innerHTML = `
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium text-green-800">Perfect! Your entry is balanced.</span>
                        `;
                        balanceMessageEl.classList.remove('hidden');
                    } else {
                        balanceMessageEl.className = 'mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4 flex items-center gap-2';
                        balanceMessageEl.innerHTML = `
                            <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium text-yellow-800">Not balanced! Difference: ${difference.toFixed(2)}</span>
                        `;
                        balanceMessageEl.classList.remove('hidden');
                    }
                } else {
                    balanceMessageEl.classList.add('hidden');
                }
            }

            // Initialize event listeners for existing inputs
            const initialDebitInputs = document.querySelectorAll('.debit-input');
            const initialCreditInputs = document.querySelectorAll('.credit-input');
            const initialAccountSelects = document.querySelectorAll('select[name*="account_id"]');
            
            initialDebitInputs.forEach(input => {
                input.addEventListener('input', () => {
                    calculateTotals();
                    colorCells();
                });
            });
            initialCreditInputs.forEach(input => {
                input.addEventListener('input', () => {
                    calculateTotals();
                    colorCells();
                });
            });
            initialAccountSelects.forEach(select => {
                select.addEventListener('change', colorCells);
            });

            // Initial calculation and coloring on page load
            calculateTotals();
            
            // Color cells if student has submitted before
            @if($studentEntries->isNotEmpty())
                colorCells();
            @endif
        });
    </script>
</x-app-layout>