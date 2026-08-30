<x-app-layout>
    <div class="p-8 mx-auto max-w-3xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Edit Course</h2>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('admin.courses.update', $course) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="space-y-6">
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Course Code</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $course->code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Course Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $course->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-md hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition">
                        Update Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
