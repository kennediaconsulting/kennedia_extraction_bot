<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user for Kennedia Consulting
        User::updateOrCreate(
            ['email' => 'admin@kennediaconsulting.net'],
            [
                'name' => 'Kennedia Admin',
                'email' => 'admin@kennediaconsulting.net',
                'password' => Hash::make('admin@1234'),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('   Email: admin@kennediaconsulting.net');
        $this->command->info('   Password: admin@1234');
    }
}
