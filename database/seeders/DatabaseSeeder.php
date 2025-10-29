<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Rakib',
            'email' => 'rakib@gmebd.com',
            'password' => bcrypt('secret'),
        ]);

        User::factory()->create([
            'name' => 'Pulak',
            'email' => 'pulak@gmebd.com',
            'password' => bcrypt('mypassword'),
        ]);
    }
}
