<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">
                <h2 class="text-2xl font-bold text-[#FF92C2] mb-6">Create XP Transaction</h2>

                <form action="{{ route('admin.xp-transactions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Student</label>
                        <select name="student_id" required class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Amount (XP)</label>
                        <input type="number" name="amount" required class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Type</label>
                            <select name="type" required class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Source</label>
                            <select name="source" required class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                                @foreach($sources as $source)
                                    <option value="{{ $source }}">{{ ucfirst(str_replace('_', ' ', $source)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Description</label>
                        <textarea name="description" required class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" rows="3"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Process Date</label>
                        <input type="datetime-local" name="processed_at" required class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-[#FF92C2] hover:bg-[#ff6fb5] text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            Create Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
