@extends('layouts.user')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :breadcrumbs="[
            ['label' => 'Courses', 'url' => route('courses.index')],
            ['label' => $attempt->quiz->lesson->course->title, 'url' => route('courses.show', $attempt->quiz->lesson->course->slug)],
            ['label' => $attempt->quiz->lesson->title, 'url' => route('lessons.show', [$attempt->quiz->lesson->course->slug, $attempt->quiz->lesson->slug])],
            ['label' => 'Quiz Result', 'url' => route('quizzes.result', $attempt)]
        ]" />

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <livewire:quiz-result :attempt="$attempt" />
            </div>
        </div>
    </div>
</div>
@endsection