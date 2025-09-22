@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Breadcrumb -->
                <x-breadcrumb :breadcrumbs="[
                    ['label' => 'Admin', 'url' => route('admin.dashboard')],
                    ['label' => 'Placement Tests', 'url' => route('admin.placement-tests.index')],
                    ['label' => $placementTest->title, 'url' => route('admin.placement-tests.show', $placementTest)],
                    ['label' => 'Edit', 'url' => route('admin.placement-tests.edit', $placementTest)]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Edit Placement Test</h1>
                    <a href="{{ route('admin.placement-tests.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                        Back to Placement Tests
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

                <form action="{{ route('admin.placement-tests.update', $placementTest) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $placementTest->title) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter placement test title" required>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter placement test description">{{ old('description', $placementTest->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', $placementTest->duration_minutes) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter duration in minutes" min="1" max="180">
                            <p class="mt-1 text-sm text-gray-500">Optional. Set time limit for the placement test.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $placementTest->is_active) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Only active placement tests can be taken by students.</p>
                        </div>
                    </div>

                    <!-- Level Mapping Section -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h2 class="text-xl font-bold mb-4">Level Mapping</h2>
                        <p class="text-sm text-gray-500 mb-4">Define score ranges and their corresponding levels.</p>
                        
                        <div id="level-mapping-container">
                            @if(old('level_mapping') || $placementTest->level_mapping)
                                @php
                                    $mapping = old('level_mapping', $placementTest->level_mapping);
                                @endphp
                                @foreach($mapping as $range => $level)
                                    <div class="mapping-row grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Score Range</label>
                                            <input type="text" name="level_mapping[{{ $loop->index }}][range]" value="{{ $range }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 0-20">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                                            <input type="text" name="level_mapping[{{ $loop->index }}][level]" value="{{ $level }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., starter">
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" class="remove-mapping bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="mapping-row grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Score Range</label>
                                        <input type="text" name="level_mapping[0][range]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 0-20">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                                        <input type="text" name="level_mapping[0][level]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., starter">
                                    </div>
                                    <div class="flex items-end">
                                        <button type="button" class="remove-mapping bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <button type="button" id="add-mapping" class="mt-2 bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                            Add Range
                        </button>
                        
                        <!-- Level Mapping Guide -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                            <h3 class="text-lg font-medium text-blue-800 mb-2">Pearson GSE Level Mapping Guide</h3>
                            <p class="text-blue-700 mb-3">Use the Pearson Global Scale of English (GSE) for accurate level assignment:</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="bg-white p-3 rounded border">
                                    <strong class="text-gray-900">Starter:</strong> 22-35 (CEFR: A1-A1+)<br>
                                    <small class="text-gray-600">Beginner learners</small>
                                </div>
                                <div class="bg-white p-3 rounded border">
                                    <strong class="text-gray-900">Elementary:</strong> 30-42 (CEFR: A1+-A2)<br>
                                    <small class="text-gray-600">Basic learners</small>
                                </div>
                                <div class="bg-white p-3 rounded border">
                                    <strong class="text-gray-900">Pre-Intermediate:</strong> 36-46 (CEFR: A2-B1-)<br>
                                    <small class="text-gray-600">Lower intermediate</small>
                                </div>
                                <div class="bg-white p-3 rounded border">
                                    <strong class="text-gray-900">Intermediate:</strong> 46-58 (CEFR: B1)<br>
                                    <small class="text-gray-600">Mid-intermediate</small>
                                </div>
                                <div class="bg-white p-3 rounded border">
                                    <strong class="text-gray-900">Upper Intermediate:</strong> 57-67 (CEFR: B2)<br>
                                    <small class="text-gray-600">High intermediate</small>
                                </div>
                                <div class="bg-white p-3 rounded border">
                                    <strong class="text-gray-900">Advanced:</strong> 66-78 (CEFR: C1)<br>
                                    <small class="text-gray-600">Advanced learners</small>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-blue-600">Note: Ranges can overlap to ensure proper level assignment. Use format like "22-35".</p>
                        </div>
                    </div>

                    <!-- Questions Section -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold">Questions</h2>
                            <div class="flex space-x-2">
                                <button type="button" id="import-questions-btn" class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded">
                                    Import from Excel
                                </button>
                                <a href="{{ route('admin.placement-tests.questions.create', $placementTest) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                                    Add New Question
                                </a>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Question
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Score
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Order
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($placementTest->questions as $question)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($question->question_text, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $question->score }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-500">{{ $question->order }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.placement-tests.questions.edit', [$placementTest, $question]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    Edit
                                                </a>
                                                <a href="{{ route('admin.placement-tests.questions.show', [$placementTest, $question]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                    View
                                                </a>
                                                <form action="{{ route('admin.placement-tests.questions.destroy', [$placementTest, $question]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this question?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                                No questions found for this placement test.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admin.placement-tests.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Update Placement Test
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Excel Import Modal -->
<div id="import-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Import Questions from Excel</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="import-form" action="{{ route('admin.placement-tests.import-questions', $placementTest) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Excel File</label>
                    <input type="file" name="excel_file" accept=".xlsx,.xls,.csv" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <p class="mt-1 text-sm text-gray-500">Supported formats: XLSX, XLS, CSV (Max 2MB)</p>
                </div>
                
                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="replace_existing" name="replace_existing" value="1" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="replace_existing" class="ml-2 block text-sm text-gray-900">
                            Replace existing questions
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Check this box to delete all existing questions before importing</p>
                </div>
                
                <div class="mb-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Template Format:</h4>
                    <ul class="text-xs text-gray-500 list-disc pl-5 space-y-1">
                        <li>Column A: Question Text</li>
                        <li>Column B: Option A</li>
                        <li>Column C: Option B</li>
                        <li>Column D: Option C</li>
                        <li>Column E: Option D</li>
                        <li>Column F: Correct Answer (A, B, C, or D)</li>
                        <li>Column G: Score (optional, default to 1)</li>
                        <li>Column H: Order (optional, default to 0)</li>
                    </ul>
                    <div class="mt-2">
                        <a href="{{ route('admin.placement-tests.download-template') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            Download Excel Template
                        </a>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancel-import" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add mapping button
    document.getElementById('add-mapping').addEventListener('click', function() {
        const container = document.getElementById('level-mapping-container');
        const rowCount = container.querySelectorAll('.mapping-row').length;
        
        const newRow = document.createElement('div');
        newRow.className = 'mapping-row grid grid-cols-1 md:grid-cols-3 gap-4 mb-3';
        newRow.innerHTML = `
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Score Range</label>
                <input type="text" name="level_mapping[${rowCount}][range]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 0-20">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                <input type="text" name="level_mapping[${rowCount}][level]" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., starter">
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-mapping bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                    Remove
                </button>
            </div>
        `;
        
        container.appendChild(newRow);
    });
    
    // Remove mapping button
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-mapping')) {
            e.target.closest('.mapping-row').remove();
        }
    });
    
    // Excel import modal
    const importModal = document.getElementById('import-modal');
    const importBtn = document.getElementById('import-questions-btn');
    const closeBtn = document.getElementById('close-modal');
    const cancelImportBtn = document.getElementById('cancel-import');
    
    importBtn.addEventListener('click', function() {
        importModal.classList.remove('hidden');
    });
    
    closeBtn.addEventListener('click', function() {
        importModal.classList.add('hidden');
    });
    
    cancelImportBtn.addEventListener('click', function() {
        importModal.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target === importModal) {
            importModal.classList.add('hidden');
        }
    });
});
</script>
@endsection