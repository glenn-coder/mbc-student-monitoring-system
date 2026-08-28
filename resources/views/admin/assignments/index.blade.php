<x-app-layout>
    <div class="p-8 mx-auto max-w-7xl">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Faculty Assignments</h2>



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
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
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
                    <span class="text-sm text-gray-700 font-bold">Show</span>
                    <select name="per_page" onchange="document.getElementById('perPageForm').submit()" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 pl-2 pr-6">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="text-sm text-gray-700 font-bold">entries</span>
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                </form>

                <form action="{{ route('admin.assignments.index') }}" method="GET" class="flex items-center gap-3">
                    <div class="flex items-center gap-2">
                        <label for="search" class="text-sm text-gray-700 font-bold">Search:</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 px-3 w-48">
                    </div>
                    @if(request('per_page'))
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-gray-700 bg-gray-100 border-b border-gray-200">
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
                            <tr class="bg-white border-b hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $assignment->instructor->first_name }} {{ $assignment->instructor->last_name }}
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
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none {{ $assignment->students->count() > 0 ? 'text-blue-800 bg-blue-100' : 'text-gray-800 bg-gray-100' }} rounded-full">
                                        {{ $assignment->students->count() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.assignments.add-students', $assignment) }}" title="View Students" class="inline-flex items-center justify-center w-8 h-8 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                        </a>
                                        
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-assignment-deletion-{{ $assignment->id }}')" type="button" title="Remove Assignment" class="inline-flex items-center justify-center w-8 h-8 text-white bg-red-500 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        <x-modal name="confirm-assignment-deletion-{{ $assignment->id }}" maxWidth="md" contentClasses="bg-red-950/70 backdrop-blur-xl border border-red-500/30" focusable>
                                            <div class="p-6 relative text-left whitespace-normal">
                                                <button x-on:click="$dispatch('close')" class="absolute top-4 right-4 text-red-200/50 hover:text-red-100 transition">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>

                                                <form method="POST" action="{{ route('admin.assignments.destroy', $assignment) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <div class="flex gap-4 mb-2">
                                                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full bg-red-500/20 border border-red-500/30 text-red-400 shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                        </div>

                                                        <div class="pt-1">
                                                            <h2 class="text-xl font-semibold text-white">
                                                                Delete Assignment
                                                            </h2>
                                                            <p class="mt-2 text-sm text-red-100/70 leading-relaxed">
                                                                Are you sure you want to delete this assignment? All associated data will be permanently removed. This action cannot be undone.
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-6 flex justify-end gap-3">
                                                        <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2.5 rounded-lg text-sm font-medium text-white bg-white/5 hover:bg-white/10 border border-white/10 transition">
                                                            Cancel
                                                        </button>

                                                        <button type="submit" class="px-4 py-2.5 rounded-lg text-sm font-medium text-white bg-red-600/80 hover:bg-red-500 border border-red-500/50 transition shadow-[0_0_15px_rgba(220,38,38,0.3)]">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </form>
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
