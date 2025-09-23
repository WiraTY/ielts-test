@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Breadcrumb -->
                <x-breadcrumb :breadcrumbs="[
                    ['label' => 'Admin', 'url' => route('admin.dashboard')],
                    ['label' => 'Levels', 'url' => route('admin.levels.index')],
                    ['label' => 'Create', 'url' => route('admin.levels.create')]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Create Level</h1>
                    <a href="{{ route('admin.levels.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                        Back to Levels
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

                <form action="{{ route('admin.levels.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter level name (e.g., starter)" required>
                        <p class="mt-1 text-sm text-gray-500">Internal identifier for the level. Must be unique and lowercase.</p>
                    </div>

                    <div class="mb-6">
                        <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                        <input type="text" id="display_name" name="display_name" value="{{ old('display_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter display name (e.g., Starter)" required>
                        <p class="mt-1 text-sm text-gray-500">User-friendly name shown to students.</p>
                    </div>

                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter level description">{{ old('description') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Optional description of the level.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700 mb-2">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $nextOrder) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Enter order number" required>
                            <p class="mt-1 text-sm text-gray-500">Sequence in which students progress through levels. Must be unique.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Active
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Only active levels can be assigned to students.</p>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('admin.levels.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Create Level
                        </button>
                    </div>
                </form>
                
                <!-- Default Levels Guide -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                    <h3 class="text-lg font-medium text-blue-800 mb-2">Recommended Default Levels</h3>
                    <p class="text-blue-700 mb-3">Based on Pearson Global Scale of English (GSE):</p>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection