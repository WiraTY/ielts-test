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
                    ['label' => $course->title, 'url' => route('admin.courses.show', $course)],
                    ['label' => $lesson->title, 'url' => route('admin.courses.lessons.show', [$course, $lesson])]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">{{ $lesson->title }}</h1>
                    <div>
                        <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Edit Lesson
                        </a>
                        <a href="{{ route('admin.courses.show', $course) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                            Back to Course
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Order</h3>
                        <p class="text-2xl font-bold text-gray-600">{{ $lesson->order }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Status</h3>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Published
                        </span>
                    </div>
                </div>

                @if($lesson->video_url)
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Video</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <a href="{{ $lesson->video_url }}" target="_blank" class="text-blue-500 hover:text-blue-700">{{ $lesson->video_url }}</a>
                    </div>
                </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Content</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        @if($lesson->content)
                            <div class="prose max-w-none">
                                {!! $lesson->content !!}
                            </div>
                        @else
                            <p class="text-gray-400 italic">No content provided</p>
                        @endif
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Quiz</h2>
                    @if($lesson->quiz)
                        <a href="{{ route('admin.lessons.quizzes.edit', [$lesson, $lesson->quiz]) }}" class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded">
                            Edit Quiz
                        </a>
                    @else
                        <a href="{{ route('admin.lessons.quizzes.create', $lesson) }}" class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded">
                            Add Quiz
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Duration
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pass Score
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if($lesson->quiz)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $lesson->quiz->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $lesson->quiz->duration_minutes }} minutes</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $lesson->quiz->pass_score }}%</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Published
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.lessons.quizzes.edit', [$lesson, $lesson->quiz]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <a href="{{ route('admin.lessons.quizzes.show', [$lesson, $lesson->quiz]) }}" class="ml-4 text-indigo-600 hover:text-indigo-900">View</a>
                                        <form action="{{ route('admin.lessons.quizzes.destroy', [$lesson, $lesson->quiz]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ml-4 text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this quiz?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No quiz found for this lesson.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection