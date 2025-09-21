@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Placement Tests</h1>
                </div>
                
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                
                @isset($placementTest)
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">{{ $placementTest->title }}</h2>
                    <p class="text-gray-600 mb-4">{{ $placementTest->description ?? 'No description available.' }}</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-medium text-gray-900 mb-2">Test Details</h3>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li>Questions: {{ $placementTest->questions->count() }}</li>
                                <li>Duration: {{ $placementTest->duration_minutes ? $placementTest->duration_minutes . ' minutes' : 'No time limit' }}</li>
                            </ul>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-medium text-gray-900 mb-2">Level Mapping</h3>
                            <ul class="text-sm text-gray-600 space-y-1">
                                @if(is_array($placementTest->level_mapping))
                                    @foreach($placementTest->level_mapping as $range => $level)
                                        <li>{{ $range }}%: {{ ucfirst($level) }}</li>
                                    @endforeach
                                @else
                                    <li>No level mapping available</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            @auth
                                @if(auth()->user()->has_taken_placement_test)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                    <span class="ml-2 text-sm text-gray-600">
                                        Your level: {{ auth()->user()->assigned_level ?? 'Not assigned' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Not Completed
                                    </span>
                                @endif
                            @endauth
                        </div>
                        
                        @auth
                        <a href="{{ route('placement-tests.show', $placementTest) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            {{ auth()->user()->has_taken_placement_test ? 'Retake Test' : 'Take Test' }}
                        </a>
                        @endauth
                    </div>
                </div>
                @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-yellow-800">No active placement test available at the moment.</p>
                </div>
                @endisset
            </div>
        </div>
    </div>
</div>
@endsection