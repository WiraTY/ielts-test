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
                    ['label' => 'Create Question', 'url' => route('admin.quizzes.questions.create', $quiz)]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Create New Question</h1>
                    <a href="{{ route('admin.courses.lessons.edit', [$quiz->lesson->course, $quiz->lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
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

                <form action="{{ route('admin.quizzes.questions.store', $quiz) }}" method="POST">
                    @csrf

                    <div class="mb-6">
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">Question Text</label>
                        <textarea id="question_text" name="question_text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter question text" rows="4" required>{{ old('question_text') }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Question Type</label>
                        <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="mcq" {{ old('type') == 'mcq' ? 'selected' : '' }}>Multiple Choice (Single Answer)</option>
                            <option value="multi" {{ old('type') == 'multi' ? 'selected' : '' }}>Multiple Choice (Multiple Answers)</option>
                            <option value="essay" {{ old('type') == 'essay' ? 'selected' : '' }}>Essay</option>
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="score" class="block text-sm font-medium text-gray-700 mb-2">Score</label>
                        <input type="number" id="score" name="score" value="{{ old('score', 1) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter score" min="1" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                        <div id="optionsContainer">
                            <div class="flex items-center mb-2">
                                <input type="text" name="options[]" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Option 1" required>
                                <button type="button" class="ml-2 text-red-600 hover:text-red-900 remove-option">×</button>
                            </div>
                            <div class="flex items-center mb-2">
                                <input type="text" name="options[]" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Option 2" required>
                                <button type="button" class="ml-2 text-red-600 hover:text-red-900 remove-option">×</button>
                            </div>
                        </div>
                        <button type="button" id="addOptionBtn" class="text-blue-500 hover:text-blue-700 text-sm font-medium mt-2">
                            + Add Option
                        </button>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer(s)</label>
                        <div id="correctAnswersContainer">
                            <p class="text-gray-500 text-sm mb-2">Select the correct answer:</p>
                            <div class="flex items-center mb-2">
                                <input type="radio" id="correct_0" name="correct_answer" value="0" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="correct_0" class="ml-2 block text-sm text-gray-700">
                                    A. Option 1
                                </label>
                            </div>
                            <div class="flex items-center mb-2">
                                <input type="radio" id="correct_1" name="correct_answer" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="correct_1" class="ml-2 block text-sm text-gray-700">
                                    B. Option 2
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.courses.lessons.edit', [$quiz->lesson->course, $quiz->lesson]) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Create Question
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
        .create(document.querySelector('#question_text'), {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'bulletedList', 'numberedList', '|',
                'link', 'imageInsert', '|', // Changed from imageUpload to imageInsert
                'undo', 'redo'
            ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                ]
            },
            // Configure the image upload endpoint
            image: {
                toolbar: [
                    'imageTextAlternative', 'imageStyle:full', 'imageStyle:side'
                ]
            },
            // Simple upload adapter configuration
            simpleUpload: {
                uploadUrl: '{{ route("admin.questions.upload-image") }}',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }
        })
        .then(newEditor => {
            editor = newEditor;
            
            // Update textarea when editor content changes
            editor.model.document.on('change:data', () => {
                document.querySelector('#question_text').value = editor.getData();
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
                document.querySelector('#question_text').value = editor.getData();
            }
        });
    }

    const addOptionBtn = document.getElementById('addOptionBtn');
    const optionsContainer = document.getElementById('optionsContainer');
    const typeSelect = document.getElementById('type');
    const correctAnswersContainer = document.getElementById('correctAnswersContainer');

    // Add option
    addOptionBtn.addEventListener('click', function() {
        const optionDiv = document.createElement('div');
        optionDiv.className = 'flex items-center mb-2';
        optionDiv.innerHTML = `
            <input type="text" name="options[]" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Option ${optionsContainer.children.length + 1}" required>
            <button type="button" class="ml-2 text-red-600 hover:text-red-900 remove-option">×</button>
        `;
        optionsContainer.appendChild(optionDiv);
        
        // Update correct answers options
        updateCorrectAnswersOptions();
    });

    // Remove option
    optionsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option')) {
            if (optionsContainer.children.length > 1) {
                e.target.parentElement.remove();
                // Update correct answers options
                updateCorrectAnswersOptions();
            }
        }
    });

    // Update correct answers options when question type changes
    typeSelect.addEventListener('change', function() {
        updateCorrectAnswersOptions();
    });

    // Function to update correct answers options
    function updateCorrectAnswersOptions() {
        const options = Array.from(optionsContainer.querySelectorAll('input[name="options[]"]')).map(input => input.value);
        
        if (typeSelect.value === 'multi') {
            // Multiple select
            correctAnswersContainer.innerHTML = '<p class="text-gray-500 text-sm mb-2">Select all correct answers below:</p>';
            options.forEach((option, index) => {
                const div = document.createElement('div');
                div.className = 'flex items-center mb-2';
                div.innerHTML = `
                    <input type="checkbox" id="correct_${index}" name="correct_answers[]" value="${index}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="correct_${index}" class="ml-2 block text-sm text-gray-700">
                        ${String.fromCharCode(65 + index)}. ${option || `Option ${index + 1}`}
                    </label>
                `;
                correctAnswersContainer.appendChild(div);
            });
        } else if (typeSelect.value === 'mcq') {
            // Single select
            correctAnswersContainer.innerHTML = '<p class="text-gray-500 text-sm mb-2">Select the correct answer:</p>';
            options.forEach((option, index) => {
                const div = document.createElement('div');
                div.className = 'flex items-center mb-2';
                div.innerHTML = `
                    <input type="radio" id="correct_${index}" name="correct_answer" value="${index}" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="correct_${index}" class="ml-2 block text-sm text-gray-700">
                        ${String.fromCharCode(65 + index)}. ${option || `Option ${index + 1}`}
                    </label>
                `;
                correctAnswersContainer.appendChild(div);
            });
        } else {
            // Essay - no correct answers needed
            correctAnswersContainer.innerHTML = '<p class="text-gray-500 text-sm">Essay questions are manually graded.</p>';
        }
    }

    // Initialize correct answers options
    updateCorrectAnswersOptions();
});
</script>
@endsection