@section('title', 'Leaderboards')
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700">
                    <!-- Filters Section -->
                    <div class="mb-6 space-y-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <h2 class="text-2xl font-bold text-[#FF92C2]">Leaderboard</h2>
                            
                            <!-- Export Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" type="button" class="px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 inline-flex items-center">
                                    <i class="fas fa-download mr-2"></i>
                                    Export
                                    <i class="fas fa-chevron-down ml-2"></i>
                                </button>
                                <div x-show="open" 
                                     @click.away="open = false"
                                     class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <a href="{{ route('admin.leaderboards.export', ['format' => 'csv'] + request()->all()) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-file-csv mr-2"></i>Export as CSV
                                        </a>
                                        <a href="{{ route('admin.leaderboards.export', ['format' => 'pdf'] + request()->all()) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-file-pdf mr-2"></i>Export as PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Form -->
                        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                            <!-- Period Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                                <select name="period" class="w-full rounded-lg border-[#FFC8FB] focus:border-[#FF92C2] focus:ring focus:ring-pink-200">
                                    @foreach(['overall' => 'Overall', 'weekly' => 'This Week', 'monthly' => 'This Month', 'semester' => 'This Semester'] as $value => $label)
                                        <option value="{{ $value }}" {{ $periodType === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Section Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Section</label>
                                <select name="section" class="w-full rounded-lg border-[#FFC8FB] focus:border-[#FF92C2] focus:ring focus:ring-pink-200">
                                    <option value="">All Sections</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ $sectionId == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Course Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                                <select name="course" class="w-full rounded-lg border-[#FFC8FB] focus:border-[#FF92C2] focus:ring focus:ring-pink-200">
                                    <option value="">All Courses</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ $courseId == $course->id ? 'selected' : '' }}>
                                            {{ $course->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                <select name="sort" class="w-full rounded-lg border-[#FFC8FB] focus:border-[#FF92C2] focus:ring focus:ring-pink-200">
                                    <option value="xp" {{ $sort === 'xp' ? 'selected' : '' }}>XP Earned</option>
                                    <option value="tasks" {{ $sort === 'tasks' ? 'selected' : '' }}>Tasks Completed</option>
                                    <option value="name" {{ $sort === 'name' ? 'selected' : '' }}>Student Name</option>
                                </select>
                            </div>

                            <!-- Filter Button -->
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                                    <i class="fas fa-filter mr-2"></i>Apply Filters
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Leaderboard Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Rank</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Student</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">XP Earned</th>
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Tasks Completed</th>
                                    <!-- <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Actions</th> -->
                                </tr>
                            </thead>
                            <tbody class="bg-[#FFF6FD] divide-y divide-[#FFC8FB]">
                                @forelse($ranked as $rank)
                                    <tr class="hover:bg-[#FFD9FF] transition-colors duration-150">
                                        <td class="py-4 px-6 text-sm">
                                            @if($rank['rank_position'] <= 3)
                                                <span class="flex items-center">
                                                    @if($rank['rank_position'] === 1)
                                                        <i class="fas fa-trophy text-yellow-400 mr-2"></i>
                                                    @elseif($rank['rank_position'] === 2)
                                                        <i class="fas fa-medal text-gray-400 mr-2"></i>
                                                    @else
                                                        <i class="fas fa-award text-yellow-700 mr-2"></i>
                                                    @endif
                                                    {{ $rank['rank_position'] }}
                                                </span>
                                            @else
                                                {{ $rank['rank_position'] }}
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-sm">{{ $rank['name'] }}</td>
                                        <td class="py-4 px-6 text-sm">
                                            <span class="flex items-center text-yellow-600">
                                                <i class="fas fa-star mr-2"></i>
                                                {{ number_format($rank['total_xp']) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-sm">{{ $rank['tasks_completed'] }}</td>
                                        <!-- <td class="py-4 px-6 text-sm space-x-2">
                                            <a href="{{ route('admin.student.show', $rank['student_id']) }}" 
                                               class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td> -->
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-6 text-sm text-center text-gray-500">
                                            No rankings found for this period
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
