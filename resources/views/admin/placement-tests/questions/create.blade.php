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
                    ['label' => $placementTest->title, 'url' => route('admin.placement-tests.edit', $placementTest)],
                    ['label' => 'Add Question', 'url' => route('admin.placement-tests.questions.create', $placementTest)]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Add Question</h1>
                    <a href="{{ route('admin.placement-tests.edit', $placementTest) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                        Back to Placement Test
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

                <form action="{{ route('admin.placement-tests.questions.store', $placementTest) }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">Question Text</label>
                        <textarea id="question_text" name="question_text" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter question text" required>{{ old('question_text') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="option_a" class="block text-sm font-medium text-gray-700 mb-2">Option A</label>
                            <input type="text" id="option_a" name="option_a" value="{{ old('option_a') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter option A" required>
                        </div>
                        
                        <div>
                            <label for="option_b" class="block text-sm font-medium text-gray-700 mb-2">Option B</label>
                            <input type="text" id="option_b" name="option_b" value="{{ old('option_b') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter option B" required>
                        </div>
                        
                        <div>
                            <label for="option_c" class="block text-sm font-medium text-gray-700 mb-2">Option C</label>
                            <input type="text" id="option_c" name="option_c" value="{{ old('option_c') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter option C" required>
                        </div>
                        
                        <div>
                            <label for="option_d" class="block text-sm font-medium text-gray-700 mb-2">Option D</label>
                            <input type="text" id="option_d" name="option_d" value="{{ old('option_d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter option D" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="correct_answer" class="block text-sm font-medium text-gray-700 mb-2">Correct Answer</label>
                            <select id="correct_answer" name="correct_answer" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select correct answer</option>
                                <option value="A" {{ old('correct_answer') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('correct_answer') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('correct_answer') == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('correct_answer') == 'D' ? 'selected' : '' }}>D</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="score" class="block text-sm font-medium text-gray-700 mb-2">Score</label>
                            <input type="number" id="score" name="score" value="{{ old('score', 1) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter score" min="1" required>
                        </div>
                        
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter order" min="0">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.placement-tests.edit', $placementTest) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Add Question
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection