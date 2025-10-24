<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-admin-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if admin user exists and display details';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', 'admin@example.com')->first();
        
        if ($user) {
            $this->info('Admin user found:');
            $this->line('ID: ' . $user->id);
            $this->line('Name: ' . $user->name);
            $this->line('Email: ' . $user->email);
            $this->line('Role: ' . $user->role);
            $this->line('Password (hashed): ' . $user->password);
        } else {
            $this->error('Admin user not found');
        }
    }
}
