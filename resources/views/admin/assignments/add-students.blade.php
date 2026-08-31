<x-app-layout>
    <div class="p-8 mx-auto max-w-4xl">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Assign Students to Subject: {{ $assignment->subject->subject_name }}</h2>

        <form action="{{ route('admin.assignments.store-students', $assignment) }}" method="POST">
            @csrf


            <div class="mb-6" x-data="{ 
                searchQuery: '',
                filterCourse: '', 
                filterYear: '', 
                appliedCourse: '', 
                appliedYear: '', 
                showFilters: false,
                applyFilters() {
                    this.appliedCourse = this.filterCourse;
                    this.appliedYear = this.filterYear;
                    this.showFilters = false;
                },
                resetFilters() {
                    this.filterCourse = '';
                    this.filterYear = '';
                    this.appliedCourse = '';
                    this.appliedYear = '';
                    this.showFilters = false;
                }
            }">
                <div class="flex items-center justify-between mb-4 relative">
                    <label class="block text-sm font-bold text-gray-800">Select Students:</label>
                    
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="text" x-model="searchQuery" placeholder="Search name or ID..." class="w-64 !pl-9 pr-3 py-1.5 text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <div class="relative">
                            <button type="button" @click="showFilters = !showFilters" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filters
                                <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="{'rotate-180': showFilters}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="showFilters" 
                                 @click.away="showFilters = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 z-10 mt-2 w-72 bg-white rounded-lg shadow-lg border border-gray-200"
                                 style="display: none;">
                                <div class="p-4 space-y-4">
                                    <div>
                                        <label for="filterCourse" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                                        <input type="text" id="filterCourse" x-model="filterCourse" placeholder="e.g. BSIT" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="filterYear" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                                        <input type="text" id="filterYear" x-model="filterYear" placeholder="e.g. 3rd Year" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    </div>
                                    <div class="pt-2 flex justify-end gap-2 border-t border-gray-100">
                                        <button type="button" @click="resetFilters" class="px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-md transition-colors">
                                            Reset
                                        </button>
                                        <button type="button" @click="applyFilters" class="px-3 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors">
                                            Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white border border-gray-300 rounded-md shadow-sm h-96 overflow-y-auto p-4">
                    <div class="space-y-2">
                        @foreach($students as $student)
                            <div class="flex items-center" 
                                 x-show="(!appliedCourse || '{{ strtolower($student->course->code ?? '') }}'.includes(appliedCourse.toLowerCase())) && (!appliedYear || '{{ strtolower($student->year) }}'.includes(appliedYear.toLowerCase())) && (!searchQuery || '{{ strtolower($student->last_name . ' ' . $student->first_name . ' ' . $student->student_number) }}'.includes(searchQuery.toLowerCase()))">
                                <input type="checkbox" id="student_{{ $student->id }}" name="student_ids[]" value="{{ $student->id }}" 
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    {{ in_array($student->id, $enrolledStudentIds) ? 'checked' : '' }}>
                                <label for="student_{{ $student->id }}" class="ml-3 text-sm text-gray-700">
                                    {{ $student->last_name }}, {{ $student->first_name }} ({{ $student->student_number }}) - <span class="text-gray-500">{{ $student->course->code ?? 'N/A' }} {{ $student->year }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Update Students
                </button>
                <a href="{{ route('admin.assignments.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
