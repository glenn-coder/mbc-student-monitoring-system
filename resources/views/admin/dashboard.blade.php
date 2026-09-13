<x-app-layout>
    <div class="p-8 mx-auto max-w-7xl">
        
        <!-- Header -->
        <div class="flex justify-between items-center w-full mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
            
            <div class="flex items-center gap-3">
                <!-- Date Range + Dropdown Group -->
                <div class="flex items-center bg-white border border-gray-200 rounded-lg shadow-sm">
                    <button class="flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 border-r border-gray-200 rounded-l-lg transition-colors">
                        <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Oct 18 - Nov 18
                    </button>
                    <button class="flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded-r-lg transition-colors">
                        Monthly
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Hero Banner -->
            <div class="lg:col-span-2 relative overflow-hidden rounded-2xl bg-[#1e293b] text-white shadow-sm h-64">
                <!-- Abstract/Image placeholder background -->
                <div class="absolute inset-0 bg-gradient-to-r from-[#1e293b] via-[#334155] to-transparent z-10"></div>
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="School Building" class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay">
                
                <div class="relative z-20 flex flex-col justify-center h-full p-8">
                    <div class="inline-block px-3 py-1 mb-4 text-xs font-semibold text-[#0ea5e9] bg-[#e0f2fe] rounded-full w-max">
                        Term 1 • 2026-2027
                    </div>
                    <h2 class="mb-2 text-3xl font-bold text-white">Metro Business College Administrator Dashboard</h2>
                    <p class="mb-6 text-sm text-gray-300 max-w-md">Central hub for system administration. Manage student profiles, instructor accounts, and faculty assignments for the current academic term.</p>
                    
                    <div class="flex space-x-3">
                        <button class="px-5 py-2 text-sm font-semibold text-[#0f172a] transition-colors bg-white rounded-full hover:bg-gray-100 shadow-sm">
                            View weekly report
                        </button>
                        <button class="px-5 py-2 text-sm font-semibold text-white transition-colors bg-transparent border border-white/30 rounded-full hover:bg-white/10">
                            Announce
                        </button>
                    </div>
                </div>
            </div>

            <!-- Today at a glance -->
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm flex flex-col h-64">
                <h3 class="text-lg font-bold text-gray-900">Today at a glance</h3>
                <p class="mb-4 text-sm text-gray-500">Live operations summary</p>
                
                <div class="flex-1 space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <a href="{{ route('admin.students.index') }}" class="hover:underline hover:text-emerald-600 transition-colors">Enrolled Students</a>
                        </div>
                        <span class="font-semibold text-gray-900">{{ $studentCount }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Active Instructors
                        </div>
                        <span class="font-semibold text-gray-900">0</span>
                    </div>
                </div>
            </div>
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

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Student Attendance Overview -->
            <div class="lg:col-span-2 p-6 bg-white border border-gray-100 rounded-2xl shadow-sm">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Student Attendance Overview</h3>
                        <p class="text-sm text-gray-500">Daily present vs absent across all students</p>
                    </div>
                </div>
                
                <!-- Chart Placeholder -->
                <div class="relative h-64 w-full">
                    <!-- Fake Y-axis -->
                    <div class="absolute left-0 top-0 bottom-0 flex flex-col justify-between text-xs text-gray-400 w-10 pb-6 text-right pr-2">
                        <span>2000</span>
                        <span>1500</span>
                        <span>1000</span>
                        <span>500</span>
                        <span>0</span>
                    </div>
                    
                    <!-- Fake Chart Area -->
                    <div class="ml-10 h-full relative border-l border-b border-gray-200">
                        <!-- Grid lines -->
                        <div class="absolute w-full border-t border-gray-100 top-[25%] z-0"></div>
                        <div class="absolute w-full border-t border-gray-100 top-[50%] z-0"></div>
                        <div class="absolute w-full border-t border-gray-100 top-[75%] z-0"></div>
                        
                        <!-- Present (Green) Area Placeholder -->
                        <div class="absolute bottom-6 left-0 right-0 h-[80%] z-10 overflow-hidden">
                            <svg preserveAspectRatio="none" class="w-full h-full text-emerald-100/50 fill-current" viewBox="0 0 100 100">
                                <path d="M0,100 L0,15 Q25,5 50,15 T100,20 L100,100 Z" />
                            </svg>
                            <svg preserveAspectRatio="none" class="absolute inset-0 w-full h-full text-emerald-400 stroke-current" viewBox="0 0 100 100" fill="none" stroke-width="1.5" vector-effect="non-scaling-stroke">
                                <path d="M0,15 Q25,5 50,15 T100,20" />
                            </svg>
                        </div>
                        
                        <!-- Absent (Blue) Area Placeholder -->
                        <div class="absolute bottom-6 left-0 right-0 h-[20%] z-20 overflow-hidden">
                            <svg preserveAspectRatio="none" class="w-full h-full text-blue-100/60 fill-current" viewBox="0 0 100 100">
                                <path d="M0,100 L0,70 Q25,85 50,75 T100,80 L100,100 Z" />
                            </svg>
                            <svg preserveAspectRatio="none" class="absolute inset-0 w-full h-full text-blue-500 stroke-current" viewBox="0 0 100 100" fill="none" stroke-width="2" vector-effect="non-scaling-stroke">
                                <path d="M0,70 Q25,85 50,75 T100,80" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Admissions -->
            <div class="p-6 bg-white border border-gray-100 rounded-2xl shadow-sm flex flex-col">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Class Attendance Status</h3>
                    <p class="text-sm text-gray-500">Total students recorded today - 690</p>
                </div>
                
                <div class="flex-1 flex flex-col items-center justify-center relative">
                    <!-- Doughnut Chart Placeholder -->
                    <div class="relative w-48 h-48 mb-6">
                        <svg class="w-full h-full" viewBox="0 0 36 36">
                            <!-- Rejected (Purple) -->
                            <path class="text-purple-500 stroke-current" stroke-dasharray="15, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4.5"/>
                            <!-- Pending (Yellow) -->
                            <path class="text-amber-400 stroke-current" stroke-dasharray="25, 100" stroke-dashoffset="-15" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4.5"/>
                            <!-- In Review (Blue) -->
                            <path class="text-blue-500 stroke-current" stroke-dasharray="20, 100" stroke-dashoffset="-40" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4.5"/>
                            <!-- Approved (Green) -->
                            <path class="text-emerald-500 stroke-current" stroke-dasharray="40, 100" stroke-dashoffset="-60" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke-width="4.5"/>
                        </svg>
                        
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-bold text-gray-900">690</span>
                            <span class="text-xs text-gray-500">Total Students</span>
                        </div>
                    </div>
                    
                    <!-- Legend -->
                    <div class="w-full space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center text-gray-600">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span>
                                Present
                            </div>
                            <span class="font-medium">412</span>
                        </div>
                         <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center text-gray-600">
                                <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                                Late 
                            </div>
                            <span class="font-medium">138</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center text-gray-600">
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                Absent 
                            </div>
                            <span class="font-medium">139</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</x-app-layout>
