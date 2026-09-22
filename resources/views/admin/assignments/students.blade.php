<x-app-layout>
    <div class="p-8 w-full">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Enrolled Students in Course: {{ $assignment->course->code ?? 'N/A' }}</h2>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-gray-700 bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Name</th>
                            <th scope="col" class="px-6 py-3 font-bold">Student Number</th>
                            <th scope="col" class="px-6 py-3 font-bold">Sex</th>
                            <th scope="col" class="px-6 py-3 font-bold">Course</th>
                            <th scope="col" class="px-6 py-3 font-bold">Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignment->students as $student)
                            <tr class="bg-white border-b hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                <td class="px-6 py-4">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ?? '' }}</td>
                                <td class="px-6 py-4">{{ $student->student_number }}</td>
                                <td class="px-6 py-4">{{ $student->sex }}</td>
                                <td class="px-6 py-4">{{ $student->course->code ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $student->year }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No students enrolled in this course yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <a href="{{ route('admin.assignments.index') }}" class="px-4 py-2 text-sm font-medium text-white bg-slate-500 rounded-md hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
            Back to Assignments
        </a>
    </div>
</x-app-layout>
