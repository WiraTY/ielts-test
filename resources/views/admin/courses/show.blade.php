@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Breadcrumb -->
                <x-breadcrumb :breadcrumbs="[
                    ['label' => 'Admin', 'url' => route('admin.dashboard')],
                    ['label' => 'Courses', 'url' => route('admin.courses.index')],
                    ['label' => $course->title, 'url' => route('admin.courses.show', $course)]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">{{ $course->title }}</h1>
                    <div>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Edit Course
                        </a>
                        <a href="{{ route('admin.courses.lessons.create', $course) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                            Add Lesson
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Course Details</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($course->published_at)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Draft
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Trial Course</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if($course->is_trial)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Yes
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            No
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Order</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $course->order }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Created By</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $course->creator->name ?? 'Unknown' }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Created At</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $course->created_at->format('M d, Y H:i') }}</dd>
                            </div>
                            @if($course->published_at)
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Published At</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $course->published_at->format('M d, Y H:i') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Thumbnail</h3>
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            @if($course->thumbnail_path)
                                <img src="{{ asset('storage/' . $course->thumbnail_path) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover rounded">
                            @else
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-full h-48 flex items-center justify-center">
                                    <span class="text-gray-500">No thumbnail uploaded</span>
                                </div>
                            @endif
                        </div>
                        
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            @if($course->description)
                                <p class="text-gray-700">{{ $course->description }}</p>
                            @else
                                <p class="text-gray-500 italic">No description provided.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Lessons</h2>
                        <a href="{{ route('admin.courses.lessons.create', $course) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                            Add Lesson
                        </a>
                    </div>

                    @if($course->lessons->count() > 0)
                        <div class="grid grid-cols-1 gap-4">
                            @foreach($course->lessons->sortBy('order') as $lesson)
                                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center">
                                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-200 text-xs font-medium text-gray-700 mr-2">
                                                    {{ $lesson->order }}
                                                </span>
                                                <h3 class="text-lg font-medium text-gray-900">{{ $lesson->title }}</h3>
                                            </div>
                                            
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    @if($lesson->duration > 0)
                                                        {{ gmdate('H:i:s', $lesson->duration) }}
                                                    @else
                                                        No duration
                                                    @endif
                                                </span>
                                                
                                                @if($lesson->video_url)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        Has video
                                                    </span>
                                                @endif
                                                
                                                @if($lesson->quizzes && $lesson->quizzes->count() > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $lesson->quizzes->count() }} quiz{{ $lesson->quizzes->count() > 1 ? 'zes' : '' }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        No quiz
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            @if($lesson->content)
                                                <p class="mt-2 text-sm text-gray-600 line-clamp-2">
                                                    {{ Str::limit(strip_tags($lesson->content), 100) }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <div class="flex flex-col space-y-2 ml-4">
                                            <a href="{{ route('admin.courses.lessons.show', [$course, $lesson]) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-1 px-3 rounded">
                                                View Details
                                            </a>
                                            <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white text-sm font-medium py-1 px-3 rounded text-center flex items-center justify-center">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white text-sm font-medium py-1 px-3 rounded" onclick="return confirm('Are you sure you want to delete this lesson? This action cannot be undone.')">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No lessons</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new lesson.</p>
                            <div class="mt-4">
                                <a href="{{ route('admin.courses.lessons.create', $course) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                    </svg>
                                    Add Lesson
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection