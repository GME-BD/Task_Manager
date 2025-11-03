<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        //-------- Create Admin
        User::create([
            'name' => 'Mohammad Ali',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
            'role' => 'admin',
            'email_verified_at' => now(),
=======
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
>>>>>>> 86d494371894abb5b75a70517ba8a54dcb37f78f
        ]);

        //--------- Create Employees
        User::create([
            'name' => 'Rakib',
            'email' => 'rakib@gmebd.com',
            'password' => Hash::make('secret'),
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Pulak Kar',
            'email' => 'pulak@gmebd.com',
            'password' => Hash::make('mypassword'),
            'role' => 'employee',
            'email_verified_at' => now(),
        ]);
    }
}