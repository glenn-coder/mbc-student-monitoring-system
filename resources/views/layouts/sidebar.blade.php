<div :class="sidebarExpanded ? 'w-56' : 'w-20'" class="flex flex-col h-screen px-4 py-8 overflow-y-auto bg-[#0D1164] border-r border-white/20 transition-all duration-300 flex-shrink-0">
    <!-- Logo & Toggle -->
    <div class="relative flex items-center pb-6 mb-6 border-b border-white/20 justify-center h-16">
        <!-- Expanded state: Logo and Text (Click to minimize) -->
        <button @click="sidebarExpanded = false" 
                class="absolute flex items-center gap-4 w-full justify-center focus:outline-none" 
                x-show="sidebarExpanded"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90">
            <img src="{{ asset('images/MBC-logo.png') }}" alt="MBC Logo" class="w-12 h-12 object-contain flex-shrink-0">
            <div class="flex flex-col text-left">
                <span class="text-2xl font-bold text-white tracking-wide leading-none mb-1">MBC</span>
                @if(auth()->check())
                    @if(auth()->user()->role === 'admin')
                        <span class="text-xs font-semibold text-blue-400 uppercase tracking-widest leading-none">Admin</span>
                    @elseif(auth()->user()->role === 'instructor')
                        <span class="text-xs font-semibold text-blue-400 uppercase tracking-widest leading-none">Instructor</span>
                    @elseif(auth()->user()->role === 'student')
                        <span class="text-xs font-semibold text-blue-400 uppercase tracking-widest leading-none">Student</span>
                    @endif
                @endif
            </div>
        </button>
        
        <!-- Collapsed state: Hamburger Icon (Click to maximize) -->
        <button @click="sidebarExpanded = true" 
                class="absolute p-1.5 text-gray-400 transition-colors rounded-lg hover:bg-white/10 hover:text-white focus:outline-none flex-shrink-0" 
                x-show="!sidebarExpanded"
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 scale-90 rotate-180"
                x-transition:enter-end="opacity-100 scale-100 rotate-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 rotate-0"
                x-transition:leave-end="opacity-0 scale-90 -rotate-180">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
    </div>

    <!-- Navigation -->
    <div class="flex flex-col justify-between flex-1 mt-6">
        <nav class="space-y-2 text-sm">
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a class="flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('admin.dashboard') ? 'text-white bg-[#0ea5e9]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'px-4' : 'justify-center px-0'" href="{{ route('admin.dashboard') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Dashboard</span>
                </a>

                <a class="flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('admin.students.*') ? 'text-white bg-[#0ea5e9]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'px-4' : 'justify-center px-0'" href="{{ route('admin.students.index') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Students</span>
                </a>

                <a class="flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('admin.instructors.*') ? 'text-white bg-[#0ea5e9]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'px-4' : 'justify-center px-0'" href="{{ route('admin.instructors.index') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Instructor</span>
                </a>
                
                <a class="flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('admin.assignments.*') ? 'text-white bg-[#0ea5e9]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'px-4' : 'justify-center px-0'" href="{{ route('admin.assignments.index') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Faculty Assignments</span>
                </a>

                <a class="flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('admin.subjects.*') ? 'text-white bg-[#0ea5e9]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'px-4' : 'justify-center px-0'" href="{{ route('admin.subjects.index') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Subjects</span>
                </a>

                <a class="flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('admin.courses.*') ? 'text-white bg-[#0ea5e9]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'px-4' : 'justify-center px-0'" href="{{ route('admin.courses.index') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Courses</span>
                </a>

                <a class="flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('admin.admins.*') ? 'text-white bg-[#0ea5e9]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'px-4' : 'justify-center px-0'" href="{{ route('admin.admins.index') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Admin Users</span>
                </a>

                <a class="flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('admin.audit-logs.*') ? 'text-white bg-[#0ea5e9]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'px-4' : 'justify-center px-0'" href="{{ route('admin.audit-logs.index') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Audit Logs</span>
                </a>

                <div x-data="{ reportsOpen: {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="reportsOpen = !reportsOpen" class="w-full flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('admin.reports.*') ? 'text-white bg-[#0ea5e9]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'justify-between px-4' : 'justify-center px-0'">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Reports</span>
                        </div>
                        <svg x-show="sidebarExpanded" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': reportsOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <div x-show="reportsOpen && sidebarExpanded" class="pl-11 pr-4 space-y-1" style="display: none;">
                        <a href="{{ route('admin.reports.students') }}" class="block py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('admin.reports.students') ? 'text-white font-medium' : 'text-gray-400 hover:text-white' }}">
                            Student Reports
                        </a>
                        <a href="{{ route('admin.reports.inactive-students') }}" class="block py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('admin.reports.inactive-students') ? 'text-white font-medium' : 'text-gray-400 hover:text-white' }}">
                            Inactive Students
                        </a>
                        <a href="{{ route('admin.reports.instructors') }}" class="block py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('admin.reports.instructors') ? 'text-white font-medium' : 'text-gray-400 hover:text-white' }}">
                            Instructor Reports
                        </a>
                    </div>
                </div>
            @elseif(auth()->check() && auth()->user()->role === 'instructor')
                <a class="flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('instructor.dashboard') ? 'text-white bg-[#10b981]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'px-4' : 'justify-center px-0'" href="{{ route('instructor.dashboard') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Dashboard</span>
                </a>
            @elseif(auth()->check() && auth()->user()->role === 'student')
                <a class="flex items-center py-3 transition-colors rounded-xl {{ request()->routeIs('student.dashboard') ? 'text-white bg-[#f59e0b]' : 'text-gray-400 hover:text-white hover:bg-white/10' }}" :class="sidebarExpanded ? 'px-4' : 'justify-center px-0'" href="{{ route('student.dashboard') }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    <span class="mx-4 font-medium whitespace-nowrap" x-show="sidebarExpanded">Dashboard</span>
                </a>
            @endif
        </nav>
    </div>
</div>
