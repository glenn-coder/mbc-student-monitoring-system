<x-app-layout>
    <div class="p-8 mx-auto max-w-5xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Edit Student</h2>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Student Information --}}
                <h3 class="text-base font-semibold text-gray-900 mb-5">Student Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name <span class="text-red-500">*</span></label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $student->first_name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name <span class="text-gray-400 font-normal">(Optional)</span></label>
                        <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $student->middle_name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        @error('middle_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name <span class="text-red-500">*</span></label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $student->last_name) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="student_number" class="block text-sm font-medium text-gray-700">Student Number <span class="text-red-500">*</span></label>
                        <input type="text" name="student_number" id="student_number" value="{{ old('student_number', $student->student_number) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('student_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}" placeholder="example@gmail.com"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <option value="active" {{ old('status', $student->user->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $student->user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Academic Information --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label for="course_id" class="block text-sm font-medium text-gray-700">Course <span class="text-red-500">*</span></label>
                        <select name="course_id" id="course_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $student->course_id) == $course->id ? 'selected' : '' }}>
                                    {{ $course->code }} - {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700">Year <span class="text-red-500">*</span></label>
                        <select name="year" id="year"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <option value="">Select Year</option>
                            <option value="1st Year" {{ old('year', $student->year) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                            <option value="2nd Year" {{ old('year', $student->year) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                            <option value="3rd Year" {{ old('year', $student->year) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                            <option value="4th Year" {{ old('year', $student->year) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        </select>
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sex" class="block text-sm font-medium text-gray-700">Sex <span class="text-red-500">*</span></label>
                        <select name="sex" id="sex"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                            <option value="">Select Sex</option>
                            <option value="M" {{ old('sex', $student->sex) == 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ old('sex', $student->sex) == 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                        @error('sex')
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
                                <span class="font-mono text-xs text-gray-500">({{ optional($student->course)->code ?? 'COURSE' }}-{{ $student->student_number }})</span>
                            </span>
                        </label>
                    </div>

                    <div x-show="mode === 'reset'" class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                        ⚠️ The student's password will be reset to <span class="font-mono font-semibold">{{ optional($student->course)->code ?? 'COURSE' }}-{{ $student->student_number }}</span> upon saving.
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.students.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 bg-amber-500 text-white rounded-md hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>