<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        //-------- Create Admin
        User::create([
            'name' => 'Mohammad Ali',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
            'role' => 'admin',
            'email_verified_at' => now(),
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