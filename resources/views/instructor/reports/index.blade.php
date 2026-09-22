<x-app-layout>
    <div class="p-8 w-full">

        {{-- ── Page Header ── --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Class Attendance Summary</h2>
                <p class="mt-1 text-sm text-gray-500">View and monitor attendance performance for your assigned classes.</p>
            </div>
        </div>

        {{-- ── Summary Cards ── --}}
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">

            {{-- Total Students --}}
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Students</span>
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($totalStudents) }}</h4>
            </div>

            {{-- Present --}}
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Present</span>
                    <div class="p-2 bg-green-50 text-green-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($totalPresent) }}</h4>
            </div>

            {{-- Late --}}
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Late</span>
                    <div class="p-2 bg-orange-50 text-orange-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($totalLate) }}</h4>
            </div>

            {{-- Absent --}}
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Total Absent</span>
                    <div class="p-2 bg-red-50 text-red-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">{{ number_format($totalAbsent) }}</h4>
            </div>

            {{-- Total Attendance Rate --}}
            <div class="p-4 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-shadow flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-start mb-3 gap-2">
                    <span class="text-xs font-bold text-gray-600 uppercase tracking-wider">Attendance Rate</span>
                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-full shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <h4 class="text-2xl font-bold text-gray-900">
                    {{ $totalAttendanceRate !== null ? $totalAttendanceRate . '%' : '—' }}
                </h4>
            </div>
        </div>

        {{-- ── Main Table Card ── --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">

            {{-- Toolbar --}}
            <div class="p-4 border-b border-gray-200 flex flex-wrap justify-between items-center gap-3 bg-gray-50 rounded-t-lg">

                {{-- Left: Showing N of X --}}
                <div class="flex items-center gap-2">
                    <form action="{{ route('instructor.reports.index') }}" method="GET" id="perPageForm" class="flex items-center gap-2">
                        <span class="text-sm text-gray-700">Showing</span>
                        <select name="per_page" onchange="document.getElementById('perPageForm').submit()"
                            class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 pl-2 pr-6">
                            <option value="5"  {{ $perPage == 5  ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        <span class="text-sm text-gray-700">of {{ $students->total() }} results</span>

                        {{-- Preserve other query params --}}
                        @if(request('search'))        <input type="hidden" name="search"        value="{{ request('search') }}">        @endif
                        @if(request('assignment_id')) <input type="hidden" name="assignment_id" value="{{ request('assignment_id') }}"> @endif
                        @if(request('date_from'))     <input type="hidden" name="date_from"     value="{{ request('date_from') }}">     @endif
                        @if(request('date_to'))       <input type="hidden" name="date_to"       value="{{ request('date_to') }}">       @endif
                        @if(request('status'))        <input type="hidden" name="status"        value="{{ request('status') }}">        @endif
                    </form>
                </div>

                {{-- Right: Filters + Search + Export --}}
                <div class="flex items-center gap-3 flex-wrap">
                    <form action="{{ route('instructor.reports.index') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                        @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif

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
                                class="absolute z-50 mt-2 w-72 rounded-md shadow-lg bg-white border border-gray-200 left-0 origin-top-left">
                                <div class="p-4 space-y-4">

                                    {{-- Class / Subject --}}
                                    <div>
                                        <label for="assignment_id" class="block text-sm font-medium text-gray-700">Class / Subject</label>
                                        <select name="assignment_id" id="assignment_id"
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

                                    {{-- Date From --}}
                                    <div>
                                        <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                                        <input type="date" name="date_from" id="date_from"
                                            value="{{ request('date_from') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    {{-- Date To --}}
                                    <div>
                                        <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                                        <input type="date" name="date_to" id="date_to"
                                            value="{{ request('date_to') }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <a href="{{ route('instructor.reports.index') }}"
                                            class="text-sm text-gray-600 hover:text-gray-900 font-medium">Reset</a>
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            Apply Filters
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Search --}}
                        <div class="flex items-center gap-2">
                            <div class="relative w-48">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" name="search" id="search"
                                    value="{{ request('search') }}"
                                    placeholder="Search..."
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

                        <div x-show="open" style="display: none;"
                            class="absolute right-0 mt-2 w-48 bg-gray-800/95 backdrop-blur-sm rounded-xl shadow-xl border border-gray-700/50 overflow-hidden">
                            <div class="py-1">
                                <a href="{{ route('instructor.reports.export', ['type' => 'pdf']) . '?' . http_build_query(array_filter(request()->only(['assignment_id', 'date_from', 'date_to', 'search']))) }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF Document
                                </a>
                                <a href="{{ route('instructor.reports.export', ['type' => 'excel']) . '?' . http_build_query(array_filter(request()->only(['assignment_id', 'date_from', 'date_to', 'search']))) }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel Spreadsheet
                                </a>
                                <a href="{{ route('instructor.reports.export', ['type' => 'csv']) . '?' . http_build_query(array_filter(request()->only(['assignment_id', 'date_from', 'date_to', 'search']))) }}"
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
            @if($selectedAssignment || request('date_from') || request('date_to') || request('search'))
            <div class="px-4 py-2 bg-blue-50 border-b border-blue-100 flex flex-wrap items-center gap-2 text-xs text-blue-700">
                <svg class="w-3.5 h-3.5 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">Filtered by:</span>
                @if($selectedAssignment)
                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                        {{ $selectedAssignment->subject->subject_name ?? 'N/A' }} — {{ $selectedAssignment->course->code ?? 'N/A' }}
                    </span>
                @endif
                @if(request('date_from'))
                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">From: {{ \Carbon\Carbon::parse(request('date_from'))->format('M d, Y') }}</span>
                @endif
                @if(request('date_to'))
                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">To: {{ \Carbon\Carbon::parse(request('date_to'))->format('M d, Y') }}</span>
                @endif
                @if(request('search'))
                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Search: "{{ request('search') }}"</span>
                @endif
                <a href="{{ route('instructor.reports.index') }}" class="ml-auto text-blue-600 hover:text-blue-800 font-medium underline underline-offset-2">Clear all</a>
            </div>
            @endif

            {{-- Report Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-white bg-blue-600 border-b border-blue-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Student Number</th>
                            <th scope="col" class="px-6 py-3 font-bold">Name</th>
                            <th scope="col" class="px-6 py-3 font-bold">Course</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Present</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Late</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Absent</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Attendance Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr class="bg-white border-b hover:bg-indigo-50 {{ $loop->even ? 'bg-indigo-50' : '' }} transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $student->student_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $student->last_name }}, {{ $student->first_name }}
                                    @if($student->middle_name)
                                        {{ $student->middle_name[0] }}.
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    {{ $student->course->code ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $student->stat_present }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $student->stat_late }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    {{ $student->stat_absent }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($student->stat_rate === null)
                                        <span class="text-gray-400 text-xs">—</span>
                                    @else
                                        {{ $student->stat_rate }}%
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-2 text-gray-400">
                                        <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        @if(request('search') || request('assignment_id') || request('date_from') || request('date_to'))
                                            <p class="text-sm font-medium text-gray-500">No students found matching the criteria.</p>
                                            <a href="{{ route('instructor.reports.index') }}" class="text-xs text-blue-600 hover:underline mt-1">Clear filters</a>
                                        @elseif($totalStudents === 0)
                                            <p class="text-sm font-medium text-gray-500">No students are enrolled in your assigned classes.</p>
                                        @else
                                            <p class="text-sm font-medium text-gray-500">No attendance records found for the selected class and date range.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($students instanceof \Illuminate\Pagination\LengthAwarePaginator && $students->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $students->links() }}
                </div>
            @endif
        </div>

        {{-- Completed Sessions Info (subtle footer note) --}}
        @if($completedSessionCount > 0)
            <p class="mt-3 text-xs text-gray-400 text-right">
                Based on {{ number_format($completedSessionCount) }} completed {{ Str::plural('session', $completedSessionCount) }}
                @if(request('date_from') || request('date_to'))
                    in the selected date range
                @endif
                .
            </p>
        @endif
    </div>
</x-app-layout>
