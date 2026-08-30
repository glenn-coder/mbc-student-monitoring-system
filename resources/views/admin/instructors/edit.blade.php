<x-app-layout>
    <div class="p-8 mx-auto max-w-3xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Edit Instructor</h2>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.instructors.update', $instructor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $instructor->full_name) }}" autocomplete="off"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instructor_number" class="block text-sm font-medium text-gray-700">Instructor Number <span class="text-red-500">*</span></label>
                        <input type="text" name="instructor_number" id="instructor_number" value="{{ old('instructor_number', $instructor->instructor_number) }}" autocomplete="off"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('instructor_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="major_specialization" class="block text-sm font-medium text-gray-700">Major / Specialization <span class="text-red-500">*</span></label>
                        <input type="text" name="major_specialization" id="major_specialization" value="{{ old('major_specialization', $instructor->major_specialization) }}" autocomplete="off"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('major_specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sex" class="block text-sm font-medium text-gray-700">Sex <span class="text-red-500">*</span></label>
                        <select name="sex" id="sex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <option value="">-- Select Sex --</option>
                            <option value="Male" {{ old('sex', $instructor->sex) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $instructor->sex) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('sex')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $instructor->email) }}" autocomplete="off" placeholder="example@gmail.com"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <option value="active" {{ old('status', $instructor->user->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $instructor->user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Password Reset --}}
                <div class="mt-6" x-data="{ mode: 'none' }">
                    <h3 class="text-base font-semibold text-gray-900 mb-3">Password Reset <span class="text-sm font-normal text-gray-400">(Optional)</span></h3>

                    <div class="flex flex-wrap gap-6 mb-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="password_action" value="none" x-model="mode" class="text-blue-600" checked>
                            <span class="text-sm text-gray-700">Keep current password</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="password_action" value="reset" x-model="mode" class="text-blue-600">
                            <span class="text-sm text-gray-700">Reset to default
                                <span class="font-mono text-xs text-gray-500">(INSTR-{{ $instructor->instructor_number }})</span>
                            </span>
                        </label>
                    </div>

                    <div x-show="mode === 'reset'" class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        ⚠️ The instructor's password will be reset to <span class="font-mono font-semibold">INSTR-{{ $instructor->instructor_number }}</span> upon saving.
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.instructors.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-amber-500 text-white rounded-md hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        Update Instructor
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
