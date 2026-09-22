<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl md:text-2xl font-bold text-slate-900 leading-tight">
            Dashboard
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
                @endphp
                <h1 class="text-2xl font-bold text-gray-900">{{ $greeting }}, {{ Auth::user()->name }}</h1>
                <span class="text-sm font-semibold tracking-wider text-slate-500 uppercase">{{ $dateString }}</span>
            </div>
            
            <div class="flex items-center gap-3">


                <!-- Filter Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:text-gray-900 hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-50 w-72 mt-2 origin-top-right bg-white border border-gray-200 rounded-lg shadow-lg" style="display: none;">
                        <form action="{{ route('instructor.dashboard') }}" method="GET" class="p-4 space-y-4">
                            <!-- Date From -->
                            <div>
                                <label for="date_from" class="block text-xs font-semibold text-gray-500 mb-1">Date From</label>
                                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 py-1.5">
                            </div>
                            
                            <!-- Date To -->
                            <div>
                                <label for="date_to" class="block text-xs font-semibold text-gray-500 mb-1">Date To</label>
                                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 py-1.5">
                            </div>

                            <!-- Class Subject -->
                            <div>
                                <label for="subject_id" class="block text-xs font-semibold text-gray-500 mb-1">Class Subject</label>
                                <select id="subject_id" name="subject_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500 py-1.5">
                                    <option value="">All Subjects</option>
                                    @foreach($uniqueSubjects ?? [] as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->subject_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 pt-2 border-t border-gray-100">
                                <a href="{{ route('instructor.dashboard') }}" class="flex-1 px-3 py-1.5 text-center text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">Reset</a>
                                <button type="submit" class="flex-1 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 transition-colors">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Export Button -->
                <button class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-lg shadow-sm hover:text-gray-900 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            </div>
        </div>

            <!-- Metric Cards -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <!-- Total Students -->
                <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Students</span>
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-full shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ $studentCount }}</h4>
                </div>

                <!-- Total Classes -->
                <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Classes</span>
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-full shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ $classCount }}</h4>
                </div>

                <!-- Total Present -->
                <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Present</span>
                        <div class="p-2 bg-green-50 text-green-600 rounded-full shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ $todayPresent }}</h4>
                </div>

                <!-- Total Late -->
                <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Late</span>
                        <div class="p-2 bg-orange-50 text-orange-600 rounded-full shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ $todayLate }}</h4>
                </div>

                <!-- Total Absent -->
                <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Absent</span>
                        <div class="p-2 bg-red-50 text-red-600 rounded-full shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900">{{ $todayAbsent }}</h4>
                </div>
            </div>
        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Left Column (Overview & Distribution) -->
            <div class="lg:col-span-2 flex flex-col space-y-6">
                <!-- Student Attendance Overview -->
                <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Student Attendance Overview</h3>
                            <p class="text-sm text-gray-500">Daily present, late, and absent across all students</p>
                        </div>
                    </div>

                    <div class="relative h-72 w-full mt-4">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>

                <!-- Attendance Distribution -->
                <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-gray-50 border border-gray-200 rounded-md">
                                <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                                </svg>
                            </div>
                            <h3 class="text-md font-bold text-gray-900">Class Attendance Status</h3>
                        </div>
                        <button class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                            Today
                            <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-500">Present</span>
                            </div>
                            <div class="text-xl font-bold text-gray-900">{{ $todayPresent }}</div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-500">Late</span>
                            </div>
                            <div class="text-xl font-bold text-gray-900">{{ $todayLate }}</div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <div class="w-1 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm text-gray-500">Absent</span>
                            </div>
                            <div class="text-xl font-bold text-gray-900">{{ $todayAbsent }}</div>
                        </div>
                    </div>

                    <!-- Chart -->
                    <div class="relative w-full flex justify-center mt-2 mb-2">
                        <div class="relative w-full max-w-[280px] flex justify-center">
                            <canvas id="attendanceDistributionChart"></canvas>
                            <div class="absolute left-1/2 bottom-0 -translate-x-1/2 flex flex-col items-center justify-end pb-4 pointer-events-none text-center">
                                <span class="text-3xl font-bold text-gray-800">{{ $studentCount }}</span>
                                <span class="text-xs text-gray-500 font-medium uppercase tracking-wider mt-1 whitespace-nowrap">Total Students</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Include Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script type="application/json" id="chartDataJson">
                {!! json_encode([
                    'present' => $chartData['present'] ?? [0,0,0,0,0,0],
                    'late'    => $chartData['late']    ?? [0,0,0,0,0,0],
                    'absent'  => $chartData['absent']  ?? [0,0,0,0,0,0],
                ]) !!}
            </script>
            <script>
                var chartData = JSON.parse(document.getElementById('chartDataJson').textContent);
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('attendanceChart').getContext('2d');
                    
                    const present = parseInt('{{ $todayPresent }}', 10) || 0;
                    const late = parseInt('{{ $todayLate }}', 10) || 0;
                    const absent = parseInt('{{ $todayAbsent }}', 10) || 0;

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                            datasets: [
                                {
                                    label: 'Present',
                                    data: chartData.present,
                                    borderColor: '#22c55e', // Green
                                    backgroundColor: 'transparent',
                                    tension: 0.4,
                                    borderWidth: 2,
                                    pointRadius: 0,
                                    pointHoverRadius: 6,
                                    pointBackgroundColor: '#22c55e'
                                },
                                {
                                    label: 'Late',
                                    data: chartData.late,
                                    borderColor: '#3b82f6', // Blue
                                    backgroundColor: 'transparent',
                                    tension: 0.4,
                                    borderWidth: 2,
                                    pointRadius: 0,
                                    pointHoverRadius: 6,
                                    pointBackgroundColor: '#3b82f6'
                                },
                                {
                                    label: 'Absent',
                                    data: chartData.absent,
                                    borderColor: '#ef4444', // Red
                                    backgroundColor: 'transparent',
                                    tension: 0.4,
                                    borderWidth: 2,
                                    pointRadius: 0,
                                    pointHoverRadius: 6,
                                    pointBackgroundColor: '#ef4444'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        color: '#4b5563', // text-gray-600
                                        usePointStyle: true,
                                        boxWidth: 6,
                                        boxHeight: 6,
                                        pointStyleWidth: 8,
                                        padding: 20,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: '#ffffff',
                                    titleColor: '#111827',
                                    bodyColor: '#4b5563',
                                    borderColor: '#e5e7eb',
                                    borderWidth: 1,
                                    padding: 12,
                                    usePointStyle: true,
                                    boxPadding: 6,
                                    titleFont: {
                                        size: 14,
                                        weight: 'bold'
                                    },
                                    bodyFont: {
                                        size: 13
                                    },
                                    callbacks: {
                                        label: function(context) {
                                            return context.dataset.label + ': ' + context.parsed.y;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        color: '#f3f4f6', // gray-100
                                        drawBorder: false,
                                        tickLength: 0
                                    },
                                    ticks: {
                                        color: '#6b7280', // gray-500
                                        padding: 10
                                    }
                                },
                                y: {
                                    grid: {
                                        color: '#f3f4f6', // gray-100
                                        drawBorder: false,
                                        tickLength: 0
                                    },
                                    ticks: {
                                        color: '#6b7280', // gray-500
                                        stepSize: 1,
                                        padding: 10
                                    },
                                    min: 0
                                }
                            }
                        }
                    });

                    // Attendance Distribution Gauge Chart
                    const distCtx = document.getElementById('attendanceDistributionChart').getContext('2d');
                    
                    // If there is no data at all, provide a default so chart renders empty track
                    let distData = [present, late, absent];
                    let bgColors = ['#22c55e', '#3b82f6', '#ef4444'];
                    
                    if (present === 0 && late === 0 && absent === 0) {
                        distData = [1];
                        bgColors = ['#f3f4f6']; // gray-100 empty state
                    }

                    new Chart(distCtx, {
                        type: 'doughnut',
                        data: {
                            labels: present === 0 && late === 0 && absent === 0 ? ['No Data'] : ['Present', 'Late', 'Absent'],
                            datasets: [{
                                data: distData,
                                backgroundColor: bgColors,
                                borderWidth: 4,
                                borderColor: '#ffffff',
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            aspectRatio: 2,
                            rotation: -90,
                            circumference: 180,
                            cutout: '75%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: !(present === 0 && late === 0 && absent === 0),
                                    backgroundColor: '#ffffff',
                                    titleColor: '#111827',
                                    bodyColor: '#4b5563',
                                    borderColor: '#e5e7eb',
                                    borderWidth: 1,
                                    padding: 12,
                                    boxPadding: 6,
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ' + context.parsed;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>

            <!-- Next Class Schedule & Upcoming Class Schedules -->
            <div class="flex flex-col justify-between h-full">
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

        <!-- Recent Attendance History -->
        <div id="recent-attendance" class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Recent Attendance History</h3>
                    <p class="text-sm text-gray-500">Latest attendance records across all your classes</p>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-white">
                    <div class="flex items-center gap-2">
                        <form action="{{ route('instructor.dashboard') }}#recent-attendance" method="GET"
                              id="perPageForm" class="flex items-center gap-2">
                            <span class="text-sm text-gray-700">Showing</span>
                            <select name="per_page" onchange="document.getElementById('perPageForm').submit()"
                                class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 pl-2 pr-6">
                                <option value="5"   {{ $perPage == 5   ? 'selected' : '' }}>5</option>
                                <option value="10"  {{ $perPage == 10  ? 'selected' : '' }}>10</option>
                                <option value="25"  {{ $perPage == 25  ? 'selected' : '' }}>25</option>
                                <option value="50"  {{ $perPage == 50  ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-gray-700">of {{ $recentRecords->total() }} results</span>
                            
                            @if(request('date_from'))  <input type="hidden" name="date_from"  value="{{ request('date_from') }}">  @endif
                            @if(request('date_to'))    <input type="hidden" name="date_to"    value="{{ request('date_to') }}">    @endif
                            @if(request('subject_id')) <input type="hidden" name="subject_id" value="{{ request('subject_id') }}"> @endif
                        </form>
                    </div>
                    
                    @if($latestSessionAssignmentId)
                    <a href="{{ route('instructor.classes.show', $latestSessionAssignmentId) }}?tab=records" 
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View
                    </a>
                    @endif
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs text-white bg-[#2F2FE4] border-b border-[#2F2FE4]">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold">Student Name</th>
                                <th scope="col" class="px-6 py-4 font-semibold">Class</th>
                                <th scope="col" class="px-6 py-4 font-semibold">Date & Time</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center">Method</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRecords as $record)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $record->student->first_name ?? '' }} {{ $record->student->last_name ?? '' }}
                                        <div class="text-xs text-gray-500 font-normal">{{ $record->student->student_number ?? '' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $record->session->schedule->instructorAssignment->subject->subject_name ?? 'N/A' }}
                                        <div class="text-xs text-gray-500">{{ $record->session->schedule->instructorAssignment->course->code ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ \Carbon\Carbon::parse($record->scanned_at ?? $record->created_at)->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($record->status === 'present')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded border border-green-200 text-xs font-medium bg-green-50 text-green-700">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span> Present
                                            </span>
                                        @elseif($record->status === 'late')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded border border-yellow-200 text-xs font-medium bg-yellow-50 text-yellow-700">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-yellow-500"></span> Late
                                            </span>
                                        @elseif($record->status === 'absent')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded border border-red-200 text-xs font-medium bg-red-50 text-red-600">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span> Absent
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded border border-gray-200 text-xs font-medium bg-gray-50 text-gray-500">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-gray-400"></span> {{ ucfirst($record->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($record->attendance_method === 'scan')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-600">Scan</span>
                                        @elseif($record->attendance_method === 'manual')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700">Manual</span>
                                        @elseif($record->attendance_method === 'system')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500">System</span>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">
                                        No recent attendance records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                @if($recentRecords instanceof \Illuminate\Pagination\LengthAwarePaginator && $recentRecords->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $recentRecords->appends(request()->query())->fragment('recent-attendance')->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

</x-app-layout>