@section('title', 'System Reports')

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-lg sm:rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] mb-6">System Reports</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Report Cards -->
                    <div class="bg-white dark:bg-[#4a4949] rounded-lg p-6 shadow hover:shadow-lg transition-shadow">
                        <h3 class="text-lg font-semibold text-[#FF92C2] mb-4">Student Reports</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">View detailed student performance and progress reports.</p>
                        <form action="{{ route('admin.reports.students') }}" method="GET" class="space-y-4">
                            <div class="space-y-2">
                                <input type="date" name="date_from" class="w-full rounded-lg border-[#FFC8FB]">
                                <input type="date" name="date_to" class="w-full rounded-lg border-[#FFC8FB]">
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                                Generate Report
                            </button>
                        </form>
                    </div>

                    <!-- Add similar cards for other report types -->
                    <!-- ...existing report cards... -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
