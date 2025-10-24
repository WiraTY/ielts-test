<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckUserTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-user-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user table structure';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if table exists
        if (Schema::hasTable('users')) {
            $this->info('Users table exists');
            
            // Check columns
            $columns = Schema::getColumnListing('users');
            $this->info('Columns: ' . implode(', ', $columns));
            
            // Check specific column
            if (Schema::hasColumn('users', 'email_verified_at')) {
                $this->info('email_verified_at column exists');
            } else {
                $this->error('email_verified_at column does not exist');
            }
            
            // Check a specific user
            $user = DB::table('users')->where('email', 'test@example.com')->first();
            if ($user) {
                $this->info('User found:');
                $this->info('ID: ' . $user->id);
                $this->info('Email: ' . $user->email);
                $this->info('Email verified at: ' . ($user->email_verified_at ? $user->email_verified_at : 'NULL'));
                
                // Try to update the user directly with DB query
                $this->info('Trying to update user with DB query...');
                $result = DB::table('users')->where('id', $user->id)->update(['email_verified_at' => null]);
                $this->info('Update result: ' . $result);
                
                // Check again
                $updatedUser = DB::table('users')->where('id', $user->id)->first();
                $this->info('After update - Email verified at: ' . ($updatedUser->email_verified_at ? $updatedUser->email_verified_at : 'NULL'));
            } else {
                $this->error('User not found');
            }
        } else {
            $this->error('Users table does not exist');
        }
    }
}
