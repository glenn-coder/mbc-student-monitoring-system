<x-app-layout>
    <div class="p-8 w-full">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">MBC Students Master List</h2>
                <p class="mt-1 text-sm text-gray-500">View and export the master list of enrolled students</p>
            </div>
        </div>

        <!-- Summary Metrics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Active Students -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 hover:shadow-md transition-shadow duration-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Total Active Students</p>
                    </div>
                </div>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($metrics['total_active']) }}</p>
            </div>

            <!-- By Course -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center gap-3 mb-3 border-b border-gray-100 pb-2">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Course</p>
                </div>
                <div class="space-y-2 mt-2 max-h-32 overflow-y-auto pr-1">
                    @forelse($metrics['by_course'] as $course => $count)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">{{ $course }}</span>
                        <span class="font-semibold text-gray-900 bg-gray-100 px-2 py-0.5 rounded-full text-xs">{{ number_format($count) }}</span>
                    </div>
                    @empty
                    <span class="text-gray-400 text-sm">No data</span>
                    @endforelse
                </div>
            </div>

            <!-- By Year -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center gap-3 mb-3 border-b border-gray-100 pb-2">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Year Level</p>
                </div>
                <div class="space-y-2 mt-2 max-h-32 overflow-y-auto pr-1">
                    @forelse($metrics['by_year'] as $year => $count)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">{{ $year }}</span>
                        <span class="font-semibold text-gray-900 bg-gray-100 px-2 py-0.5 rounded-full text-xs">{{ number_format($count) }}</span>
                    </div>
                    @empty
                    <span class="text-gray-400 text-sm">No data</span>
                    @endforelse
                </div>
            </div>

            <!-- By Sex -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center gap-3 mb-3 border-b border-gray-100 pb-2">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-800 uppercase tracking-wider">Sex</p>
                </div>
                <div class="space-y-2 mt-2 max-h-32 overflow-y-auto pr-1">
                    @forelse($metrics['by_sex'] as $sex => $count)
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600">{{ $sex }}</span>
                        <span class="font-semibold text-gray-900 bg-gray-100 px-2 py-0.5 rounded-full text-xs">{{ number_format($count) }}</span>
                    </div>
                    @empty
                    <span class="text-gray-400 text-sm">No data</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.reports.students') }}" method="GET" id="perPageForm" class="flex items-center gap-2">
                        <span class="text-sm text-gray-700">Showing</span>
                        <select name="per_page" onchange="document.getElementById('perPageForm').submit()" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 pl-2 pr-6">
                            <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        <span class="text-sm text-gray-700">of {{ $students->total() }} results</span>
                        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                        @if(request('course'))<input type="hidden" name="course" value="{{ request('course') }}">@endif
                        @if(request('year'))<input type="hidden" name="year" value="{{ request('year') }}">@endif
                    </form>
                </div>

                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.reports.students') }}" method="GET" class="flex items-center gap-3">
                        @if(request('per_page'))<input type="hidden" name="per_page" value="{{ request('per_page') }}">@endif

                        <div x-data="{ open: false }" class="relative z-40">
                            <button @click="open = !open" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-md font-medium text-sm text-gray-700 hover:bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filters
                                <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" style="display: none;" class="absolute z-50 mt-2 w-64 rounded-md shadow-lg bg-white border border-gray-200 left-0 origin-top-left">
                                <div class="p-4 space-y-4">
                                    <div>
                                        <label for="course" class="block text-sm font-medium text-gray-700">Course</label>
                                        <select name="course" id="course" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            <option value="">All Courses</option>
                                            @foreach(\App\Models\Course::orderBy('code')->get() as $c)
                                            <option value="{{ $c->code }}" {{ request('course') == $c->code ? 'selected' : '' }}>{{ $c->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                                        <input type="text" name="year" id="year" value="{{ request('year') }}" placeholder="e.g. 3rd Year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <a href="{{ route('admin.reports.students') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Reset</a>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <div class="relative w-48">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search..." class="block w-full pl-9 pr-3 py-1.5 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm shadow-sm">
                        </div>
                        </div>
                    </form>

                    <div x-data="{ open: false }" class="relative z-40">
                        <button @click="open = !open" @click.away="open = false" type="button" class="px-3 py-1.5 text-xs font-medium bg-gray-800 text-white rounded-md hover:bg-gray-700 flex items-center gap-1.5 shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export Data
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-gray-800/95 backdrop-blur-sm rounded-xl shadow-xl border border-gray-700/50 overflow-hidden">
                            <div class="py-1">
                                <a href="{{ route('admin.reports.students.export', 'pdf') }}?{{ http_build_query(request()->all()) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    PDF Document
                                </a>
                                <a href="{{ route('admin.reports.students.export', 'excel') }}?{{ http_build_query(request()->all()) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Excel Spreadsheet
                                </a>
                                <a href="{{ route('admin.reports.students.export', 'csv') }}?{{ http_build_query(request()->all()) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
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


            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-white bg-blue-600 border-b border-blue-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Student Number</th>
                            <th scope="col" class="px-6 py-3 font-bold">Name</th>
                            <th scope="col" class="px-6 py-3 font-bold">Email</th>
                            <th scope="col" class="px-6 py-3 font-bold">Sex</th>
                            <th scope="col" class="px-6 py-3 font-bold">Course</th>
                            <th scope="col" class="px-6 py-3 font-bold">Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr class="bg-white border-b hover:bg-indigo-100 {{ $loop->even ? 'bg-indigo-50' : '' }} transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $student->student_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name ?? '' }}</td>
                            <td class="px-6 py-4">{{ $student->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $student->sex === 'M' ? 'Male' : ($student->sex === 'F' ? 'Female' : $student->sex) }}</td>
                            <td class="px-6 py-4">{{ $student->course->code ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $student->year }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No active students found matching the criteria.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students instanceof \Illuminate\Pagination\LengthAwarePaginator && $students->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $students->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>