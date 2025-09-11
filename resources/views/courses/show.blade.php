@extends('layouts.user')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <x-breadcrumb :breadcrumbs="[
            ['label' => 'Courses', 'url' => route('courses.index')],
            ['label' => $course->title, 'url' => route('courses.show', $course->slug)]
        ]" />

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <livewire:course-detail :course="$course" />
            </div>
        </div>
    </div>
</div>
@endsection