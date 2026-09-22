<x-app-layout>
    <div class="p-8 w-full">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Faculty Assignments</h2>
            <p class="mt-1 text-sm text-gray-500">Manage faculty assignments</p>
        </div>



        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Assign Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="p-4 border-b border-gray-200 bg-blue-600 rounded-t-lg">
                <h3 class="text-white font-bold">Assign Faculty to a Course & Subject</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.assignments.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="instructor_id" class="block text-sm font-bold text-gray-800 mb-1">Select Instructor</label>
                            <select name="instructor_id" id="instructor_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                <option value="">-- Choose Instructor --</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                        {{ $instructor->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="course_id" class="block text-sm font-bold text-gray-800 mb-1">Course</label>
                            <select name="course_id" id="course_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                <option value="">-- Choose Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->code }} - {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="subject_id" class="block text-sm font-bold text-gray-800 mb-1">Select Subject</label>
                            <select name="subject_id" id="subject_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                <option value="">-- Choose Subject --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->subject_name }} ({{ $subject->subject_code }})
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('admin.subjects.create') }}" class="text-sm text-blue-600 hover:underline mt-1 inline-block">Add New Subject</a>
                        </div>

                        <div>
                            <label for="year" class="block text-sm font-bold text-gray-800 mb-1">Year</label>
                            <select name="year" id="year" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                <option value="">-- Choose Year --</option>
                                <option value="1st Year" {{ old('year') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                <option value="2nd Year" {{ old('year') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                <option value="3rd Year" {{ old('year') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                <option value="4th Year" {{ old('year') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                            </select>
                        </div>

                        <div>
                            <label for="semester" class="block text-sm font-bold text-gray-800 mb-1">Semester</label>
                            <select name="semester" id="semester" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                <option value="">-- Choose Semester --</option>
                                <option value="First" {{ old('semester') == 'First' ? 'selected' : '' }}>First</option>
                                <option value="Second" {{ old('semester') == 'Second' ? 'selected' : '' }}>Second</option>
                                <option value="Third" {{ old('semester') == 'Third' ? 'selected' : '' }}>Third</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Assign Faculty
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Current Assignments Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 bg-slate-500 rounded-t-lg">
                <h3 class="text-white font-bold">Current Assignments</h3>
            </div>
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <form action="{{ route('admin.assignments.index') }}" method="GET" class="flex items-center gap-2" id="perPageForm">
                    <span class="text-sm text-gray-700">Showing</span>
                    <select name="per_page" onchange="document.getElementById('perPageForm').submit()" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 pl-2 pr-6">
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="text-sm text-gray-700">of {{ $assignments->total() }} results</span>
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('instructor_id'))
                        <input type="hidden" name="instructor_id" value="{{ request('instructor_id') }}">
                    @endif
                    @if(request('subject_id'))
                        <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                    @endif
                    @if(request('year'))
                        <input type="hidden" name="year" value="{{ request('year') }}">
                    @endif
                </form>

                <form action="{{ route('admin.assignments.index') }}" method="GET" class="flex items-center gap-3">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button" class="inline-flex items-center gap-1.5 px-2 py-1 bg-white rounded-md font-medium text-xs text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filters
                            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" style="display: none;" class="absolute z-50 mt-2 w-72 rounded-md shadow-lg bg-white border border-gray-200 left-0 origin-top-left">
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="filter_instructor" class="block text-sm font-medium text-gray-700">Instructor</label>
                                    <select name="instructor_id" id="filter_instructor" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">All Instructors</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{ $instructor->id }}" {{ request('instructor_id') == $instructor->id ? 'selected' : '' }}>{{ $instructor->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="filter_subject" class="block text-sm font-medium text-gray-700">Subject</label>
                                    <select name="subject_id" id="filter_subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">All Subjects</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->subject_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="filter_year" class="block text-sm font-medium text-gray-700">Year</label>
                                    <select name="year" id="filter_year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">All Years</option>
                                        <option value="1st Year" {{ request('year') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ request('year') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ request('year') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ request('year') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                </div>
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <a href="{{ route('admin.assignments.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Reset</a>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-l pl-3 ml-1">
                        <div class="relative w-48">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search..." class="block w-full pl-9 pr-3 py-1.5 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm">
                        </div>
                    </div>
                    @if(request('per_page'))
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-white bg-blue-600 border-b border-blue-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Faculty</th>
                            <th scope="col" class="px-6 py-3 font-bold">Subject</th>
                            <th scope="col" class="px-6 py-3 font-bold">Course</th>
                            <th scope="col" class="px-6 py-3 font-bold">Year/Semester</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Students</th>
                            <th scope="col" class="px-6 py-3 font-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                            <tr class="bg-white border-b hover:bg-indigo-100 {{ $loop->even ? 'bg-indigo-50' : '' }} transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $assignment->instructor->full_name }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $assignment->subject->subject_code }}</div>
                                    <div class="text-xs text-gray-500">{{ $assignment->subject->subject_name }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $assignment->course->code ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $assignment->year }} / {{ $assignment->semester }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $assignment->students->count() }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'edit-assignment-{{ $assignment->id }}')" type="button" title="Edit Assignment" class="inline-flex items-center justify-center w-8 h-8 text-white bg-amber-500 rounded hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        
                                        <a href="{{ route('admin.instructors.classes', $assignment->instructor_id) }}" title="View Class" class="inline-flex items-center justify-center w-8 h-8 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('admin.assignments.add-students', $assignment) }}" title="Add Students" class="inline-flex items-center justify-center w-8 h-8 text-white bg-emerald-500 rounded hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </a>
                                        
                                        <form action="{{ route('admin.assignments.destroy', $assignment) }}" method="POST" class="inline-block" id="delete-assignment-{{ $assignment->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-assignment-{{ $assignment->id }}')" title="Remove Assignment" class="inline-flex items-center justify-center w-8 h-8 text-white bg-red-500 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>

                                        <x-modal name="edit-assignment-{{ $assignment->id }}" maxWidth="md" focusable>
                                            <form method="POST" action="{{ route('admin.assignments.update', $assignment) }}" class="p-6 text-left whitespace-normal">
                                                @csrf
                                                @method('PUT')

                                                <h2 class="text-lg font-bold text-gray-900 mb-6">
                                                    Edit Faculty Assignment
                                                </h2>

                                                <div class="space-y-4">
                                                    <div>
                                                        <label for="instructor_id_{{ $assignment->id }}" class="block text-sm font-bold text-gray-800 mb-1">Select Instructor</label>
                                                        <select name="instructor_id" id="instructor_id_{{ $assignment->id }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                                            @foreach($instructors as $instructor)
                                                                <option value="{{ $instructor->id }}" {{ $assignment->instructor_id == $instructor->id ? 'selected' : '' }}>
                                                                    {{ $instructor->full_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="course_id_{{ $assignment->id }}" class="block text-sm font-bold text-gray-800 mb-1">Course</label>
                                                        <select name="course_id" id="course_id_{{ $assignment->id }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                                            @foreach($courses as $course)
                                                                <option value="{{ $course->id }}" {{ $assignment->course_id == $course->id ? 'selected' : '' }}>
                                                                    {{ $course->code }} - {{ $course->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="subject_id_{{ $assignment->id }}" class="block text-sm font-bold text-gray-800 mb-1">Select Subject</label>
                                                        <select name="subject_id" id="subject_id_{{ $assignment->id }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                                            @foreach($subjects as $subject)
                                                                <option value="{{ $subject->id }}" {{ $assignment->subject_id == $subject->id ? 'selected' : '' }}>
                                                                    {{ $subject->subject_name }} ({{ $subject->subject_code }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="year_{{ $assignment->id }}" class="block text-sm font-bold text-gray-800 mb-1">Year</label>
                                                        <select name="year" id="year_{{ $assignment->id }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                                            <option value="1st Year" {{ $assignment->year == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                                            <option value="2nd Year" {{ $assignment->year == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                                            <option value="3rd Year" {{ $assignment->year == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                                            <option value="4th Year" {{ $assignment->year == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <label for="semester_{{ $assignment->id }}" class="block text-sm font-bold text-gray-800 mb-1">Semester</label>
                                                        <select name="semester" id="semester_{{ $assignment->id }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                                                            <option value="First" {{ $assignment->semester == 'First' ? 'selected' : '' }}>First</option>
                                                            <option value="Second" {{ $assignment->semester == 'Second' ? 'selected' : '' }}>Second</option>
                                                            <option value="Third" {{ $assignment->semester == 'Third' ? 'selected' : '' }}>Third</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mt-6 flex justify-end gap-3">
                                                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                        Cancel
                                                    </button>
                                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                        Update Assignment
                                                    </button>
                                                </div>
                                            </form>
                                        </x-modal>

                                        <x-modal name="confirm-delete-assignment-{{ $assignment->id }}" maxWidth="md" focusable>
                                            <div class="p-6 relative text-left whitespace-normal">
                                                <h2 class="text-lg font-bold text-gray-900 mb-2">
                                                    Remove Assignment
                                                </h2>
                                                <p class="text-sm text-gray-500 mb-6">
                                                    Are you sure you want to delete this assignment? All associated data will be permanently removed. This action cannot be undone.
                                                </p>
                                                <div class="flex justify-end gap-3">
                                                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-sm font-medium">
                                                        Cancel
                                                    </button>
                                                    <button type="button" onclick="document.getElementById('delete-assignment-{{ $assignment->id }}').submit();" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm font-medium">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </x-modal>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    No assignments found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($assignments instanceof \Illuminate\Pagination\LengthAwarePaginator && $assignments->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $assignments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

