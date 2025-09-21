@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Breadcrumb -->
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('placement-tests.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Placement Tests</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $placementTest->title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">{{ $placementTest->title }}</h1>
                </div>
                
                <div class="mb-6">
                    <p class="text-gray-600 mb-4">{{ $placementTest->description }}</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-medium text-gray-900 mb-2">Test Information</h3>
                            <ul class="text-sm text-gray-600 space-y-2">
                                <li class="flex justify-between">
                                    <span>Total Questions:</span>
                                    <span class="font-medium">{{ $placementTest->questions->count() }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Duration:</span>
                                    <span class="font-medium">{{ $placementTest->duration_minutes ? $placementTest->duration_minutes . ' minutes' : 'No time limit' }}</span>
                                </li>
                                <li class="flex justify-between">
                                    <span>Total Points:</span>
                                    <span class="font-medium">{{ $placementTest->questions->sum('score') }}</span>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-medium text-gray-900 mb-2">Level Mapping</h3>
                            <ul class="text-sm text-gray-600 space-y-2">
                                @foreach($placementTest->level_mapping as $range => $level)
                                    <li class="flex justify-between">
                                        <span>{{ $range }}%</span>
                                        <span class="font-medium">{{ ucfirst($level) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h3 class="font-medium text-blue-800 mb-2">Important Information</h3>
                        <ul class="text-sm text-blue-700 list-disc pl-5 space-y-1">
                            <li>You will be assigned a level based on your score</li>
                            <li>You can only take this test once unless you retake it</li>
                            <li>Your assigned level determines which courses you can access</li>
                            <li>If you don't take the test, you'll start at the Starter level</li>
                        </ul>
                    </div>
                    
                    <div class="flex justify-center">
                        <a href="{{ route('placement-tests.start', $placementTest) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-6 rounded-lg text-lg">
                            Start Placement Test
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection