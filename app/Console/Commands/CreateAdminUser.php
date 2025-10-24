<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user with default credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        
        if ($admin) {
            $this->info('Admin user already exists');
            $admin->password = Hash::make('password');
            $admin->save();
            $this->info('Admin password has been reset to "password"');
        } else {
            $admin = new User();
            $admin->name = 'Admin';
            $admin->email = 'admin@example.com';
            $admin->password = Hash::make('password');
            $admin->role = 'admin';
            $admin->save();
            $this->info('Admin user created successfully with email "admin@example.com" and password "password"');
        }
    }
}
