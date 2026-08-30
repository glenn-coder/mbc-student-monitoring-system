<x-app-layout>
    <div class="p-8 mx-auto max-w-3xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Add Instructor</h2>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.instructors.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" autocomplete="off"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instructor_number" class="block text-sm font-medium text-gray-700">Instructor Number <span class="text-red-500">*</span></label>
                        <input type="text" name="instructor_number" id="instructor_number" value="{{ old('instructor_number') }}" autocomplete="off"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('instructor_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="major_specialization" class="block text-sm font-medium text-gray-700">Major / Specialization <span class="text-red-500">*</span></label>
                        <input type="text" name="major_specialization" id="major_specialization" value="{{ old('major_specialization') }}" autocomplete="off"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('major_specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sex" class="block text-sm font-medium text-gray-700">Sex <span class="text-red-500">*</span></label>
                        <select name="sex" id="sex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <option value="">-- Select Sex --</option>
                            <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('sex')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="off" placeholder="example@gmail.com"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Password notice --}}
                <div class="mt-6">
                    <div class="flex items-start gap-3 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3">
                        <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Password automatically generated</p>
                            <p class="mt-0.5 text-sm text-blue-700">
                                Initial password: <span class="font-mono font-semibold">INSTR-00001</span>
                                (format: <span class="font-mono">INSTR-&lt;Instructor Number&gt;</span>)
                                <br>
                                Instructors can change their password after logging in.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.instructors.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Save Instructor
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
