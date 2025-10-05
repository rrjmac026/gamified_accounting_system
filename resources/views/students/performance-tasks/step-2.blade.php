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
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-red-800 mb-1">Error</h3>
                        <p class="text-sm text-red-700 leading-relaxed">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Similar structure as step-1 but for General Ledger -->
        <div class="mb-6 sm:mb-8">
            <div class="relative">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 text-blue-700 rounded-full text-xs sm:text-sm font-semibold mb-3">
                    <span>Step 2 of 10</span>
                </div>
                
                <div class="flex-1">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 tracking-tight">
                        General Ledger
                    </h1>
                    <p class="mt-3 text-sm sm:text-base text-gray-600 leading-relaxed max-w-3xl">
                        Transfer entries from the journal to individual ledger accounts.
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Add spreadsheet implementation similar to step-1 -->
            <div id="spreadsheet"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('spreadsheet');
            
            // Get saved data if it exists
            const savedData = @json($submission->submission_data ?? null);
            const initialData = savedData ? JSON.parse(savedData) : Array(15).fill().map(() => Array(14).fill(''));

            hot = new Handsontable(container, {
                data: initialData,
                // ...rest of your Handsontable configuration...
            });
        });
    </script>
</x-app-layout>