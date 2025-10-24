<x-app-layout>
    <!-- Handsontable -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.css" />
    <!-- Formula Parser (HyperFormula) -->
    <script src="https://cdn.jsdelivr.net/npm/hyperformula@2.6.2/dist/hyperformula.full.min.js"></script>
    
    <div class="py-4 sm:py-6 lg:py-8">
         @if (session('error'))
            <div class="mb-6 animate-slideDown">
                <div class="flex items-start gap-3 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-red-800 mb-1">Error</h3>
                        <p class="text-sm text-red-700 leading-relaxed">{{ session('error') }}</p>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-red-400 hover:text-red-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 animate-slideDown">
                <div class="flex items-start gap-3 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-green-800 mb-1">Success</h3>
                        <p class="text-sm text-green-700 leading-relaxed">{{ session('success') }}</p>
                    </div>
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 text-green-400 hover:text-green-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <x-view-answers-button :submission="$submission" :performanceTask="$performanceTask" :step="$step" />

        <!-- Enhanced Header Section with better design -->
        <div class="mb-6 sm:mb-8">
            <div class="relative">
                <!-- Step Indicator Badge -->
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs sm:text-sm font-semibold mb-3">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Step 1 of 10</span>
                </div>
                
                <!-- Title Section -->
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">
                            Analyzing Transactions
                        </h1>
                        <p class="mt-3 text-sm sm:text-base text-gray-600 leading-relaxed max-w-3xl">
                            Identify which accounts are affected by each transaction and determine whether they should be debited or credited before recording them in the journal.
                        </p>
                        <!-- Add attempts counter -->
                        <div class="mt-2 text-sm text-gray-600">
                            Attempts remaining: {{ 2 - ($submission->attempts ?? 0) }}/2
                        </div>
                    </div>
                </div>
            </div>
        </div>

           

            <!-- Main Content Card -->
            <div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Instructions Section -->
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <p class="text-xs sm:text-sm text-gray-600">
                        {!! $performanceTask->description ?? 'No instructions provided by your instructor.' !!}
                    </p>
                </div>

                <form id="taskForm" action="{{ route('students.performance-tasks.save-step', ['id' => $performanceTask->id, 'step' => $step ?? 1]) }}" method="POST">
                    @csrf
                    <!-- Spreadsheet Section -->
                    <div class="p-3 sm:p-4 lg:p-6">
                        <!-- Spreadsheet Container with Scroll -->
                        <div class="border rounded-lg shadow-inner bg-gray-50 overflow-hidden">
                            <div class="overflow-x-auto overflow-y-auto" style="max-height: calc(100vh - 400px); min-height: 400px;">
                                <div id="spreadsheet" class="bg-white min-w-full"></div>
                            </div>
                            <input type="hidden" name="submission_data" id="submissionData" required>
                        </div>

                        <!-- Mobile Scroll Hint -->
                        <div class="mt-2 text-xs text-gray-500 sm:hidden text-center">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            Swipe to scroll spreadsheet
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="p-4 sm:p-6 bg-gray-50 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4">
                            <button type="button" onclick="window.history.back()" class="inline-flex items-center justify-center px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors text-sm sm:text-base">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back
                            </button>
                            <button type="submit" id="submitButton" 
                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition-colors text-sm sm:text-base"
                            {{ ($submission->attempts ?? 0) >= 2 ? 'disabled' : '' }}>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Save and Continue
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let hot;

        document.addEventListener("DOMContentLoaded", function () {
            const container = document.getElementById('spreadsheet');
            
            // Get saved data if it exists
            const savedData = @json($submission->submission_data ?? null);
            const initialData = savedData ? JSON.parse(savedData) : Array(15).fill().map(() => Array(14).fill(''));
            
            // Get correct answer data and submission status
            const correctData = @json($answerSheet->correct_data ?? null);
            const submissionStatus = @json($submission->status ?? null);
            const isReadOnly = @json(($submission->attempts ?? 0) >= 2);

            // Initialize HyperFormula
            const hyperformulaInstance = HyperFormula.buildEmpty({
                licenseKey: 'internal-use-in-handsontable',
            });

            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
            
            hot = new Handsontable(container, {
                data: initialData,
                colHeaders: false,
                rowHeaders: true,
                width: '100%',
                height: isMobile ? 350 : (isTablet ? 450 : 500),
                licenseKey: 'non-commercial-and-evaluation',
                readOnly: isReadOnly, // Lock spreadsheet after 2 attempts

                nestedHeaders: [
                    [
                        '',
                        { label: 'ASSETS', colspan: 6 },
                        { label: 'LIABILITIES', colspan: 2 },
                        { label: "OWNER'S EQUITY", colspan: 3 },
                        { label: 'EXPENSES', colspan: 3 }
                    ],
                    [
                        '',
                        'Cash', 'Accounts Receivable', 'Supplies', 'Furniture & Fixtures', 'Land', 'Equipment',
                        'Accounts Payable', 'Notes Payable',
                        'Capital', 'Withdrawal', 'Service Revenue',
                        'Rent Expense', 'Utilities Expense', 'Salaries Expense'
                    ]
                ],

                columns: Array(14).fill({ type: 'text' }),
                colWidths: isMobile ? 100 : (isTablet ? 110 : 120),

                formulas: { engine: hyperformulaInstance },
                contextMenu: !isReadOnly,
                undo: !isReadOnly,
                manualColumnResize: true,
                manualRowResize: true,
                manualColumnMove: !isReadOnly,
                manualRowMove: !isReadOnly,
                fillHandle: !isReadOnly,
                autoColumnSize: false,
                autoRowSize: false,
                copyPaste: !isReadOnly,
                minRows: 15,
                minCols: 15,
                stretchH: 'none',
                enterMoves: { row: 1, col: 0 },
                tabMoves: { row: 0, col: 1 },
                outsideClickDeselects: false,
                selectionMode: 'multiple',
                mergeCells: true,
                comments: true,
                customBorders: true,

                // Add cell renderer for color feedback
                cells: function(row, col) {
                    const cellProperties = {};
                    
                    // Only apply colors if submission exists and has been graded
                    if (submissionStatus && correctData && savedData) {
                        const parsedCorrect = typeof correctData === 'string' ? JSON.parse(correctData) : correctData;
                        const parsedStudent = typeof savedData === 'string' ? JSON.parse(savedData) : savedData;
                        
                        const studentValue = parsedStudent[row]?.[col];
                        const correctValue = parsedCorrect[row]?.[col];
                        
                        // Only compare non-empty cells
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

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    const newIsMobile = window.innerWidth < 640;
                    const newIsTablet = window.innerWidth >= 640 && window.innerWidth < 1024;
                    const newHeight = newIsMobile ? 350 : (newIsTablet ? 450 : 500);
                    
                    hot.updateSettings({
                        height: newHeight,
                        colWidths: newIsMobile ? 100 : (newIsTablet ? 110 : 120)
                    });
                }, 250);
            });

            // Capture spreadsheet data on submit
            const taskForm = document.getElementById("taskForm");
            if (taskForm && !isReadOnly) {
                taskForm.addEventListener("submit", function (e) {
                    e.preventDefault();
                    const data = hot.getData();
                    document.getElementById("submissionData").value = JSON.stringify(data);
                    this.submit();
                });
            }
        });
    </script>

    <style>

        .cell-correct {
            background-color: #dcfce7 !important; /* Light green */
            border: 2px solid #16a34a !important; /* Green border */
        }

        .cell-wrong {
            background-color: #fee2e2 !important; /* Light red */
            border: 2px solid #dc2626 !important; /* Red border */
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

        /* Read-only indicator */
        .handsontable.readOnly td {
            background-color: #f9fafb;
            cursor: not-allowed;
        }
        /* Prevent body overflow issues */
        body {
            overflow-x: hidden;
        }

        /* Custom styling for headers */
        .handsontable .font-bold {
            font-weight: bold;
        }
        .handsontable .bg-gray-100 {
            background-color: #f3f4f6 !important;
        }
        .handsontable .bg-blue-50 {
            background-color: #eff6ff !important;
        }
        
        /* Excel-like grid appearance */
        .handsontable td {
            border-color: #d1d5db;
        }
        
        /* Selected cell highlight */
        .handsontable .area {
            background-color: rgba(59, 130, 246, 0.1);
        }
        
        /* Form validation styles */
        .border-red-500 {
            border-color: #ef4444 !important;
        }

        /* Ensure proper stacking context */
        .handsontable {
            position: relative;
            z-index: 1;
        }

        /* Prevent spreadsheet from overlapping with fixed elements */
        #spreadsheet {
            isolation: isolate;
        }

        /* Smooth scrolling for touch devices */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .handsontable {
                font-size: 12px;
            }
            
            .handsontable th,
            .handsontable td {
                padding: 4px;
            }
        }

        /* Tablet optimizations */
        @media (min-width: 640px) and (max-width: 1024px) {
            .handsontable {
                font-size: 13px;
            }
        }

        /* Loading animation */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</x-app-layout>