<x-app-layout>
    <div class="py-4 sm:py-8 px-4 sm:px-6 lg:px-10 max-w-7xl mx-auto space-y-4 sm:space-y-6">

        {{-- Back to Dashboard --}}
        <div>
            <a href="{{ route('student.dashboard') }}"
               class="inline-flex items-center gap-1 text-sm text-[#2F2FE4] hover:underline font-medium">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        {{-- Header --}}
        <div class="w-full mb-2">
            <h2 class="text-2xl font-bold text-gray-900">All Attendance Records</h2>
            <p class="text-sm text-slate-500 mt-1">Showing completed sessions only, newest first.</p>
        </div>

        {{-- Main Table Card --}}
        <div class="bg-white rounded-lg shadow-sm border border-slate-200">
            
            {{-- Toolbar --}}
            <div class="p-3 sm:p-4 border-b border-gray-200 flex justify-between items-center gap-2 sm:gap-3 bg-gray-50 rounded-t-lg">
                <div class="flex items-center shrink-0">
                    <form action="{{ route('student.attendance') }}" method="GET" id="perPageForm" class="flex items-center gap-2">
                        @if(request('date_from')) <input type="hidden" name="date_from" value="{{ request('date_from') }}"> @endif
                        @if(request('date_to')) <input type="hidden" name="date_to" value="{{ request('date_to') }}"> @endif
                        @if(request('subject_id')) <input type="hidden" name="subject_id" value="{{ request('subject_id') }}"> @endif
                        <span class="text-sm text-gray-700 whitespace-nowrap">Showing</span>
                        <select name="per_page" onchange="document.getElementById('perPageForm').submit()"
                            class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 pl-2 pr-6">
                            <option value="5"   {{ request('per_page', 5) == 5   ? 'selected' : '' }}>5</option>
                            <option value="10"  {{ request('per_page') == 10  ? 'selected' : '' }}>10</option>
                            <option value="25"  {{ request('per_page') == 25  ? 'selected' : '' }}>25</option>
                            <option value="50"  {{ request('per_page') == 50  ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-sm text-gray-700 whitespace-nowrap">of {{ $records->total() }} <span class="hidden sm:inline">results</span></span>
                    </form>
                </div>

                <div class="flex items-center gap-3 shrink-0 ml-auto">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 hover:text-gray-900 font-medium hover:bg-gray-50 shadow-sm transition-colors">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filters
                            <svg class="w-4 h-4 text-slate-500 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <!-- Dropdown Panel -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-50 w-[calc(100vw-2rem)] max-w-sm sm:w-72 mt-2 origin-top-right bg-white border border-gray-200 rounded-lg shadow-lg" style="display: none;">
                            <form action="{{ route('student.attendance') }}" method="GET" class="p-4 space-y-4 text-left">
                                @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                                
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
                                    <a href="{{ route('student.attendance', ['per_page' => request('per_page')]) }}" class="flex-1 px-3 py-1.5 text-center text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 transition-colors">Reset</a>
                                    <button type="submit" class="flex-1 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-700 transition-colors">Apply</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-white bg-blue-600 border-b border-blue-700 uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-semibold">Date</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Subject</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Instructor</th>
                            <th scope="col" class="px-6 py-4 font-semibold">Class Time</th>
                            <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $record)
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
                            <tr class="border-b transition-colors {{ $loop->even ? 'bg-indigo-50/50' : 'bg-white' }} hover:bg-indigo-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $sessionDate }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $subjectName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-800">{{ $instructorName }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $classTime }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($record->status === 'present')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                            Present
                                        </span>
                                    @elseif($record->status === 'late')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 border border-orange-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 mr-1.5"></span>
                                            Late
                                        </span>
                                    @elseif($record->status === 'absent')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>
                                            Absent
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span class="text-gray-500 font-medium">No attendance records found.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $records->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
