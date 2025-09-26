@section('title', 'Instructors List')

<x-app-layout>
    {{-- Add Instructor Header Section --}}
    <div class="flex flex-col sm:flex-row justify-between items-center px-4 sm:px-8 mt-4 gap-4">
        <h2 class="text-lg sm:text-xl font-semibold text-[#FF92C2]">Instructor Management</h2>
        <div class="w-full sm:w-auto">
            <a href="{{ route('admin.instructors.create') }}" 
               class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-lg hover:from-pink-600 hover:to-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg">
                <i class="fas fa-plus mr-2"></i>
                Add Instructor
            </a>
        </div>
    </div>

    {{-- Enhanced Search Form --}}
    <div class="px-4 sm:px-8 mt-8">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-[#FFC8FB]/30 p-6">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-gradient-to-r from-[#FF92C2] to-[#FFC8FB] rounded-full flex items-center justify-center mr-3">
                    <i class="fas fa-search text-white text-sm"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Search Instructors</h3>
            </div>
            
            <form action="{{ route('admin.instructors.index') }}" method="GET" class="space-y-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search by name, email, or department..." 
                           class="w-full pl-11 pr-4 py-3 border border-[#FFC8FB]/50 rounded-xl bg-white/70 focus:bg-white focus:border-[#FF92C2] focus:ring-2 focus:ring-[#FF92C2]/20 focus:outline-none transition-all duration-200 text-gray-700 placeholder-gray-400">
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-end">
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-[#FF92C2] to-[#ff6fb5] text-white rounded-xl hover:from-[#ff6fb5] hover:to-[#FF92C2] focus:outline-none focus:ring-2 focus:ring-[#FF92C2]/30 transition-all duration-200 font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <i class="fas fa-search mr-2"></i>
                        Search Instructors
                    </button>
                    
                    @if(request('search'))
                        <a href="{{ route('admin.instructors.index') }}" 
                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-all duration-200 font-medium border border-gray-200 hover:border-gray-300 flex items-center justify-center">
                            <i class="fas fa-times mr-2"></i>
                            Clear Search
                        </a>
                    @endif
                </div>

                {{-- Search Results Info --}}
                @if(request('search'))
                    <div class="flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            <span class="text-sm text-blue-700">
                                Showing results for: <strong>"{{ request('search') }}"</strong>
                            </span>
                        </div>
                        <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                            {{ $instructors->total() ?? 0 }} found
                        </span>
                    </div>
                @endif
            </form>
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
                    <div class="relative">
                        <div class="overflow-x-auto">
                            <div class="inline-block min-w-full align-middle">
                                <div class="overflow-hidden shadow-md rounded-lg">
                                    <table class="min-w-full divide-y divide-[#FFC8FB]">
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
                                                        @if(request('search'))
                                                            <p class="text-lg font-medium text-gray-900 mb-1">No instructors found</p>
                                                            <p class="text-gray-600">No results match your search for "{{ request('search') }}"</p>
                                                            <a href="{{ route('admin.instructors.index') }}" 
                                                               class="mt-3 text-[#FF92C2] hover:text-[#ff6fb5] font-medium">
                                                                Clear search and view all instructors
                                                            </a>
                                                        @else
                                                            <p class="text-lg font-medium text-gray-900 mb-1">No instructors found</p>
                                                            <p class="text-gray-600">Add a new instructor to get started</p>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
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