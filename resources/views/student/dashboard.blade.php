<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <x-breadcrumb :breadcrumbs="[
                ['label' => 'Home', 'url' => route('dashboard')]
            ]" />

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Courses Enrolled</h3>
                                <p class="text-2xl font-semibold text-gray-900">{{ count($coursesWithProgress) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Average Score</h3>
                                <p class="text-2xl font-semibold text-gray-900">{{ $averageScore }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 p-3 rounded-full">
                                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Lessons Completed</h3>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalLessonsCompleted }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Courses Progress and Continue Learning -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Courses Progress -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Course Aktif</h3>
                        @if(count($coursesWithProgress) > 0)
                            <div class="space-y-4">
                                @foreach($coursesWithProgress as $courseData)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-md font-medium text-gray-800">
                                                <a href="{{ route('courses.show', $courseData['course']->slug) }}" class="hover:text-blue-600">
                                                    {{ $courseData['course']->title }}
                                                </a>
                                            </h4>
                                            <span class="text-sm font-medium text-gray-600">
                                                {{ $courseData['completed_lessons'] }}/{{ $courseData['total_lessons'] }} lessons
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $courseData['progress_percentage'] }}%"></div>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            {{ $courseData['progress_percentage'] }}% completed
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No courses enrolled</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by browsing our course catalog.</p>
                                <div class="mt-3">
                                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Browse Courses
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lessons in Progress -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Continue Learning</h3>
                        @if($lessonsInProgress->count() > 0)
                            <div class="space-y-4">
                                @foreach($lessonsInProgress as $lesson)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-md font-medium text-gray-800">{{ $lesson->title }}</h4>
                                                <p class="text-sm text-gray-500">{{ $lesson->course->title }}</p>
                                            </div>
                                            <a href="{{ route('lessons.show', [$lesson->course->slug, $lesson->slug]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Continue
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No lessons in progress</h3>
                                <p class="mt-1 text-sm text-gray-500">Start learning by opening a lesson from your enrolled courses.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Quiz Attempts and Student Recordings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Recent Quiz Attempts -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Hasil Tes Terbaru</h3>
                        @if($quizAttempts->count() > 0)
                            <div class="space-y-3">
                                @foreach($quizAttempts as $attempt)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-md font-medium text-gray-800">{{ $attempt->quiz->title }}</h4>
                                                <p class="text-xs text-gray-500">{{ $attempt->quiz->lesson->course->title }} - {{ $attempt->quiz->lesson->title }}</p>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="mr-2 text-xs font-medium {{ $attempt->score >= $attempt->quiz->pass_score ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $attempt->score }}%
                                                </span>
                                                <a href="{{ route('quizzes.result', $attempt) }}" class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    View Result
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No quiz attempts</h3>
                                <p class="mt-1 text-sm text-gray-500">Take a quiz to see your results here.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Student Recordings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Recordings</h3>
                        @if(isset($recentRecordings) && $recentRecordings->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentRecordings as $recording)
                                    <div class="border border-gray-200 rounded-lg p-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-md font-medium text-gray-800">{{ $recording->lesson->title }}</h4>
                                                <p class="text-xs text-gray-500">{{ $recording->lesson->course->title }}</p>
                                            </div>
                                            <div class="flex items-center">
                                                <span class="mr-2 text-xs text-gray-500">
                                                    {{ $recording->created_at->format('M d, Y') }}
                                                </span>
                                                <a href="{{ asset('storage/' . $recording->file_path) }}" target="_blank" class="inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Play
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No recordings yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Complete speaking practice exercises to see your recordings here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>