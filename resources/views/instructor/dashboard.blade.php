<x-app-layout>
    <div class="p-8 mx-auto max-w-7xl">

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
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
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
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
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
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
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
            <!-- Student Attendance Overview -->
            <div class="lg:col-span-2 p-6 bg-white border border-gray-100 rounded-2xl shadow-sm self-start">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Student Attendance Overview</h3>
                        <p class="text-sm text-gray-500">Daily present, late, and absent across all students</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex bg-gray-100 rounded-full p-1">
                            <button class="px-3 py-1 text-xs font-medium text-white bg-blue-500 rounded-full shadow-sm">Week</button>
                            <button class="px-3 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 transition-colors">Month</button>
                            <button class="px-3 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 transition-colors">Term</button>
                        </div>
                        
                        <!-- Dropdown Menu -->
                        <div x-data="{ open: false }" class="relative">
                            <!-- Hidden date input kept outside the dropdown panel so it doesn't get destroyed when dropdown closes -->
                            <input type="date" x-ref="datePicker" class="absolute w-0 h-0 opacity-0 pointer-events-none" style="top: 0; left: 0;" @change="open = false" />
                            
                            <button @click="open = !open" @click.away="open = false" class="p-1.5 rounded-lg hover:bg-gray-100 transition-colors text-gray-500 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Panel -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50"
                                 style="display: none;">
                                
                                <a href="#" @click.prevent="open = false" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    Export Data
                                </a>
                                
                                <a href="#" @click.prevent="$refs.datePicker.showPicker()" class="relative flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Change Date
                                </a>
                                
                                <a href="#" @click.prevent="open = false" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                    Filter Platforms
                                </a>
                                
                                <a href="#" @click.prevent="open = false" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                    Refresh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative h-72 w-full mt-4">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Include Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script type="application/json" id="chartDataJson">
                {!! json_encode([
                    'present' => $chartData['present'] ?? [0,0,0,0,0],
                    'late'    => $chartData['late']    ?? [0,0,0,0,0],
                    'absent'  => $chartData['absent']  ?? [0,0,0,0,0],
                ]) !!}
            </script>
            <script>
                var chartData = JSON.parse(document.getElementById('chartDataJson').textContent);
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('attendanceChart').getContext('2d');
                    
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
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
                });
            </script>

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