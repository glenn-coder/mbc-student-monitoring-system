<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl md:text-2xl font-bold text-slate-900 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8 px-4 sm:px-6 lg:px-10 w-full">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between w-full mb-6 gap-4">
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
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div x-data="{ open: false }" class="relative flex-1 sm:flex-none">
                    <button @click="open = !open" class="w-full sm:w-auto justify-center inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 hover:text-gray-900 font-medium hover:bg-gray-50 shadow-sm transition-colors">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                    <!-- Dropdown Panel -->
                    <div x-show="open" @click.away="open = false" x-transition class="absolute left-0 sm:left-auto sm:right-0 z-50 w-[calc(100vw-2rem)] max-w-sm sm:w-72 mt-2 origin-top-left sm:origin-top-right bg-white border border-gray-200 rounded-lg shadow-lg" style="display: none;">
                        <form action="{{ route('student.dashboard') }}" method="GET" class="p-4 space-y-4 text-left">
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
                                <a href="{{ route('student.dashboard') }}" class="flex-1 px-3 py-1.5 text-center text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">Reset</a>
                                <button type="submit" class="flex-1 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 transition-colors">Apply</button>
                            </div>
                        </form>
                    </div>
                </div>
                <button class="flex-1 sm:flex-none justify-center sm:w-auto inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 hover:text-gray-900 font-medium hover:bg-gray-50 shadow-sm transition-colors">
                    <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </button>
            </div>
        </div>

        {{-- ── Attendance Overview ──────────────────────────────────────── --}}
        <div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Present --}}
                <div class="p-5 bg-white border border-slate-100 rounded-xl shadow-sm flex flex-col relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Total Present</span>
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-3xl font-bold text-slate-900">{{ $present }}</h4>
                </div>

                {{-- Late --}}
                <div class="p-5 bg-white border border-slate-100 rounded-xl shadow-sm flex flex-col relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Total Late</span>
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-3xl font-bold text-slate-900">{{ $late }}</h4>
                </div>

                {{-- Absent --}}
                <div class="p-5 bg-white border border-slate-100 rounded-xl shadow-sm flex flex-col relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Total Absent</span>
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-3xl font-bold text-slate-900">{{ $absent }}</h4>
                </div>

                {{-- Attendance Rate --}}
                <div class="p-5 bg-white border border-slate-100 rounded-xl shadow-sm flex flex-col relative overflow-hidden">
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Attendance Rate</span>
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-3xl font-bold text-slate-900">{{ $attendanceRate }}%</h4>
                </div>

            </div>
        </div>

        {{-- ── Recent Attendance ─────────────────────────────────────────── --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-8">
            
            {{-- Toolbar --}}
            <div class="p-4 border-b border-gray-200 flex flex-wrap justify-between items-center gap-3 bg-gray-50 rounded-t-lg">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-sm font-semibold text-gray-700">Recent Attendance</span>
                    @if($recentRecords instanceof \Illuminate\Pagination\LengthAwarePaginator && $recentRecords->total() > 0)
                        <span class="text-sm text-gray-500 font-normal ml-2">
                            (Showing {{ $recentRecords->firstItem() }} to {{ $recentRecords->lastItem() }} of {{ $recentRecords->total() }} results)
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <a href="{{ route('student.attendance') }}" class="text-sm font-medium text-blue-600 hover:underline">
                        View All Attendance &rarr;
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-white bg-blue-600 border-b border-blue-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Date</th>
                            <th scope="col" class="px-6 py-3 font-bold">Subject</th>
                            <th scope="col" class="px-6 py-3 font-bold">Instructor</th>
                            <th scope="col" class="px-6 py-3 font-bold">Class Time</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRecords as $record)
                            @php
                                $schedule   = optional($record->session?->schedule);
                                $assignment = optional($schedule->instructorAssignment);
                                $subject    = optional($assignment->subject);
                                $instructor = optional($assignment->instructor);
                                $sessionDate = optional($record->session?->session_date)->format('M d, Y') ?? '—';
                                $subjectName = $subject->subject_name ?? '—';
                                $instructorName = $instructor->full_name ?? '—';
                                $classTime = ($schedule->start_time && $schedule->end_time)
                                    ? \Carbon\Carbon::parse($schedule->start_time)->format('g:i A')
                                      . ' – '
                                      . \Carbon\Carbon::parse($schedule->end_time)->format('g:i A')
                                    : '—';
                            @endphp
                            <tr class="border-b transition-colors {{ $loop->even ? 'bg-indigo-50' : 'bg-white' }} hover:bg-indigo-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $sessionDate }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $subjectName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $instructorName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $classTime }}</td>
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
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 text-sm">
                                    No attendance records yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if($recentRecords instanceof \Illuminate\Pagination\LengthAwarePaginator && $recentRecords->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $recentRecords->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
