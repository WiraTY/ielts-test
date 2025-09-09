<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $lesson->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <x-breadcrumb :breadcrumbs="[
                ['label' => 'Courses', 'url' => route('courses.index')],
                ['label' => $course->title, 'url' => route('courses.show', $course->slug)],
                ['label' => $lesson->title, 'url' => route('lessons.show', [$course->slug, $lesson->slug])]
            ]" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <livewire:lesson-viewer :course="$course" :lesson="$lesson" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>