<x-app-layout>
    <div class="flex justify-end px-8 mt-4">
        <a href="{{ route('admin.badges.create') }}" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#FF92C2] hover:bg-[#ff6fb5] rounded-lg shadow-sm hover:shadow transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>Add Badge
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Icon</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Name</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Description</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">XP Required</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Type</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                                @forelse ($badges as $badge)
                                    <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm text-gray-700">
                                            @if($badge->icon_path)
                                                <img src="{{ Storage::url($badge->icon_path) }}" 
                                                     alt="{{ $badge->name }}" 
                                                     class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-[#FFC8FB] flex items-center justify-center">
                                                    <i class="fas fa-medal text-[#FF92C2]"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm text-gray-700">{{ $badge->name }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-700">{{ Str::limit($badge->description, 50) }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-700">{{ $badge->xp_threshold }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-700">{{ ucfirst($badge->criteria) }}</td>
                                        <td class="py-4 px-6 text-sm space-x-2">
                                            <a href="{{ route('admin.badges.show', $badge) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.badges.edit', $badge) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.badges.destroy', $badge) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" 
                                                        onclick="return confirm('Are you sure you want to delete this badge?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 px-6 text-sm text-center text-gray-500">No badges found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $badges->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
