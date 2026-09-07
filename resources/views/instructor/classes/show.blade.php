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
                        <button @click="tab = 'records'" :class="{'bg-blue-600 text-white rounded-t-md': tab === 'records', 'text-gray-500 hover:text-gray-700': tab !== 'records'}" class="px-4 py-2 text-sm font-medium">
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
                                        // $assignment->schedules would normally have the data if populated
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
                                                        $isDone = true; // Day has passed this week
                                                    } elseif ($isToday && $nowTime > $schedule->end_time) {
                                                        $isDone = true; // Today, but time has passed
                                                    }
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
                                                                    <button @click="tab = 'attendance'" class="px-4 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors shadow-sm">
                                                                        Start
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

                        <!-- Placeholder for other tabs -->
                        <div x-show="tab === 'attendance'" style="display: none;">
                            <p class="text-gray-500">Attendance Session content will go here.</p>
                        </div>
                        <div x-show="tab === 'records'" style="display: none;">
                            <p class="text-gray-500">Attendance Record content will go here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
