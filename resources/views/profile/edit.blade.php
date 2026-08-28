<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Back to Dashboard Button -->
            <div class="flex items-center mb-4">
                @php
                    $dashboardRoute = match(auth()->user()->role) {
                        'admin' => route('admin.dashboard'),
                        'instructor' => route('instructor.dashboard'),
                        'student' => route('student.dashboard'),
                        default => url('/'),
                    };
                @endphp
                <a href="{{ $dashboardRoute }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Dashboard
                </a>
            </div>

            <!-- Account Information Display -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section class="max-w-4xl">
                    <header class="mb-8">
                        <h2 class="text-lg font-medium text-gray-900 border-b border-gray-100 pb-4">
                            {{ __('Account Information') }}
                        </h2>
                    </header>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-y-10 gap-x-8">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Username</p>
                            <p class="text-[15px] font-semibold text-gray-900">{{ $user->username ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Name</p>
                            <p class="text-[15px] font-semibold text-gray-900">{{ $user->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Email Address</p>
                            <p class="text-[15px] font-semibold text-gray-900">{{ $user->email }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">User Role</p>
                            <p class="text-[15px] font-semibold text-gray-900 capitalize">{{ $user->role ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Status</p>
                            <div class="mt-0.5">
                                @if(($user->status ?? 'active') === 'active')
                                    <span class="inline-flex items-center text-[15px] font-semibold text-gray-900 capitalize">
                                        {{ $user->status ?? 'Active' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-[15px] font-semibold text-red-600 capitalize">
                                        {{ $user->status }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
