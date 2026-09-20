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
            <h2 class="text-2xl font-bold text-gray-900">My Class Schedule</h2>
            <p class="text-sm text-slate-500 mt-1">View your current term's weekly class schedule.</p>
        </div>

        @php
            $groupedSchedules = collect($schedules)->groupBy('day_of_week');
            $daysOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        @endphp

        @if($schedules->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                <div class="flex flex-col items-center justify-center text-gray-500">
                    <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-base font-medium text-gray-900">No classes scheduled</p>
                    <p class="text-sm mt-1">You don't have any classes assigned to your schedule yet.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($daysOrder as $day)
                    @if($groupedSchedules->has($day))
                        <div x-data="{ showModal: false }" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-full border-l-4 border-l-[#2F2FE4]">
                            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/30">
                                <h3 class="font-bold text-gray-900">{{ $day }}</h3>
                                <span class="text-xs font-medium text-gray-400">{{ $groupedSchedules[$day]->count() }} classes</span>
                            </div>
                            <div class="p-5 flex-1 flex flex-col gap-5">
                                @foreach($groupedSchedules[$day] as $index => $schedule)
                                    @php
                                        $assignment = optional($schedule->instructorAssignment);
                                        $subject    = optional($assignment->subject);
                                        $instructor = optional($assignment->instructor);
                                        $course     = optional($assignment->course);
                                        
                                        $classTime = ($schedule->start_time && $schedule->end_time)
                                            ? \Carbon\Carbon::parse($schedule->start_time)->format('g:i A')
                                              . ' - '
                                              . \Carbon\Carbon::parse($schedule->end_time)->format('g:i A')
                                            : '—';
                                    @endphp
                                    @if($index < 2)
                                        <div class="relative {{ $index == 0 && $groupedSchedules[$day]->count() > 1 ? 'pb-5 border-b border-gray-100' : '' }}">
                                            <div class="flex justify-between items-start mb-2">
                                                <h4 class="font-bold text-gray-900 leading-tight pr-4">{{ $subject->subject_name ?? '—' }}</h4>
                                                <div class="flex gap-2 shrink-0">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 tracking-wider">ACTIVE</span>
                                                    @if($course && $assignment->year)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 tracking-wider uppercase">{{ $course->code }}-{{ $assignment->year }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex flex-col gap-1.5 text-sm text-gray-500">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>{{ $classTime }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    <span>{{ $instructor->full_name ?? '—' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            
                            @if($groupedSchedules[$day]->count() > 2)
                                <div class="mt-auto border-t border-gray-100 bg-gray-50/30">
                                    <button @click="showModal = true" class="w-full text-center text-xs font-bold text-[#2F2FE4] hover:bg-indigo-50 py-3 transition-colors focus:outline-none">
                                        <span>View all {{ $groupedSchedules[$day]->count() }} classes</span>
                                    </button>
                                </div>
                                
                                <!-- Modal -->
                                <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                                    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" @click="showModal = false"></div>

                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                        <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                            <div class="absolute top-0 right-0 pt-4 pr-4">
                                                <button type="button" @click="showModal = false" class="text-gray-400 bg-white rounded-md hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2F2FE4]">
                                                    <span class="sr-only">Close</span>
                                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            
                                            <div class="sm:flex sm:items-start">
                                                <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                    <h3 class="text-lg font-bold leading-6 text-gray-900 border-b border-gray-100 pb-3 mb-4" id="modal-title">
                                                        {{ $day }}'s Schedule
                                                    </h3>
                                                    
                                                    <div class="flex flex-col gap-4 overflow-y-auto pr-2" style="max-height: 270px;">
                                                        @foreach($groupedSchedules[$day] as $schedule)
                                                            @php
                                                                $assignment = optional($schedule->instructorAssignment);
                                                                $subject    = optional($assignment->subject);
                                                                $instructor = optional($assignment->instructor);
                                                                $course     = optional($assignment->course);
                                                                
                                                                $classTime = ($schedule->start_time && $schedule->end_time)
                                                                    ? \Carbon\Carbon::parse($schedule->start_time)->format('g:i A')
                                                                      . ' - '
                                                                      . \Carbon\Carbon::parse($schedule->end_time)->format('g:i A')
                                                                    : '—';
                                                            @endphp
                                                            <div class="relative {{ !$loop->last ? 'pb-4 border-b border-gray-100' : '' }}">
                                                                <div class="flex justify-between items-start mb-2">
                                                                    <h4 class="font-bold text-gray-900 leading-tight pr-4">{{ $subject->subject_name ?? '—' }}</h4>
                                                                    <div class="flex gap-2 shrink-0">
                                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 tracking-wider">ACTIVE</span>
                                                                        @if($course && $assignment->year)
                                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 tracking-wider uppercase">{{ $course->code }}-{{ $assignment->year }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="flex flex-col gap-1.5 text-sm text-gray-500">
                                                                    <div class="flex items-center gap-2">
                                                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                        </svg>
                                                                        <span>{{ $classTime }}</span>
                                                                    </div>
                                                                    <div class="flex items-center gap-2">
                                                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                        </svg>
                                                                        <span>{{ $instructor->full_name ?? '—' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

    </div>
</x-app-layout>
