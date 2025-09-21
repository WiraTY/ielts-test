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
                    ['label' => 'Edit Audio Listening', 'url' => route('admin.courses.lessons.audio.edit', [$course, $lesson])]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Edit Audio Listening Practice</h1>
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

                <form action="{{ route('admin.courses.lessons.audio.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="has_audio" class="flex items-center">
                            <input type="checkbox" id="has_audio" name="has_audio" value="1" {{ old('has_audio', $lesson->has_audio) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">Enable Audio Listening Practice</span>
                        </label>
                    </div>
                    
                    <div class="mb-6">
                        <label for="audio_file" class="block text-sm font-medium text-gray-700 mb-2">Audio File</label>
                        <input type="file" id="audio_file" name="audio_file" accept="audio/*" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Upload an audio file for listening practice (MP3, WAV, or WEBM, max 5MB)</p>
                        @if($lesson->audio_path)
                            <p class="mt-1 text-sm text-green-600">Current audio file: {{ basename($lesson->audio_path) }}</p>
                        @endif
                    </div>
                    
                    <div class="mb-6">
                        <label for="listening_description" class="block text-sm font-medium text-gray-700 mb-2">Listening Practice Description</label>
                        <textarea id="listening_description" name="listening_description" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter description for listening practice">{{ old('listening_description', $lesson->listening_description) }}</textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Update Audio Listening Practice
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
        .create(document.querySelector('#listening_description'), {
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
                document.querySelector('#listening_description').value = editor.getData();
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
                document.querySelector('#listening_description').value = editor.getData();
            }
        });
    }
});
</script>
@endsection