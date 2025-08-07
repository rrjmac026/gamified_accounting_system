<x-app-layout>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg p-6">
                <form action="{{ route('subjects.update', $subject) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Subject Code</label>
                        <input type="text" name="subject_code" value="{{ old('subject_code', $subject->subject_code) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Subject Name</label>
                        <input type="text" name="subject_name" value="{{ old('subject_name', $subject->subject_name) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Description</label>
                        <textarea name="description" class="mt-1 block w-full rounded-md shadow-sm" rows="3" required>{{ old('description', $subject->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Instructor</label>
                        <select name="instructor_id" class="mt-1 block w-full rounded-md shadow-sm" required>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ $instructor->id == $subject->instructor_id ? 'selected' : '' }}>
                                    {{ $instructor->user->name ?? 'Unnamed' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Semester</label>
                            <input type="text" name="semester" value="{{ old('semester', $subject->semester) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Academic Year</label>
                            <input type="text" name="academic_year" value="{{ old('academic_year', $subject->academic_year) }}" class="mt-1 block w-full rounded-md shadow-sm" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Status</label>
                        <select name="is_active" class="mt-1 block w-full rounded-md shadow-sm" required>
                            <option value="1" {{ $subject->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$subject->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded shadow">
                            Update Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
