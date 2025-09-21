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
                    ['label' => 'Edit', 'url' => route('admin.courses.lessons.edit', [$course, $lesson])]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Edit Lesson</h1>
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

                <form action="{{ route('admin.courses.lessons.update', [$course, $lesson]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Lesson Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $lesson->title) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter lesson title" required>
                    </div>

                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                        <textarea id="content" name="content" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter lesson content" rows="15">{{ old('content', $lesson->content) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $lesson->order) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter lesson order" min="0">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="draft" {{ old('status', $lesson->published_at ? 'published' : 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $lesson->published_at ? 'published' : 'draft') == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="video_url" class="block text-sm font-medium text-gray-700 mb-2">Video URL</label>
                        <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter video URL">
                    </div>

                    <!-- Audio Listening Section -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h2 class="text-xl font-bold mb-4">Audio Listening Practice</h2>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="audio_enabled" name="audio_enabled" value="1" {{ old('audio_enabled', $lesson->audio?->is_enabled) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="audio_enabled" class="ml-2 block text-sm font-medium text-gray-700">
                                    Enable Audio Listening Practice
                                </label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="audio_description" class="block text-sm font-medium text-gray-700 mb-2">Listening Practice Instructions</label>
                                <textarea id="audio_description" name="audio_description" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter instructions for listening practice">{{ old('audio_description', $lesson->audio?->description) }}</textarea>
                            </div>
                            
                            <div>
                                <label for="audio_file" class="block text-sm font-medium text-gray-700 mb-2">Audio File</label>
                                <input type="file" id="audio_file" name="audio_file" accept="audio/*" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Upload an audio file for listening practice (MP3 or WAV, max 5MB)</p>
                                @if($lesson->audio?->audio_path)
                                    <p class="mt-1 text-sm text-green-600">Current audio file: {{ basename($lesson->audio->audio_path) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Speaking Practice Section -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h2 class="text-xl font-bold mb-4">Speaking Practice</h2>
                        
                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="speaking_enabled" name="speaking_enabled" value="1" {{ old('speaking_enabled', $lesson->speaking?->is_enabled) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="speaking_enabled" class="ml-2 block text-sm font-medium text-gray-700">
                                    Enable Speaking Practice
                                </label>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="speaking_description" class="block text-sm font-medium text-gray-700 mb-2">Speaking Practice Instructions</label>
                                <textarea id="speaking_description" name="speaking_description" rows="6" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter instructions for speaking practice">{{ old('speaking_description', $lesson->speaking?->description) }}</textarea>
                            </div>
                            
                            <div>
                                <label for="speaking_duration" class="block text-sm font-medium text-gray-700 mb-2">Speaking Duration (seconds)</label>
                                <input type="number" id="speaking_duration" name="speaking_duration" value="{{ old('speaking_duration', $lesson->speaking?->duration) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter duration in seconds" min="1" max="300">
                                <p class="mt-1 text-sm text-gray-500">Set time limit for speaking practice (1-300 seconds)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quiz Section -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h2 class="text-xl font-bold mb-4">Quiz</h2>
                        
                        @if($lesson->quiz)
                            <input type="hidden" name="quiz_id" value="{{ $lesson->quiz->id }}">
                        @endif
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="quiz_title" class="block text-sm font-medium text-gray-700 mb-2">Quiz Title</label>
                                <input type="text" id="quiz_title" name="quiz_title" value="{{ old('quiz_title', $lesson->quiz->title ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter quiz title">
                            </div>

                            <div>
                                <label for="quiz_duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                                <input type="number" id="quiz_duration_minutes" name="quiz_duration_minutes" value="{{ old('quiz_duration_minutes', $lesson->quiz->duration_minutes ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter duration in minutes" min="1">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="quiz_pass_score" class="block text-sm font-medium text-gray-700 mb-2">Pass Score (%)</label>
                                <input type="number" id="quiz_pass_score" name="quiz_pass_score" value="{{ old('quiz_pass_score', $lesson->quiz->pass_score ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter pass score" min="0" max="100">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <div class="flex items-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Published
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.courses.show', $course) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Update Lesson and Quiz
                        </button>
                    </div>
                </form>
                
                <!-- Questions Section -->
                @if($lesson->quiz)
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold">Questions</h2>
                        <a href="{{ route('admin.quizzes.questions.create', $lesson->quiz) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
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
                                @forelse($lesson->quiz->questions as $question)
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
                                            <a href="{{ route('admin.quizzes.questions.edit', [$lesson->quiz, $question]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <a href="{{ route('admin.quizzes.questions.show', [$lesson->quiz, $question]) }}" class="ml-4 text-indigo-600 hover:text-indigo-900">View</a>
                                            <form action="{{ route('admin.quizzes.questions.destroy', [$lesson->quiz, $question]) }}" method="POST" class="inline">
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
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let editor, audioEditor, speakingEditor;
    
    // Initialize CKEditor for content
    ClassicEditor
        .create(document.querySelector('#content'), {
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
        
    // Initialize CKEditor for audio description
    ClassicEditor
        .create(document.querySelector('#audio_description'), {
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
            audioEditor = newEditor;
            
            // Update textarea when editor content changes
            newEditor.model.document.on('change:data', () => {
                document.querySelector('#audio_description').value = newEditor.getData();
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
            if (editor) {
                document.querySelector('#content').value = editor.getData();
            }
            if (audioEditor) {
                document.querySelector('#audio_description').value = audioEditor.getData();
            }
            if (speakingEditor) {
                document.querySelector('#speaking_description').value = speakingEditor.getData();
            }
        });
    }
});
</script>
@endsection