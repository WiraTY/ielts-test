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
                    ['label' => $lesson->title, 'url' => route('admin.courses.lessons.show', [$course, $lesson])],
                    ['label' => 'Edit Speaking Practice', 'url' => route('admin.courses.lessons.speaking.edit', [$course, $lesson])]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Edit Speaking Practice</h1>
                    <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
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

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.courses.lessons.speaking.update', [$course, $lesson]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="has_speaking_practice" class="flex items-center">
                            <input type="checkbox" id="has_speaking_practice" name="has_speaking_practice" value="1" {{ old('has_speaking_practice', $lesson->has_speaking_practice) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Enable Speaking Practice</span>
                        </label>
                    </div>
                    
                    <div class="mb-6">
                        <label for="speaking_duration" class="block text-sm font-medium text-gray-700 mb-2">Speaking Duration (seconds)</label>
                        <input type="number" id="speaking_duration" name="speaking_duration" value="{{ old('speaking_duration', $lesson->speaking_duration) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter duration in seconds" min="1" max="300">
                        <p class="mt-1 text-sm text-gray-500">Set time limit for speaking practice (1-300 seconds)</p>
                    </div>
                    
                    <div class="mb-6">
                        <label for="speaking_description" class="block text-sm font-medium text-gray-700 mb-2">Speaking Practice Description</label>
                        <textarea id="speaking_description" name="speaking_description" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter description for speaking practice">{{ old('speaking_description', $lesson->speaking_description) }}</textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Update Speaking Practice
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let editor;
    
    // Initialize CKEditor
    ClassicEditor
        .create(document.querySelector('#speaking_description'), {
            toolbar: [
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'bulletedList', 'numberedList', '|',
                'link', '|',
                'undo', 'redo'
            ],
            image: {
                toolbar: [
                    'imageTextAlternative', 'imageStyle:full', 'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells'
                ]
            }
        })
        .then(newEditor => {
            editor = newEditor;
            
            // Update textarea when editor content changes
            editor.model.document.on('change:data', () => {
                document.querySelector('#speaking_description').value = editor.getData();
            });
        })
        .catch(error => {
            console.error(error);
        });
        
    // Handle form submission to ensure textarea is updated
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (editor) {
                document.querySelector('#speaking_description').value = editor.getData();
            }
        });
    }
});
</script>
@endsection