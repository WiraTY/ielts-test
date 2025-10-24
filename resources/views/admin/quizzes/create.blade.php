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
                    ['label' => $lesson->course->title, 'url' => route('admin.courses.show', $lesson->course)],
                    ['label' => $lesson->title, 'url' => route('admin.courses.lessons.show', [$lesson->course, $lesson])],
                    ['label' => 'Create Quiz', 'url' => route('admin.lessons.quizzes.create', $lesson)]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Create New Quiz</h1>
                    <a href="{{ route('admin.courses.lessons.edit', [$lesson->course, $lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                        Back to Lesson
                    </a>
                </div>

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.lessons.quizzes.store', $lesson) }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Quiz Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter quiz title" required>
                        </div>

                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 10) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter duration in minutes" min="1" required>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="pass_score" class="block text-sm font-medium text-gray-700 mb-2">Pass Score (%)</label>
                        <input type="number" id="pass_score" name="pass_score" value="{{ old('pass_score', 80) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter pass score" min="0" max="100" required>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.courses.lessons.edit', [$lesson->course, $lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Save Quiz
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection