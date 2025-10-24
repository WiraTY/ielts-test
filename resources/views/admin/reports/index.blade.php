@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <x-breadcrumb :breadcrumbs="[
        ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['label' => 'Reports', 'url' => null]
    ]" />

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
        <div class="flex space-x-2">
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Placement Test Reports -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Placement Test Reports</h2>
            <p class="text-gray-600 mb-4">View detailed analytics and reports for placement tests.</p>
            <a href="{{ route('admin.placement-tests.reports') }}" class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded text-center">
                View Reports
            </a>
        </div>
        
        <!-- User Level Tracking -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">User Level Tracking</h2>
            <p class="text-gray-600 mb-4">Track user levels and progress through the system.</p>
            <a href="{{ route('admin.user-level-tracking') }}" class="block w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded text-center">
                Track Levels
            </a>
        </div>
        
        <!-- Level Progression Tracking -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-bold mb-4">Level Progression Tracking</h2>
            <p class="text-gray-600 mb-4">Monitor user progression through different course levels.</p>
            <a href="{{ route('admin.reports.level-progression') }}" class="block w-full bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded text-center">
                Track Progression
            </a>
        </div>
    </div>
</div>
@endsection