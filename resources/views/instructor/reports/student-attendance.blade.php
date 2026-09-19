<x-app-layout>
    <div class="p-8 mx-auto max-w-7xl">

        {{-- ── Page Header ── --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Student Attendance Report</h2>
                <p class="mt-1 text-sm text-gray-500">
                    Detailed attendance records across all your assigned classes.
                </p>
            </div>
        </div>

        {{-- ── KPI Cards ── --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

            {{-- Total Records --}}
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Records</span>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($totalRecords) }}</h4>
            </div>

            {{-- Present --}}
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Present</span>
                    <div class="p-2 bg-green-50 text-green-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($kpiPresent) }}</h4>
            </div>

            {{-- Late --}}
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Late</span>
                    <div class="p-2 bg-orange-50 text-orange-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($kpiLate) }}</h4>
            </div>

            {{-- Absent --}}
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Absent</span>
                    <div class="p-2 bg-red-50 text-red-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($kpiAbsent) }}</h4>
            </div>
        </div>

        {{-- ── Main Table Card ── --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">

            {{-- Toolbar --}}
            <div class="p-4 border-b border-gray-200 flex flex-wrap justify-between items-center gap-3 bg-gray-50 rounded-t-lg">

                {{-- Left: per-page selector --}}
                <div class="flex items-center gap-2">
                    <form action="{{ route('instructor.reports.student-attendance') }}" method="GET"
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
                        <span class="text-sm text-gray-700">of {{ $records->total() }} results</span>

                        {{-- Preserve all active query params when changing per_page --}}
                        @if(request('date'))          <input type="hidden" name="date"          value="{{ request('date') }}">          @endif
                        @if(request('assignment_id')) <input type="hidden" name="assignment_id" value="{{ request('assignment_id') }}"> @endif
                        @if(request('search'))     <input type="hidden" name="search"      value="{{ request('search') }}">     @endif
                        @foreach(request('statuses', []) as $s)
                            <input type="hidden" name="statuses[]" value="{{ $s }}">
                        @endforeach
                    </form>
                </div>

                {{-- Right: Filters + Search + Export --}}
                <div class="flex items-center gap-3 flex-wrap">
                    <form action="{{ route('instructor.reports.student-attendance') }}" method="GET"
                          id="filterForm" class="flex items-center gap-3 flex-wrap">
                        @if(request('per_page') && request('per_page') != 25)
                            <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                        @endif

                        {{-- Filters Dropdown --}}
                        <div x-data="{ open: false }" class="relative z-40">
                            <button @click="open = !open" type="button"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-md font-medium text-sm text-gray-700 hover:bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filters
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" style="display: none;"
                                class="absolute z-50 mt-2 w-72 max-h-[75vh] overflow-y-auto rounded-md shadow-lg bg-white border border-gray-200 right-0 origin-top-right custom-scrollbar">
                                <div class="p-4 space-y-4">

                                    {{-- Date --}}
                                    <div>
                                        <label for="sa_date" class="block text-sm font-medium text-gray-700">Date</label>
                                        <input type="date" name="date" id="sa_date"
                                            value="{{ request('date') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>



                                    {{-- Class / Subject --}}
                                    <div>
                                        <label for="sa_assignment_id" class="block text-sm font-medium text-gray-700">Class / Subject</label>
                                        <select name="assignment_id" id="sa_assignment_id"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="">All My Classes</option>
                                            @foreach($assignments as $assignment)
                                                <option value="{{ $assignment->id }}"
                                                    {{ request('assignment_id') == $assignment->id ? 'selected' : '' }}>
                                                    {{ $assignment->subject->subject_name ?? 'N/A' }}
                                                    — {{ $assignment->course->code ?? 'N/A' }}
                                                    {{ $assignment->year ? '(' . $assignment->year . ')' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Status checkboxes --}}
                                    <div>
                                        <span class="block text-sm font-medium text-gray-700 mb-2">Status</span>
                                        <div class="space-y-1.5">
                                            @foreach(['present' => 'Present', 'late' => 'Late', 'absent' => 'Absent'] as $value => $label)
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" name="statuses[]" value="{{ $value }}"
                                                        {{ in_array($value, request('statuses', [])) ? 'checked' : '' }}
                                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <p class="mt-1.5 text-xs text-gray-400">Leave all unchecked to show all statuses.</p>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <a href="{{ route('instructor.reports.student-attendance') }}"
                                            class="text-sm text-gray-600 hover:text-gray-900 font-medium">Reset</a>
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-sm">
                                            Apply Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Search: student number or name --}}
                        <div class="flex items-center gap-2">
                            <div class="relative w-52">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="sa_search"
                                    value="{{ request('search') }}"
                                    placeholder="Student No. or Name…"
                                    class="block w-full pl-9 pr-3 py-1.5 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm">
                            </div>
                        </div>
                    </form>

                    {{-- Export Data --}}
                    <div x-data="{ open: false }" class="relative z-40">
                        <button @click="open = !open" @click.away="open = false" type="button"
                            class="px-3 py-1.5 text-xs font-medium bg-gray-800 text-white rounded-md hover:bg-gray-700 flex items-center gap-1.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Data
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        @php
                            // Build the filter query string to append to each export link.
                            // All currently active filters must be forwarded to the export.
                            $exportParams = array_filter(
                                request()->only(['date', 'assignment_id', 'search'])
                            );
                            if (!empty(request('statuses'))) {
                                // http_build_query handles arrays natively
                                $exportParams['statuses'] = request('statuses');
                            }
                            $exportQuery = http_build_query($exportParams);
                        @endphp

                        <div x-show="open" style="display: none;"
                            class="absolute right-0 mt-2 w-48 bg-gray-800/95 backdrop-blur-sm rounded-xl shadow-xl border border-gray-700/50 overflow-hidden">
                            <div class="py-1">
                                <a href="{{ route('instructor.reports.student-attendance.export', ['type' => 'pdf']) . ($exportQuery ? '?' . $exportQuery : '') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF Document
                                </a>
                                <a href="{{ route('instructor.reports.student-attendance.export', ['type' => 'excel']) . ($exportQuery ? '?' . $exportQuery : '') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel Spreadsheet
                                </a>
                                <a href="{{ route('instructor.reports.student-attendance.export', ['type' => 'csv']) . ($exportQuery ? '?' . $exportQuery : '') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    CSV Format
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Active Filters Info Bar --}}
            @php
                $activeStatuses = request('statuses', []);
                $hasActiveFilters = request('date') || request('assignment_id')
                    || request('search') || !empty($activeStatuses);
            @endphp
            @if($hasActiveFilters)
                <div class="px-4 py-2 bg-blue-50 border-b border-blue-100 flex flex-wrap items-center gap-2 text-xs text-blue-700">
                    <svg class="w-3.5 h-3.5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">Filtered by:</span>
                    @if(request('assignment_id'))
                        @php $activeAssignment = $assignments->firstWhere('id', (int) request('assignment_id')); @endphp
                        @if($activeAssignment)
                            <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                                {{ $activeAssignment->subject->subject_name ?? 'N/A' }} — {{ $activeAssignment->course->code ?? 'N/A' }}
                            </span>
                        @endif
                    @endif
                    @if(request('date'))
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                            Date: {{ \Carbon\Carbon::parse(request('date'))->format('M d, Y') }}
                        </span>
                    @endif
                    @foreach($activeStatuses as $st)
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full capitalize">{{ $st }}</span>
                    @endforeach
                    @if(request('search'))
                        <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                            Search: "{{ request('search') }}"
                        </span>
                    @endif
                    <a href="{{ route('instructor.reports.student-attendance') }}"
                        class="ml-auto text-blue-600 hover:text-blue-800 font-medium underline underline-offset-2">
                        Clear all
                    </a>
                </div>
            @endif

            {{-- Attendance Records Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-white bg-blue-600 border-b border-blue-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Date</th>
                            <th scope="col" class="px-6 py-3 font-bold">Student Number</th>
                            <th scope="col" class="px-6 py-3 font-bold">Name</th>
                            <th scope="col" class="px-6 py-3 font-bold">Course / Year</th>
                            <th scope="col" class="px-6 py-3 font-bold">Subject</th>
                            <th scope="col" class="px-6 py-3 font-bold">Timestamp</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Method</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
                            @php
                                $session  = $record->session;
                                $schedule = $session?->schedule;
                                $subject  = $schedule?->instructorAssignment?->subject;
                                $course   = $schedule?->instructorAssignment?->course;
                                $student  = $record->student;
                            @endphp
                            <tr class="border-b transition-colors {{ $loop->even ? 'bg-indigo-50' : 'bg-white' }} hover:bg-indigo-50">

                                {{-- Date (session_date, not scanned_at) --}}
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                    {{ $session?->session_date?->format('M d, Y') ?? '—' }}
                                </td>

                                {{-- Student Number --}}
                                <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-800">
                                    {{ $student?->student_number ?? '—' }}
                                </td>

                                {{-- Name: Last, First MI --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student)
                                        {{ $student->last_name }}, {{ $student->first_name }}
                                        @if($student->middle_name) {{ mb_substr($student->middle_name, 0, 1) }}. @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Course / Year (from the student's own profile) --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($student)
                                        <span class="font-medium">{{ $student->course?->code ?? 'N/A' }}</span>
                                        @if($student->year)
                                            <span class="text-gray-500"> — {{ $student->year }}</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Subject --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $subject?->subject_name ?? 'N/A' }}
                                </td>

                                {{-- Timestamp (scanned_at — null for system-generated absent records) --}}
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                    @if($record->scanned_at)
                                        {{ $record->scanned_at->format('g:i A') }}
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>

                                {{-- Method Badge --}}
                                <td class="px-6 py-4 text-center">
                                    @php $method = $record->attendance_method ?? 'system'; @endphp
                                    @if($method === 'scan')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                            Scan
                                        </span>
                                    @elseif($method === 'manual')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Manual
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            System
                                        </span>
                                    @endif
                                </td>

                                {{-- Status Badge --}}
                                <td class="px-6 py-4 text-center">
                                    @if($record->status === 'present')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                            Present
                                        </span>
                                    @elseif($record->status === 'late')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-800">
                                            Late
                                        </span>
                                    @elseif($record->status === 'absent')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                            Absent
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">{{ $record->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        @if($hasActiveFilters)
                                            <p class="text-sm font-medium text-gray-500">No records match the current filters.</p>
                                            <a href="{{ route('instructor.reports.student-attendance') }}"
                                               class="text-xs text-blue-600 hover:underline mt-1">Clear all filters</a>
                                        @else
                                            <p class="text-sm font-medium text-gray-500">No completed attendance records found for your classes.</p>
                                            <p class="text-xs text-gray-400">Records appear here once an attendance session is closed.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $records->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
