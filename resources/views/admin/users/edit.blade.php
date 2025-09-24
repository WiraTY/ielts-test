@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Breadcrumb -->
                <x-breadcrumb :breadcrumbs="[
                    ['label' => 'Admin', 'url' => route('admin.dashboard')],
                    ['label' => 'Users', 'url' => route('admin.users.index')],
                    ['label' => $user->name, 'url' => route('admin.users.edit', $user)]
                ]" />

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Edit User</h1>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                            Back to Users
                        </a>
                    </div>
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

                <form id="user-update-form" action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                            <select id="role" name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Student</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Account Status</label>
                            <div class="flex items-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($user->email_verified_at) bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    @if($user->email_verified_at) Active @else Pending @endif
                                </span>
                                <form method="POST" action="{{ $user->email_verified_at ? route('admin.users.disable', $user) : route('admin.users.enable', $user) }}" class="ml-4">
                                    @csrf
                                    <button type="submit" class="text-sm @if($user->email_verified_at) bg-yellow-500 hover:bg-yellow-600 @else bg-green-500 hover:bg-green-600 @endif text-white font-medium py-1 px-3 rounded">
                                        @if($user->email_verified_at) Disable @else Enable @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Level Information (only for students) -->
                    <div id="student-fields" class="{{ $user->role === 'student' ? '' : 'hidden' }}">
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <h2 class="text-xl font-bold mb-4">Level Information</h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="assigned_level" class="block text-sm font-medium text-gray-700 mb-2">Assigned Level</label>
                                    <select id="assigned_level" name="assigned_level" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Level</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->name }}" {{ old('assigned_level', $user->assigned_level) === $level->name ? 'selected' : '' }}>{{ $level->display_name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">Level assigned from placement test</p>
                                </div>

                                <div>
                                    <label for="current_level" class="block text-sm font-medium text-gray-700 mb-2">Current Level</label>
                                    <select id="current_level" name="current_level" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select Level</option>
                                        @foreach($levels as $level)
                                            <option value="{{ $level->name }}" {{ old('current_level', $user->current_level) === $level->name ? 'selected' : '' }}>{{ $level->display_name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">User's current accessible level</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Unlocked Levels</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
                                        @foreach($levels as $level)
                                            <div class="flex items-center">
                                                <input type="checkbox" id="unlocked_level_{{ $level->name }}" name="unlocked_levels[]" value="{{ $level->name }}" 
                                                    {{ in_array($level->name, old('unlocked_levels', $user->unlocked_levels ?? [])) ? 'checked' : '' }}
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                                <label for="unlocked_level_{{ $level->name }}" class="ml-2 block text-sm text-gray-900">
                                                    {{ $level->display_name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">Levels unlocked for this user</p>
                                </div>
                            </div>

                            <div class="flex items-center mb-6">
                                <input type="checkbox" id="has_taken_placement_test" name="has_taken_placement_test" value="1" 
                                    {{ old('has_taken_placement_test', $user->has_taken_placement_test) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="has_taken_placement_test" class="ml-2 block text-sm text-gray-900">
                                    Has taken placement test
                                </label>
                            </div>

                            <div class="flex items-center">
                                <form method="POST" action="{{ route('admin.users.reset-placement-test', $user) }}" onsubmit="return confirm('Are you sure you want to reset this user\'s placement test status? This will clear all level information.')">
                                    @csrf
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                                        Reset Placement Test Status
                                    </button>
                                </form>
                                <p class="ml-4 text-sm text-gray-500">This will allow the user to retake the placement test</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-2">
                            Cancel
                        </a>
                        <div class="relative inline-block text-left mr-2">
                            <button type="button" onclick="confirmPasswordReset()" class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded">
                                Reset Password
                            </button>
                        </div>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                            Update User
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const studentFields = document.getElementById('student-fields');
    
    // Toggle student fields when role changes
    roleSelect.addEventListener('change', function() {
        if (this.value === 'student') {
            studentFields.classList.remove('hidden');
        } else {
            studentFields.classList.add('hidden');
        }
    });
    
    // Add detailed form submission logging and ensure form works properly
    const form = document.getElementById('user-update-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission started');
            
            // Log all form data
            const formData = new FormData(form);
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            
            // Check for empty required fields
            const requiredFields = form.querySelectorAll('[required]');
            let hasEmptyRequired = false;
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    console.log('Empty required field:', field.name);
                    hasEmptyRequired = true;
                }
            });
            
            if (hasEmptyRequired) {
                console.log('Form has empty required fields');
                // Don't prevent submission, but log it
            }
            
            // Explicitly allow form submission to continue
            console.log('Form submission proceeding...');
        });
    }
});

function confirmPasswordReset() {
    if (confirm('Are you sure you want to reset this user\'s password? A new password will be generated and displayed.')) {
        // Create a form dynamically and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.users.reset-password', $user) }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add form to document and submit
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection