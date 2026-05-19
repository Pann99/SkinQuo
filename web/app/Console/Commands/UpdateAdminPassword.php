<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateAdminPassword extends Command
{
    protected $signature = 'admin:update-password {email?} {password?}';
    protected $description = 'Update admin password with valid Bcrypt hash';

    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@skinquo.co';
        $password = $this->argument('password') ?? 'password123';

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User with email $email not found!");
            return 1;
        }

        // Generate valid Bcrypt hash
        $hashedPassword = Hash::make($password);

        // Update user password
        $user->password = $hashedPassword;
        $user->save();

        $this->info("✅ Password updated successfully!");
        $this->line("Email: $email");
        $this->line("Password: $password");
        $this->line("New Hash: $hashedPassword");

        return 0;
    }
}
