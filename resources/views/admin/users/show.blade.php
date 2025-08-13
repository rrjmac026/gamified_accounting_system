<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">
                <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] mb-6">User Details</h2>

                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Full Name</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $user->name }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Email</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">ID Number</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $user->id_number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Employee ID</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB]">
                                {{ $user->instructor->employee_id ?? 'N/A' }}
                            </p>
                        </div>


                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Role</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB] capitalize">{{ $user->role }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Status</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB]">
                                <span class="px-2 py-1 rounded-full text-xs {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Joined Date</label>
                            <p class="text-gray-700 dark:text-[#FFC8FB]">{{ $user->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Back</a>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">Edit User</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
