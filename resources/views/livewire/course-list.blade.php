<div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                @if($course->thumbnail_path)
                    <img src="{{ asset('storage/' . $course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48 flex items-center justify-center">
                        <span class="text-gray-500">No Image</span>
                    </div>
                @endif
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-bold text-gray-800">{{ $course->title }}</h3>
                        @auth
                            @if(isset($course->is_completed) && $course->is_completed)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Completed
                                </span>
                            @endif
                        @endauth
                    </div>
                    <p class="text-gray-600 mb-4">{{ Str::limit($course->description, 100) }}</p>
                    <a href="{{ route('courses.show', $course->slug) }}" 
                       class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                        View Course
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>