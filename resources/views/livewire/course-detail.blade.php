<div>
    <!-- Course Header with Progress -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $course->title }}</h1>
                <!-- Level indicator -->
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    Level: {{ ucfirst($course->level) }}
                </span>
            </div>
            <p class="text-gray-600 mb-4">{{ $course->description }}</p>
            
            <!-- Course Details and Progress -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $course->lessons->count() }} Lessons
                    </span>
                    
                    @auth
                        @if(isset($courseProgress) && $courseProgress['total'] > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ $courseProgress['completed'] }}/{{ $courseProgress['total'] }} Completed
                            </span>
                            
                            @if($courseProgress['is_completed'])
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Completed
                                </span>
                            @endif
                        @endif
                    @endauth
                </div>
                
                @auth
                    @if(isset($courseProgress) && $courseProgress['total'] > 0)
                    <div class="w-full sm:w-48 flex-shrink-0">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-700">Progress</span>
                            <span class="text-xs font-bold text-blue-600">{{ $courseProgress['percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $courseProgress['percentage'] }}%"></div>
                        </div>
                    </div>
                    @endif
                @endauth
            </div>
            
            <!-- Lock indicator for inaccessible courses -->
            @if(isset($isCourseAccessible) && !$isCourseAccessible)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Course Locked</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>This course is locked. Complete previous courses or take the placement test to unlock this content.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    @auth
                        @if(isset($courseProgress) && !$courseProgress['is_completed'] && $courseProgress['total'] > 0)
                            <span class="text-sm text-gray-600">
                                Continue from lesson {{ $courseProgress['completed'] + 1 }}
                            </span>
                        @endif
                    @endauth
                </div>
                
                <div class="flex-shrink-0">
                    @auth
                        @if(isset($isCourseAccessible) && !$isCourseAccessible)
                            <button disabled class="bg-gray-300 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed">
                                Enroll Now (Locked)
                            </button>
                        @else
                            <button wire:click="enroll" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg">
                                Enroll Now
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg">
                            Login to Enroll
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Lessons Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Lessons</h2>
            
            @if($lessons->count() > 0)
                <div class="space-y-3">
                    @foreach($lessons as $lesson)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                        <span class="text-blue-800 text-sm font-bold">{{ $lesson->order }}</span>
                                    </div>
                                    <div class="ml-2">
                                        <h3 class="font-medium text-gray-800">{{ $lesson->title }}</h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            @if($lesson->duration > 0)
                                                <span class="text-xs text-gray-500">
                                                    {{ gmdate('H:i:s', $lesson->duration) }}
                                                </span>
                                            @endif
                                            
                                            @if($lesson->video_url)
                                                <span class="text-xs text-gray-500">• Video</span>
                                            @endif
                                            
                                            @if(isset($progress[$lesson->id]) && $progress[$lesson->id]->status === 'completed')
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            @elseif(isset($progress[$lesson->id]) && $progress[$lesson->id]->status === 'in_progress')
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-800">
                                                    In Progress
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                @if(isset($isCourseAccessible) && !$isCourseAccessible)
                                    <span class="ml-4 bg-gray-300 text-gray-500 text-sm font-medium py-1.5 px-4 rounded-lg cursor-not-allowed">
                                        Locked
                                    </span>
                                @else
                                    <a href="{{ route('lessons.show', [$course->slug, $lesson->slug]) }}" 
                                       class="ml-4 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-1.5 px-4 rounded-lg">
                                        @if(isset($progress[$lesson->id]) && $progress[$lesson->id]->status === 'completed')
                                            Review
                                        @else
                                            Start
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No lessons available</h3>
                    <p class="mt-1 text-sm text-gray-500">This course doesn't have any lessons yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
