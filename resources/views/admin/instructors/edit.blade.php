<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] backdrop-blur-sm overflow-hidden shadow-lg rounded-lg sm:rounded-2xl p-4 sm:p-8 border border-[#FFC8FB]/50">
                <h2 class="text-xl sm:text-2xl font-bold text-[#FF92C2] mb-4 sm:mb-6">Edit Instructor</h2>

                <form action="{{ route('admin.instructors.update', $instructor) }}" method="POST" class="space-y-4 sm:space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Full Name</label>
                            <input type="text" name="name" value="{{ $instructor->name }}"
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Email</label>
                            <input type="email" name="email" value="{{ $instructor->email }}"
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                        </div>

                        {{-- Employee ID --}}
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Employee ID</label>
                            <input type="text" name="employee_id" value="{{ $instructor->employee_id }}"
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                        </div>

                        {{-- Department & Specialization --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Department</label>
                                <input type="text" name="department" value="{{ $instructor->department }}"
                                       class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                              border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                              text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                       required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Specialization</label>
                                <input type="text" name="specialization" value="{{ $instructor->specialization }}"
                                       class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                              border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                              text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                       required>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.instructors.index') }}" 
                           class="w-full sm:w-auto px-4 sm:px-6 py-2 text-center bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto px-4 sm:px-6 py-2 bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2] hover:from-[#FF5DA2] hover:to-[#FF92C2] 
                                       text-white text-sm sm:text-base font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            Update Instructor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
