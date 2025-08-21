@section('title', 'Leaderboards')
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300 sm:rounded-lg">
                <div class="p-6 text-gray-700">
                    <!-- Period Filter -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-[#FF92C2]">Leaderboard</h2>
                            <div class="flex gap-2">
                                @php
                                    $periods = [
                                        'weekly' => 'This Week',
                                        'monthly' => 'This Month',
                                        'semester' => 'This Semester',
                                        'overall' => 'Overall'
                                    ];
                                @endphp
                                @foreach($periods as $value => $label)
                                    <a href="{{ route('admin.leaderboards.index', ['period' => $value]) }}" 
                                       class="px-4 py-2 rounded-lg text-sm {{ $periodType === $value 
                                            ? 'bg-[#FF92C2] text-white' 
                                            : 'bg-white text-[#FF92C2] hover:bg-[#FFF0FA]' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
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
                                    <th class="py-3 px-6 text-left text-sm font-medium text-pink-900">Actions</th>
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
                                        <td class="py-4 px-6 text-sm space-x-2">
                                            <a href="{{ route('admin.student.show', $rank['student_id']) }}" 
                                               class="text-[#FF92C2] hover:text-[#ff6fb5]">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
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
