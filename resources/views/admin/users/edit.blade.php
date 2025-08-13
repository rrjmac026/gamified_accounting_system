<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">
                <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FFC8FB] mb-6">Edit User</h2>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">ID Number</label>
                            <input type="text" name="id_number" value="{{ $user->id_number }}" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Role</label>
                            <select name="role" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200" required>
                                <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="instructor" {{ $user->role === 'instructor' ? 'selected' : '' }}>Instructor</option>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">New Password</label>
                            <input type="password" name="password" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                            <p class="text-sm text-gray-500 mt-1">Leave blank to keep current password</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FFC8FB] mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="w-full rounded-lg bg-white dark:bg-[#595758] border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-4">
                        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
