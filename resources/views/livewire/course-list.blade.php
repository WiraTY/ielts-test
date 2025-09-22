<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 {{ isset($course->is_accessible) && !$course->is_accessible ? 'opacity-60' : '' }}">
                @if($course->thumbnail_path)
                    <img src="{{ asset('storage/' . $course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48 flex items-center justify-center">
                        <span class="text-gray-500">No Image</span>
                    </div>
                @endif
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-800 {{ isset($course->is_accessible) && !$course->is_accessible ? 'text-gray-500' : '' }}">
                            {{ $course->title }}
                        </h3>
                        @auth
                            @if(isset($course->is_completed) && $course->is_completed)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Completed
                                </span>
                            @endif
                        @endauth
                    </div>
                    <p class="text-gray-600 mb-4 {{ isset($course->is_accessible) && !$course->is_accessible ? 'text-gray-500' : '' }}">
                        {{ Str::limit($course->description, 100) }}
                    </p>
                    
                    <!-- Level and lesson count indicators -->
                    <div class="flex justify-between items-center mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ isset($course->is_accessible) && !$course->is_accessible ? 'bg-gray-200 text-gray-600' : 'bg-blue-100 text-blue-800' }}">
                            Level: {{ ucfirst($course->level) }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $course->lessons_count }} {{ Str::plural('Lesson', $course->lessons_count) }}
                        </span>
                    </div>
                    
                    <!-- Lock indicator for inaccessible courses -->
                    @if(isset($course->is_accessible) && !$course->is_accessible)
                        <div class="flex items-center justify-center mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Locked
                            </span>
                        </div>
                        <div class="text-center text-sm text-gray-500 mt-2">
                            Complete previous level to unlock
                        </div>
                    @else
                        <a href="{{ route('courses.show', $course->slug) }}" 
                           class="inline-block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            View Course
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>