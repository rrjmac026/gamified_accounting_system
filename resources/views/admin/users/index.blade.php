<x-app-layout>
    <div class="flex justify-end px-8 mt-4">
        <a href="{{ route('admin.users.create') }}" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#FF92C2] hover:bg-[#ff6fb5] rounded-lg shadow-sm hover:shadow transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>Add User
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:bg-[#595758] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700 dark:text-[#FFC8FB]">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Name</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Email</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">ID Number</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Role</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Status</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-[#595758] dark:text-[#FFC8FB]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#FFF6FD] dark:bg-[#595758] divide-y divide-[#FFC8FB]">
                                @forelse ($users as $user)
                                    <tr class="hover:bg-[#FFD9FF] dark:hover:bg-[#6a6869] transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm">{{ $user->name }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $user->email }}</td>
                                        <td class="py-4 px-6 text-sm">{{ $user->id_number }}</td>

                                        <td class="py-4 px-6 text-sm capitalize">{{ $user->role }}</td>
                                        <td class="py-4 px-6 text-sm">
                                            <span class="px-2 py-1 rounded-full text-xs {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-sm space-x-2">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-6 text-sm text-center">No users found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
