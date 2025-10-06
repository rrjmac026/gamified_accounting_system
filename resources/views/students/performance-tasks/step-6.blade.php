<x-app-layout>
    <!-- Handsontable -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.css" />

    <div class="py-6">
        <div class="mb-6">
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                Step 6 of 10
            </span>
            <h1 class="text-3xl font-bold mt-2">Worksheet</h1>
            <p class="text-gray-600 mt-2">
                Prepare the worksheet by adjusting account balances and extending them to the appropriate financial statement columns.
            </p>
            <!-- Add attempts counter -->
            <div class="mt-2 text-sm text-gray-600">
                Attempts remaining: {{ 2 - ($submission->attempts ?? 0) }}/2
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div id="spreadsheet" class="overflow-x-auto"></div>

            <form id="saveForm" method="POST" action="{{ route('students.performance-tasks.save-step', 6) }}" class="mt-6 text-right">
                @csrf
                <input type="hidden" name="submission_data" id="submission_data">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition"
                    {{ ($submission->attempts ?? 0) >= 2 ? 'disabled' : '' }}>
                    💾 Save and Continue
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('spreadsheet');

            // ✅ Load saved data from DB if exists
            const savedData = @json($submission->submission_data ?? null);
            const initialData = savedData
                ? JSON.parse(savedData)
                : Array.from({ length: 15 }, () => Array(11).fill(''));

            // ✅ Initialize Handsontable
            const hot = new Handsontable(container, {
                data: initialData,
                rowHeaders: true,
                colHeaders: [
                    'Account Title',
                    'Trial Balance (Dr)', 'Trial Balance (Cr)',
                    'Adjustments (Dr)', 'Adjustments (Cr)',
                    'Adjusted Trial Balance (Dr)', 'Adjusted Trial Balance (Cr)',
                    'Income Statement (Dr)', 'Income Statement (Cr)',
                    'Balance Sheet (Dr)', 'Balance Sheet (Cr)'
                ],
                columns: [
                    { type: 'text' },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } }
                ],
                stretchH: 'all',
                height: 'auto',
                className: 'htCenter htMiddle',
                minSpareRows: 1,
                licenseKey: 'non-commercial-and-evaluation',
            });

            // ✅ Keep data synced before submit
            document.getElementById('saveForm').addEventListener('submit', function () {
                document.getElementById('submission_data').value = JSON.stringify(hot.getData());
            });
        });
    </script>
</x-app-layout>
