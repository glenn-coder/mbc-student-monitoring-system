<x-app-layout>
    <div class="p-8 w-full">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Instructor Workload Report</h2>
                <p class="mt-1 text-sm text-gray-500">View and export faculty teaching loads and assignments</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.reports.instructors') }}" method="GET" id="perPageForm" class="flex items-center gap-2">
                        <span class="text-sm text-gray-700">Showing</span>
                        <select name="per_page" onchange="document.getElementById('perPageForm').submit()" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 pl-2 pr-6">
                            <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                        <span class="text-sm text-gray-700">of {{ $instructors->total() }} results</span>
                        @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                        @if(request('course'))<input type="hidden" name="course" value="{{ request('course') }}">@endif
                        @if(request('specialization'))<input type="hidden" name="specialization" value="{{ request('specialization') }}">@endif
                    </form>
                </div>

                <div class="flex items-center gap-3">
                    <form action="{{ route('admin.reports.instructors') }}" method="GET" class="flex items-center gap-3">
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
                                        <label for="specialization" class="block text-sm font-medium text-gray-700">Specialization</label>
                                        <input type="text" name="specialization" id="specialization" value="{{ request('specialization') }}" placeholder="e.g. Web Development" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <a href="{{ route('admin.reports.instructors') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Reset</a>
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
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Export Data
                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        
                        <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-48 bg-gray-800/95 backdrop-blur-sm rounded-xl shadow-xl border border-gray-700/50 overflow-hidden">
                            <div class="py-1">
                                <a href="{{ route('admin.reports.instructors.export', 'pdf') }}?{{ http_build_query(request()->all()) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    PDF Document
                                </a>
                                <a href="{{ route('admin.reports.instructors.export', 'excel') }}?{{ http_build_query(request()->all()) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Excel Spreadsheet
                                </a>
                                <a href="{{ route('admin.reports.instructors.export', 'csv') }}?{{ http_build_query(request()->all()) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition-colors">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                                    CSV Format
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 flex justify-end">
                <div class="text-sm text-gray-600">
                    Total Active Instructors: <span class="font-bold">@if(isset($instructors) && $instructors instanceof \Illuminate\Pagination\LengthAwarePaginator) {{ $instructors->total() }} @else {{ $instructors->count() }} @endif</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-white bg-blue-600 border-b border-blue-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Instructor Number</th>
                            <th scope="col" class="px-6 py-3 font-bold">Name</th>
                            <th scope="col" class="px-6 py-3 font-bold">Email</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Assigned Subjects</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Total Students</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($instructors as $instructor)
                            <tr class="bg-white border-b hover:bg-indigo-100 {{ $loop->even ? 'bg-indigo-50' : '' }} transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $instructor->instructor_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>{{ $instructor->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $instructor->major_specialization }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $instructor->email ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center text-blue-600 font-bold">
                                    {{ $instructor->assignments->count() }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold">
                                    {{ $instructor->assignments->sum(function($a) { return $a->students->count(); }) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    No active instructors found matching the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($instructors instanceof \Illuminate\Pagination\LengthAwarePaginator && $instructors->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $instructors->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
