<x-app-layout>
    <!-- Handsontable -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.css" />

    <div class="py-6">
        <div class="mb-6">
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                Step 10 of 10
            </span>
            <h1 class="text-3xl font-bold mt-2">Post-Closing Trial Balance</h1>
            <p class="text-gray-600 mt-2">
                Prepare the post-closing trial balance to verify equality of debits and credits in permanent accounts after closing entries.
            </p>
            <!-- Add attempts counter -->
            <div class="mt-2 text-sm text-gray-600">
                Attempts remaining: {{ 2 - ($submission->attempts ?? 0) }}/2
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div id="spreadsheet" class="overflow-x-auto"></div>

            <form id="saveForm" method="POST" action="{{ route('students.performance-tasks.save-step', 10) }}" class="mt-6 text-right">
                @csrf
                <input type="hidden" name="submission_data" id="submission_data">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition"
                    {{ ($submission->attempts ?? 0) >= 2 ? 'disabled' : '' }}>
                    💾 Save and Finish
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('spreadsheet');
            const savedData = @json($submission->submission_data ?? null);
            const initialData = savedData
                ? JSON.parse(savedData)
                : Array.from({ length: 15 }, () => ['', '', '', '']);

            const hot = new Handsontable(container, {
                data: initialData,
                rowHeaders: true,
                colHeaders: [
                    'Account Title',
                    'Reference',
                    'Debit (₱)',
                    'Credit (₱)'
                ],
                columns: [
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } }
                ],
                stretchH: 'all',
                height: 'auto',
                minSpareRows: 1,
                className: 'htCenter htMiddle',
                licenseKey: 'non-commercial-and-evaluation',
                contextMenu: true,
                afterChange: function () {
                    calculateTotals();
                }
            });

            const form = document.getElementById('saveForm');
            form.addEventListener('submit', function (e) {
                // Validate before saving
                if (!calculateTotals()) {
                    e.preventDefault();
                    alert('⚠️ Debits must equal Credits before you can submit.');
                    return false;
                }
                document.getElementById('submission_data').value = JSON.stringify(hot.getData());
            });

            // ✅ Check if debits = credits and highlight totals if not
            function calculateTotals() {
                const data = hot.getData();
                let debitTotal = 0;
                let creditTotal = 0;

                data.forEach(row => {
                    debitTotal += parseFloat(row[2] || 0);
                    creditTotal += parseFloat(row[3] || 0);
                });

                // Optional: visually warn student
                if (Math.abs(debitTotal - creditTotal) > 0.001) {
                    container.style.border = '2px solid #EF4444'; // red border
                    return false;
                } else {
                    container.style.border = '2px solid #10B981'; // green border
                    return true;
                }
            }

            // Initial check
            calculateTotals();
        });
    </script>
</x-app-layout>
