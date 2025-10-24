<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        // Check if user is authorized to view users
        Gate::authorize('viewAny', User::class);
        
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }
    
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Check if user is authorized to create users
        Gate::authorize('create', User::class);
        
        return view('admin.users.create');
    }
    
    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Check if user is authorized to create users
        Gate::authorize('create', User::class);
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'string', 'in:admin,student'],
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }
    
    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Check if user is authorized to update users
        Gate::authorize('update', $user);
        
        // Get all levels for level assignment
        $levels = Level::active()->ordered()->get();
        
        return view('admin.users.edit', compact('user', 'levels'));
    }
    
    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            // Log the request
            \Log::info('User update request received', [
                'user_id' => $user->id,
                'request_data' => $request->all(),
                'request_method' => $request->method(),
            ]);
            
            // Check if user is authorized to update users
            Gate::authorize('update', $user);
            
            // Prepare validation rules
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'role' => ['required', 'string', 'in:admin,student'],
            ];
            
            // Only validate level fields if role is student
            if ($request->role === 'student') {
                $rules['assigned_level'] = ['nullable', 'string', 'exists:levels,name'];
                $rules['current_level'] = ['nullable', 'string', 'exists:levels,name'];
                $rules['unlocked_levels'] = ['nullable', 'array'];
                $rules['unlocked_levels.*'] = ['string', 'exists:levels,name'];
            }
            
            // Validate the request
            $validator = \Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                \Log::error('User update validation failed', [
                    'user_id' => $user->id,
                    'errors' => $validator->errors()->toArray(),
                ]);
                
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];
            
            // Update level information if user is a student
            if ($request->role === 'student') {
                // Handle empty string values for level fields
                $userData['assigned_level'] = $request->assigned_level === '' ? null : $request->assigned_level;
                $userData['current_level'] = $request->current_level === '' ? null : $request->current_level;
                $userData['unlocked_levels'] = $request->unlocked_levels ?? null;
                $userData['has_taken_placement_test'] = $request->has_taken_placement_test ? true : false;
            }
            
            \Log::info('Updating user with data', [
                'user_id' => $user->id,
                'user_data' => $userData,
            ]);
            
            $user->update($userData);
            
            \Log::info('User updated successfully', ['user_id' => $user->id]);
            
            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            \Log::error('User update failed with exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Reset user's password
     */
    public function resetPassword(User $user)
    {
        // Check if user is authorized to update users
        Gate::authorize('update', $user);
        
        // Generate a random password
        $newPassword = str()->random(12);
        
        // Update user's password
        $user->update([
            'password' => Hash::make($newPassword)
        ]);
        
        // In a real application, you would send an email to the user with the new password
        // For now, we'll just return it in the session flash message
        return redirect()->back()->with('success', "Password reset successfully. New password: {$newPassword}");
    }
    
    /**
     * Reset user's placement test status
     */
    public function resetPlacementTest(User $user)
    {
        // Check if user is authorized to update users
        Gate::authorize('update', $user);
        
        $user->update([
            'has_taken_placement_test' => false,
            'assigned_level' => null,
            'current_level' => null,
            'unlocked_levels' => null,
        ]);
        
        return redirect()->back()->with('success', 'Placement test status reset successfully.');
    }
    
    /**
     * Enable a user by setting email_verified_at
     */
    public function enable(User $user)
    {
        // Check if user is authorized to update users
        Gate::authorize('update', $user);
        
        $user->update([
            'email_verified_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'User enabled successfully.');
    }
    
    /**
     * Disable a user by clearing email_verified_at
     */
    public function disable(User $user)
    {
        // Check if user is authorized to update users
        Gate::authorize('update', $user);
        
        $user->update([
            'email_verified_at' => null
        ]);
        
        return redirect()->back()->with('success', 'User disabled successfully.');
    }
}