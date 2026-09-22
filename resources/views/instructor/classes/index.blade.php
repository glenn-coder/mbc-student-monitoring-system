<x-app-layout>
    <div class="p-8 w-full" x-data="{ showInfoModal: null }">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">My Classes</h2>
        </div>

        <!-- Class Assigned Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">

            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <form action="{{ route('instructor.classes.index') }}" method="GET" class="flex items-center gap-2" id="perPageForm">
                    <span class="text-sm text-gray-700">Showing</span>
                    <select name="per_page" onchange="document.getElementById('perPageForm').submit()" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 pl-2 pr-6">
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="text-sm text-gray-700">of {{ $assignments->total() }} results</span>

                    @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('subject_id'))
                    <input type="hidden" name="subject_id" value="{{ request('subject_id') }}">
                    @endif
                    @if(request('year'))
                    <input type="hidden" name="year" value="{{ request('year') }}">
                    @endif
                </form>

                <form action="{{ route('instructor.classes.index') }}" method="GET" class="flex items-center gap-3">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button" class="inline-flex items-center gap-1.5 px-2 py-1 bg-white rounded-md font-medium text-xs text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 border border-gray-300 shadow-sm">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filters
                            <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" style="display: none;" class="absolute z-50 mt-2 w-72 rounded-md shadow-lg bg-white border border-gray-200 left-auto right-0 md:left-0 md:right-auto origin-top-right md:origin-top-left">
                            <div class="p-4 space-y-4">
                                <div>
                                    <label for="filter_subject" class="block text-sm font-medium text-gray-700">Subject</label>
                                    <select name="subject_id" id="filter_subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">All Subjects</option>
                                        @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->subject_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="filter_year" class="block text-sm font-medium text-gray-700">Year</label>
                                    <select name="year" id="filter_year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">All Years</option>
                                        <option value="1st Year" {{ request('year') == '1st Year' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd Year" {{ request('year') == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd Year" {{ request('year') == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th Year" {{ request('year') == '4th Year' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                </div>
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <a href="{{ route('instructor.classes.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Reset</a>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-l pl-3 ml-1">
                        <div class="relative w-48">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search..." class="block w-full pl-9 pr-3 py-1.5 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm">
                        </div>
                    </div>
                    @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-white bg-blue-600 border-b border-blue-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Subject</th>
                            <th scope="col" class="px-6 py-3 font-bold">Course</th>
                            <th scope="col" class="px-6 py-3 font-bold">Year/Semester</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Students</th>
                            <th scope="col" class="px-6 py-3 font-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $assignment)
                        <tr class="bg-white border-b hover:bg-indigo-100 {{ $loop->even ? 'bg-indigo-50' : '' }} transition-colors">
                            <td class="px-6 py-4 cursor-pointer" @click="showInfoModal = {{ $assignment->id }}">
                                <div class="font-medium text-blue-600 hover:underline">{{ $assignment->subject->subject_code }}</div>
                                <div class="text-xs text-gray-500">{{ $assignment->subject->subject_name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                {{ $assignment->course->code ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $assignment->year }} / {{ $assignment->semester }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $assignment->students->count() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('instructor.classes.show', $assignment) }}" title="View Class Students" class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-white bg-[#2F2FE4] rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-[#2F2FE4] focus:ring-offset-2 transition-colors">
                                        View Class
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No classes assigned to you yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($assignments->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $assignments->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

        <!-- Modals for each class -->
        @foreach($assignments as $assignment)
        <div @keydown.escape.window="showInfoModal = null"
            x-show="showInfoModal === {{ $assignment->id }}"
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title-{{ $assignment->id }}"
            role="dialog"
            aria-modal="true"
            style="display: none;">

            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showInfoModal === {{ $assignment->id }}"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75"
                    @click="showInfoModal = null"
                    aria-hidden="true"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div x-show="showInfoModal === {{ $assignment->id }}"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-md w-full">

                    <div class="bg-blue-600 px-4 py-3 flex justify-between items-center">
                        <h3 class="text-white font-bold" id="modal-title-{{ $assignment->id }}">Class Information</h3>
                        <button @click="showInfoModal = null" class="text-blue-100 hover:text-white transition-colors focus:outline-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-0">
                        <table class="w-full text-sm text-left">
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">Section</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ $assignment->course->code ?? 'N/A' }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">Subject</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ $assignment->subject->subject_name }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">Code</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ $assignment->subject->subject_code }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">School Year</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ now()->format('Y') }}-{{ now()->addYear()->format('Y') }}</td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">Semester</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ $assignment->semester }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 px-4 font-semibold text-gray-700 bg-white">Students</th>
                                <td class="py-3 px-4 text-blue-600 text-right bg-white">{{ $assignment->students->count() }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-lg">
                        <button type="button" @click="showInfoModal = null" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>