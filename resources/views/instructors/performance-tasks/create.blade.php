<x-app-layout>
    <!-- Handsontable -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable@14.1.0/dist/handsontable.full.min.css" />
    <!-- Formula Parser (HyperFormula) -->
    <script src="https://cdn.jsdelivr.net/npm/hyperformula@2.6.2/dist/hyperformula.full.min.js"></script>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Create Performance Task
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('instructors.performance-tasks.store') }}" method="POST" id="taskForm">
                    @csrf

                    <!-- Task Title -->
                    <div class="mb-4">
                        <label for="title" class="block font-medium text-sm text-gray-700">
                            Title <span class="text-red-500">*</span>
                        </label>
                        <input id="title" name="title" type="text" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 @error('title') border-red-500 @enderror" 
                               value="{{ old('title') }}" required>
                        @error('title')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subject -->
                    <div class="mb-4">
                        <label for="subject_id" class="block font-medium text-sm text-gray-700">
                            Subject <span class="text-red-500">*</span>
                        </label>
                        <select id="subject_id" name="subject_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 @error('subject_id') border-red-500 @enderror" 
                                required>
                            <option value="">-- Select Subject --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" 
                                    {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->subject_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Section -->
                    <div class="mb-4">
                        <label for="section_id" class="block font-medium text-sm text-gray-700">
                            Section <span class="text-red-500">*</span>
                        </label>
                        <select id="section_id" name="section_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 @error('section_id') border-red-500 @enderror" 
                                required>
                            <option value="">-- Select Section --</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" 
                                    {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('section_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" 
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- XP Reward -->
                    <div class="mb-4">
                        <label for="xp_reward" class="block font-medium text-sm text-gray-700">
                            XP Reward <span class="text-red-500">*</span>
                        </label>
                        <input id="xp_reward" name="xp_reward" type="number" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 @error('xp_reward') border-red-500 @enderror" 
                               value="{{ old('xp_reward', 50) }}" min="0" required>
                        @error('xp_reward')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Attempts -->
                    <div class="mb-4">
                        <label for="max_attempts" class="block font-medium text-sm text-gray-700">
                            Max Attempts <span class="text-red-500">*</span>
                        </label>
                        <input id="max_attempts" name="max_attempts" type="number" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 p-2 @error('max_attempts') border-red-500 @enderror" 
                               value="{{ old('max_attempts', 2) }}" min="1" required>
                        @error('max_attempts')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Spreadsheet -->
                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700 mb-2">
                            Spreadsheet Template <span class="text-red-500">*</span>
                        </label>
                        <div class="mb-2 text-sm text-gray-600">
                            <strong>Excel Features:</strong> Formulas (=SUM, =AVERAGE, etc.), Copy/Paste, Fill Down, Undo/Redo, Cell Formatting
                        </div>
                        <div id="spreadsheet" style="width: 100%; height: 500px; overflow: hidden;"></div>
                        <input type="hidden" name="template_data" id="templateData" required>
                        @error('template_data')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center gap-4">
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Save Task
                        </button>
                        <a href="{{ route('instructors.performance-tasks.index') }}" 
                           class="px-4 py-2 bg-gray-300 text-gray-700 rounded shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let hot;

        document.addEventListener("DOMContentLoaded", function () {
            const container = document.getElementById('spreadsheet');

            // Initialize HyperFormula for Excel-like formulas
            const hyperformulaInstance = HyperFormula.buildEmpty({
                licenseKey: 'internal-use-in-handsontable',
            });

            hot = new Handsontable(container, {
                data: [
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', ''],
                    ['', '', '', '', '', '', '', '', '', '']
                ],
                colHeaders: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'],
                rowHeaders: true,
                width: '100%',
                height: 500,
                licenseKey: 'non-commercial-and-evaluation',
                
                // Excel-like formula support
                formulas: {
                    engine: hyperformulaInstance,
                },
                
                // Column settings
                columns: [
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' },
                    { type: 'text' }
                ],
                
                // Excel-like features
                contextMenu: [
                    'row_above',
                    'row_below',
                    'col_left',
                    'col_right',
                    '---------',
                    'remove_row',
                    'remove_col',
                    '---------',
                    'undo',
                    'redo',
                    '---------',
                    'copy',
                    'cut',
                    'paste',
                    '---------',
                    'mergeCells',
                    '---------',
                    'alignment',
                    '---------',
                    'clear_column'
                ],
                
                // Enable Excel-like features
                undo: true,
                manualColumnResize: true,
                manualRowResize: true,
                manualColumnMove: true,
                manualRowMove: true,
                fillHandle: true, // Fill down/right like Excel
                autoColumnSize: false,
                autoRowSize: false,
                
                // Copy/Paste
                copyPaste: true,
                
                // Cell properties
                cell: [],
                
                minRows: 15,
                minCols: 10,
                stretchH: 'all',
                
                // Keyboard shortcuts
                enterMoves: { row: 1, col: 0 },
                tabMoves: { row: 0, col: 1 },
                
                // Selection
                outsideClickDeselects: false,
                selectionMode: 'multiple',
                
                // Merge cells support
                mergeCells: true,
                
                // Comments
                comments: true,
                
                // Custom borders
                customBorders: true,
            });

            // Capture spreadsheet data on submit
            document.getElementById("taskForm").addEventListener("submit", function (e) {
                // Prevent double submission
                const submitButton = e.target.querySelector('button[type="submit"]');
                if (submitButton.disabled) {
                    e.preventDefault();
                    return;
                }
                
                // Get the spreadsheet data
                const data = hot.getData();
                
                // Set the template data
                document.getElementById("templateData").value = JSON.stringify(data);
                
                // Validate that subject and section are selected
                const subjectId = document.getElementById('subject_id').value;
                const sectionId = document.getElementById('section_id').value;
                
                if (!subjectId || !sectionId) {
                    e.preventDefault();
                    alert('Please select both Subject and Section before submitting.');
                    return false;
                }
                
                // Disable submit button to prevent double submission
                submitButton.disabled = true;
                submitButton.textContent = 'Saving...';
            });

            // Keyboard shortcuts info
            console.log('Excel-like shortcuts:');
            console.log('Ctrl+C: Copy');
            console.log('Ctrl+V: Paste');
            console.log('Ctrl+X: Cut');
            console.log('Ctrl+Z: Undo');
            console.log('Ctrl+Y: Redo');
            console.log('Fill Handle: Drag corner of cell to fill down/right');
        });
    </script>

    <style>
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
    </style>
</x-app-layout>