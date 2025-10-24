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
                    ['label' => 'Audio & Speaking', 'url' => route('admin.courses.lessons.audio-speaking.edit', [$course, $lesson])]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Audio & Speaking Settings</h1>
                    <a href="{{ route('admin.courses.lessons.show', [$course, $lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                        Back to Lesson
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.courses.lessons.audio-speaking.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Audio Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Audio Listening Practice</h2>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="has_audio" name="has_audio" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('has_audio', $lesson->has_audio) ? 'checked' : '' }}>
                                <label for="has_audio" class="ml-2 block text-sm text-gray-900">
                                    Enable Audio Listening Practice
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="listening_description" class="block text-sm font-medium text-gray-700 mb-2">Listening Description</label>
                            <textarea id="listening_description" name="listening_description" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter listening description" rows="10">{{ old('listening_description', $lesson->listening_description) }}</textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label for="audio_file" class="block text-sm font-medium text-gray-700 mb-2">Audio File</label>
                            <input type="file" id="audio_file" name="audio_file" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   accept="audio/mp3,audio/wav,audio/ogg">
                                   
                            @if($lesson->audio_path)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Current audio file:</p>
                                    <audio controls class="mt-1 w-full">
                                        <source src="{{ asset('storage/' . $lesson->audio_path) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                    <div class="mt-2">
                                        <input type="checkbox" id="remove_audio" name="remove_audio" value="1" class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                        <label for="remove_audio" class="ml-2 block text-sm text-gray-900">
                                            Remove current audio file
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Speaking Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-4">Speaking Practice</h2>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="has_speaking_practice" name="has_speaking_practice" value="1" 
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                       {{ old('has_speaking_practice', $lesson->has_speaking_practice) ? 'checked' : '' }}>
                                <label for="has_speaking_practice" class="ml-2 block text-sm text-gray-900">
                                    Enable Speaking Practice
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="speaking_description" class="block text-sm font-medium text-gray-700 mb-2">Speaking Description</label>
                            <textarea id="speaking_description" name="speaking_description" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter speaking description" rows="10">{{ old('speaking_description', $lesson->speaking_description) }}</textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="speaking_duration" class="block text-sm font-medium text-gray-700 mb-2">Speaking Duration (seconds)</label>
                                <input type="number" id="speaking_duration" name="speaking_duration" 
                                       value="{{ old('speaking_duration', $lesson->speaking_duration) }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                       placeholder="Enter duration in seconds" min="1" max="3600">
                                <p class="mt-1 text-sm text-gray-500">Enter duration between 1 second and 1 hour (3600 seconds)</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <a href="{{ route('admin.courses.lessons.show', [$course, $lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Save Changes
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
    let listeningEditor, speakingEditor;
    
    // Initialize CKEditor for listening description
    ClassicEditor
        .create(document.querySelector('#listening_description'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'alignment', '|',
                'bulletedList', 'numberedList', '|',
                'link', 'imageInsert', 'insertTable', '|',
                'undo', 'redo'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            image: {
                toolbar: [
                    'imageTextAlternative', 'imageStyle:full', 'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells'
                ]
            },
            simpleUpload: {
                uploadUrl: '{{ route("admin.lessons.upload-image") }}',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }
        })
        .then(newEditor => {
            listeningEditor = newEditor;
            
            // Update textarea when editor content changes
            newEditor.model.document.on('change:data', () => {
                document.querySelector('#listening_description').value = newEditor.getData();
            });
        })
        .catch(error => {
            console.error(error);
        });
        
    // Initialize CKEditor for speaking description
    ClassicEditor
        .create(document.querySelector('#speaking_description'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'alignment', '|',
                'bulletedList', 'numberedList', '|',
                'link', 'imageInsert', 'insertTable', '|',
                'undo', 'redo'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
                ]
            },
            image: {
                toolbar: [
                    'imageTextAlternative', 'imageStyle:full', 'imageStyle:side'
                ]
            },
            table: {
                contentToolbar: [
                    'tableColumn', 'tableRow', 'mergeTableCells'
                ]
            },
            simpleUpload: {
                uploadUrl: '{{ route("admin.lessons.upload-image") }}',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }
        })
        .then(newEditor => {
            speakingEditor = newEditor;
            
            // Update textarea when editor content changes
            newEditor.model.document.on('change:data', () => {
                document.querySelector('#speaking_description').value = newEditor.getData();
            });
        })
        .catch(error => {
            console.error(error);
        });
        
    // Handle form submission to ensure textarea is updated
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (listeningEditor) {
                document.querySelector('#listening_description').value = listeningEditor.getData();
            }
            if (speakingEditor) {
                document.querySelector('#speaking_description').value = speakingEditor.getData();
            }
        });
    }
});
</script>
@endsection