<x-app-layout>
    <div class="p-8 mx-auto max-w-3xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Student Details</h2>
            <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to List
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="block text-sm font-medium text-gray-500">First Name</span>
                    <span class="block mt-1 text-lg text-gray-900">{{ $student->first_name }}</span>
                </div>

                <div>
                    <span class="block text-sm font-medium text-gray-500">Last Name</span>
                    <span class="block mt-1 text-lg text-gray-900">{{ $student->last_name }}</span>
                </div>

                <div>
                    <span class="block text-sm font-medium text-gray-500">Student Number</span>
                    <span class="block mt-1 text-lg text-gray-900">{{ $student->student_number }}</span>
                </div>

                <div>
                    <span class="block text-sm font-medium text-gray-500">Sex</span>
                    <span class="block mt-1 text-lg text-gray-900">{{ $student->sex == 'M' ? 'Male' : 'Female' }}</span>
                </div>

                <div>
                    <span class="block text-sm font-medium text-gray-500">Course</span>
                    <span class="block mt-1 text-lg text-gray-900">{{ $student->course }}</span>
                </div>

                <div>
                    <span class="block text-sm font-medium text-gray-500">Year</span>
                    <span class="block mt-1 text-lg text-gray-900">{{ $student->year }}</span>
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
