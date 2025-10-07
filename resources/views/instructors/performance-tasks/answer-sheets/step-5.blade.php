<x-app-layout>
    <!-- Handsontable -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.css" />

    <div class="py-4 sm:py-6 lg:py-8">
        <div class="mb-6 sm:mb-8">
            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs sm:text-sm font-semibold mb-3">
                Step 5 of 10
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Adjusting Entries</h1>
            <p class="mt-2 text-gray-600 text-sm sm:text-base">
                Record adjusting entries for accrued, deferred, and estimated items before preparing financial statements.
            </p>
            <!-- Add attempts counter -->
            <div class="mt-2 text-sm text-gray-600">
                Attempts remaining: {{ 2 - ($submission->attempts ?? 0) }}/2
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
            <div id="spreadsheet" class="overflow-x-auto"></div>

            <div class="mt-6 flex justify-end">
                <form id="saveForm" method="POST" action="{{ route('students.performance-tasks.save-step', 5) }}">
                    @csrf
                    <input type="hidden" name="submission_data" id="submission_data">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition"
                        {{ ($submission->attempts ?? 0) >= 2 ? 'disabled' : '' }}>
                        💾 Save and Continue
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('spreadsheet');

            // ✅ Decode saved submission from database if it exists
            const savedData = @json($submission->submission_data ?? null);
            const initialData = savedData ? JSON.parse(savedData) : Array.from({ length: 15 }, () => ['', '', '', '']);

            // ✅ Initialize Handsontable
            const hot = new Handsontable(container, {
                data: initialData,
                rowHeaders: true,
                colHeaders: ['Account Title', 'Reference', 'Debit (₱)', 'Credit (₱)'],
                columns: [
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                ],
                stretchH: 'all',
                height: 'auto',
                className: 'htCenter htMiddle',
                minSpareRows: 1,
                licenseKey: 'non-commercial-and-evaluation',
            });

            // ✅ Update hidden input before submit
            document.getElementById('saveForm').addEventListener('submit', function () {
                document.getElementById('submission_data').value = JSON.stringify(hot.getData());
            });
        });
    </script>
</x-app-layout>
