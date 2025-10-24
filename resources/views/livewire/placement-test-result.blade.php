<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="p-6">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Test Results</h2>
            <p class="mt-1 text-gray-600">{{ $attempt->placementTest->title }}</p>
        </div>
        
        <!-- Score summary -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <div class="text-center">
                <div class="text-4xl font-bold text-gray-900">{{ $attempt->score }}</div>
                <div class="text-gray-600">Your Score</div>
                <div class="mt-2 text-lg font-medium text-gray-900">
                    {{ number_format($scorePercentage, 1) }}% Correct
                </div>
                <div class="text-gray-600">
                    ({{ $attempt->score }} out of {{ $totalPossibleScore }} points)
                </div>
            </div>
        </div>
        
        <!-- Level assignment -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="text-center">
                <h3 class="text-lg font-medium text-blue-800">Your English Level</h3>
                <div class="mt-2 text-2xl font-bold text-blue-900 uppercase">
                    {{ $attempt->assigned_level ?? 'Not Assigned' }}
                </div>
                <p class="mt-2 text-blue-700">
                    Based on your test performance, you have been assigned to the 
                    <span class="font-semibold">{{ $attempt->assigned_level ?? 'starter' }}</span> level.
                </p>
            </div>
        </div>
        
        <!-- Next steps -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <h3 class="font-medium text-green-800 mb-2">Next Steps</h3>
            <ul class="text-green-700 list-disc pl-5 space-y-1">
                <li>You now have access to {{ $attempt->assigned_level ?? 'starter' }} level courses</li>
                <li>You can browse and enroll in courses from your dashboard</li>
                <li>Start with the recommended courses for your level</li>
            </ul>
        </div>
        
        <!-- Action buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-3">
            <a href="{{ route('dashboard') }}" 
               class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 text-center">
                Go to Dashboard
            </a>
            <a href="{{ route('courses.index') }}" 
               class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 text-center">
                Browse Courses
            </a>
        </div>
    </div>
</div>