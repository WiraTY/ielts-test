@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Courses', 'url' => route('admin.courses.index')],
        ['label' => 'Bulk Level Assignment', 'url' => null]
    ]" />

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Bulk Course Level Assignment</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.courses.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Back to Courses
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </h3>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="mb-6 rounded-md bg-red-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </h3>
                </div>
            </div>
        </div>
    @endif

    <!-- Bulk Assignment Form -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Assign Levels to Courses</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Select courses and assign them to specific levels.</p>
        </div>
        <div class="border-t border-gray-200">
            <form method="POST" action="{{ route('admin.courses.bulk-assign-level.store') }}" class="px-4 py-5 sm:p-6">
                @csrf
                
                <!-- Level Selection -->
                <div class="mb-6">
                    <label for="level" class="block text-sm font-medium text-gray-700">Assign Level</label>
                    <select id="level" name="level" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">Select a level</option>
                        <option value="starter">Starter</option>
                        <option value="beginner">Beginner</option>
                        <option value="elementary">Elementary</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                    @error('level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Course Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Courses</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($courses as $course)
                            <div class="border border-gray-200 rounded-md p-3">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="course_{{ $course->id }}" name="courses[]" type="checkbox" value="{{ $course->id }}" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="course_{{ $course->id }}" class="font-medium text-gray-700">{{ $course->title }}</label>
                                        <p class="text-gray-500">{{ Str::limit($course->description, 50) }}</p>
                                        @if($course->level)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                                Current: {{ ucfirst($course->level) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-4 text-gray-500">
                                No courses found.
                            </div>
                        @endforelse
                    </div>
                    @error('courses')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Assign Level to Selected Courses
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Current Level Distribution -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Current Course Level Distribution</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Overview of courses by level.</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                        <div class="text-sm font-medium text-blue-800">Starter</div>
                        <div class="mt-1 text-2xl font-semibold text-blue-900">{{ $starterCount }}</div>
                    </div>
                    
                    <div class="bg-green-50 border border-green-100 rounded-lg p-4">
                        <div class="text-sm font-medium text-green-800">Beginner</div>
                        <div class="mt-1 text-2xl font-semibold text-green-900">{{ $beginnerCount }}</div>
                    </div>
                    
                    <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4">
                        <div class="text-sm font-medium text-yellow-800">Elementary</div>
                        <div class="mt-1 text-2xl font-semibold text-yellow-900">{{ $elementaryCount }}</div>
                    </div>
                    
                    <div class="bg-purple-50 border border-purple-100 rounded-lg p-4">
                        <div class="text-sm font-medium text-purple-800">Intermediate</div>
                        <div class="mt-1 text-2xl font-semibold text-purple-900">{{ $intermediateCount }}</div>
                    </div>
                    
                    <div class="bg-pink-50 border border-pink-100 rounded-lg p-4">
                        <div class="text-sm font-medium text-pink-800">Advanced</div>
                        <div class="mt-1 text-2xl font-semibold text-pink-900">{{ $advancedCount }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
