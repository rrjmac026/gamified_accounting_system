<x-app-layout>
    <!-- Handsontable -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.css" />

    <div class="py-6">
        <div class="mb-6">
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                Step 9 of 10
            </span>
            <h1 class="text-3xl font-bold mt-2">Closing Entries</h1>
            <p class="text-gray-600 mt-2">
                Record closing entries to transfer temporary account balances to the Income Summary and Owner's Capital accounts.
            </p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div id="spreadsheet" class="overflow-x-auto"></div>

            <form id="saveForm" method="POST" action="{{ route('students.performance-tasks.save-step', 9) }}" class="mt-6 text-right">
                @csrf
                <input type="hidden" name="submission_data" id="submission_data">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    💾 Save and Continue
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('spreadsheet');

            // ✅ Load saved data from DB if it exists
            const savedData = @json($submission->submission_data ?? null);
            const initialData = savedData
                ? JSON.parse(savedData)
                : Array.from({ length: 15 }, () => ['', '', '', '', '', '']);

            // ✅ Initialize Handsontable
            const hot = new Handsontable(container, {
                data: initialData,
                rowHeaders: true,
                colHeaders: [
                    'Date',
                    'Account Title',
                    'Reference',
                    'Debit (₱)',
                    'Credit (₱)',
                    'Explanation'
                ],
                columns: [
                    { type: 'date', dateFormat: 'MM/DD/YYYY', correctFormat: true },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'text' }
                ],
                stretchH: 'all',
                height: 'auto',
                minSpareRows: 1,
                className: 'htCenter htMiddle',
                licenseKey: 'non-commercial-and-evaluation',
                contextMenu: true,
            });

            // ✅ Sync data before form submission
            const form = document.getElementById('saveForm');
            form.addEventListener('submit', function () {
                document.getElementById('submission_data').value = JSON.stringify(hot.getData());
            });
        });
    </script>
</x-app-layout>
