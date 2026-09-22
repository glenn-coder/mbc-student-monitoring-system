<x-app-layout>
    <div class="p-8 w-full">
        <div class="mb-6">
            <a href="{{ route('instructor.classes.index') }}" class="text-blue-600 hover:underline flex items-center text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to My Classes
            </a>
        </div>

        <div class="flex flex-col gap-6" x-data="{ tab: new URLSearchParams(window.location.search).get('tab') || 'timetable' }">
            
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $assignment->subject->subject_name }}</h2>
                    <p class="text-sm text-gray-500">{{ $assignment->subject->subject_code }} • Section: {{ $assignment->course->code ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Right Content: Tabs and Table -->
            <div class="w-full">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Tabs -->
                    <div class="flex border-b border-gray-200 px-4 pt-4 space-x-2">
                        <button @click="tab = 'timetable'" :class="{'bg-blue-600 text-white rounded-t-md': tab === 'timetable', 'text-gray-500 hover:text-gray-700': tab !== 'timetable'}" class="px-4 py-2 text-sm font-medium">
                            TimeTable
                        </button>
                        <button @click="tab = 'attendance'" :class="{'bg-blue-600 text-white rounded-t-md': tab === 'attendance', 'text-gray-500 hover:text-gray-700': tab !== 'attendance'}" class="px-4 py-2 text-sm font-medium">
                            Attendance Session
                        </button>
                        <button @click="tab = 'students'" :class="{'bg-blue-600 text-white rounded-t-md': tab === 'students', 'text-gray-500 hover:text-gray-700': tab !== 'students'}" class="px-4 py-2 text-sm font-medium">
                            Students
                        </button>
                        <button @click="tab = 'records'" :class="{'bg-blue-600 text-white rounded-t-md': tab === 'records', 'text-gray-500 hover:text-gray-700': tab !== 'records'}" class="px-4 py-2 text-sm font-medium" @click.once="loadRecords()">
                            Attendance Record
                        </button>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        <div x-show="tab === 'students'" 
                             x-data="{
                                isAddStudentModalOpen: false,
                                search: '',
                                currentPage: 1,
                                itemsPerPage: 5,
                                students: {{ Js::from($assignment->students->sortBy(function($s) {
                                    return strtolower($s->last_name . ' ' . $s->first_name);
                                })->values()->map(function($s) {
                                    return [
                                        'student_number' => $s->student_number,
                                        'name' => $s->first_name . ' ' . $s->last_name,
                                        'gender' => $s->gender ?? 'N/A',
                                        'course' => $s->course->code ?? 'N/A',
                                        'year_level' => $s->year_level ?? 'N/A'
                                    ];
                                })) }},
                                get filteredStudents() {
                                    if (this.search === '') return this.students;
                                    const q = this.search.toLowerCase();
                                    return this.students.filter(s => s.name.toLowerCase().includes(q) || s.student_number.toLowerCase().includes(q));
                                },
                                get paginatedStudents() {
                                    let start = (this.currentPage - 1) * this.itemsPerPage;
                                    let end = start + this.itemsPerPage;
                                    return this.filteredStudents.slice(start, end);
                                },
                                get totalPages() {
                                    return Math.ceil(this.filteredStudents.length / this.itemsPerPage) || 1;
                                }
                             }"
                             x-transition:enter="transition ease-out duration-200" 
                             x-transition:enter-start="opacity-0 translate-y-1" 
                             x-transition:enter-end="opacity-100 translate-y-0">
                             
                            <!-- Card Wrapper for Table & Controls -->
                            <div class="mt-6 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                
                                <!-- Table Header Controls -->
                                <div class="px-6 py-4 flex justify-between items-center gap-4 bg-white border-b border-gray-200">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span>Showing</span>
                                        <select x-model.number="itemsPerPage" @change="currentPage = 1" class="mx-2 border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 py-1.5 pl-3 pr-8">
                                            <option value="5">5</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="50">50</option>
                                        </select>
                                        <span>of</span>
                                        <span class="mx-1 font-semibold text-gray-900" x-text="filteredStudents.length"></span>
                                        <span>results</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="relative w-64">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                            <input type="text" x-model="search" @input="currentPage = 1" placeholder="Search..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                                        </div>
                                        <button @click="isAddStudentModalOpen = true" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-sm">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Add Student
                                        </button>
                                    </div>
                                </div>

                                <!-- Students Table -->
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-700">
                                        <thead class="text-xs text-white bg-[#2F2FE4] border-b border-[#2F2FE4]">
                                            <tr>
                                                <th scope="col" class="px-6 py-4 font-semibold">Student Number</th>
                                                <th scope="col" class="px-6 py-4 font-semibold">Name</th>
                                                <th scope="col" class="px-6 py-4 font-semibold">Sex</th>
                                                <th scope="col" class="px-6 py-4 font-semibold">Course</th>
                                                <th scope="col" class="px-6 py-4 font-semibold">Year</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-if="filteredStudents.length === 0">
                                                <tr>
                                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                        No students found.
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-for="student in paginatedStudents" :key="student.student_number">
                                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                                    <td class="px-6 py-4 text-gray-600" x-text="student.student_number"></td>
                                                    <td class="px-6 py-4 font-medium text-gray-900" x-text="student.name"></td>
                                                    <td class="px-6 py-4 text-gray-600" x-text="student.gender"></td>
                                                    <td class="px-6 py-4 text-gray-600" x-text="student.course"></td>
                                                    <td class="px-6 py-4 text-gray-600" x-text="student.year_level"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Table Footer (Pagination) -->
                                <div class="px-6 py-4 bg-white border-t border-gray-200 flex justify-end">
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <button @click="if(currentPage > 1) currentPage--"
                                                :disabled="currentPage === 1"
                                                :class="{'cursor-not-allowed text-gray-400': currentPage === 1, 'text-gray-500 hover:bg-gray-50': currentPage > 1}"
                                                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium">
                                            <span class="sr-only">Previous</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <!-- Page numbers -->
                                        <template x-for="page in totalPages" :key="page">
                                            <button @click="currentPage = page"
                                                    :class="{'z-10 bg-blue-600 border-blue-600 text-white': currentPage === page, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== page}"
                                                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                    x-text="page">
                                            </button>
                                        </template>

                                        <button @click="if(currentPage < totalPages) currentPage++"
                                                :disabled="currentPage === totalPages"
                                                :class="{'cursor-not-allowed text-gray-400': currentPage === totalPages, 'text-gray-500 hover:bg-gray-50': currentPage < totalPages}"
                                                class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium">
                                            <span class="sr-only">Next</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </nav>
                                </div>
                            </div>
                            
                            <!-- Add Student Modal -->
                            <div x-show="isAddStudentModalOpen" 
                                 class="fixed inset-0 z-50 overflow-y-auto" 
                                 aria-labelledby="modal-title" 
                                 role="dialog" 
                                 aria-modal="true"
                                 style="display: none;">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    
                                    <div x-show="isAddStudentModalOpen"
                                         x-transition:enter="ease-out duration-300"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="ease-in duration-200"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                                         aria-hidden="true"></div>

                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                    <div x-show="isAddStudentModalOpen"
                                         x-transition:enter="ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave="ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         @click.away="isAddStudentModalOpen = false"
                                         class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                                        
                                        <form action="{{ route('instructor.classes.students.add', $assignment->id) }}" method="POST">
                                            @csrf
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="sm:flex sm:items-start">
                                                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                            Add Student to Class
                                                        </h3>
                                                        <div class="mt-2">
                                                            <p class="text-sm text-gray-500 mb-4">
                                                                Enter the student's number to assign them to this class. The student must already be registered in the system.
                                                            </p>
                                                            
                                                            <div>
                                                                <label for="student_number" class="block text-sm font-medium text-gray-700">Student Number</label>
                                                                <div class="mt-1">
                                                                    <input type="text" name="student_number" id="student_number" required placeholder="e.g. 23-12345" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Add Student
                                                </button>
                                                <button type="button" @click="isAddStudentModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TimeTable Tab -->
                        <div x-show="tab === 'timetable'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-gray-900">TimeTable</h3>
                                <p class="text-sm text-gray-500">Weekly class schedule</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @php
                                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                @endphp
                                @foreach($days as $day)
                                    @php
                                        $daySchedules = $assignment->schedules ? $assignment->schedules->where('day_of_week', $day) : collect();
                                        $isToday = now()->format('l') === $day;
                                    @endphp
                                    <div class="bg-white rounded-lg shadow-sm border {{ $isToday ? 'border-blue-300 ring-1 ring-blue-100' : 'border-gray-200' }} p-6 flex flex-col h-full">
                                        <div class="flex justify-between items-center mb-4">
                                            <h4 class="font-bold {{ $isToday ? 'text-blue-600' : 'text-gray-900' }} text-base">{{ $day }}</h4>
                                            <span class="text-xs font-medium text-gray-400">{{ $daySchedules->count() }} classes</span>
                                        </div>
                                        
                                        <div class="flex-grow">
                                            @forelse($daySchedules as $schedule)
                                                @php
                                                    $nowTz = now()->setTimezone('Asia/Manila');
                                                    $nowTime = $nowTz->format('H:i:s');
                                                    
                                                    $dayIndex = array_search($day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
                                                    $realDate = now()->setTimezone('Asia/Manila')->startOfWeek()->addDays($dayIndex);
                                                    
                                                    $isActiveNow = $isToday && $nowTime >= $schedule->start_time && $nowTime <= $schedule->end_time;
                                                    
                                                    $isDone = false;
                                                    if ($realDate->isBefore($nowTz->startOfDay())) {
                                                        $isDone = true;
                                                    } elseif ($isToday && $nowTime > $schedule->end_time) {
                                                        $isDone = true;
                                                    }
                                                    
                                                    $todaySession = $schedule->todaySession ?? null;
                                                @endphp
                                                <div class="mb-4 last:mb-0">
                                                    <div class="flex items-center flex-wrap gap-2 mb-3">
                                                        <h5 class="font-bold text-gray-800 text-sm">{{ $assignment->subject->subject_name }}</h5>
                                                        @if($isActiveNow)
                                                            <span class="px-2 py-0.5 text-[10px] font-bold text-green-700 bg-green-100 rounded">ACTIVE</span>
                                                        @elseif(in_array(strtolower($schedule->status ?? ''), ['active']))
                                                            <span class="px-2 py-0.5 text-[10px] font-bold text-gray-600 bg-gray-100 rounded">SCHEDULED</span>
                                                        @endif
                                                        <span class="px-2 py-0.5 text-[10px] font-bold text-emerald-700 bg-emerald-100 rounded">{{ $assignment->course->code ?? '' }}</span>
                                                    </div>
                                                    
                                                    <div class="flex justify-between items-end">
                                                        <div class="space-y-1.5">
                                                            <div class="flex items-center text-xs text-gray-500">
                                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:ia') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:ia') }}
                                                            </div>
                                                            <div class="flex items-center text-xs text-gray-500">
                                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                Grace: {{ $schedule->grace_period_minutes }} min
                                                            </div>
                                                            <div class="flex items-center text-xs text-gray-500">
                                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                {{ $realDate->format('M d, Y') }}
                                                            </div>
                                                        </div>
                                                        
                                                        @if($isToday)
                                                            <div>
                                                                @if($isDone)
                                                                    <button disabled class="px-4 py-1.5 text-xs font-medium text-gray-400 bg-gray-100 rounded-md cursor-not-allowed border border-gray-200">
                                                                        Done
                                                                    </button>
                                                                @else
                                                                    <button
                                                                        @click="tab = 'attendance'; $nextTick(() => openAttendanceSession({{ $schedule->id }}))"
                                                                        class="px-4 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors shadow-sm">
                                                                        {{ $todaySession ? 'Resume' : 'Start' }}
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="flex items-center justify-center h-full min-h-[80px]">
                                                    <span class="text-sm text-gray-400 italic">No schedules set</span>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- ═══════════════════════════════════════════════════════════ -->
                        <!-- ATTENDANCE SESSION TAB                                      -->
                        <!-- ═══════════════════════════════════════════════════════════ -->
                        @php
                            $activeScheduleId = null;
                            $latestSessionId = null;
                            if ($assignment->schedules) {
                                $todayStr = now()->setTimezone('Asia/Manila')->format('l');
                                foreach ($assignment->schedules->where('day_of_week', $todayStr) as $s) {
                                    if ($s->todaySession && $s->todaySession->status === 'open' && !$s->todaySession->has_ended) {
                                        $activeScheduleId = $s->id;
                                        break;
                                    }
                                }
                            }

                            // Load the latest session across all schedules if no active session today
                            $latestSession = \App\Models\AttendanceSession::whereHas('schedule', function($q) use ($assignment) {
                                $q->where('instructor_assignment_id', $assignment->id);
                            })->orderBy('session_date', 'desc')->orderBy('created_at', 'desc')->first();
                            
                            if ($latestSession) {
                                $latestSessionId = $latestSession->id;
                            }
                        @endphp
                        <div x-show="tab === 'attendance'" style="display: none;"
                             x-data="attendanceSession({{ $assignment->id }}, {{ $activeScheduleId ?? 'null' }}, {{ $latestSessionId ?? 'null' }})"
                             x-init="init()"
                             @open-attendance-session.window="openAttendanceSession($event.detail.scheduleId)"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0">

                            <!-- No session open yet -->
                            <div x-show="!session" class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-gray-500 text-sm mb-2">No attendance history available.</p>
                                <p class="text-gray-400 text-xs">Click <strong>Start</strong> on a schedule in the TimeTable tab to begin a new session.</p>
                            </div>

                            <!-- Session is open -->
                            <div x-show="session" class="space-y-5">


                                <!-- Policy / Session Info Banner -->
                                <div class="flex flex-wrap gap-6 items-center pt-2">
                                    <div>
                                        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Session Date</p>
                                        <p class="text-xs font-bold text-gray-900" x-text="session?.session_date"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Class Time</p>
                                        <p class="text-xs font-bold text-gray-900" x-text="(session?.start_time ?? '—') + ' – ' + (session?.end_time ?? '—')"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Grace Period</p>
                                        <p class="text-xs font-bold text-gray-900" x-text="(session?.grace_period_minutes ?? '—') + ' min'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-medium text-gray-500 uppercase tracking-wide">Status</p>
                                        <span x-show="session?.is_open" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                            <span>Open</span>
                                        </span>
                                        <span x-show="!session?.is_open && session?.status === 'closed'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">
                                            Closed
                                        </span>
                                        <span x-show="!session?.is_open && session?.status === 'open' && !session?.has_ended" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700">
                                            Not started yet
                                        </span>
                                    </div>
                                </div>


                                <!-- ─── Active Session: Scan + Details + Manual ─────────────────────────────── -->
                                <div x-show="session && session?.status === 'open' && !session?.has_ended" class="space-y-4">

                                    <!-- Scan and Details Wrapper (Hidden during Manual Attendance) -->
                                    <div x-show="!showManual" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-4">
                                        <!-- Scan Student ID -->
                                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                            <p class="text-sm font-semibold text-gray-700 mb-3">Scan Student ID</p>
                                            <div class="flex gap-2">
                                                <input
                                                    id="scanInput"
                                                    type="text"
                                                    x-model="scanInput"
                                                    @keydown.enter.prevent="submitScan()"
                                                    placeholder="Student Number (e.g. 23-000001)"
                                                    :disabled="scanning"
                                                    class="flex-1 border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                                                    autocomplete="off"
                                                >
                                                <button
                                                    @click="submitScan()"
                                                    :disabled="scanning || !scanInput.trim()"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                    <span x-show="!scanning">Record</span>
                                                    <span x-show="scanning">…</span>
                                                </button>
                                            </div>

                                            <!-- Brief feedback: errors & duplicates only -->
                                            <div x-show="scanFeedback" x-transition
                                                 class="mt-3 rounded-md border text-sm px-4 py-2.5 flex items-center gap-2"
                                                 :class="{
                                                     'bg-yellow-50 border-yellow-200 text-yellow-800': scanFeedback?.type === 'warning',
                                                     'bg-red-50   border-red-200   text-red-700'   : scanFeedback?.type === 'error',
                                                 }">
                                                <span x-show="scanFeedback?.type === 'warning'" class="font-bold text-yellow-500">!</span>
                                                <span x-show="scanFeedback?.type === 'error'"   class="font-bold text-red-500">✕</span>
                                                <p class="font-medium" x-text="scanFeedback?.message"></p>
                                            </div>
                                        </div>

                                        <!-- Student Details Form -->
                                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                            <div class="bg-gray-50 border-b border-gray-200 px-4 py-2.5 flex items-center justify-between">
                                                <p class="text-sm font-semibold text-gray-700">Student Details</p>
                                                <div>
                                                    <span x-show="!lastScan" class="text-xs text-gray-400 italic">No student processed yet</span>
                                                    <span x-show="lastScan?.method === 'scan'"
                                                          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                                        Scan
                                                    </span>
                                                    <span x-show="lastScan?.method === 'manual'"
                                                          class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                                        Manual
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Student Number</label>
                                                    <input type="text" readonly
                                                           :value="lastScan?.student_number ?? ''"
                                                           placeholder="—"
                                                           class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-700 cursor-default focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Full Name</label>
                                                    <input type="text" readonly
                                                           :value="lastScan?.full_name ?? ''"
                                                           placeholder="—"
                                                           class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-700 cursor-default focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Course</label>
                                                    <input type="text" readonly
                                                           :value="lastScan?.course ?? ''"
                                                           placeholder="—"
                                                           class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-700 cursor-default focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Timestamp</label>
                                                    <input type="text" readonly
                                                           :value="lastScan?.scanned_at ?? ''"
                                                           placeholder="—"
                                                           class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-700 cursor-default focus:outline-none">
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                                                    <div class="flex items-center h-9">
                                                        <span x-show="!lastScan" class="text-sm text-gray-400">—</span>
                                                        <span x-show="lastScan?.status === 'present'"
                                                              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-green-100 text-green-700">
                                                            ✓ Present
                                                        </span>
                                                        <span x-show="lastScan?.status === 'late'"
                                                              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-700">
                                                            Late
                                                        </span>
                                                        <span x-show="lastScan?.status === 'absent'"
                                                              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-red-100 text-red-600">
                                                            Absent
                                                        </span>
                                                        <span x-show="lastScan?.status === 'duplicate'"
                                                              class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-100 text-blue-700">
                                                            Already Recorded
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Take Manual Attendance button -->
                                        <div class="flex justify-end mb-4">
                                            <button
                                                @click="showManual = true"
                                                class="bg-white hover:bg-gray-50 text-gray-700 border-gray-300 inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border rounded-md shadow-sm transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span>Take Manual Attendance</span>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Manual Attendance Panel (inline, collapsible) -->
                                    <div x-show="showManual"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         class="bg-white border border-gray-200 rounded-lg overflow-hidden mb-4">

                                        <div class="bg-gray-50 border-b border-gray-200 px-4 py-3">
                                            <p class="text-sm font-semibold text-gray-700">Manual Attendance</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Use this when a student cannot present their ID (forgot, misplaced, or lost).</p>
                                        </div>

                                        <div class="p-4 space-y-4">
                                            <!-- Search -->
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 mb-1">Search</label>
                                                <input
                                                    type="text"
                                                    x-model="manualSearch"
                                                    placeholder="Search by name or student number…"
                                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                                                >
                                            </div>

                                            <!-- Student selector -->
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-500 mb-1">Student</label>
                                                <select
                                                    x-model="manualStudentId"
                                                    @change="selectManualStudent()"
                                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">— Select enrolled student —</option>
                                                    <template x-for="s in filteredStudents" :key="s.id">
                                                        <option :value="s.id" x-text="s.full_name + ' (' + s.student_number + ')'"></option>
                                                    </template>
                                                </select>
                                            </div>

                                            <!-- Student preview (read-only) -->
                                            <div x-show="manualPreview" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Student Number</label>
                                                    <input type="text" readonly
                                                           :value="manualPreview?.student_number ?? ''"
                                                           placeholder="—"
                                                           class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-700 cursor-default focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Full Name</label>
                                                    <input type="text" readonly
                                                           :value="manualPreview?.full_name ?? ''"
                                                           placeholder="—"
                                                           class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-700 cursor-default focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Course</label>
                                                    <input type="text" readonly
                                                           :value="manualPreview?.course ?? ''"
                                                           placeholder="—"
                                                           class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-700 cursor-default focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Timestamp &amp; Status</label>
                                                    <input type="text" readonly
                                                           value="Calculated on submission"
                                                           class="w-full border border-gray-200 bg-gray-50 rounded-md px-3 py-2 text-sm text-gray-400 italic cursor-default focus:outline-none">
                                                </div>
                                            </div>

                                            <!-- Manual feedback -->
                                            <div x-show="manualFeedback" x-transition
                                                 class="rounded-md border text-sm px-4 py-2.5 flex items-center gap-2"
                                                 :class="{
                                                     'bg-yellow-50 border-yellow-200 text-yellow-800': manualFeedback?.type === 'warning',
                                                     'bg-red-50   border-red-200   text-red-700'   : manualFeedback?.type === 'error',
                                                 }">
                                                <span x-show="manualFeedback?.type === 'warning'" class="font-bold text-yellow-500">!</span>
                                                <span x-show="manualFeedback?.type === 'error'"   class="font-bold text-red-500">✕</span>
                                                <p class="font-medium" x-text="manualFeedback?.message"></p>
                                            </div>

                                            <!-- Action buttons -->
                                            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 mt-2">
                                                <button
                                                    @click="showManual = false; manualSearch = ''; manualStudentId = null; manualPreview = null; manualFeedback = null;"
                                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 border border-gray-300 rounded-md shadow-sm transition-colors">
                                                    Cancel
                                                </button>
                                                <button
                                                    @click="submitManual()"
                                                    :disabled="!manualStudentId || manualRecording"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                    <span x-show="!manualRecording">Record Attendance</span>
                                                    <span x-show="manualRecording">Recording…</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div><!-- /active session controls -->

                                <!-- Session Ended: Finalize Button -->
                                <div x-show="session?.has_ended && session?.status !== 'closed'" class="rounded-lg border border-orange-200 bg-orange-50 p-4 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-orange-800">Class session has ended.</p>
                                        <p class="text-xs text-orange-600 mt-0.5">Students with no scan will be marked Absent.</p>
                                    </div>
                                    <button
                                        @click="finalizeAbsent()"
                                        :disabled="finalizing"
                                        class="px-4 py-2 text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 rounded-md shadow-sm disabled:opacity-50 transition-colors">
                                        <span x-show="!finalizing">Finalize &amp; Mark Absent</span>
                                        <span x-show="finalizing">Processing...</span>
                                    </button>
                                </div>

                                <!-- Session Closed confirmation -->
                                <div x-show="session?.status === 'closed'" class="rounded-lg border border-gray-200 bg-gray-50 p-3 text-center">
                                    <p class="text-sm font-semibold text-gray-600">Session closed. Attendance is finalized.</p>
                                </div>

                                <!-- Card Wrapper for Table & Controls -->
                                <div class="mt-6 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                    
                                    <!-- Table Header Controls -->
                                    <div class="px-6 py-4 flex justify-between items-center gap-4 bg-white border-b border-gray-200">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <span>Showing</span>
                                            <select x-model.number="itemsPerPage" @change="currentPage = 1" class="mx-2 border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 py-1.5 pl-3 pr-8">
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                            </select>
                                            <span>of</span>
                                            <span class="mx-1 font-semibold text-gray-900" x-text="filteredSessionRoster.length"></span>
                                            <span>results</span>
                                        </div>
                                        <div class="relative w-64">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                            <input type="text" x-model="sessionSearch" placeholder="Search..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                                        </div>
                                    </div>

                                    <!-- Attendance Roster Table -->
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm text-left text-gray-700">
                                            <thead class="text-xs text-white bg-[#2F2FE4] border-b border-[#2F2FE4]">
                                                <tr>
                                                    <th class="px-6 py-4 font-semibold">Name</th>
                                                    <th class="px-6 py-4 font-semibold">Student ID</th>
                                                    <th class="px-6 py-4 font-semibold">Course</th>
                                                    <th class="px-6 py-4 font-semibold">Timestamp</th>
                                                    <th class="px-6 py-4 font-semibold text-center">Status</th>
                                                    <th class="px-6 py-4 font-semibold text-center">Method</th>
                                                    <th class="px-6 py-4 font-semibold text-right">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-if="filteredSessionRoster.length === 0">
                                                    <tr>
                                                        <td colspan="7" class="px-6 py-8 text-center text-gray-500 text-sm">
                                                            No students found matching your search.
                                                        </td>
                                                    </tr>
                                                </template>
                                                <template x-for="row in paginatedSessionRoster" :key="row.student_id">
                                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                                        <td class="px-6 py-4 font-medium text-gray-900" x-text="row.full_name"></td>
                                                        <td class="px-6 py-4 text-gray-600" x-text="row.student_number"></td>
                                                        <td class="px-6 py-4 text-gray-600" x-text="row.course"></td>
                                                        <td class="px-6 py-4 text-gray-600" x-text="row.scanned_at ?? '—'"></td>
                                                        <td class="px-6 py-4 text-center">
                                                            <span x-show="row.status === 'present'"
                                                                  class="inline-flex items-center px-2.5 py-1 rounded border border-green-200 text-xs font-medium bg-green-50 text-green-700">
                                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span> Present
                                                            </span>
                                                            <span x-show="row.status === 'late'"
                                                                  class="inline-flex items-center px-2.5 py-1 rounded border border-yellow-200 text-xs font-medium bg-yellow-50 text-yellow-700">
                                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-500"></span> Late
                                                            </span>
                                                            <span x-show="row.status === 'absent'"
                                                                  class="inline-flex items-center px-2.5 py-1 rounded border border-red-200 text-xs font-medium bg-red-50 text-red-600">
                                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span> Absent
                                                            </span>
                                                            <span x-show="row.status === 'pending' && session?.is_open"
                                                                  class="inline-flex items-center px-2.5 py-1 rounded border border-gray-200 text-xs font-medium bg-gray-50 text-gray-500">
                                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-gray-400"></span> Pending
                                                            </span>
                                                            <span x-show="row.status === 'pending' && !session?.is_open"
                                                                  class="inline-flex items-center px-2.5 py-1 rounded border border-gray-200 text-xs font-medium bg-gray-50 text-gray-500">
                                                                —
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 text-center">
                                                            <span x-show="row.attendance_method === 'scan'"
                                                                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-600">
                                                                Scan
                                                            </span>
                                                            <span x-show="row.attendance_method === 'manual'"
                                                                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700">
                                                                Manual
                                                            </span>
                                                            <span x-show="row.attendance_method === 'system'"
                                                                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                                                                System
                                                            </span>
                                                            <span x-show="!row.attendance_method" class="text-gray-300">—</span>
                                                        </td>
                                                        <td class="px-6 py-4 text-right">
                                                            <button x-show="row.status !== 'pending' && row.id" 
                                                                    @click="deleteRecord(row.id)" 
                                                                    title="Delete Record"
                                                                    class="text-gray-400 hover:text-red-600 transition-colors bg-transparent border-0 cursor-pointer">
                                                                <svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                </svg>
                                                            </button>
                                                            <span x-show="row.status === 'pending' || !row.id" class="text-gray-300">—</span>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Table Footer (Pagination) -->
                                    <div class="px-6 py-4 bg-white border-t border-gray-200 flex justify-end">
                                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                            <button @click="if(currentPage > 1) currentPage--"
                                                    :disabled="currentPage === 1"
                                                    :class="{'cursor-not-allowed text-gray-400': currentPage === 1, 'text-gray-500 hover:bg-gray-50': currentPage > 1}"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium">
                                                <span class="sr-only">Previous</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </button>

                                            <!-- Page numbers -->
                                            <template x-for="page in totalPages" :key="page">
                                                <button @click="currentPage = page"
                                                        :class="{'z-10 bg-blue-600 border-blue-600 text-white': currentPage === page, 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': currentPage !== page}"
                                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                                        x-text="page">
                                                </button>
                                            </template>

                                            <button @click="if(currentPage < totalPages) currentPage++"
                                                    :disabled="currentPage === totalPages"
                                                    :class="{'cursor-not-allowed text-gray-400': currentPage === totalPages, 'text-gray-500 hover:bg-gray-50': currentPage < totalPages}"
                                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium">
                                                <span class="sr-only">Next</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </nav>
                                    </div>
                                </div>
                            </div><!-- /session -->
                        </div><!-- /attendance tab -->

                        <!-- ═══════════════════════════════════════════════════════════ -->
                        <!-- ATTENDANCE RECORD TAB                                       -->
                        <!-- ═══════════════════════════════════════════════════════════ -->
                        <div x-show="tab === 'records'" style="display: none;"
                             x-data="attendanceRecords({{ $assignment->id }})"
                             x-init="init()"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0">

                            <div class="mb-4">
                                <div class="mb-3">
                                    <h3 class="text-lg font-bold text-gray-900">Attendance History</h3>
                                    <span class="text-xs text-gray-400" x-text="sessions.length + ' session(s) found'"></span>
                                </div>

                            </div>

                            <!-- Overall Summary Cards -->
                            <div class="grid grid-cols-4 gap-3 mb-6">
                                <div class="bg-white rounded-lg border border-gray-200 p-3 text-center shadow-sm">
                                    <p class="text-xs text-gray-500 font-medium">Total Records</p>
                                    <p class="text-2xl font-bold text-gray-800" x-text="summary.total ?? 0"></p>
                                </div>
                                <div class="bg-green-50 rounded-lg border border-green-100 p-3 text-center shadow-sm">
                                    <p class="text-xs text-green-600 font-medium">Present</p>
                                    <p class="text-2xl font-bold text-green-700" x-text="summary.present ?? 0"></p>
                                </div>
                                <div class="bg-yellow-50 rounded-lg border border-yellow-100 p-3 text-center shadow-sm">
                                    <p class="text-xs text-yellow-600 font-medium">Late</p>
                                    <p class="text-2xl font-bold text-yellow-700" x-text="summary.late ?? 0"></p>
                                </div>
                                <div class="bg-red-50 rounded-lg border border-red-100 p-3 text-center shadow-sm">
                                    <p class="text-xs text-red-500 font-medium">Absent</p>
                                    <p class="text-2xl font-bold text-red-600" x-text="summary.absent ?? 0"></p>
                                </div>
                            </div>

                            <!-- Loading -->
                            <div x-show="loading" class="text-center py-10 text-gray-400 text-sm">Loading records...</div>

                            <!-- Empty -->
                            <div x-show="!loading && sessions.length === 0" class="text-center py-10 text-gray-400 text-sm">
                                No attendance sessions found for this class yet.
                            </div>

                            <!-- Sessions list (Unified Table) -->
                            <div x-show="!loading && sessions.length > 0">
                                <!-- Unified Filters & Search (Top Bar) -->
                                <div class="bg-gray-50 border border-gray-200 px-4 py-3 flex flex-wrap gap-4 items-center justify-between rounded-t-lg mb-0 border-b-0">
                                    <template x-if="filteredSessions.length > 0">
                                        <div class="flex items-center gap-3">
                                            <span class="font-semibold text-gray-800 text-sm" x-text="filteredSessions[0].session_date"></span>
                                            <span class="text-xs text-gray-500" x-text="filteredSessions[0].day_of_week + ' · ' + filteredSessions[0].start_time + ' – ' + filteredSessions[0].end_time"></span>
                                        </div>
                                    </template>
                                    <template x-if="filteredSessions.length === 0">
                                        <div class="font-semibold text-gray-800 text-sm">No Records Found</div>
                                    </template>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <!-- Unified Filters Dropdown -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="globalDateFilter = appliedDateFilter; globalStatusFilter = appliedStatusFilter; open = !open" class="flex items-center gap-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                                </svg>
                                                Filters
                                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </button>
                                            <!-- Dropdown panel -->
                                            <div x-show="open" @click.away="open = false" style="display: none; min-width: 240px;" class="absolute right-0 mt-2 bg-white border border-gray-200 rounded-md shadow-lg z-20 p-4">
                                                <!-- Date Filter -->
                                                <div class="mb-4">
                                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Date</label>
                                                    <input type="date" x-model="globalDateFilter" class="w-full border border-gray-300 rounded-md text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                                                </div>
                                                <!-- Status Filter -->
                                                <div class="mb-4">
                                                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status</label>
                                                    <select x-model="globalStatusFilter" class="w-full border border-gray-300 rounded-md text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                                                        <option value="">All Statuses</option>
                                                        <option value="present">Present</option>
                                                        <option value="late">Late</option>
                                                        <option value="absent">Absent</option>
                                                    </select>
                                                </div>
                                                <!-- Action Buttons -->
                                                <div class="border-t border-gray-100 pt-4 mt-2 flex justify-between items-center gap-3">
                                                    <button @click="globalDateFilter = ''; globalStatusFilter = ''; appliedDateFilter = ''; appliedStatusFilter = ''; open = false" class="text-sm text-gray-500 hover:text-gray-700 font-medium whitespace-nowrap transition-colors">
                                                        Reset
                                                    </button>
                                                    <button @click="appliedDateFilter = globalDateFilter; appliedStatusFilter = globalStatusFilter; open = false" class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md font-medium whitespace-nowrap shadow-sm transition-colors">
                                                        Apply Filters
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="h-5 w-px bg-gray-300 mx-1 hidden sm:block"></div>
                                        <!-- Global Search Input -->
                                        <div class="relative w-48">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                            <input type="text" x-model="globalSearchQuery" placeholder="Search..." class="block w-full pl-10 pr-3 py-1.5 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 bg-white">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Active Filters Info Bar -->
                                <div x-show="appliedStatusFilter || globalSearchQuery" style="display: none;" class="px-4 py-2 bg-blue-50 border border-blue-100 rounded-lg mb-6 flex flex-wrap items-center gap-2 text-xs text-blue-700">
                                    <svg class="w-3.5 h-3.5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-medium">Filtered by:</span>
                                    
                                    <template x-if="appliedStatusFilter">
                                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full capitalize" x-text="appliedStatusFilter"></span>
                                    </template>
                                    <template x-if="globalSearchQuery">
                                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full" x-text="'Search: &quot;' + globalSearchQuery + '&quot;'"></span>
                                    </template>
                                    
                                    <button @click="appliedDateFilter = ''; appliedStatusFilter = ''; globalDateFilter = ''; globalStatusFilter = ''; globalSearchQuery = ''" class="ml-auto text-blue-600 hover:text-blue-800 font-medium underline underline-offset-2">
                                        Clear all
                                    </button>
                                </div>

                                <!-- Sessions list -->
                                <template x-if="totalFilteredRecords === 0">
                                    <div class="px-4 py-6 text-center text-gray-400 text-sm border border-gray-200 rounded-lg bg-white">
                                        No records match the current filters.
                                    </div>
                                </template>

                                <template x-for="sess in filteredSessions" :key="sess.session_id">
                                    <div class="mb-6 border border-gray-200 rounded-lg shadow-sm" x-show="getFilteredRecords(sess).length > 0 || (!globalStatusFilter && !globalSearchQuery)">
                                        <!-- Records table -->
                                        <div class="overflow-x-auto rounded-t-lg">
                                            <table class="w-full text-sm text-left">
                                                <thead class="text-xs text-white bg-blue-500 border-b border-blue-500">
                                                    <tr>
                                                        <th class="px-4 py-3 font-semibold">Student</th>
                                                        <th class="px-4 py-3 font-semibold">Student ID</th>
                                                        <th class="px-4 py-3 font-semibold">Course</th>
                                                        <th class="px-4 py-3 font-semibold">Scan Time</th>
                                                        <th class="px-4 py-3 font-semibold text-center">Status</th>
                                                        <th class="px-4 py-3 font-semibold text-right">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-if="getFilteredRecords(sess).length === 0">
                                                        <tr>
                                                            <td colspan="6" class="px-4 py-6 text-center text-gray-400 text-xs bg-white">No records match the current filters.</td>
                                                        </tr>
                                                    </template>
                                                    <template x-for="rec in getPaginatedRecords(sess)" :key="rec.id">
                                                        <tr class="border-b border-gray-100 hover:bg-gray-50 bg-white">
                                                            <td class="px-4 py-3 font-medium text-gray-800" x-text="rec.full_name"></td>
                                                            <td class="px-4 py-3 text-gray-600" x-text="rec.student_number"></td>
                                                            <td class="px-4 py-3 text-gray-600" x-text="rec.course"></td>
                                                            <td class="px-4 py-3 text-gray-600" x-text="rec.scanned_at ?? '—'"></td>
                                                            <td class="px-4 py-3 text-center">
                                                                <span x-show="rec.status === 'present'"
                                                                      class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700">Present</span>
                                                                <span x-show="rec.status === 'late'"
                                                                      class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700">Late</span>
                                                                <span x-show="rec.status === 'absent'"
                                                                      class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-600">Absent</span>
                                                            </td>
                                                            <td class="px-4 py-3 text-right">
                                                                <button x-show="rec.id" 
                                                                        @click="deleteRecord(rec.id)" 
                                                                        title="Delete Record"
                                                                        class="text-gray-400 hover:text-red-600 transition-colors bg-transparent border-0 cursor-pointer">
                                                                    <svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Pagination Controls -->
                                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between rounded-b-lg" x-show="getFilteredRecords(sess).length > itemsPerPage">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm text-gray-700">Showing</span>
                                                <select x-model.number="itemsPerPage" @change="currentPage = 1" class="border-gray-300 rounded-md text-sm py-1 pl-2 pr-6 focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                </select>
                                                <span class="text-sm text-gray-700">of <span x-text="getFilteredRecords(sess).length"></span> results</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button @click="prevPage()" :disabled="currentPage === 1" :class="{'opacity-50 cursor-not-allowed': currentPage === 1}" class="w-8 h-8 flex items-center justify-center rounded-md bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                                    </svg>
                                                </button>
                                                
                                                <template x-for="page in totalPages" :key="page">
                                                    <button @click="currentPage = page" 
                                                            :class="{'bg-blue-600 text-white shadow-sm': currentPage === page, 'bg-gray-50 text-gray-700 hover:bg-gray-100': currentPage !== page}" 
                                                            class="w-8 h-8 flex items-center justify-center rounded-md text-sm font-medium transition-colors"
                                                            x-text="page">
                                                    </button>
                                                </template>

                                                <button @click="nextPage()" :disabled="currentPage >= totalPages" :class="{'opacity-50 cursor-not-allowed': currentPage >= totalPages}" class="w-8 h-8 flex items-center justify-center rounded-md bg-gray-50 text-gray-600 hover:bg-gray-100 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                        </div><!-- /records tab -->

                    </div><!-- /tab content -->
                </div><!-- /card -->
            </div><!-- /right -->
        </div><!-- /flex -->
    </div><!-- /container -->

    <!-- ══════════════════════════════════════════════════════════════════════ -->
    <!-- Alpine.js Components                                                   -->
    <!-- ══════════════════════════════════════════════════════════════════════ -->
    <script>
        /**
         * Attendance Session tab component.
         * Manages opening a session, scanning student IDs, and finalizing absent marks.
         */
        function attendanceSession(assignmentId, initialActiveScheduleId = null, latestSessionId = null) {
            return {
                // ── Session state ─────────────────────────────────────────────────
                session:          null,
                records:          [],
                enrolledStudents: [],
                summary:          { total: 0, present: 0, late: 0, absent: 0 },
                sessionId:        null,

                // ── Pagination & Search state ──────────────────────────────────────
                sessionSearch:    '',
                itemsPerPage:     5,
                currentPage:      1,

                // ── Scan state ────────────────────────────────────────────────────
                scanInput:        '',
                scanning:         false,
                /** Persistent student details of the last successfully processed student. */
                lastScan:         null,
                /** Brief error/warning feedback (auto-clears). */
                scanFeedback:     null,

                // ── Manual attendance state ───────────────────────────────────────
                showManual:       false,
                manualSearch:     '',
                manualStudentId:  null,
                manualPreview:    null,
                manualRecording:  false,
                manualFeedback:   null,

                // ── Finalize state ────────────────────────────────────────────────
                finalizing:       false,

                init() {
                    // If a session is already active for this class today, load it immediately
                    if (initialActiveScheduleId) {
                        this.openAttendanceSession(initialActiveScheduleId);
                    } else if (latestSessionId) {
                        // Or load the latest historical session so it's always visible
                        this.sessionId = latestSessionId;
                        this.refreshSession();
                    }

                    // Auto-focus the scan input when the tab becomes active
                    this.$watch('tab', (val) => {
                        if (val === 'attendance') {
                            this.$nextTick(() => {
                                document.getElementById('scanInput')?.focus();
                            });
                        }
                    });

                    // Reset pagination when search changes
                    this.$watch('sessionSearch', () => {
                        this.currentPage = 1;
                    });
                },

                /**
                 * Merged roster: enrolled students merged with their records.
                 * Students with no record appear as 'pending' (UI-only, not saved to DB).
                 */
                get mergedRoster() {
                    const recordMap = {};
                    this.records.forEach(r => { recordMap[r.student_id] = r; });

                    return this.enrolledStudents.map(s => {
                        const rec = recordMap[s.id];
                        if (rec) {
                            return {
                                id:                rec.id,
                                student_id:        s.id,
                                full_name:         rec.full_name,
                                student_number:    rec.student_number,
                                course:            rec.course,
                                scanned_at:        rec.scanned_at,
                                status:            rec.status,
                                attendance_method: rec.attendance_method,
                            };
                        }
                        return {
                            student_id:        s.id,
                            full_name:         s.full_name,
                            student_number:    s.student_number,
                            course:            s.course,
                            scanned_at:        null,
                            status:            'pending',
                            attendance_method: null,
                        };
                    });
                },

                /**
                 * Filtered session roster based on search query.
                 */
                get filteredSessionRoster() {
                    const q = this.sessionSearch.trim().toLowerCase();
                    let roster = this.mergedRoster;
                    if (q) {
                        roster = roster.filter(s => 
                            (s.full_name && s.full_name.toLowerCase().includes(q)) || 
                            (s.student_number && s.student_number.toLowerCase().includes(q))
                        );
                    }
                    return roster;
                },

                /**
                 * Paginated chunk of the filtered roster.
                 */
                get paginatedSessionRoster() {
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + parseInt(this.itemsPerPage);
                    return this.filteredSessionRoster.slice(start, end);
                },

                /**
                 * Total number of pages for pagination.
                 */
                get totalPages() {
                    return Math.max(1, Math.ceil(this.filteredSessionRoster.length / this.itemsPerPage));
                },

                /**
                 * Filtered enrolled students for the manual attendance dropdown.
                 * Searches full_name and student_number case-insensitively.
                 */
                get filteredStudents() {
                    const q = this.manualSearch.trim().toLowerCase();
                    if (!q) return this.enrolledStudents;
                    return this.enrolledStudents.filter(s =>
                        s.full_name.toLowerCase().includes(q) ||
                        s.student_number.toLowerCase().includes(q)
                    );
                },

                async openAttendanceSession(scheduleId) {
                    try {
                        const res = await fetch(`{{ route('instructor.attendance.open') }}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ schedule_id: scheduleId }),
                        });

                        const data = await res.json();
                        if (res.ok) {
                            this.session   = data.session;
                            this.sessionId = data.session.id;
                            this.summary   = data.summary ?? { total: 0, present: 0, late: 0, absent: 0 };
                            await this.refreshSession();
                            this.$nextTick(() => document.getElementById('scanInput')?.focus());
                        } else {
                            alert(data.message ?? 'Failed to open session.');
                        }
                    } catch (e) {
                        console.error(e);
                    }
                },

                async refreshSession() {
                    if (!this.sessionId) return;
                    try {
                        const res  = await fetch(`/instructor/attendance/${this.sessionId}`, {
                            headers: { 'Accept': 'application/json' },
                        });
                        const data = await res.json();
                        this.session          = data.session;
                        this.records          = data.records;
                        this.enrolledStudents = data.enrolled_students ?? [];
                        this.summary          = data.summary;
                        this.currentPage      = 1;
                        this.sessionSearch    = '';
                    } catch (e) {
                        console.error(e);
                    }
                },

                async submitScan() {
                    const val = this.scanInput.trim();
                    if (!val || this.scanning) return;

                    this.scanning     = true;
                    this.scanFeedback = null;

                    try {
                        const res  = await fetch(`/instructor/attendance/${this.sessionId}/scan`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ student_number: val }),
                        });

                        const data = await res.json();

                        if (data.status === 'recorded') {
                            const rec = data.record;
                            // Update persistent student details form
                            this.lastScan = {
                                student_number: rec.student_number,
                                full_name:      rec.full_name,
                                course:         rec.course,
                                scanned_at:     rec.scanned_at,
                                status:         rec.status,
                                method:         'scan',
                            };
                            this.records = [rec, ...this.records.filter(r => r.student_id !== rec.student_id)];
                            this.summary = data.summary;
                            // Notify any open dashboard tab to refresh its live stats
                            window.dispatchEvent(new CustomEvent('attendance-recorded'));

                        } else if (data.status === 'duplicate') {
                            const rec = data.record;
                            this.scanFeedback = { type: 'warning', message: data.message };
                            if (rec) {
                                this.lastScan = {
                                    student_number: rec.student_number,
                                    full_name:      rec.full_name,
                                    course:         rec.course,
                                    scanned_at:     rec.scanned_at,
                                    status:         'duplicate',
                                    method:         rec.attendance_method,
                                };
                            }
                            setTimeout(() => { this.scanFeedback = null; }, 4000);

                        } else {
                            const typeMap = {
                                not_found:     'error',
                                not_enrolled:  'error',
                                window_closed: 'error',
                            };
                            this.scanFeedback = {
                                type:    typeMap[data.status] ?? 'error',
                                message: data.message,
                            };
                            setTimeout(() => { this.scanFeedback = null; }, 4000);
                        }

                    } catch (e) {
                        this.scanFeedback = { type: 'error', message: 'Network error. Please try again.' };
                        setTimeout(() => { this.scanFeedback = null; }, 4000);
                    } finally {
                        this.scanning = false;
                        // Keep the student number visible; select-all so next scanner entry replaces it
                        this.$nextTick(() => {
                            const inp = document.getElementById('scanInput');
                            if (inp) { inp.focus(); inp.select(); }
                        });
                    }
                },

                /** Called when the instructor picks a student from the manual dropdown. */
                selectManualStudent() {
                    if (!this.manualStudentId) {
                        this.manualPreview  = null;
                        this.manualFeedback = null;
                        return;
                    }
                    const id = parseInt(this.manualStudentId, 10);
                    this.manualPreview  = this.enrolledStudents.find(s => s.id === id) ?? null;
                    this.manualFeedback = null;
                },

                async submitManual() {
                    if (!this.manualStudentId || this.manualRecording) return;

                    this.manualRecording = true;
                    this.manualFeedback  = null;

                    try {
                        const res  = await fetch(`/instructor/attendance/${this.sessionId}/manual`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ student_id: parseInt(this.manualStudentId, 10) }),
                        });

                        const data = await res.json();

                        if (data.status === 'recorded') {
                            const rec = data.record;
                            this.lastScan = {
                                student_number: rec.student_number,
                                full_name:      rec.full_name,
                                course:         rec.course,
                                scanned_at:     rec.scanned_at,
                                status:         rec.status,
                                method:         'manual',
                            };
                            this.records = [rec, ...this.records.filter(r => r.student_id !== rec.student_id)];
                            this.summary = data.summary;
                            // Notify any open dashboard tab to refresh its live stats
                            window.dispatchEvent(new CustomEvent('attendance-recorded'));
                            // Close the panel and reset
                            this.showManual      = false;
                            this.manualSearch    = '';
                            this.manualStudentId = null;
                            this.manualPreview   = null;
                            this.manualFeedback  = null;
                            // Return focus to the scan input for the next student
                            this.$nextTick(() => document.getElementById('scanInput')?.focus());

                        } else if (data.status === 'duplicate') {
                            this.manualFeedback = { type: 'warning', message: data.message };
                            const rec = data.record;
                            if (rec) {
                                this.lastScan = {
                                    student_number: rec.student_number,
                                    full_name:      rec.full_name,
                                    course:         rec.course,
                                    scanned_at:     rec.scanned_at,
                                    status:         'duplicate',
                                    method:         rec.attendance_method,
                                };
                            }
                        } else {
                            this.manualFeedback = { type: 'error', message: data.message };
                        }

                    } catch (e) {
                        this.manualFeedback = { type: 'error', message: 'Network error. Please try again.' };
                    } finally {
                        this.manualRecording = false;
                    }
                },

                async finalizeAbsent() {
                    if (this.finalizing) return;
                    this.finalizing = true;

                    try {
                        const res  = await fetch(`/instructor/attendance/${this.sessionId}/mark-absent`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });

                        const data = await res.json();

                        if (res.ok) {
                            await this.refreshSession();
                        } else {
                            alert(data.message ?? 'Could not finalize session.');
                        }
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.finalizing = false;
                    }
                },

                async deleteSession() {
                    if (!confirm('Are you sure you want to delete this session? This action cannot be undone and will delete all attendance records for this session.')) return;
                    try {
                        const res = await fetch(`/instructor/attendance/${this.sessionId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            }
                        });
                        
                        if (res.ok) {
                            alert('Session deleted successfully.');
                            window.location.reload();
                        } else {
                            const data = await res.json();
                            alert(data.message ?? 'Failed to delete session.');
                        }
                    } catch (e) {
                        alert('Network error while deleting session.');
                    }
                },

                async deleteRecord(recordId) {
                    if (!confirm('Are you sure you want to delete this attendance record?')) return;
                    try {
                        const res = await fetch(`/instructor/attendance/record/${recordId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            }
                        });
                        
                        if (res.ok) {
                            const data = await res.json();
                            // If we're on the active session tab, refresh it.
                            if (this.sessionId) {
                                await this.refreshSession();
                            }
                        } else {
                            const data = await res.json();
                            alert(data.message ?? 'Failed to delete record.');
                        }
                    } catch (e) {
                        alert('Network error while deleting record.');
                    }
                },
            };
        }

        /**
         * Expose openAttendanceSession to the Timetable tab's Start/Resume buttons.
         * Dispatches a custom event that the Alpine attendance component listens for
         * via @open-attendance-session.window.
         */
        function openAttendanceSession(scheduleId) {
            window.dispatchEvent(
                new CustomEvent('open-attendance-session', { detail: { scheduleId } })
            );
        }

        /**
         * Attendance Record tab component.
         * Loads historical sessions on first render.
         */
        function attendanceRecords(assignmentId) {
            return {
                sessions: [],
                loading:  true,
                globalDateFilter: '',
                globalStatusFilter: '',
                globalSearchQuery: '',
                appliedDateFilter: '',
                appliedStatusFilter: '',
                
                itemsPerPage: 5,
                currentPage: 1,

                get summary() {
                    let total = 0, present = 0, late = 0, absent = 0;
                    this.filteredSessions.forEach(sess => {
                        const recs = this.getFilteredRecords(sess);
                        total += recs.length;
                        present += recs.filter(r => r.status === 'present').length;
                        late += recs.filter(r => r.status === 'late').length;
                        absent += recs.filter(r => r.status === 'absent').length;
                    });
                    return { total, present, late, absent };
                },

                get totalFilteredRecords() {
                    return this.filteredSessions.reduce((sum, sess) => sum + this.getFilteredRecords(sess).length, 0);
                },

                get filteredSessions() {
                    if (this.appliedDateFilter) {
                        return this.sessions.filter(sess => sess.raw_date === this.appliedDateFilter);
                    }
                    // By default, just show the latest session
                    return this.sessions.length > 0 ? [this.sessions[0]] : [];
                },

                getFilteredRecords(sess) {
                    return sess.records.filter(r => {
                        const matchesSearch = !this.globalSearchQuery || 
                                            r.full_name.toLowerCase().includes(this.globalSearchQuery.toLowerCase()) || 
                                            r.student_number.toLowerCase().includes(this.globalSearchQuery.toLowerCase());
                        const matchesFilter = !this.appliedStatusFilter || r.status === this.appliedStatusFilter;
                        return matchesSearch && matchesFilter;
                    });
                },
                
                getPaginatedRecords(sess) {
                    const filtered = this.getFilteredRecords(sess);
                    const start = (this.currentPage - 1) * this.itemsPerPage;
                    const end = start + this.itemsPerPage;
                    return filtered.slice(start, end);
                },
                
                get totalPages() {
                    if (this.filteredSessions.length === 0) return 0;
                    const filtered = this.getFilteredRecords(this.filteredSessions[0]);
                    return Math.ceil(filtered.length / this.itemsPerPage);
                },
                
                nextPage() {
                    if (this.currentPage < this.totalPages) this.currentPage++;
                },
                
                prevPage() {
                    if (this.currentPage > 1) this.currentPage--;
                },

                async init() {
                    // Reset pagination when search/filter changes
                    this.$watch('globalSearchQuery', () => { this.currentPage = 1; });
                    this.$watch('appliedStatusFilter', () => { this.currentPage = 1; });
                    this.$watch('appliedDateFilter', () => { this.currentPage = 1; });
                    await this.load();
                },

                async load() {
                    this.loading = true;
                    try {
                        const res  = await fetch(`/instructor/classes/${assignmentId}/attendance-records`, {
                            headers: { 'Accept': 'application/json' },
                        });
                        const data = await res.json();
                        this.sessions = data.sessions ?? [];
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.loading = false;
                    }
                },
            };
        }
    </script>

    </div>
</x-app-layout>
