<x-app-layout>
    <div class="p-8 mx-auto max-w-7xl">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Classes Assigned to {{ $instructor->full_name }}</h2>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-gray-700 bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Course</th>
                            <th scope="col" class="px-6 py-3 font-bold">Subject Name</th>
                            <th scope="col" class="px-6 py-3 font-bold">Subject Code</th>
                            <th scope="col" class="px-6 py-3 font-bold">Year</th>
                            <th scope="col" class="px-6 py-3 font-bold">Semester</th>
                            <th scope="col" class="px-6 py-3 font-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr class="bg-white border-b hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                <td class="px-6 py-4">{{ $assignment->course->code ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $assignment->subject->subject_name }}</td>
                                <td class="px-6 py-4">{{ $assignment->subject->subject_code }}</td>
                                <td class="px-6 py-4">{{ $assignment->year }}</td>
                                <td class="px-6 py-4">{{ $assignment->semester }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.assignments.students', $assignment) }}" class="px-3 py-1.5 text-sm text-gray-900 bg-[#fbbf24] rounded hover:bg-[#f59e0b]">View Students</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No classes assigned to this instructor.
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
