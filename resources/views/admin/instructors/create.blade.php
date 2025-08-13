<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#FFF0FA] dark:from-[#4B4B4B] dark:to-[#3B3B3B] 
                        backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-2xl p-8 border border-[#FFC8FB]/50">
                
                <h2 class="text-2xl font-bold text-[#FF92C2] dark:text-[#FF92C2] mb-6">Create New Instructor</h2>

                <form action="{{ route('admin.instructors.store') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Full Name</label>
                        <input type="text" name="name" 
                               class="w-full rounded-lg shadow-sm bg-white dark:from-[#FF92C2] dark:to-[#FF92C2] 
                                      border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                      text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                               required>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Email</label>
                        <input type="email" name="email" 
                               class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                      border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                      text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                               required>
                    </div>

                    {{-- Employee ID --}}
                    <div>
                        <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Employee ID</label>
                        <input type="text" name="employee_id" 
                               class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                      border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                      text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                               required>
                    </div>

                    {{-- Department & Specialization --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Department</label>
                            <input type="text" name="department" 
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-[#FF92C2] dark:text-[#FF92C2] mb-1">Specialization</label>
                            <input type="text" name="specialization" 
                                   class="w-full rounded-lg shadow-sm bg-white dark:from-[#595758] dark:to-[#4B4B4B] 
                                          border border-[#FFC8FB] focus:border-pink-400 focus:ring focus:ring-pink-200 dark:focus:ring-pink-500
                                          text-gray-800 dark:text-black-200 px-4 py-2 transition-all duration-200"
                                   required>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-gradient-to-r from-[#FF92C2] to-[#FF5DA2] hover:from-[#FF5DA2] hover:to-[#FF92C2] 
                                       text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            Create Instructor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
