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
                    ['label' => $level->display_name, 'url' => route('admin.levels.show', $level)]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">{{ $level->display_name }}</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.levels.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                            Back to Levels
                        </a>
                        <a href="{{ route('admin.levels.edit', $level) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Edit Level
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Level Information</h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Name</label>
                                <div class="text-sm text-gray-900">{{ $level->name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Display Name</label>
                                <div class="text-sm text-gray-900">{{ $level->display_name }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Order</label>
                                <div class="text-sm text-gray-900">{{ $level->order }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Status</label>
                                <div class="text-sm text-gray-900">
                                    @if($level->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Description</h3>
                        <div class="text-sm text-gray-900">
                            @if($level->description)
                                {{ $level->description }}
                            @else
                                <span class="text-gray-500">No description provided.</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Usage Information -->
                <div class="border-t border-gray-200 pt-6 mt-6">
                    <h2 class="text-xl font-bold mb-4">Usage Information</h2>
                    
                    <!-- Courses using this level -->
                    @php
                        $coursesCount = \App\Models\Course::where('level', $level->name)->count();
                    @endphp
                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Courses</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $coursesCount }} course(s)
                            </span>
                        </div>
                        @if($coursesCount > 0)
                            <p class="mt-1 text-sm text-gray-500">This level is assigned to {{ $coursesCount }} course(s).</p>
                        @else
                            <p class="mt-1 text-sm text-gray-500">No courses are currently assigned to this level.</p>
                        @endif
                    </div>
                    
                    <!-- Users using this level -->
                    @php
                        $usersCount = \App\Models\User::where('assigned_level', $level->name)
                            ->orWhere('current_level', $level->name)
                            ->count();
                    @endphp
                    <div class="mb-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900">Users</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $usersCount }} user(s)
                            </span>
                        </div>
                        @if($usersCount > 0)
                            <p class="mt-1 text-sm text-gray-500">This level is assigned to {{ $usersCount }} user(s).</p>
                        @else
                            <p class="mt-1 text-sm text-gray-500">No users are currently assigned to this level.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection