<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-admin-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset admin password to default';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        
        if ($admin) {
            $admin->password = Hash::make('password');
            $admin->save();
            $this->info('Admin password has been reset to "password"');
        } else {
            $this->error('Admin user not found');
        }
    }
}
