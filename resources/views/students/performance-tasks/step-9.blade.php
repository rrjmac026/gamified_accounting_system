<x-app-layout>
    <!-- Handsontable -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.css" />
    <!-- Formula Parser (HyperFormula) -->
    <script src="https://cdn.jsdelivr.net/npm/hyperformula@2.6.2/dist/hyperformula.full.min.js"></script>

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
                Step 9 of 10
            </span>
            <h1 class="text-3xl font-bold mt-2">Closing Entries</h1>
            <p class="text-gray-600 mt-2">
                Record closing entries to close temporary accounts (revenues, expenses, withdrawals) at the end of the accounting period.
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

        <!-- Instructions Panel -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-blue-800">Closing Entry Steps:</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ol class="list-decimal ml-4 space-y-1">
                            <li>Close revenue accounts to Income Summary</li>
                            <li>Close expense accounts to Income Summary</li>
                            <li>Close Income Summary to Owner's Capital</li>
                            <li>Close Owner's Withdrawals to Owner's Capital</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="border rounded-lg shadow-inner bg-gray-50 overflow-hidden">
                <div class="overflow-x-auto overflow-y-auto" style="max-height: calc(100vh - 400px); min-height: 500px;">
                    <div id="spreadsheet" class="bg-white min-w-full"></div>
                </div>
            </div>

            <!-- Mobile Scroll Hint -->
            <div class="mt-2 text-xs text-gray-500 sm:hidden text-center">
                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
                Swipe to scroll spreadsheet
            </div>

            <form id="saveForm" method="POST" action="{{ route('students.performance-tasks.save-step', ['id' => $performanceTask->id, 'step' => 9]) }}" class="mt-4">
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
                : Array(20).fill().map(() => Array(3).fill(''));

            // Instructor's correct data
            const correctData = @json($answerSheet->correct_data ?? null);
            const submissionStatus = @json($submission->status ?? null);

            // Initialize HyperFormula for Excel-like formulas
            const hyperformulaInstance = HyperFormula.buildEmpty({
                licenseKey: 'internal-use-in-handsontable',
            });

            // Determine responsive dimensions
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;

            // Initialize Handsontable with General Journal format
            hot = new Handsontable(container, {
                data: initialData,
                rowHeaders: true,
                colHeaders: [
                    'Account Title',
                    'Debit',
                    'Credit'
                ],
                columns: [
                    { 
                        type: 'text',
                        width: isMobile ? 200 : 350
                    },
                    { 
                        type: 'numeric', 
                        numericFormat: { pattern: '0,0.00' },
                        width: isMobile ? 100 : 140
                    },
                    { 
                        type: 'numeric', 
                        numericFormat: { pattern: '0,0.00' },
                        width: isMobile ? 100 : 140
                    }
                ],
                width: '100%',
                height: isMobile ? 400 : (isTablet ? 500 : 600),
                licenseKey: 'non-commercial-and-evaluation',
                formulas: { engine: hyperformulaInstance },
                contextMenu: true,
                undo: true,
                manualColumnResize: true,
                manualRowResize: true,
                fillHandle: true,
                autoColumnSize: false,
                autoRowSize: false,
                copyPaste: true,
                minRows: 20,
                minSpareRows: 1,
                stretchH: 'all',
                enterMoves: { row: 1, col: 0 },
                tabMoves: { row: 0, col: 1 },
                outsideClickDeselects: false,
                selectionMode: 'multiple',
                cells: function(row, col) {
                    const cellProperties = {};
                    
                    // Apply correct/incorrect coloring if submission has been graded
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
                    
                    // Apply alignment classes (merge with existing className if coloring was applied)
                    if (col === 1 || col === 2) {
                        cellProperties.className = (cellProperties.className || '') + ' htRight htMiddle';
                    } else if (col === 0) {
                        cellProperties.className = (cellProperties.className || '') + ' htLeft htMiddle';
                    }
                    
                    return cellProperties;
                },
                afterChange: function(changes, source) {
                    if (source === 'loadData') return;
                    
                    // Auto-indent account titles that don't start with a date
                    if (changes) {
                        changes.forEach(([row, prop, oldValue, newValue]) => {
                            if (prop === 0 && newValue) { // Account title column
                                if (newValue && !newValue.startsWith('  ')) {
                                    // Check if this might be a sub-account (no need to auto-indent if user handles it)
                                }
                            }
                        });
                    }
                }
            });

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    const newIsMobile = window.innerWidth < 640;
                    const newIsTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
                    const newHeight = newIsMobile ? 400 : (newIsTablet ? 500 : 600);
                    
                    hot.updateSettings({
                        height: newHeight,
                        columns: [
                            { 
                                type: 'text',
                                width: newIsMobile ? 200 : 350
                            },
                            { 
                                type: 'numeric', 
                                numericFormat: { pattern: '0,0.00' },
                                width: newIsMobile ? 100 : 140
                            },
                            { 
                                type: 'numeric', 
                                numericFormat: { pattern: '0,0.00' },
                                width: newIsMobile ? 100 : 140
                            }
                        ]
                    });
                }, 250);
            });

            // Sync data before form submission
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
            vertical-align: middle;
            background-color: #ffffff; /* Default white background */
        }
        
        .handsontable thead th {
            background-color: #f3f4f6;
            font-weight: 600;
            border-bottom: 2px solid #9ca3af;
        }
        
        .handsontable .htRight {
            text-align: right;
            padding-right: 8px;
        }
        
        .handsontable .htLeft {
            text-align: left;
            padding-left: 8px;
        }
        
        .handsontable .htCenter {
            text-align: center;
        }
        
        .handsontable .htMiddle {
            vertical-align: middle;
        }
        
        /* Add visual separator for journal entries */
        .handsontable tbody tr:hover {
            background-color: #f9fafb;
        }

        .handsontable .area { 
            background-color: rgba(147, 51, 234, 0.1); 
        }
        
        .handsontable { 
            position: relative; 
            z-index: 1; 
        }
        
        #spreadsheet { 
            isolation: isolate; 
        }
        
        .overflow-x-auto { 
            -webkit-overflow-scrolling: touch; 
            scroll-behavior: smooth; 
        }

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
        .hantml:handantable td.cell-wrong.current {
            background-color: #fecaca !important; /* Slightly darker red when selected */
        }

        @media (max-width: 640px) {
            .handsontable { font-size: 12px; }
            .handsontable th, .handsontable td { padding: 4px; }
        }

        @media (min-width: 640px) and (max-width: 1024px) {
            .handsontable { font-size: 13px; }
        }
    </style>
</x-app-layout>