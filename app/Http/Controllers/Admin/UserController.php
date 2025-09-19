<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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