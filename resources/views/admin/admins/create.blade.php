<x-app-layout>
    <div class="p-8 mx-auto max-w-3xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Add Admin User</h2>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.admins.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-bold text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" autocomplete="off" class="mt-1 block w-full rounded-md border-transparent bg-slate-50 focus:border-mbc-navy focus:bg-white focus:ring-2 focus:ring-mbc-navy/20 sm:text-sm transition-colors duration-200" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-bold text-gray-700">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" autocomplete="off" class="mt-1 block w-full rounded-md border-transparent bg-slate-50 focus:border-mbc-navy focus:bg-white focus:ring-2 focus:ring-mbc-navy/20 sm:text-sm transition-colors duration-200" required>
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-bold text-gray-700">Email Address (Optional)</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="off" class="mt-1 block w-full rounded-md border-transparent bg-slate-50 focus:border-mbc-navy focus:bg-white focus:ring-2 focus:ring-mbc-navy/20 sm:text-sm transition-colors duration-200">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-bold text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-transparent bg-slate-50 focus:border-mbc-navy focus:bg-white focus:ring-2 focus:ring-mbc-navy/20 sm:text-sm transition-colors duration-200" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div x-data="{ password: '', showPassword: false }" class="md:col-span-2">
                        <label for="password" class="block text-sm font-bold text-gray-700">Password</label>
                        <div class="relative mt-1">
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="password" x-model="password" autocomplete="new-password" class="block w-full rounded-md border-transparent bg-slate-50 pr-10 focus:border-mbc-navy focus:bg-white focus:ring-2 focus:ring-mbc-navy/20 sm:text-sm transition-colors duration-200" required>
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <div class="mt-3 text-sm space-y-1">
                            <div class="flex items-center gap-2" :class="password.length >= 8 ? 'text-blue-600' : 'text-gray-500'">
                                <svg x-show="password.length < 8" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <svg x-show="password.length >= 8" style="display: none;" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>At least 8 characters</span>
                            </div>
                            <div class="flex items-center gap-2" :class="/[!@#$%^&*(),.?\':{}|<>]/.test(password) ? 'text-blue-600' : 'text-gray-500'">
                                <svg x-show="!/[!@#$%^&*(),.?\':{}|<>]/.test(password)" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <svg x-show="/[!@#$%^&*(),.?\':{}|<>]/.test(password)" style="display: none;" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>At least 1 symbol (!,?,&...)</span>
                            </div>
                            <div class="flex items-center gap-2" :class="/[A-Z]/.test(password) ? 'text-blue-600' : 'text-gray-500'">
                                <svg x-show="!/[A-Z]/.test(password)" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <svg x-show="/[A-Z]/.test(password)" style="display: none;" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>At least 1 capital letter</span>
                            </div>
                            <div class="flex items-center gap-2" :class="/[0-9]/.test(password) ? 'text-blue-600' : 'text-gray-500'">
                                <svg x-show="!/[0-9]/.test(password)" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                <svg x-show="/[0-9]/.test(password)" style="display: none;" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>At least 1 number</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.admins.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 font-medium rounded-md hover:bg-slate-200 transition-colors focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-mbc-navy text-white font-medium rounded-md hover:bg-mbc-navy/90 focus:outline-none focus:ring-2 focus:ring-mbc-navy focus:ring-offset-2 transition-all active:scale-[0.98] shadow-sm hover:shadow-md">
                        Save Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
