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
                    ['label' => $quiz->lesson->course->title, 'url' => route('admin.courses.show', $quiz->lesson->course)],
                    ['label' => $quiz->lesson->title, 'url' => route('admin.courses.lessons.show', [$quiz->lesson->course, $quiz->lesson])],
                    ['label' => $quiz->title, 'url' => route('admin.lessons.quizzes.show', [$quiz->lesson, $quiz])],
                    ['label' => 'Question Details', 'url' => route('admin.quizzes.questions.show', [$quiz, $question])]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Question Details</h1>
                    <div>
                        <a href="{{ route('admin.quizzes.questions.edit', [$quiz, $question]) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Edit Question
                        </a>
                        <a href="{{ route('admin.courses.lessons.edit', [$quiz->lesson->course, $quiz->lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                            Back to Lesson
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Question Type</h3>
                        <p class="text-2xl font-bold text-gray-600">
                            @if($question->type === 'mcq')
                                Multiple Choice
                            @elseif($question->type === 'multi')
                                Multi Select
                            @elseif($question->type === 'essay')
                                Essay
                            @else
                                {{ ucfirst($question->type) }}
                            @endif
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Score</h3>
                        <p class="text-2xl font-bold text-gray-600">{{ $question->score }} point(s)</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">Status</h3>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Published
                        </span>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Question Text</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="text-gray-600">{!! $question->question_text !!}</div>
                    </div>
                </div>

                @if($question->type === 'mcq' || $question->type === 'multi')
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Options</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        <div class="space-y-2">
                            @if($question->options && is_array($question->options))
                                @foreach($question->options as $index => $option)
                                    @php
                                        $isUserAnswer = false;
                                        $isCorrectAnswer = false;
                                        
                                        // Check if this is correct answer
                                        if ($question->type === 'mcq') {
                                            $isCorrectAnswer = isset($question->answer_key['correct']) && $question->answer_key['correct'] == $index;
                                        } elseif ($question->type === 'multi') {
                                            $correctAnswers = $question->answer_key['correct'] ?? [];
                                            $isCorrectAnswer = is_array($correctAnswers) && in_array($index, $correctAnswers);
                                        }
                                    @endphp
                                    
                                    <div class="flex items-center">
                                        <span class="{{ $isCorrectAnswer ? 'text-green-600 font-bold' : '' }}">
                                            {{ chr(65 + $index) }}. {{ $option }}
                                            @if($isCorrectAnswer)
                                                <span class="ml-2 text-green-600 font-bold">[Correct]</span>
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-500 italic">No options available</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($question->type === 'essay')
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-2">Answer Key</h3>
                    <div class="bg-gray-50 rounded-lg p-6">
                        @if($question->answer_key && isset($question->answer_key['sample']))
                            <p class="text-gray-600">{{ $question->answer_key['sample'] }}</p>
                        @else
                            <p class="text-gray-500 italic">No sample answer provided</p>
                        @endif
                    </div>
                </div>
                @endif

                <div class="flex justify-end">
                    <form action="{{ route('admin.quizzes.questions.destroy', [$quiz, $question]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded" onclick="return confirm('Are you sure you want to delete this question?')">
                            Delete Question
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection