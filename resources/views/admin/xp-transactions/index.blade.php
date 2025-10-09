@section('title', 'XP Transactions')
<x-app-layout>
    <!-- 🌸 Search & Add Button -->
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-4 sm:px-8 mt-4">
        <!-- Search Bar -->
        <div class="w-full sm:w-2/3 bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-[#FFC8FB]/30 p-4">
            <div class="flex items-center mb-2">
                <div class="w-8 h-8 bg-gradient-to-r from-[#FF92C2] to-[#FFC8FB] rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-search text-white text-sm"></i>
                </div>
                <h3 class="text-sm sm:text-base font-semibold text-gray-800">Search XP Transactions</h3>
            </div>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                    type="text" 
                    id="xp-search"
                    placeholder="Search by student, type, source, or date..." 
                    class="w-full pl-11 pr-4 py-2 sm:py-3 border border-[#FFC8FB]/50 rounded-xl bg-white/70 focus:bg-white focus:border-[#FF92C2] focus:ring-2 focus:ring-[#FF92C2]/20 focus:outline-none transition-all duration-200 text-gray-700 placeholder-gray-400">
            </div>

            <div class="flex justify-between items-center text-xs text-gray-500 mt-2">
                <span id="xp-counter">Showing {{ $transactions->count() }} transactions</span>
                @if(request('search'))
                    <a href="{{ route('admin.xp-transactions.index') }}" 
                       class="text-[#FF92C2] hover:text-[#ff6fb5] flex items-center gap-1">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </div>

        <!-- Add Button -->
        <a href="{{ route('admin.xp-transactions.create') }}" 
           class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-[#FF92C2] hover:bg-[#ff6fb5] rounded-lg shadow-sm hover:shadow transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>Add XP Transaction
        </a>
    </div>

    <!-- 🌸 XP Transactions Table -->
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 rounded-lg sm:rounded-2xl">
                <div class="p-4 sm:p-6 text-gray-700">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Student</th>
                                    <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Amount</th>
                                    <th class="hidden sm:table-cell py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Type</th>
                                    <th class="hidden md:table-cell py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Source</th>
                                    <th class="hidden lg:table-cell py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Date</th>
                                    <th class="py-2 sm:py-3 px-3 sm:px-6 text-left text-xs sm:text-sm font-medium text-[#595758]">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="xp-table-body" class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                                @forelse ($transactions as $transaction)
                                    <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                        <td class="py-4 px-3 sm:px-6 text-sm">{{ $transaction->student->user->name ?? 'N/A' }}</td>
                                        <td class="py-4 px-3 sm:px-6 text-sm">{{ $transaction->amount }} XP</td>
                                        <td class="hidden sm:table-cell py-4 px-3 sm:px-6 text-sm capitalize">{{ $transaction->type }}</td>
                                        <td class="hidden md:table-cell py-4 px-3 sm:px-6 text-sm capitalize">{{ str_replace('_', ' ', $transaction->source) }}</td>
                                        <td class="hidden lg:table-cell py-4 px-3 sm:px-6 text-sm">{{ $transaction->processed_at->format('M d, Y h:i A') }}</td>
                                        <td class="py-4 px-3 sm:px-6 text-sm space-x-2">
                                            <a href="{{ route('admin.xp-transactions.show', $transaction) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.xp-transactions.edit', $transaction) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-3 sm:px-6 text-sm text-center">No transactions found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🌸 Search Script -->
    <script>
        const xpSearch = document.getElementById("xp-search");
        const xpTableBody = document.getElementById("xp-table-body");
        const xpRows = xpTableBody.getElementsByTagName("tr");
        const xpCounter = document.getElementById("xp-counter");

        xpSearch.addEventListener("keyup", function() {
            let searchValue = this.value.toLowerCase();
            let visibleCount = 0;

            for (let i = 0; i < xpRows.length; i++) {
                let rowText = xpRows[i].textContent.toLowerCase();

                if (rowText.includes(searchValue)) {
                    xpRows[i].style.display = "";
                    visibleCount++;
                } else {
                    xpRows[i].style.display = "none";
                }
            }

            xpCounter.textContent = `Showing ${visibleCount} transaction${visibleCount !== 1 ? 's' : ''}`;
        });
    </script>
</x-app-layout>
