<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run this with: php artisan db:seed
     *
     * Creates one admin and one regular user for testing.
     * ⚠️ Only use this in development — never seed real passwords.
     */
    public function run(): void
    {
        // Create admin account
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Create regular user account
        User::create([
            'name'     => 'Regular User',
            'email'    => 'user@example.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        $this->command->info('✅ Seeded: admin@example.com (admin) and user@example.com (user)');
        $this->command->info('   Password for both: password');
    }
}
