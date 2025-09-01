<x-app-layout>
    <div class="flex justify-end px-4 sm:px-8 mt-4">
        <div class="flex gap-4">
            <button onclick="toggleUploadForm()" 
                   class="w-full sm:w-auto inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#8B5CF6] hover:bg-[#7C3AED] rounded-lg shadow-sm hover:shadow">
                <i class="fas fa-file-upload mr-2"></i>Bulk Upload Student Tasks
            </button>
            <a href="{{ route('instructors.tasks.download-csv-template') }}" 
               class="w-full sm:w-auto inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-sm hover:shadow">
                <i class="fas fa-download mr-2"></i>Download Template
            </a>
            <a href="{{ route('instructors.tasks.create') }}" 
               class="w-full sm:w-auto inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#FF92C2] hover:bg-[#ff6fb5] rounded-lg shadow-sm hover:shadow">
                <i class="fas fa-plus mr-2"></i>Create New Task
            </a>
        </div>
    </div>

    <!-- Upload Section -->
    <div id="uploadSection" class="max-w-4xl mx-auto mt-4 px-4 hidden">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Bulk Upload Student Task Assignments</h3>
            <p class="text-sm text-gray-600 mb-4">
                Upload a CSV file to assign existing tasks to students. The system will automatically create student accounts for new email addresses.
            </p>
            <form action="{{ route('instructors.tasks.csv-upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CSV File</label>
                        <input type="file" name="csv_file" accept=".csv,.txt" required 
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 
                                      file:rounded-full file:border-0 file:text-sm file:font-semibold
                                      file:bg-[#FF92C2] file:text-white hover:file:bg-[#ff6fb5]">
                        <p class="text-xs text-gray-500 mt-1">
                            CSV should include: student_email, task_id, status, score (optional), xp_earned (optional), submitted_at (optional), graded_at (optional)
                        </p>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="toggleUploadForm()" 
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#FF92C2] text-white rounded-lg hover:bg-[#ff6fb5]">
                            Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Warnings -->
            @if(session('warnings'))
                <div class="mb-4 p-4 bg-yellow-100 border border-yellow-200 text-yellow-700 rounded-lg">
                    <h4 class="font-semibold mb-2">Warnings:</h4>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach(session('warnings') as $warning)
                            <li class="text-sm">{{ $warning }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
                    <h4 class="font-semibold mb-2">Errors:</h4>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-[#FFF0FA] overflow-hidden shadow-xl rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-[#FF92C2]">Tasks Management</h2>
                        <div class="text-sm text-gray-600">
                            Showing only main tasks (excluding questions)
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-[#FFC8FB]">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Title</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Type</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Subject</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Due Date</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Level</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Students</th>
                                    <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-[#FFC8FB]">
                                @forelse($tasks->where('parent_task_id', null) as $task)
                                    <tr class="hover:bg-[#FFF6FD]">
                                        <td class="px-6 py-4">{{ $task->title }}</td>
                                        <td class="px-6 py-4 capitalize">{{ $task->type }}</td>
                                        <td class="px-6 py-4">{{ $task->subject->subject_name }}</td>
                                        <td class="px-6 py-4">{{ $task->due_date->format('M d, Y H:i') }}</td>
                                        <td class="px-6 py-4">{{ $task->difficulty_level }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                {{ $task->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $task->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-600">
                                                {{ $task->students->count() }} assigned
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 space-x-3">
                                            <a href="{{ route('instructors.tasks.show', $task) }}" 
                                               class="text-[#FF92C2] hover:text-[#ff6fb5]" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('instructors.tasks.edit', $task) }}" 
                                               class="text-[#FF92C2] hover:text-[#ff6fb5]" title="Edit Task">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('instructors.tasks.destroy', $task) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this task? This will also delete all associated questions and student assignments.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700" title="Delete Task">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                            No tasks found. <a href="{{ route('instructors.tasks.create') }}" class="text-[#FF92C2] hover:underline">Create your first task</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow border border-[#FFC8FB]/30">
                    <h3 class="text-sm font-medium text-gray-500">Total Tasks</h3>
                    <p class="text-2xl font-bold text-[#FF92C2]">{{ $tasks->where('parent_task_id', null)->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-[#FFC8FB]/30">
                    <h3 class="text-sm font-medium text-gray-500">Active Tasks</h3>
                    <p class="text-2xl font-bold text-green-600">{{ $tasks->where('parent_task_id', null)->where('is_active', true)->count() }}</p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border border-[#FFC8FB]/30">
                    <h3 class="text-sm font-medium text-gray-500">Student Assignments</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $tasks->where('parent_task_id', null)->sum(function($task) { return $task->students->count(); }) }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleUploadForm() {
            const section = document.getElementById('uploadSection');
            section.classList.toggle('hidden');
        }
    </script>
</x-app-layout>