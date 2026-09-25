<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl md:text-2xl font-bold text-slate-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <div class="p-8 w-full">
        
        <!-- Header -->
        <div class="flex justify-between items-center w-full mb-6">
            <div class="flex flex-col">
                @php
                $now = \Carbon\Carbon::now('Asia/Manila');
                $dateString = $now->format('l · F j, Y');
                $hour = $now->hour;
                if ($hour < 12) {
                    $greeting='Good morning';
                } elseif ($hour < 18) {
                    $greeting='Good afternoon';
                } else {
                    $greeting='Good evening';
                }
                
                // Academic Year logic (assuming June is the start of the academic year)
                $currentYear = $now->year;
                $currentMonth = $now->month;
                $academicYear = $currentMonth >= 6 ? $currentYear . '-' . ($currentYear + 1) : ($currentYear - 1) . '-' . $currentYear;
                @endphp
                <h1 class="text-2xl font-bold text-gray-900">{{ $greeting }}, {{ Auth::user()->name }}</h1>
                <span class="text-sm font-semibold tracking-wider text-slate-500 uppercase">{{ $dateString }}</span>
            </div>
            
            <div class="flex items-center gap-3">


                <!-- Filter Button -->
                <button class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:text-gray-900 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>

                <!-- Export Button -->
                <button class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:text-gray-900 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            </div>
        </div>
        <!-- Top Section: Hero & Glance -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6 items-stretch">
            <!-- Hero Banner -->
            <div class="lg:col-span-2 relative overflow-hidden rounded-2xl bg-[#1e293b] text-white shadow-sm min-h-[14rem] h-full">
                <!-- Abstract/Image placeholder background -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#1e293b] via-[#334155] to-transparent z-10"></div>
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="School Building" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay">
                
                <div class="relative z-20 flex flex-col justify-center h-full p-6 md:p-8">
                    <div class="inline-block px-3 py-1 mb-3 text-xs font-semibold text-[#0ea5e9] bg-[#e0f2fe] rounded-full w-max">
                        Term • {{ $academicYear }}
                    </div>
                    <h2 class="mb-2 text-2xl md:text-3xl font-bold text-white">Metro Business College Administrator Dashboard</h2>
                    <p class="mb-2 text-xs md:text-sm text-gray-300 max-w-md">Central hub for system administration. Manage student profiles, instructor accounts, and faculty assignments for the current academic term.</p>

                </div>
            </div>

            <!-- Mini Calendar -->
            <div x-data="calendarWidget()" x-init="initDate()" class="p-5 bg-white border border-gray-100 rounded-2xl shadow-sm flex flex-col min-h-[14rem] h-full">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-gray-900" x-text="monthName + ' ' + year"></h3>
                    <div class="flex gap-1">
                        <button @click="prevMonth()" class="p-1 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <button @click="nextMonth()" class="p-1 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-7 text-center text-xs font-semibold text-gray-400 mb-2">
                    <div>Su</div><div>Mo</div><div>Tu</div><div>We</div><div>Th</div><div>Fr</div><div>Sa</div>
                </div>
                <div class="grid grid-cols-7 gap-y-1 text-xs flex-1 content-start">
                    <template x-for="blank in blankdays">
                        <div class="py-0.5 text-transparent">0</div>
                    </template>
                    <template x-for="date in no_of_days" :key="date">
                        <div class="py-0.5 flex items-center justify-center">
                            <div :class="{
                                    'w-6 h-6 flex items-center justify-center bg-blue-600 text-white rounded-full font-bold shadow-sm': isToday(date),
                                    'w-6 h-6 flex items-center justify-center text-gray-700 hover:bg-gray-100 rounded-full cursor-pointer transition-colors': !isToday(date)
                                }">
                                <span x-text="date"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <script>
                function calendarWidget() {
                    return {
                        month: '',
                        year: '',
                        no_of_days: [],
                        blankdays: [],
                        monthName: '',
                        initDate() {
                            let today = new Date();
                            this.month = today.getMonth();
                            this.year = today.getFullYear();
                            this.getNoOfDays();
                        },
                        isToday(date) {
                            const today = new Date();
                            const d = new Date(this.year, this.month, date);
                            return today.toDateString() === d.toDateString();
                        },
                        getNoOfDays() {
                            let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                            let dayOfWeek = new Date(this.year, this.month).getDay();
                            let blankdaysArray = [];
                            for (var i = 1; i <= dayOfWeek; i++) {
                                blankdaysArray.push(i);
                            }
                            let daysArray = [];
                            for (var i = 1; i <= daysInMonth; i++) {
                                daysArray.push(i);
                            }
                            this.blankdays = blankdaysArray;
                            this.no_of_days = daysArray;
                            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                            this.monthName = monthNames[this.month];
                        },
                        prevMonth() {
                            this.month--;
                            if (this.month < 0) {
                                this.month = 11;
                                this.year--;
                            }
                            this.getNoOfDays();
                        },
                        nextMonth() {
                            this.month++;
                            if (this.month > 11) {
                                this.month = 0;
                                this.year++;
                            }
                            this.getNoOfDays();
                        }
                    }
                }
            </script>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Students -->
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Students</span>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ $studentCount }}</h4>

            </div>

            <!-- Total Instructor -->
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Instructor</span>
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ $instructorCount }}</h4>

            </div>

            <!-- Total Subject -->
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Subject</span>
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ $subjectCount }}</h4>

            </div>

            <!-- Total Faculty Assignments -->
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Assignments</span>
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ $assignmentCount }}</h4>

            </div>
        </div>

        <!-- Recently Enrolled Students Table -->
        <div id="recent-students-table" class="bg-white border border-gray-100 rounded-2xl shadow-sm mb-8 overflow-hidden scroll-mt-6">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Recently Enrolled Students</h3>
                    <p class="text-sm text-gray-500 mt-1">The latest students added to the system</p>
                </div>
                <a href="{{ route('admin.students.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                    View All Students &rarr;
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-sm text-white bg-blue-600 border-b border-blue-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Name</th>
                            <th scope="col" class="px-6 py-3 font-bold">Email</th>
                            <th scope="col" class="px-6 py-3 font-bold">Student Number</th>
                            <th scope="col" class="px-6 py-3 font-bold">Sex</th>
                            <th scope="col" class="px-6 py-3 font-bold">Course</th>
                            <th scope="col" class="px-6 py-3 font-bold">Year</th>
                            <th scope="col" class="px-6 py-3 font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentStudents as $student)
                        <tr class="bg-white border-b hover:bg-indigo-100 {{ $loop->even ? 'bg-indigo-50' : '' }} transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ?? '' }}
                            </td>
                            <td class="px-6 py-4">{{ $student->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $student->student_number }}</td>
                            <td class="px-6 py-4">{{ $student->sex }}</td>
                            <td class="px-6 py-4">
                                {{ $student->course->code ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">{{ $student->year }}</td>
                            <td class="px-6 py-4">
                                @if($student->user && $student->user->status === 'active')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Inactive
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p>No students enrolled yet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($recentStudents->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $recentStudents->fragment('recent-students-table')->links() }}
            </div>
            @endif
        </div>

        <!-- Current Assignments Table -->
        <div id="recent-assignments-table" class="bg-white border border-gray-100 rounded-2xl shadow-sm mb-8 overflow-hidden scroll-mt-6">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Current Assignments</h3>
                    <p class="text-sm text-gray-500 mt-1">Latest faculty assignments</p>
                </div>
                <a href="{{ route('admin.assignments.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
                    View All Assignments &rarr;
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-sm text-white bg-blue-600 border-b border-blue-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Faculty</th>
                            <th scope="col" class="px-6 py-3 font-bold">Subject</th>
                            <th scope="col" class="px-6 py-3 font-bold">Course</th>
                            <th scope="col" class="px-6 py-3 font-bold">Year/Semester</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentAssignments as $assignment)
                            <tr class="bg-white border-b hover:bg-indigo-100 {{ $loop->even ? 'bg-indigo-50' : '' }} transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $assignment->instructor->full_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $assignment->subject->subject_code }}</div>
                                    <div class="text-xs text-gray-500">{{ $assignment->subject->subject_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $assignment->course->code ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $assignment->year }} / {{ $assignment->semester }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    {{ $assignment->students_count }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                        </svg>
                                        <p>No assignments found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($recentAssignments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $recentAssignments->fragment('recent-assignments-table')->links() }}
            </div>
            @endif
        </div>
        
    </div>
</x-app-layout>
