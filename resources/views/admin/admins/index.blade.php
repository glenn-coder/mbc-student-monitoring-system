<x-app-layout>
    <div class="p-8 mx-auto max-w-7xl">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Admin Users</h2>
                <p class="mt-1 text-sm text-gray-500">Manage administrator accounts</p>
            </div>
            <a href="{{ route('admin.admins.create') }}" class="px-3 py-1.5 text-sm bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Admin
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-lg">
                <form action="{{ route('admin.admins.index') }}" method="GET" class="flex items-center gap-2" id="perPageForm">
                    <span class="text-sm text-gray-700">Showing</span>
                    <select name="per_page" onchange="document.getElementById('perPageForm').submit()" class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 py-1 pl-2 pr-6">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                    <span class="text-sm text-gray-700">of {{ $admins->total() }} results</span>
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                </form>

                <form action="{{ route('admin.admins.index') }}" method="GET" class="flex items-center gap-3">
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
                                    <a href="{{ route('admin.admins.index') }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">Reset</a>
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
                            <th scope="col" class="px-6 py-3 font-bold">Name</th>
                            <th scope="col" class="px-6 py-3 font-bold">Username</th>
                            <th scope="col" class="px-6 py-3 font-bold">Email</th>
                            <th scope="col" class="px-6 py-3 font-bold">Status</th>
                            <th scope="col" class="px-6 py-3 font-bold text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                            <tr class="bg-white border-b hover:bg-indigo-100 {{ $loop->even ? 'bg-indigo-50' : '' }} transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $admin->name }}
                                </td>
                                <td class="px-6 py-4">{{ $admin->username }}</td>
                                <td class="px-6 py-4">{{ $admin->email ?? 'N/A' }}</td>
                                <td class="px-6 py-4">
                                    @if($admin->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'view-admin-{{ $admin->id }}')" title="View" class="inline-flex items-center justify-center w-8 h-8 text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <a href="{{ route('admin.admins.edit', $admin) }}" title="Edit" class="inline-flex items-center justify-center w-8 h-8 text-white bg-amber-500 rounded hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        @if(auth()->id() !== $admin->id)
                                        <form action="{{ route('admin.admins.destroy', $admin) }}" method="POST" class="inline-block" id="delete-admin-{{ $admin->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-delete-admin-{{ $admin->id }}')" title="Remove" class="inline-flex items-center justify-center w-8 h-8 text-white bg-red-500 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        @else
                                        <button disabled title="Cannot remove yourself" class="inline-flex items-center justify-center w-8 h-8 text-white bg-gray-400 rounded cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                        @endif

                                        <x-modal name="view-admin-{{ $admin->id }}" maxWidth="2xl" focusable>
                                            <div class="p-6 relative text-left">
                                                <div class="mb-6">
                                                    <h2 class="text-xl font-bold text-gray-900">Admin User Details</h2>
                                                </div>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div>
                                                        <span class="block text-sm font-medium text-gray-500">Name</span>
                                                        <span class="block mt-1 text-base text-gray-900">{{ $admin->name }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-sm font-medium text-gray-500">Username</span>
                                                        <span class="block mt-1 text-base text-gray-900">{{ $admin->username }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-sm font-medium text-gray-500">Email</span>
                                                        <span class="block mt-1 text-base text-gray-900">{{ $admin->email ?? 'N/A' }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-sm font-medium text-gray-500">Status</span>
                                                        <span class="block mt-1">
                                                            @if($admin->status === 'active')
                                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                                    Active
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200/60">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                                    Inactive
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-8 flex justify-end">
                                                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                                        Close
                                                    </button>
                                                </div>
                                            </div>
                                        </x-modal>

                                        <x-modal name="confirm-delete-admin-{{ $admin->id }}" maxWidth="md" focusable>
                                            <div class="p-6 relative text-left">
                                                <h2 class="text-lg font-bold text-gray-900 mb-2">
                                                    Remove Admin User
                                                </h2>
                                                <p class="text-sm text-gray-500 mb-6">
                                                    Are you sure you want to remove <span class="font-bold text-gray-900">{{ $admin->name }}</span>? This action cannot be undone.
                                                </p>
                                                <div class="flex justify-end gap-3">
                                                    <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 text-sm font-medium">
                                                        Cancel
                                                    </button>
                                                    <button type="button" onclick="document.getElementById('delete-admin-{{ $admin->id }}').submit();" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-sm font-medium">
                                                        Remove
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
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-base font-medium text-gray-900">No admin users found</p>
                                        <p class="text-sm">Try adjusting your search criteria or add a new admin.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($admins->hasPages())
                <div class="p-4 border-t border-gray-200">
                    {{ $admins->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

