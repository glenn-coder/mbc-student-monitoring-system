<x-app-layout>
    <div class="p-8 mx-auto max-w-7xl">

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Students -->
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-medium text-gray-500">Total Students</span>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-bold text-gray-900 mb-2">{{ $studentCount }}</h4>

            </div>

            <!-- Total Instructor -->
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-medium text-gray-500">Total Classes</span>
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-full">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-bold text-gray-900 mb-2">{{ $instructorCount }}</h4>

            </div>

            <!-- Total Subject -->
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-medium text-gray-500">Total Present</span>
                    <div class="p-2 bg-green-50 text-green-600 rounded-full">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-bold text-gray-900 mb-2">{{ $subjectCount }}</h4>

            </div>

            <!-- Total Faculty Assignments -->
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-4">
                    <span class="text-sm font-medium text-gray-500">Total Absent</span>
                    <div class="p-2 bg-red-50 text-red-600 rounded-full">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-3xl font-bold text-gray-900 mb-2">{{ $assignmentCount }}</h4>

            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Student Attendance Overview -->
            <div class="lg:col-span-2 p-6 bg-white border border-gray-100 rounded-2xl shadow-sm self-start">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Student Attendance Overview</h3>
                        <p class="text-sm text-gray-500">Daily present, late, and absent across all students</p>
                    </div>
                    <div class="flex bg-gray-100 rounded-full p-1">
                        <button class="px-3 py-1 text-xs font-medium text-white bg-blue-500 rounded-full shadow-sm">Week</button>
                        <button class="px-3 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 transition-colors">Month</button>
                        <button class="px-3 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 transition-colors">Term</button>
                    </div>
                </div>

                <!-- Chart Placeholder -->
                <div class="relative h-72 w-full flex flex-col mt-4">
                    <div class="flex-1 relative">
                        <!-- Fake Y-axis -->
                        <div class="absolute left-0 top-0 bottom-0 flex flex-col justify-between text-xs text-gray-400 w-10 text-right pr-2">
                            <span>200</span>
                            <span>150</span>
                            <span>100</span>
                            <span>50</span>
                            <span>0</span>
                        </div>

                        <!-- Fake Chart Area -->
                        <div class="ml-10 h-full border-l border-b border-gray-200 relative">
                            <!-- Grid lines -->
                            <div class="absolute w-full border-t border-gray-100 top-[25%] z-0"></div>
                            <div class="absolute w-full border-t border-gray-100 top-[50%] z-0"></div>
                            <div class="absolute w-full border-t border-gray-100 top-[75%] z-0"></div>

                            <!-- Bar Groups -->
                            <div class="absolute inset-0 z-10 flex justify-around items-end px-2 pt-4">
                                @php
                                $fakeData = [
                                ['day' => 'Mon', 'present' => 85, 'late' => 15, 'absent' => 10],
                                ['day' => 'Tue', 'present' => 90, 'late' => 5, 'absent' => 5],
                                ['day' => 'Wed', 'present' => 75, 'late' => 20, 'absent' => 25],
                                ['day' => 'Thu', 'present' => 80, 'late' => 10, 'absent' => 15],
                                ['day' => 'Fri', 'present' => 95, 'late' => 8, 'absent' => 2],
                                ];
                                @endphp

                                @foreach($fakeData as $data)
                                <div class="flex flex-col items-center h-full justify-end w-full">
                                    <div class="flex items-end space-x-1 h-full w-full justify-center pb-px">
                                        <div class="w-3 sm:w-4 lg:w-6 bg-green-500 rounded-t-sm hover:opacity-80 transition-opacity cursor-pointer" @style(["height: {$data['present']}%"]) title="Present: {{ $data['present'] }}"></div>
                                        <div class="w-3 sm:w-4 lg:w-6 bg-blue-500 rounded-t-sm hover:opacity-80 transition-opacity cursor-pointer" @style(["height: {$data['late']}%"]) title="Late: {{ $data['late'] }}"></div>
                                        <div class="w-3 sm:w-4 lg:w-6 bg-red-500 rounded-t-sm hover:opacity-80 transition-opacity cursor-pointer" @style(["height: {$data['absent']}%"]) title="Absent: {{ $data['absent'] }}"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Fake X-axis labels -->
                    <div class="ml-10 flex justify-around text-xs text-gray-500 font-medium mt-2">
                        @foreach($fakeData as $data)
                        <div class="w-full text-center">{{ $data['day'] }}</div>
                        @endforeach
                    </div>

                    <!-- Legend -->
                    <div class="ml-10 flex justify-center space-x-6 mt-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded mr-2 shadow-sm"></div>
                            <span class="text-xs text-gray-600 font-medium">Present</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded mr-2 shadow-sm"></div>
                            <span class="text-xs text-gray-600 font-medium">Late</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded mr-2 shadow-sm"></div>
                            <span class="text-xs text-gray-600 font-medium">Absent</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Class Schedule & Upcoming Class Schedules -->
            <div class="flex flex-col space-y-6">
                <!-- Next Class Schedule -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Class Schedule</h3>
                    @if($nextSchedule)
                    <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                        <h4 class="text-md font-bold text-gray-900 mb-3">{{ $nextSchedule->instructorAssignment->subject->subject_name ?? 'N/A' }}</h4>

                        <div class="space-y-2 mb-6">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ ucfirst($nextSchedule->day_of_week) }} - {{ \Carbon\Carbon::parse($nextSchedule->start_time)->format('h:i A') }} to {{ \Carbon\Carbon::parse($nextSchedule->end_time)->format('h:i A') }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                Course: {{ $nextSchedule->instructorAssignment->course->code ?? 'N/A' }}
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                {{ $nextSchedule->instructorAssignment->students->count() ?? 0 }} Students
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="#" class="block w-full px-4 py-2 text-sm font-semibold text-center text-white transition-colors bg-[#2F2FE4] rounded-lg hover:bg-[#2F2FE4]/90">
                                View Class
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                        <p class="text-sm text-gray-500">No next schedule available.</p>
                    </div>
                    @endif
                </div>

                <!-- Upcoming Class Schedules -->
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">Next Class Schedules</h3>
                    <p class="text-sm text-gray-500 mb-4">Don't miss scheduled classes</p>

                    <div class="space-y-3">
                        @forelse($upcomingSchedules as $schedule)
                        <div class="p-4 bg-white border border-gray-100 rounded-xl shadow-sm flex items-start relative">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mt-1.5 mr-3 flex-shrink-0"></div>
                            <div class="flex-1 pr-6">
                                <h4 class="text-sm font-bold text-gray-900">{{ $schedule->instructorAssignment->subject->subject_name ?? 'N/A' }}</h4>
                                <div class="text-xs text-gray-500 mt-1 mb-2">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}, {{ ucfirst($schedule->day_of_week) }}
                                </div>
                                <p class="text-xs text-gray-700 mb-1">Course: {{ $schedule->instructorAssignment->course->code ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-700 flex items-center gap-1.5 mt-1">
                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span>{{ $schedule->instructorAssignment->students->count() ?? 0 }} Students</span>
                                </p>
                            </div>
                            <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </div>
                        @empty
                        <div class="p-4 bg-white border border-gray-100 rounded-xl shadow-sm">
                            <p class="text-sm text-gray-500">No upcoming schedules.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>