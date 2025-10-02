<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/hyperformula@2.6.2/dist/hyperformula.full.min.js"></script>

    <style>
        /* Fix z-index and overflow issues */
        .spreadsheet-container {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 500px;
            overflow: hidden;
        }

        /* Ensure Handsontable headers don't overlap navbar */
        #spreadsheet .ht_clone_top,
        #spreadsheet .ht_clone_left,
        #spreadsheet .ht_clone_top_left_corner {
            z-index: 1 !important;
        }

        /* Ensure the main table container respects boundaries */
        #spreadsheet .wtHolder {
            overflow: auto !important;
        }

        /* Prevent table from bleeding outside container */
        .handsontable {
            max-width: 100%;
        }

        /* Fix for sticky headers within container only */
        .handsontable .ht_master .wtHolder {
            overflow: auto;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $task->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow mb-6">
                <h3 class="text-lg font-semibold mb-2">Instructions</h3>
                <p class="text-gray-700 mb-4">{{ $task->description }}</p>
                
                <div class="flex gap-4 text-sm text-gray-600">
                    <span><strong>XP Reward:</strong> {{ $task->xp_reward }}</span>
                    <span><strong>Max Attempts:</strong> {{ $task->max_attempts }}</span>
                    <span><strong>Attempts Used:</strong> {{ $attemptsUsed }} / {{ $task->max_attempts }}</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <form action="{{ route('students.performance-tasks.submit', $task->id) }}" method="POST" id="submissionForm">
                    @csrf

                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700 mb-2">Your Answer</label>
                        <div class="spreadsheet-container">
                            <div id="spreadsheet"></div>
                        </div>
                        <input type="hidden" name="submission_data" id="submissionData">
                    </div>

                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded shadow hover:bg-green-700"
                            @if($attemptsUsed >= $task->max_attempts) disabled @endif>
                        Submit Answer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let hot;

        document.addEventListener("DOMContentLoaded", function () {
            const container = document.getElementById('spreadsheet');
            const templateData = @json($task->template_data);

            const hyperformulaInstance = HyperFormula.buildEmpty({
                licenseKey: 'internal-use-in-handsontable',
            });

            hot = new Handsontable(container, {
                data: templateData,
                colHeaders: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'],
                rowHeaders: true,
                width: '100%',
                height: 500,
                licenseKey: 'non-commercial-and-evaluation',
                formulas: {
                    engine: hyperformulaInstance,
                },
                columns: [
                    { type: 'text' },
                    { type: 'numeric', numericFormat: { pattern: '0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '0,0.00' } },
                    { type: 'numeric', numericFormat: { pattern: '0,0.00' } },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' }
                ],
                contextMenu: true,
                undo: true,
                manualColumnResize: true,
                manualRowResize: true,
                fillHandle: true,
                copyPaste: true,
                minRows: 15,
                minCols: 10,
                stretchH: 'all',
                // These settings ensure proper scrolling behavior
                fixedRowsTop: 0,
                fixedColumnsLeft: 0,
                // Prevent overflow issues
                preventOverflow: 'horizontal',
            });

            document.getElementById("submissionForm").addEventListener("submit", function (e) {
                const data = hot.getData();
                document.getElementById("submissionData").value = JSON.stringify(data);
            });
        });
    </script>
</x-app-layout>