<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-user-status {email?} {--disable=} {--enable=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user status by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Handle disable option
        if ($this->option('disable')) {
            $user = User::where('email', $this->option('disable'))->first();
            if ($user) {
                $this->info("Before update - Email verified at: " . ($user->email_verified_at ? $user->email_verified_at : 'Not verified'));
                $result = $user->update(['email_verified_at' => null]);
                $user->refresh(); // Refresh model to get updated data
                $this->info("Update result: " . ($result ? 'true' : 'false'));
                $this->info("After update - Email verified at: " . ($user->email_verified_at ? $user->email_verified_at : 'Not verified'));
                $this->info("User {$user->email} disabled successfully.");
            } else {
                $this->error("User not found.");
            }
            return;
        }
        
        // Handle enable option
        if ($this->option('enable')) {
            $user = User::where('email', $this->option('enable'))->first();
            if ($user) {
                $this->info("Before update - Email verified at: " . ($user->email_verified_at ? $user->email_verified_at : 'Not verified'));
                $result = $user->update(['email_verified_at' => now()]);
                $user->refresh(); // Refresh model to get updated data
                $this->info("Update result: " . ($result ? 'true' : 'false'));
                $this->info("After update - Email verified at: " . ($user->email_verified_at ? $user->email_verified_at : 'Not verified'));
                $this->info("User {$user->email} enabled successfully.");
            } else {
                $this->error("User not found.");
            }
            return;
        }
        
        $email = $this->argument('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $this->info("User: " . $user->name . " (" . $user->email . ")");
                $this->info("Email verified at: " . ($user->email_verified_at ? $user->email_verified_at : 'Not verified'));
                $this->info("Status: " . ($user->email_verified_at ? 'Active' : 'Pending'));
            } else {
                $this->error("User with email {$email} not found.");
            }
        } else {
            $users = User::all();
            $this->info("Total users: " . $users->count());
            foreach ($users as $user) {
                $this->line("{$user->name} ({$user->email}) - " . ($user->email_verified_at ? 'Active' : 'Pending'));
            }
        }
    }
}
