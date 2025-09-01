@section('title', 'Sections Management')
<x-app-layout>
    <div class="flex justify-end px-4 sm:px-8 mt-4">
        <a href="{{ route('admin.sections.create') }}" 
           class="w-full sm:w-auto inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#FF92C2] hover:bg-[#ff6fb5] rounded-lg shadow-sm hover:shadow transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>Add Section
        </a>
    </div>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 rounded-lg sm:rounded-2xl">
                <div class="p-4 sm:p-6 text-gray-700">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-[#FFC8FB]">
                        <thead class="bg-[#FFC8FB]">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#595758] uppercase tracking-wider">Section Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#595758] uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#595758] uppercase tracking-wider">Course</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#595758] uppercase tracking-wider">Capacity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#595758] uppercase tracking-wider">Students</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-[#595758] uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                            @forelse ($sections as $section)
                                <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $section->section_code }}</td>
                                    <td class="px-6 py-4">{{ $section->name }}</td>
                                    <td class="px-6 py-4">{{ $section->course->course_name }}</td>
                                    <td class="px-6 py-4">{{ $section->capacity ?? 'Unlimited' }}</td>
                                    <td class="px-6 py-4">{{ $section->students->count() }}</td>
                                    <td class="px-6 py-4 space-x-2">
                                        <a href="{{ route('admin.sections.show', $section) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.sections.edit', $section) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" 
                                                    onclick="return confirm('Are you sure you want to delete this section?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No sections found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
