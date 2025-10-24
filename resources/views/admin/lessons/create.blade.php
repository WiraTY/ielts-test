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
                    ['label' => 'Create Lesson', 'url' => route('admin.courses.lessons.create', $course)]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Create New Lesson</h1>
                    <a href="{{ route('admin.courses.show', $course) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                        Back to Course
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

                <form action="{{ route('admin.courses.lessons.store', $course) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Lesson Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter lesson title" required>
                    </div>

                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                        <textarea id="content" name="content" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter lesson content" rows="15">{{ old('content') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter lesson order" min="0">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                        <input type="url" id="video_url" name="video_url" value="{{ old('video_url') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter video URL">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="audio_file" class="block text-sm font-medium text-gray-700 mb-2">Audio File</label>
                            <input type="file" id="audio_file" name="audio_file" accept="audio/*" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Upload an audio file for listening practice (MP3 or WAV, max 5MB)</p>
                        </div>

                        <div>
                            <label for="speaking_duration" class="block text-sm font-medium text-gray-700 mb-2">Speaking Duration (seconds)</label>
                            <input type="number" id="speaking_duration" name="speaking_duration" value="{{ old('speaking_duration') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter duration in seconds" min="1" max="300">
                            <p class="mt-1 text-sm text-gray-500">Set time limit for speaking practice (1-300 seconds)</p>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.courses.show', $course) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Save Lesson
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
        .create(document.querySelector('#content'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'alignment', '|',
                'bulletedList', 'numberedList', '|',
                'link', 'imageInsert', 'insertTable', '|', // Changed from imageUpload to imageInsert
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
            // Simple upload adapter configuration
            simpleUpload: {
                uploadUrl: '{{ route("admin.lessons.upload-image") }}',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }
        })
        .then(newEditor => {
            editor = newEditor;
            
            // Update textarea when editor content changes
            editor.model.document.on('change:data', () => {
                document.querySelector('#content').value = editor.getData();
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
                document.querySelector('#content').value = editor.getData();
            }
        });
    }
});
</script>
@endsection