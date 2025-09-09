<div>
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $course->title }}</h1>
            <p class="text-gray-600 mb-6">{{ $course->description }}</p>
            
            <div class="flex items-center justify-between">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $course->lessons->count() }} Lessons
                    </span>
                </div>
                @auth
                <button class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                    Enroll Now
                </button>
                @else
                <a href="{{ route('login') }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                    Login to Enroll
                </a>
                @endauth
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Lessons</h2>
            
            <div class="space-y-4">
                @foreach($lessons as $lesson)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <h3 class="text-lg font-medium text-gray-800">{{ $lesson->title }}</h3>
                                @if(isset($progress[$lesson->id]) && $progress[$lesson->id]->status === 'completed')
                                    <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                @endif
                                @if($lesson->duration > 0)
                                    <p class="ml-2 text-sm text-gray-500">{{ gmdate('H:i:s', $lesson->duration) }}</p>
                                @endif
                            </div>
                            <a href="{{ route('lessons.show', [$course->slug, $lesson->slug]) }}" 
                               class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                                Start Lesson
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
