<x-app-layout>
    <div class="p-8 w-full" x-data="{ showModal: false, searchQuery: '' }">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Timetable</h2>
                <p class="mt-1 text-sm text-slate-500">Weekly class schedule</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative w-64">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" x-model="searchQuery" placeholder="Search schedule..." class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>

                <button @click="showModal = true" class="px-4 py-2 bg-emerald-700 text-white font-medium rounded-md hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 flex items-center gap-2 transition-all active:scale-[0.98] shadow-sm hover:shadow-md text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Schedule
                </button>
            </div>
        </div>


        @if ($errors->any())
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
             x-transition:leave-end="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
             class="fixed bottom-4 right-4 z-50 pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900">There were some errors:</p>
                        <ul class="mt-1 list-disc pl-5 text-sm text-gray-500">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="ml-4 flex flex-shrink-0">
                        <button @click="show = false" type="button" class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($days as $day)
            @php
            $daySchedules = $schedules->get($day, collect());
            @endphp
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full border-l-4 border-l-[#2F2FE4]">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-white">
                    <h3 class="text-base font-bold text-gray-800">{{ $day }}</h3>
                    <span class="text-xs text-gray-400 font-medium">{{ $daySchedules->count() }} classes</span>
                </div>

                <div class="p-0 flex-grow bg-white flex flex-col max-h-80 overflow-y-auto">
                    @if($daySchedules->isEmpty())
                    <div class="flex-grow flex flex-col items-center justify-center p-8 text-gray-400">
                        <svg class="w-5 h-5 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                        <span class="text-xs font-medium">No classes</span>
                    </div>
                    @else
                    @foreach($daySchedules as $schedule)
                    <div class="p-4 border-b border-gray-50 last:border-b-0 hover:bg-gray-50 transition-colors cursor-pointer {{ $schedule->status == 'inactive' ? 'opacity-50 grayscale' : '' }}"
                        x-data="" x-on:click="$dispatch('open-modal', 'edit-schedule-{{ $schedule->id }}')"
                        x-show="searchQuery === '' || 
                                         '{{ strtolower($schedule->instructorAssignment->subject->code) }}'.includes(searchQuery.toLowerCase()) || 
                                         '{{ strtolower($schedule->instructorAssignment->subject->subject_name) }}'.includes(searchQuery.toLowerCase()) ||
                                         '{{ strtolower($schedule->instructorAssignment->instructor->full_name) }}'.includes(searchQuery.toLowerCase())">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-bold text-sm text-gray-900 pr-2 leading-tight">{{ $schedule->instructorAssignment->subject->subject_name }}</h4>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                @if($schedule->status == 'active')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-800 tracking-wider">
                                    ACTIVE
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-200 text-gray-800 tracking-wider">
                                    INACTIVE
                                </span>
                                @endif
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 tracking-wider">
                                    {{ $schedule->instructorAssignment->course->code }}-{{ $schedule->instructorAssignment->year }}
                                </span>
                                <form action="{{ route('admin.timetable.destroy', $schedule) }}" method="POST" class="inline" id="delete-schedule-{{ $schedule->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" x-data="" x-on:click.stop.prevent="$dispatch('open-modal', 'confirm-delete-{{ $schedule->id }}')" class="text-gray-400 hover:text-red-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-600 gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:ia') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:ia') }}</span>
                            </div>

                            <div class="flex items-center text-sm text-gray-600 gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>{{ $schedule->instructorAssignment->instructor->full_name }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Modals outside of clickable card -->
                    <x-modal name="confirm-delete-{{ $schedule->id }}" maxWidth="md" focusable>
                        <div class="p-6 relative text-left">
                            <h2 class="text-lg font-bold text-gray-900 mb-2">
                                Remove Schedule
                            </h2>
                            <p class="text-sm text-gray-500 mb-6">
                                Are you sure you want to remove this schedule? This action cannot be undone.
                            </p>
                            <div class="flex justify-end gap-3">
                                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-sm font-medium">
                                    Cancel
                                </button>
                                <button type="button" onclick="document.getElementById('delete-schedule-{{ $schedule->id }}').submit();" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm font-medium">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </x-modal>

                    <x-modal name="edit-schedule-{{ $schedule->id }}" maxWidth="lg" focusable>
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 text-left">
                            <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                                <h3 class="text-lg leading-6 font-bold text-gray-900">
                                    Update Schedule
                                </h3>
                                <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-500">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <form action="{{ route('admin.timetable.update', $schedule) }}" method="POST" id="editScheduleForm-{{ $schedule->id }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Class</label>
                                    <select name="instructor_assignment_id" required class="w-full border-gray-300 focus:border-[#2F2FE4] focus:ring-0 rounded-md shadow-sm sm:text-sm">
                                        @foreach($assignments as $assignment)
                                        <option value="{{ $assignment->id }}" {{ $schedule->instructor_assignment_id == $assignment->id ? 'selected' : '' }}>
                                            {{ $assignment->instructor->full_name }} - {{ $assignment->subject->subject_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Days</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($days as $dayOption)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="days[]" value="{{ $dayOption }}" {{ $schedule->day_of_week == $dayOption ? 'checked' : '' }} class="rounded border-gray-300 text-[#2F2FE4] shadow-sm focus:border-[#2F2FE4] focus:ring-0">
                                            <span class="ml-2 text-sm text-gray-600">{{ $dayOption }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" required class="w-full border-gray-300 focus:border-[#2F2FE4] focus:ring-0 rounded-md shadow-sm sm:text-sm">
                                        <option value="active" {{ $schedule->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ $schedule->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                                        <input type="time" name="start_time" value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" required class="w-full border-gray-300 focus:border-[#2F2FE4] focus:ring-0 rounded-md shadow-sm sm:text-sm text-gray-600">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                                        <input type="time" name="end_time" value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}" required class="w-full border-gray-300 focus:border-[#2F2FE4] focus:ring-0 rounded-md shadow-sm sm:text-sm text-gray-600">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Grace Period
                                        <span class="text-gray-400 font-normal">(minutes)</span>
                                    </label>
                                    <input type="number" name="grace_period_minutes" value="{{ $schedule->grace_period_minutes }}" min="0" max="120" required
                                        class="w-full border-gray-300 focus:border-[#2F2FE4] focus:ring-0 rounded-md shadow-sm sm:text-sm text-gray-600">
                                    <p class="mt-1 text-xs text-gray-400">Students scanned within this window after class start are marked <strong>Present</strong>. After the window, they are marked <strong>Late</strong>.</p>
                                </div>
                            </form>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                            <button type="submit" form="editScheduleForm-{{ $schedule->id }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-amber-600 text-base font-medium text-white hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Update Schedule
                            </button>
                            <button type="button" x-on:click="$dispatch('close')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </x-modal>
                    @endforeach
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Add Schedule Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="showModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-5 pb-4 border-b border-gray-100">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                Add Schedule
                            </h3>
                            <button @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('admin.timetable.store') }}" method="POST" id="addScheduleForm">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Class</label>
                                <select name="instructor_assignment_id" required class="w-full border-gray-300 focus:border-[#2F2FE4] focus:ring-0 rounded-md shadow-sm sm:text-sm">
                                    <option value="">Select a class...</option>
                                    @foreach($assignments as $assignment)
                                    <option value="{{ $assignment->id }}">
                                        {{ $assignment->instructor->full_name }} - {{ $assignment->subject->subject_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Schedule Days</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($days as $day)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="days[]" value="{{ $day }}" class="rounded border-gray-300 text-[#2F2FE4] shadow-sm focus:border-[#2F2FE4] focus:ring-0">
                                        <span class="ml-2 text-sm text-gray-600">{{ $day }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" required class="w-full border-gray-300 focus:border-[#2F2FE4] focus:ring-0 rounded-md shadow-sm sm:text-sm">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Time</label>
                                    <input type="time" name="start_time" required class="w-full border-gray-300 focus:border-[#2F2FE4] focus:ring-0 rounded-md shadow-sm sm:text-sm text-gray-600">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">End Time</label>
                                    <input type="time" name="end_time" required class="w-full border-gray-300 focus:border-[#2F2FE4] focus:ring-0 rounded-md shadow-sm sm:text-sm text-gray-600">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Grace Period
                                    <span class="text-gray-400 font-normal">(minutes)</span>
                                </label>
                                <input type="number" name="grace_period_minutes" value="15" min="0" max="120" required
                                    class="w-full border-gray-300 focus:border-[#2F2FE4] focus:ring-0 rounded-md shadow-sm sm:text-sm text-gray-600"
                                    placeholder="e.g. 15">
                                <p class="mt-1 text-xs text-gray-400">Students who scan within the grace period after class starts are marked <strong>Present</strong>. Scans after the grace period but before the class ends are marked <strong>Late</strong>.</p>
                            </div>

                        </form>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                        <button type="submit" form="addScheduleForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-emerald-700 text-base font-medium text-white hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Save Schedule
                        </button>
                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>