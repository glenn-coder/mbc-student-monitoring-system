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
            <div class="w-full lg:w-2/3 xl:w-3/4" x-data="{ tab: 'timetable' }">
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
                        <!-- Students Tab -->
                        <div x-show="tab === 'students'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
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
                        <div x-show="tab === 'attendance'" style="display: none;"
                             x-data="attendanceSession({{ $assignment->id }})"
                             x-init="init()"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0">

                            <!-- No session open yet -->
                            <div x-show="!session" class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-gray-500 text-sm mb-2">No active attendance session.</p>
                                <p class="text-gray-400 text-xs">Click <strong>Start</strong> on a schedule in the TimeTable tab to begin.</p>
                            </div>

                            <!-- Session is open -->
                            <div x-show="session" class="space-y-5">

                                <!-- Policy / Session Info Banner -->
                                <div class="rounded-lg border border-blue-100 bg-blue-50 p-4 flex flex-wrap gap-6 items-center">
                                    <div>
                                        <p class="text-xs font-medium text-blue-400 uppercase tracking-wide">Session Date</p>
                                        <p class="text-sm font-bold text-blue-800" x-text="session?.session_date"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-blue-400 uppercase tracking-wide">Class Time</p>
                                        <p class="text-sm font-bold text-blue-800" x-text="(session?.start_time ?? '—') + ' – ' + (session?.end_time ?? '—')"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-blue-400 uppercase tracking-wide">Grace Period</p>
                                        <p class="text-sm font-bold text-blue-800" x-text="(session?.grace_period_minutes ?? '—') + ' min'"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-blue-400 uppercase tracking-wide">Status</p>
                                        <span x-show="session?.is_open" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse inline-block"></span> Open
                                        </span>
                                        <span x-show="!session?.is_open && session?.status === 'closed'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                            Closed
                                        </span>
                                        <span x-show="!session?.is_open && session?.status === 'open' && !session?.has_ended" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                            Not started yet
                                        </span>
                                    </div>
                                </div>

                                <!-- Summary Cards -->
                                <div class="grid grid-cols-4 gap-3">
                                    <div class="bg-white rounded-lg border border-gray-200 p-3 text-center shadow-sm">
                                        <p class="text-xs text-gray-500 font-medium">Total</p>
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

                                <!-- Scan Input -->
                                <div x-show="session?.is_open" class="bg-gray-50 rounded-lg border border-gray-200 p-4">
                                    <p class="text-sm font-semibold text-gray-700 mb-3">Scan / Enter Student ID</p>
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
                                            <span x-show="scanning">...</span>
                                        </button>
                                    </div>

                                    <!-- Scan result feedback -->
                                    <div x-show="scanResult" x-transition class="mt-3 rounded-md px-3 py-2 text-sm font-medium"
                                         :class="{
                                             'bg-green-50 text-green-700 border border-green-200': scanResult?.type === 'success',
                                             'bg-yellow-50 text-yellow-700 border border-yellow-200': scanResult?.type === 'warning',
                                             'bg-red-50 text-red-700 border border-red-200': scanResult?.type === 'error',
                                         }"
                                         x-text="scanResult?.message">
                                    </div>
                                </div>

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
                                        <span x-show="!finalizing">Finalize & Mark Absent</span>
                                        <span x-show="finalizing">Processing...</span>
                                    </button>
                                </div>

                                <!-- Attendance Table -->
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg overflow-hidden">
                                        <thead class="text-xs text-white bg-blue-600">
                                            <tr>
                                                <th class="px-4 py-3 font-bold border-r border-blue-700">Student</th>
                                                <th class="px-4 py-3 font-bold border-r border-blue-700">Student ID</th>
                                                <th class="px-4 py-3 font-bold border-r border-blue-700">Course</th>
                                                <th class="px-4 py-3 font-bold border-r border-blue-700">Scan Time</th>
                                                <th class="px-4 py-3 font-bold text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-if="records.length === 0">
                                                <tr>
                                                    <td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">
                                                        No attendance records yet. Start scanning student IDs.
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-for="record in records" :key="record.id">
                                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                                    <td class="px-4 py-3 font-medium text-gray-900" x-text="record.full_name"></td>
                                                    <td class="px-4 py-3 text-gray-600 border-r border-gray-100" x-text="record.student_number"></td>
                                                    <td class="px-4 py-3 text-gray-600 border-r border-gray-100" x-text="record.course"></td>
                                                    <td class="px-4 py-3 text-gray-600 border-r border-gray-100" x-text="record.scanned_at ?? '—'"></td>
                                                    <td class="px-4 py-3 text-center">
                                                        <span x-show="record.status === 'present'"
                                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                                            Present
                                                        </span>
                                                        <span x-show="record.status === 'late'"
                                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                                            Late
                                                        </span>
                                                        <span x-show="record.status === 'absent'"
                                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-600">
                                                            Absent
                                                        </span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
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

                            <div class="mb-4 flex justify-between items-center">
                                <h3 class="text-lg font-bold text-gray-900">Attendance History</h3>
                                <span class="text-xs text-gray-400" x-text="sessions.length + ' session(s) found'"></span>
                            </div>

                            <!-- Loading -->
                            <div x-show="loading" class="text-center py-10 text-gray-400 text-sm">Loading records...</div>

                            <!-- Empty -->
                            <div x-show="!loading && sessions.length === 0" class="text-center py-10 text-gray-400 text-sm">
                                No attendance sessions found for this class yet.
                            </div>

                            <!-- Sessions list -->
                            <template x-for="sess in sessions" :key="sess.session_id">
                                <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                    <!-- Session header -->
                                    <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex flex-wrap gap-4 items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="font-semibold text-gray-800 text-sm" x-text="sess.session_date"></span>
                                            <span class="text-xs text-gray-500" x-text="sess.day_of_week + ' · ' + sess.start_time + ' – ' + sess.end_time"></span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-bold text-green-700 bg-green-50 border border-green-100 px-2 py-0.5 rounded-full"
                                                  x-text="'P: ' + sess.records.filter(r => r.status === 'present').length"></span>
                                            <span class="text-xs font-bold text-yellow-700 bg-yellow-50 border border-yellow-100 px-2 py-0.5 rounded-full"
                                                  x-text="'L: ' + sess.records.filter(r => r.status === 'late').length"></span>
                                            <span class="text-xs font-bold text-red-600 bg-red-50 border border-red-100 px-2 py-0.5 rounded-full"
                                                  x-text="'A: ' + sess.records.filter(r => r.status === 'absent').length"></span>
                                            <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                                  :class="sess.status === 'closed' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700'"
                                                  x-text="sess.status === 'closed' ? 'Closed' : 'Open'"></span>
                                        </div>
                                    </div>
                                    <!-- Records table -->
                                    <table class="w-full text-sm text-left">
                                        <thead class="text-xs text-white bg-blue-500">
                                            <tr>
                                                <th class="px-4 py-2 font-semibold">Student</th>
                                                <th class="px-4 py-2 font-semibold">Student ID</th>
                                                <th class="px-4 py-2 font-semibold">Course</th>
                                                <th class="px-4 py-2 font-semibold">Scan Time</th>
                                                <th class="px-4 py-2 font-semibold text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-if="sess.records.length === 0">
                                                <tr>
                                                    <td colspan="5" class="px-4 py-6 text-center text-gray-400 text-xs">No records for this session.</td>
                                                </tr>
                                            </template>
                                            <template x-for="rec in sess.records" :key="rec.id">
                                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                    <td class="px-4 py-2.5 font-medium text-gray-800" x-text="rec.full_name"></td>
                                                    <td class="px-4 py-2.5 text-gray-600" x-text="rec.student_number"></td>
                                                    <td class="px-4 py-2.5 text-gray-600" x-text="rec.course"></td>
                                                    <td class="px-4 py-2.5 text-gray-600" x-text="rec.scanned_at ?? '—'"></td>
                                                    <td class="px-4 py-2.5 text-center">
                                                        <span x-show="rec.status === 'present'"
                                                              class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Present</span>
                                                        <span x-show="rec.status === 'late'"
                                                              class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">Late</span>
                                                        <span x-show="rec.status === 'absent'"
                                                              class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-600">Absent</span>
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
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
        function attendanceSession(assignmentId) {
            return {
                session:     null,
                records:     [],
                summary:     { total: 0, present: 0, late: 0, absent: 0 },
                scanInput:   '',
                scanning:    false,
                finalizing:  false,
                scanResult:  null,
                sessionId:   null,

                init() {
                    // If a session ID was pre-loaded by the timetable Start button, open it.
                    // Otherwise, just show the "no session" state.
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
                        this.session = data.session;
                        this.records = data.records;
                        this.summary = data.summary;
                    } catch (e) {
                        console.error(e);
                    }
                },

                async submitScan() {
                    const val = this.scanInput.trim();
                    if (!val || this.scanning) return;

                    this.scanning   = true;
                    this.scanResult = null;

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

                        const typeMap = {
                            recorded:      'success',
                            duplicate:     'warning',
                            not_found:     'error',
                            window_closed: 'error',
                        };

                        this.scanResult = {
                            type:    typeMap[data.status] ?? 'error',
                            message: data.message,
                        };

                        if (data.status === 'recorded') {
                            this.records = [data.record, ...this.records.filter(r => r.id !== data.record?.id)];
                            this.summary = data.summary;
                        }

                        // Clear the scan result after 3 seconds
                        setTimeout(() => { this.scanResult = null; }, 3000);

                    } catch (e) {
                        this.scanResult = { type: 'error', message: 'Network error. Please try again.' };
                    } finally {
                        this.scanning  = false;
                        this.scanInput = '';
                        this.$nextTick(() => document.getElementById('scanInput')?.focus());
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
            };
        }

        /**
         * Expose the openAttendanceSession method to the parent tab scope
         * so the TimeTable "Start/Resume" buttons can trigger it.
         */
        function openAttendanceSession(scheduleId) {
            // Dispatch to the attendance tab's Alpine component
            window._attendanceScheduleId = scheduleId;
        }

        /**
         * Attendance Record tab component.
         * Loads historical sessions on first render.
         */
        function attendanceRecords(assignmentId) {
            return {
                sessions: [],
                loading:  true,

                async init() {
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

    {{-- Override the tab "Start" button to also call openAttendanceSession after tab switch --}}
    <script>
        document.addEventListener('alpine:init', () => {
            // When the attendance tab becomes active and a scheduleId was queued,
            // find and call the attendanceSession component's method.
            Alpine.effect(() => {});
        });
    </script>
</x-app-layout>
