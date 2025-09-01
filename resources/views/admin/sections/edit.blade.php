<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold text-[#FF92C2] mb-6">Edit Section</h2>

                <form action="{{ route('admin.sections.update', $section) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="section_code" class="block text-sm font-medium text-[#595758]">Section Code</label>
                            <input type="text" name="section_code" id="section_code" 
                                   value="{{ $section->section_code }}" required
                                   class="mt-1 block w-full rounded-md shadow-sm border border-[#FFC8FB] focus:border-[#FF92C2] focus:ring focus:ring-[#FF92C2] focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-medium text-[#595758]">Section Name</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ $section->name }}" required
                                   class="mt-1 block w-full rounded-md shadow-sm border border-[#FFC8FB] focus:border-[#FF92C2] focus:ring focus:ring-[#FF92C2] focus:ring-opacity-50">
                        </div>

                        <div>
                            <label for="course_id" class="block text-sm font-medium text-[#595758]">Course</label>
                            <select name="course_id" id="course_id" required
                                    class="mt-1 block w-full rounded-md shadow-sm border border-[#FFC8FB] focus:border-[#FF92C2] focus:ring focus:ring-[#FF92C2] focus:ring-opacity-50">
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" 
                                            {{ $section->course_id == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="capacity" class="block text-sm font-medium text-[#595758]">Capacity (Optional)</label>
                            <input type="number" name="capacity" id="capacity" 
                                   value="{{ $section->capacity }}" min="1"
                                   class="mt-1 block w-full rounded-md shadow-sm border border-[#FFC8FB] focus:border-[#FF92C2] focus:ring focus:ring-[#FF92C2] focus:ring-opacity-50">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-[#595758]">Notes (Optional)</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-1 block w-full rounded-md shadow-sm border border-[#FFC8FB] focus:border-[#FF92C2] focus:ring focus:ring-[#FF92C2] focus:ring-opacity-50">{{ $section->notes }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-[#FF92C2] text-white rounded-md hover:bg-[#ff6fb5] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#FF92C2]">
                            Update Section
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
