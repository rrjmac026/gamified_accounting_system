@section('title', 'Sections Management')
<x-app-layout>
    <div class="flex justify-between items-center px-4 sm:px-8 mt-4">
        <h1 class="text-xl sm:text-2xl font-bold text-[#FF92C2]">Sections Management</h1>
        <a href="{{ route('admin.sections.create') }}" 
           class="w-full sm:w-auto inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#FF92C2] hover:bg-[#ff6fb5] rounded-lg shadow-sm hover:shadow transition-all duration-200">
            <i class="fas fa-plus mr-2"></i>Add Section
        </a>
    </div>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 rounded-lg sm:rounded-2xl">
                <div class="p-4 sm:p-6 text-gray-700">

                    {{-- Flash message --}}
                    @if (session('success'))
                        <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Search Bar --}}
                    <div class="flex justify-between items-center mb-4">
                        <input type="text" id="section-search" 
                            placeholder="Search sections..." 
                            class="w-full sm:w-1/3 px-3 py-2 text-sm border border-[#FFC8FB] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF92C2] focus:border-transparent shadow-sm">
                        <span class="ml-4 text-xs text-gray-500" id="section-counter">
                            Showing {{ $sections->count() }} sections
                        </span>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-[#FFC8FB] rounded-lg overflow-hidden">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-[#595758] uppercase">Section Code</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-[#595758] uppercase">Section Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-[#595758] uppercase">Subject Codes</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-[#595758] uppercase">Capacity</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-[#595758] uppercase">Students</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-[#595758] uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="section-table-body" class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                                @forelse ($sections as $section)
                                    <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $section->section_code }}</td>
                                        <td class="px-6 py-4">{{ $section->name }}</td>
                                        <td class="px-6 py-4">
                                            @if($section->subjects->count())
                                                <ul class="space-y-1">
                                                    @foreach($section->subjects as $subject)
                                                        <li class="inline-block">
                                                            <a href="{{ route('admin.subjects.show', $subject) }}" 
                                                            class="bg-[#FFE6F0] text-[#FF92C2] px-2 py-1 rounded text-sm hover:bg-[#FF92C2] hover:text-white transition">
                                                                {{ $subject->subject_code }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-gray-400">No subjects assigned</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">{{ $section->capacity ?? 'Unlimited' }}</td>
                                        <td class="px-6 py-4">{{ $section->students->count() }}</td>
                                        <td class="px-6 py-4 flex items-center gap-3">
                                            <a href="{{ route('admin.sections.show', $section) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.sections.edit', $section) }}" class="text-[#FF92C2] hover:text-[#ff6fb5]" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" 
                                                        onclick="return confirm('Are you sure you want to delete this section?')" title="Delete">
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
    </div>

    {{-- Search Script --}}
    <script>
        const searchInput = document.getElementById("section-search");
        const tableBody = document.getElementById("section-table-body");
        const rows = tableBody.getElementsByTagName("tr");
        const counter = document.getElementById("section-counter");

        searchInput.addEventListener("keyup", function() {
            let searchValue = this.value.toLowerCase();
            let visibleCount = 0;

            for (let i = 0; i < rows.length; i++) {
                let rowText = rows[i].textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    rows[i].style.display = "";
                    visibleCount++;
                } else {
                    rows[i].style.display = "none";
                }
            }
            counter.textContent = `Showing ${visibleCount} section(s)`;
        });
    </script>
</x-app-layout>
