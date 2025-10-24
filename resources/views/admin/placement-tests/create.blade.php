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
                    ['label' => 'Create', 'url' => route('admin.placement-tests.create')]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Create Placement Test</h1>
                    <a href="{{ route('admin.placement-tests.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                        Back to Placement Tests
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

                <form action="{{ route('admin.placement-tests.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter placement test title" required>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter placement test description">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">Duration (minutes)</label>
                            <input type="number" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter duration in minutes" min="1" max="180">
                            <p class="mt-1 text-sm text-gray-500">Optional. Set time limit for the placement test.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Only active placement tests can be taken by students.</p>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.placement-tests.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Create Placement Test
                        </button>
                    </div>
                </form>
                
                <!-- Level Mapping Guide -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                    <h3 class="text-lg font-medium text-blue-800 mb-2">Pearson GSE Level Mapping Guide</h3>
                    <p class="text-blue-700 mb-3">After creating the placement test, you can define score ranges using the Pearson Global Scale of English (GSE):</p>
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
                    <p class="mt-3 text-sm text-blue-600">Note: You can edit the level mapping after creating the placement test.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection