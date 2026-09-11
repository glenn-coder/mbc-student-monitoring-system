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

        <div class="flex flex-col gap-6" x-data="{ tab: 'timetable', showInfoModal: false }">
            
            <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $assignment->subject->subject_name }}</h2>
                    <p class="text-sm text-gray-500">{{ $assignment->subject->subject_code }} • Section: {{ $assignment->course->code ?? 'N/A' }}</p>
                </div>
                <button @click="showInfoModal = true" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition-colors border border-blue-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Class Information
                </button>
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
                             @open-attendance-session.window="openAttendanceSession($event.detail.scheduleId)"
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


                                <!-- ─── Active Session: Scan + Details + Manual ─────────────────────────────── -->
                                <div x-show="session && session?.status === 'open' && !session?.has_ended" class="space-y-4">

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
                                                        ! Late
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
                                    <div class="flex justify-end">
                                        <button
                                            @click="showManual = !showManual; if (!showManual) { manualSearch = ''; manualStudentId = null; manualPreview = null; manualFeedback = null; }"
                                            :class="showManual
                                                ? 'bg-amber-600 hover:bg-amber-700 text-white border-transparent'
                                                : 'bg-white hover:bg-amber-50 text-amber-700 border-amber-300'"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium border rounded-md shadow-sm transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span x-text="showManual ? 'Cancel Manual Attendance' : 'Take Manual Attendance'"></span>
                                        </button>
                                    </div>

                                    <!-- Manual Attendance Panel (inline, collapsible) -->
                                    <div x-show="showManual"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         class="bg-amber-50 border border-amber-200 rounded-lg overflow-hidden">

                                        <div class="bg-amber-100 border-b border-amber-200 px-4 py-3">
                                            <h4 class="text-sm font-bold text-amber-800">Manual Attendance</h4>
                                            <p class="text-xs text-amber-700 mt-0.5">Use this when a student cannot present their ID (forgot, misplaced, or lost).</p>
                                        </div>

                                        <div class="p-4 space-y-4">
                                            <!-- Search -->
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 mb-1">Search</label>
                                                <input
                                                    type="text"
                                                    x-model="manualSearch"
                                                    placeholder="Search by name or student number…"
                                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-amber-400 focus:border-amber-400"
                                                >
                                            </div>

                                            <!-- Student selector -->
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-600 mb-1">Student</label>
                                                <select
                                                    x-model="manualStudentId"
                                                    @change="selectManualStudent()"
                                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-amber-400 focus:border-amber-400">
                                                    <option value="">— Select enrolled student —</option>
                                                    <template x-for="s in filteredStudents" :key="s.id">
                                                        <option :value="s.id" x-text="s.full_name + ' (' + s.student_number + ')'"></option>
                                                    </template>
                                                </select>
                                            </div>

                                            <!-- Student preview (read-only) -->
                                            <div x-show="manualPreview" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Student Number</label>
                                                    <input type="text" readonly
                                                           :value="manualPreview?.student_number ?? ''"
                                                           class="w-full border border-gray-200 bg-white rounded-md px-3 py-2 text-sm text-gray-700 cursor-default focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Full Name</label>
                                                    <input type="text" readonly
                                                           :value="manualPreview?.full_name ?? ''"
                                                           class="w-full border border-gray-200 bg-white rounded-md px-3 py-2 text-sm text-gray-700 cursor-default focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Course</label>
                                                    <input type="text" readonly
                                                           :value="manualPreview?.course ?? ''"
                                                           class="w-full border border-gray-200 bg-white rounded-md px-3 py-2 text-sm text-gray-700 cursor-default focus:outline-none">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Timestamp &amp; Status</label>
                                                    <input type="text" readonly
                                                           value="Calculated on submission"
                                                           class="w-full border border-gray-200 bg-white rounded-md px-3 py-2 text-sm text-gray-400 italic cursor-default focus:outline-none">
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
                                            <div class="flex justify-end gap-2 pt-1 border-t border-amber-200">
                                                <button
                                                    @click="showManual = false; manualSearch = ''; manualStudentId = null; manualPreview = null; manualFeedback = null;"
                                                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 border border-gray-300 rounded-md transition-colors">
                                                    Cancel
                                                </button>
                                                <button
                                                    @click="submitManual()"
                                                    :disabled="!manualStudentId || manualRecording"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 rounded-md shadow-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
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

                                <!-- Attendance Roster Table: ALL enrolled students -->
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg overflow-hidden">
                                        <thead class="text-xs text-white bg-blue-600">
                                            <tr>
                                                <th class="px-4 py-3 font-bold border-r border-blue-700">Student</th>
                                                <th class="px-4 py-3 font-bold border-r border-blue-700">Student ID</th>
                                                <th class="px-4 py-3 font-bold border-r border-blue-700">Course</th>
                                                <th class="px-4 py-3 font-bold border-r border-blue-700">Timestamp</th>
                                                <th class="px-4 py-3 font-bold border-r border-blue-700 text-center">Status</th>
                                                <th class="px-4 py-3 font-bold text-center">Method</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template x-if="mergedRoster.length === 0">
                                                <tr>
                                                    <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                                                        No students enrolled in this class.
                                                    </td>
                                                </tr>
                                            </template>
                                            <template x-for="row in mergedRoster" :key="row.student_id">
                                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                                    <td class="px-4 py-3 font-medium text-gray-900 border-r border-gray-100" x-text="row.full_name"></td>
                                                    <td class="px-4 py-3 text-gray-600 border-r border-gray-100" x-text="row.student_number"></td>
                                                    <td class="px-4 py-3 text-gray-600 border-r border-gray-100" x-text="row.course"></td>
                                                    <td class="px-4 py-3 text-gray-600 border-r border-gray-100" x-text="row.scanned_at ?? '—'"></td>
                                                    <td class="px-4 py-3 text-center border-r border-gray-100">
                                                        <span x-show="row.status === 'present'"
                                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                                            Present
                                                        </span>
                                                        <span x-show="row.status === 'late'"
                                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                                            Late
                                                        </span>
                                                        <span x-show="row.status === 'absent'"
                                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-600">
                                                            Absent
                                                        </span>
                                                        <span x-show="row.status === 'pending' && session?.is_open"
                                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                                                            Pending
                                                        </span>
                                                        <span x-show="row.status === 'pending' && !session?.is_open"
                                                              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500">
                                                            —
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
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
                // ── Session state ─────────────────────────────────────────────────
                session:          null,
                records:          [],
                enrolledStudents: [],
                summary:          { total: 0, present: 0, late: 0, absent: 0 },
                sessionId:        null,

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
                    // Component is ready; session is opened via @open-attendance-session.window
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
    </div>

    <!-- Class Information Modal -->
    <template x-teleport="body">
        <div @keydown.escape.window="showInfoModal = false"
             x-show="showInfoModal"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true"
             style="display: none;">
             
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                
                <!-- Background overlay -->
                <div x-show="showInfoModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                     @click="showInfoModal = false"
                     aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="showInfoModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-md w-full">
                     
                    <div class="bg-blue-600 px-4 py-3 flex justify-between items-center">
                        <h3 class="text-white font-bold" id="modal-title">Class Information</h3>
                        <button @click="showInfoModal = false" class="text-blue-100 hover:text-white transition-colors focus:outline-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
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
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                        <button type="button" @click="showInfoModal = false" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</x-app-layout>
