<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin';
    protected $description = 'Create default admin account if not exists';

    public function handle()
    {
        if (User::where('username', 'admin')->exists()) {
            $this->info('Admin already exists, skipping.');
            return;
        }

        User::create([
            'username' => 'admin',
            'password' => 'admin123',
            'role'     => 'admin',
        ]);

        $this->info('Admin account created! (admin / admin123)');
    }
}
