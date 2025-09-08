@section('title', 'Instructors List')

<x-app-layout>
    {{-- Add Instructor Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-center px-4 sm:px-8 mt-4 gap-4">
        <h2 class="text-lg sm:text-xl font-semibold text-[#FF92C2]">Instructor Management</h2>
        <div class="w-full sm:w-auto">
            <a href="{{ route('admin.instructors.create') }}" 
               class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-pink-500 text-white rounded-lg hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-colors duration-200 flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i>
                Add Instructor
            </a>
        </div>
    </div>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-700">
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Responsive Table --}}
                    <div class="overflow-x-auto relative sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-700">
                            <thead class="bg-[#FFC8FB] text-xs uppercase">
                                <tr>
                                    <th scope="col" class="py-3 px-4">
                                        Name/Email
                                    </th>
                                    <th scope="col" class="hidden md:table-cell py-3 px-4">
                                        Employee ID
                                    </th>
                                    <th scope="col" class="hidden lg:table-cell py-3 px-4">
                                        Department/Specialization
                                    </th>
                                    <th scope="col" class="py-3 px-4">
                                        Stats
                                    </th>
                                    <th scope="col" class="py-3 px-4">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($instructors as $instructor)
                                    <tr class="bg-white border-b border-[#FFC8FB] hover:bg-[#FFD9FF]">
                                        <td class="py-3 px-4 font-medium">
                                            {{ $instructor->name }}
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $instructor->email }}
                                            </div>
                                        </td>
                                        <td class="hidden md:table-cell py-3 px-4">
                                            {{ $instructor->employee_id ?? 'N/A' }}
                                        </td>
                                        <td class="hidden lg:table-cell py-3 px-4">
                                            <span class="block font-medium">{{ $instructor->department ?? 'N/A' }}</span>
                                            <span class="text-sm text-gray-500">{{ $instructor->specialization ?? 'N/A' }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex flex-col gap-1.5">
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-book text-xs text-gray-400"></i>
                                                    <span class="text-xs">{{ $instructor->stats['total_subjects'] ?? 0 }} subjects</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-tasks text-xs text-gray-400"></i>
                                                    <span class="text-xs">{{ $instructor->stats['active_tasks'] ?? 0 }} tasks</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <i class="fas fa-users text-xs text-gray-400"></i>
                                                    <span class="text-xs">{{ $instructor->stats['total_students'] ?? 0 }} students</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="flex flex-col sm:flex-row gap-2">
                                                <a href="{{ route('admin.instructors.show', $instructor->id) }}" 
                                                   class="inline-flex items-center text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="ml-2 sm:hidden">View</span>
                                                </a>
                                                <a href="{{ route('admin.instructors.edit', $instructor->id) }}" 
                                                   class="inline-flex items-center text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="ml-2 sm:hidden">Edit</span>
                                                </a>
                                                <form action="{{ route('admin.instructors.destroy', $instructor->id) }}" 
                                                      method="POST" 
                                                      class="inline-flex" 
                                                      onsubmit="return confirm('Are you sure you want to delete this instructor?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                        <span class="ml-2 sm:hidden">Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 px-4 text-center text-gray-500">
                                            <div class="flex flex-col items-center">
                                                <i class="fas fa-user-tie text-4xl mb-4"></i>
                                                <p class="text-lg font-medium text-gray-900 mb-1">No instructors found</p>
                                                <p class="text-gray-600">Add a new instructor to get started</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($instructors->hasPages())
                        <div class="mt-4 sm:mt-6">
                            {{ $instructors->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

