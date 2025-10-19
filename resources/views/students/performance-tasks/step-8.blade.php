<x-app-layout>
    <!-- Handsontable -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.css" />

    <div class="py-6">
        {{-- Flash Messages --}}
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

        @if (session('success'))
            <div class="mb-6 animate-slideDown">
                <div class="flex items-start gap-3 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-green-800 mb-1">Success</h3>
                        <p class="text-sm text-green-700 leading-relaxed">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-6">
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                Step 8 of 10
            </span>
            <h1 class="text-3xl font-bold mt-2">Balance Sheet</h1>
            <p class="text-gray-600 mt-2">
                Prepare the balance sheet showing assets, liabilities, and owner's equity as of the end of the period.
            </p>
            <!-- Add attempts counter and status -->
            <div class="mt-2 flex items-center gap-4">
                <span class="text-sm text-gray-600">
                    Attempts remaining: {{ 2 - ($submission->attempts ?? 0) }}/2
                </span>
                @if($submission && $submission->status)
                    <span class="text-sm font-semibold {{ $submission->status === 'correct' ? 'text-green-600' : 'text-red-600' }}">
                        Status: {{ ucfirst($submission->status) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div id="spreadsheet" class="overflow-x-auto"></div>

            <form id="saveForm" method="POST" action="{{ route('students.performance-tasks.save-step', 8) }}" class="mt-6 text-right">
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
        let hot;
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('spreadsheet');

            // Student's saved answers
            const savedData = @json($submission->submission_data ?? null);
            const initialData = savedData
                ? JSON.parse(savedData)
                : Array.from({ length: 20 }, () => ['', '', '', '']);

            // Instructor's correct data
            const correctData = @json($answerSheet->correct_data ?? null);
            const submissionStatus = @json($submission->status ?? null);

            // Initialize Handsontable
            hot = new Handsontable(container, {
                data: initialData,
                rowHeaders: true,
                colHeaders: [
                    'Date',
                    'Descriptions/Particulars',
                    'Debit',
                    'Credit'
                ],
                columns: [
                    { type: 'date', dateFormat: 'MM/DD/YYYY' },
                    { type: 'text' },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '₱0,0.00' } }
                ],
                stretchH: 'all',
                height: 500,
                minSpareRows: 1,
                className: 'htCenter htMiddle',
                licenseKey: 'non-commercial-and-evaluation',
                contextMenu: true,
                undo: true,
                manualColumnResize: true,
                fillHandle: true,
                copyPaste: true,
                colWidths: [120, 250, 150, 150],
                enterMoves: { row: 1, col: 0 },
                tabMoves: { row: 0, col: 1 },
                cells: function(row, col) {
                    const cellProperties = {};
                    
                    // Only apply correct/incorrect coloring if submission has been graded
                    if (submissionStatus && correctData && savedData) {
                        const parsedCorrect = typeof correctData === 'string' ? JSON.parse(correctData) : correctData;
                        const parsedStudent = typeof savedData === 'string' ? JSON.parse(savedData) : savedData;
                        
                        const studentValue = parsedStudent[row]?.[col];
                        const correctValue = parsedCorrect[row]?.[col];
                        
                        // Only compare non-empty cells that the STUDENT filled in
                        if (studentValue !== null && studentValue !== undefined && studentValue !== '') {
                            // Normalize values for comparison (trim whitespace, case-insensitive)
                            const normalizedStudent = String(studentValue).trim().toLowerCase();
                            const normalizedCorrect = String(correctValue || '').trim().toLowerCase();
                            
                            if (normalizedStudent === normalizedCorrect) {
                                cellProperties.className = 'cell-correct';
                            } else {
                                cellProperties.className = 'cell-wrong';
                            }
                        }
                    }
                    
                    return cellProperties;
                }
            });

            // Keep data synced before form submission
            const form = document.getElementById('saveForm');
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                document.getElementById('submission_data').value = JSON.stringify(hot.getData());
                this.submit();
            });
        });
    </script>

    <style>
        body { overflow-x: hidden; }
        .handsontable td { 
            border-color: #d1d5db;
            background-color: #ffffff; /* Default white background */
        }
        .handsontable th { background-color: #f3f4f6; font-weight: 600; }
        .handsontable .area { background-color: rgba(59,130,246,0.1); }
        .handsontable { position: relative; z-index: 1; }
        #spreadsheet { isolation: isolate; }

        /* Correct/Incorrect answer styling - consistent with Step 6 */
        .handsontable td.cell-correct {
            background-color: #dcfce7 !important; /* Light green */
            border: 2px solid #16a34a !important; /* Green border */
            color: #166534;
        }

        .handsontable td.cell-wrong {
            background-color: #fee2e2 !important; /* Light red */
            border: 2px solid #dc2626 !important; /* Red border */
            color: #991b1b;
        }

        /* Prevent selected cells from overriding colors */
        .handsontable td.cell-correct.area,
        .handsontable td.cell-correct.current {
            background-color: #bbf7d0 !important; /* Slightly darker green when selected */
        }

        .handsontable td.cell-wrong.area,
        .handsontable td.cell-wrong.current {
            background-color: #fecaca !important; /* Slightly darker red when selected */
        }
    </style>
</x-app-layout>