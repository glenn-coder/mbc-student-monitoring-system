<x-app-layout>
    <div class="p-8 mx-auto max-w-7xl">
        <div class="mb-6">
            <a href="{{ route('instructor.classes.index') }}" class="text-blue-600 hover:underline flex items-center text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to My Classes
            </a>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Left Sidebar: Class Information -->
            <div class="w-full lg:w-1/3 xl:w-1/4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-blue-600 p-4">
                        <h3 class="text-white font-bold">Class Information</h3>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-sm text-left">
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">Section</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ $assignment->course->code ?? 'N/A' }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">Subject</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ $assignment->subject->subject_name }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">Code</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ $assignment->subject->subject_code }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">School Year</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ now()->format('Y') }}-{{ now()->addYear()->format('Y') }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">Semester</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ $assignment->semester }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">Students</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ $assignment->students->count() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Content: Tabs and Table -->
            <div class="w-full lg:w-2/3 xl:w-3/4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Tabs -->
                    <div class="flex border-b border-gray-200 px-4 pt-4 space-x-2">
                        <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-t-md">
                            Students
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Assessments
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Syllabus
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-700">
                            Seat Plan
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-700 border border-gray-200">
                                <thead class="text-xs text-white bg-blue-600 border-b border-blue-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 font-bold border-r border-blue-700">Student Number</th>
                                        <th scope="col" class="px-6 py-3 font-bold border-r border-blue-700">Name</th>
                                        <th scope="col" class="px-6 py-3 font-bold border-r border-blue-700">Sex</th>
                                        <th scope="col" class="px-6 py-3 font-bold border-r border-blue-700">Course</th>
                                        <th scope="col" class="px-6 py-3 font-bold">Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assignment->students as $student)
                                        <tr class="bg-white border-b hover:bg-indigo-100 {{ $loop->even ? 'bg-indigo-50' : '' }} transition-colors">
                                            <td class="px-6 py-4 font-medium border-r border-gray-200">{{ $student->student_number }}</td>
                                            <td class="px-6 py-4 border-r border-gray-200">{{ $student->first_name }} {{ $student->last_name }}</td>
                                            <td class="px-6 py-4 border-r border-gray-200">{{ $student->gender ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 border-r border-gray-200">{{ $student->course->code ?? 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $student->year_level ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 bg-gray-50">
                                                No students enrolled in this class
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
    </div>
</x-app-layout>
