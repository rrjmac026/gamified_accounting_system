<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">
                <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] mb-6">XP Transaction Details</h2>

                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Student</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $xpTransaction->student->user->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Amount</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $xpTransaction->amount }} XP</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Type</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB] capitalize">{{ $xpTransaction->type }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Source</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB] capitalize">{{ str_replace('_', ' ', $xpTransaction->source) }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Description</label>
                        <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $xpTransaction->description }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Processed At</label>
                        <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $xpTransaction->processed_at->format('F j, Y g:i A') }}</p>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.xp-transactions.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-all duration-200">
                            Back
                        </a>
                        <a href="{{ route('admin.xp-transactions.edit', $xpTransaction) }}" class="px-6 py-2 bg-[#FF92C2] hover:bg-[#ff6fb5] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
