<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CheckUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check users in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking users in the database...');
        
        try {
            $users = User::all();
            $this->info('Total users found: ' . $users->count());
            
            foreach ($users as $user) {
                $this->line('ID: ' . $user->id . ', Name: ' . $user->name . ', Email: ' . $user->email . ', Role: ' . $user->role);
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}