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
                    ['label' => $quiz->title, 'url' => route('admin.lessons.quizzes.show', [$lesson, $quiz])]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">{{ $quiz->title }}</h1>
                    <div>
                        <a href="{{ route('admin.lessons.quizzes.edit', [$lesson, $quiz]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Edit Quiz
                        </a>
                        <a href="{{ route('admin.courses.lessons.edit', [$lesson->course, $lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                            Back to Lesson
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Duration</h3>
                        <p class="text-2xl font-bold text-gray-600">{{ $quiz->duration_minutes }} minutes</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Pass Score</h3>
                        <p class="text-2xl font-bold text-gray-600">{{ $quiz->pass_score }}%</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Status</h3>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Published
                        </span>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Questions</h2>
                    <a href="{{ route('admin.quizzes.questions.create', $quiz) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                        Add New Question
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Question
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Score
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($quiz->questions as $question)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{!! Str::limit($question->question_text, 50) !!}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            @if($question->type === 'mcq')
                                                Multiple Choice
                                            @elseif($question->type === 'multi')
                                                Multi Select
                                            @elseif($question->type === 'essay')
                                                Essay
                                            @else
                                                {{ ucfirst($question->type) }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $question->score }} point(s)</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <a href="{{ route('admin.quizzes.questions.show', [$quiz, $question]) }}" class="ml-4 text-indigo-600 hover:text-indigo-900">View</a>
                                        <form action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ml-4 text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this question?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No questions found for this quiz.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection