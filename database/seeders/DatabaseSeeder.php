<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@cece.com'],
            [
                'name' => 'Admin Gudangku',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_approved' => true,
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer Test',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'is_approved' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
