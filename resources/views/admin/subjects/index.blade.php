<x-app-layout>
    <div class="p-8 mx-auto max-w-7xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Subject List</h2>
            <a href="{{ route('admin.subjects.create') }}" class="px-3 py-1.5 text-sm bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Subject
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                <form action="{{ route('admin.subjects.index') }}" method="GET" class="flex items-center gap-2" id="perPageForm">
                    <span class="text-sm text-gray-700">Showing</span>
                    <select name="per_page" onchange="document.getElementById('perPageForm').submit()" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 pl-2 pr-6">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="text-sm text-gray-700">of {{ $subjects->total() }} results</span>
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                </form>

                <form action="{{ route('admin.subjects.index') }}" method="GET" class="flex items-center gap-3" autocomplete="off">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button" class="inline-flex items-center gap-1.5 px-2 py-1 bg-white rounded-md font-medium text-xs text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">All Statuses</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <a href="{{ route('admin.subjects.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Reset</a>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Filter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 border-l pl-3 ml-1">
                        <label for="search" class="text-sm text-gray-700 font-bold">Search:</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 px-3 w-48 shadow-sm">
                    </div>
                    @if(request('per_page'))
                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="text-xs text-gray-700 bg-gray-100 border-b border-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-bold">Subject Code</th>
                            <th scope="col" class="px-6 py-3 font-bold">Subject Name</th>
                            <th scope="col" class="px-6 py-3 font-bold">Units</th>
                            <th scope="col" class="px-6 py-3 font-bold">Status</th>
                            <th scope="col" class="px-6 py-3 font-bold">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            <tr class="bg-white border-b hover:bg-gray-50 {{ $loop->even ? 'bg-gray-50' : '' }}">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $subject->subject_code }}
                                </td>
                                <td class="px-6 py-4">{{ $subject->subject_name }}</td>
                                <td class="px-6 py-4">{{ $subject->units }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $subject->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($subject->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-subject-{{ $subject->id }}')" title="View" class="inline-flex items-center justify-center w-8 h-8 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <a href="{{ route('admin.subjects.edit', $subject) }}" title="Edit" class="inline-flex items-center justify-center w-8 h-8 text-white bg-amber-500 rounded hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-subject-deletion-{{ $subject->id }}')" title="Remove" class="inline-flex items-center justify-center w-8 h-8 text-white bg-red-500 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>

                                        <x-modal name="confirm-subject-deletion-{{ $subject->id }}" maxWidth="md" contentClasses="bg-red-950/70 backdrop-blur-xl border border-red-500/30" focusable>
                                            <div class="p-6 relative text-left">
                                                <button x-on:click="$dispatch('close')" class="absolute top-4 right-4 text-red-200/50 hover:text-red-100 transition">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>

                                                <form method="post" action="{{ route('admin.subjects.destroy', $subject) }}">
                                                    @csrf
                                                    @method('delete')

                                                    <div class="flex gap-4 mb-2">
                                                        <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full bg-red-500/20 border border-red-500/30 text-red-400 shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                        </div>

                                                        <div class="pt-1">
                                                            <h2 class="text-xl font-semibold text-white">
                                                                Remove subject
                                                            </h2>
                                                            <p class="mt-2 text-sm text-red-100/70 leading-relaxed">
                                                                Are you sure you want to remove this subject? This action cannot be undone.
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="mt-6 flex justify-end gap-3">
                                                        <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2.5 rounded-lg text-sm font-medium text-white bg-white/5 hover:bg-white/10 border border-white/10 transition">
                                                            Cancel
                                                        </button>

                                                        <button type="submit" class="px-4 py-2.5 rounded-lg text-sm font-medium text-white bg-red-600/80 hover:bg-red-500 border border-red-500/50 transition shadow-[0_0_15px_rgba(220,38,38,0.3)]">
                                                            Remove
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </x-modal>

                                        <x-modal name="view-subject-{{ $subject->id }}" maxWidth="2xl" focusable>
                                            <div class="p-6 relative text-left">
                                                <div class="mb-6">
                                                    <h2 class="text-xl font-bold text-gray-900">Subject Details</h2>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div>
                                                        <span class="block text-sm font-medium text-gray-500">Subject Code</span>
                                                        <span class="block mt-1 text-base text-gray-900">{{ $subject->subject_code }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-sm font-medium text-gray-500">Subject Name</span>
                                                        <span class="block mt-1 text-base text-gray-900">{{ $subject->subject_name }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-sm font-medium text-gray-500">Units</span>
                                                        <span class="block mt-1 text-base text-gray-900">{{ $subject->units }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-sm font-medium text-gray-500">Status</span>
                                                        <span class="block mt-1">
                                                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $subject->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                                                {{ ucfirst($subject->status) }}
                                                            </span>
                                                        </span>
                                                    </div>
                                                    <div class="md:col-span-2">
                                                        <span class="block text-sm font-medium text-gray-500">Description</span>
                                                        <span class="block mt-1 text-base text-gray-900 whitespace-pre-wrap">{{ $subject->description ?: 'No description provided.' }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-8 flex justify-end">
                                                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </x-modal>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No subjects found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($subjects->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $subjects->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

