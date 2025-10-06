<x-app-layout>
    <!-- Handsontable -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.css" />
    <!-- Formula Parser (HyperFormula) -->
    <script src="https://cdn.jsdelivr.net/npm/hyperformula@2.6.2/dist/hyperformula.full.min.js"></script>

    <div class="py-4 sm:py-6 lg:py-8">
        {{-- Flash Error --}}
        @if (session('error'))
            <div class="mb-6 animate-slideDown">
                <div class="flex items-start gap-3 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-red-800 mb-1">Error</h3>
                        <p class="text-sm text-red-700 leading-relaxed">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-6 sm:mb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs sm:text-sm font-semibold mb-3">
                <span>Step 3 of 10</span>
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">
                Analyzing Transactions
            </h1>
            <p class="mt-3 text-sm sm:text-base text-gray-600 leading-relaxed max-w-3xl">
                Identify which accounts are affected by each transaction and determine whether they should be debited or credited.
            </p>
        </div>

        {{-- Spreadsheet Container --}}
        <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-gray-200">
                <p class="text-xs sm:text-sm text-gray-600">
                    {{ $performanceTask->description ?? 'Analyze the transactions below and fill in the affected accounts and amounts.' }}
                </p>
            </div>

            <form id="saveForm" method="POST" action="{{ route('students.performance-tasks.save-step', 3) }}">
                @csrf
                <div class="p-3 sm:p-4 lg:p-6">
                    <div class="border rounded-lg shadow-inner bg-gray-50 overflow-hidden">
                        <div class="overflow-x-auto overflow-y-auto" style="max-height: calc(100vh - 400px); min-height: 400px;">
                            <div id="spreadsheet" class="bg-white min-w-full"></div>
                        </div>
                        <input type="hidden" name="submission_data" id="submission_data" required>
                    </div>

                    <div class="mt-2 text-xs text-gray-500 sm:hidden text-center">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        Swipe horizontally to scroll table
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="p-4 sm:p-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4">
                        <button type="button" onclick="window.history.back()"
                            class="inline-flex items-center justify-center px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition text-sm sm:text-base">
                            ← Back
                        </button>

                        <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition text-sm sm:text-base">
                            💾 Save and Continue
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('spreadsheet');
            const savedData = @json($submission->submission_data ?? null);

            // ✅ FIXED: no shared array references
            const initialData = savedData 
                ? JSON.parse(savedData) 
                : Array.from({ length: 15 }, () => ['', '', '', '', '', '']);

            const hot = new Handsontable(container, {
                data: initialData,
                rowHeaders: true,
                colHeaders: [
                    'Date',
                    'Transaction Description',
                    'Account Title',
                    'Reference',
                    'Debit (₱)',
                    'Credit (₱)'
                ],
                columns: [
                    { type: 'date', dateFormat: 'MM/DD/YYYY', correctFormat: true },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } }
                ],
                stretchH: 'all',
                height: 'auto',
                licenseKey: 'non-commercial-and-evaluation',
                contextMenu: true,
                manualColumnResize: true,
                manualRowResize: true,
                minSpareRows: 1,
            });

            // Save data on submit
            document.getElementById('saveForm').addEventListener('submit', function(e) {
                document.getElementById('submission_data').value = JSON.stringify(hot.getData());
            });
        });
    </script>

    <style>
        .handsontable td { border-color: #d1d5db; }
        .handsontable .area { background-color: rgba(59,130,246,0.1); }
        .overflow-x-auto { -webkit-overflow-scrolling: touch; scroll-behavior: smooth; }

        @media (max-width: 640px) {
            .handsontable { font-size: 12px; }
        }
        @media (min-width: 640px) and (max-width: 1024px) {
            .handsontable { font-size: 13px; }
        }
    </style>
</x-app-layout>
