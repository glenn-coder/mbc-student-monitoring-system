<x-app-layout>
    <div class="p-8 mx-auto max-w-4xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Subject Details</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.subjects.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to List
                </a>

            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">Subject Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Details about the subject.</p>
            </div>
            <div class="px-6 py-5">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Subject Code</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $subject->subject_code }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Subject Name</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $subject->subject_name }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="text-sm font-medium text-gray-500">Units</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $subject->units }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $subject->description ?: 'No description provided.' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>
</x-app-layout>
