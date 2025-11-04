<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Clear users safely
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create Admin
        User::create([
            'name' => 'Mohammad Ali',
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Employees - ADD AS MANY AS YOU WANT
        $employees = [
            [
                'name' => 'Rakib',
                'email' => 'rakib@gmebd.com',
                'password' => 'secret',
            ],
            [
                'name' => 'Pulak Kar', 
                'email' => 'pulak@gmebd.com',
                'password' => 'mypassword',
            ],
            [
                'name' => 'Sazzad', 
                'email' => 'sazzad@gmebd.com',
                'password' => 'mypassword',
            ],
            [
                'name' => 'Opu', 
                'email' => 'opu@gmebd.com',
                'password' => 'mypassword',
            ],
            [
                'name' => 'Shoriful', 
                'email' => 'shoriful@gmebd.com',
                'password' => 'mypassword',
            ],
            [
                'name' => 'Mahin', 
                'email' => 'mahin@gmebd.com',
                'password' => 'mypassword',
            ],
            [
                'name' => 'Hasib', 
                'email' => 'hasib@gmebd.com',
                'password' => 'mypassword',
            ],
            [
                'name' => 'Sabbir', 
                'email' => 'sabbir@gmebd.com',
                'password' => 'mypassword',
            ],
                        [
                'name' => 'Ratry', 
                'email' => 'ratry@gmebd.com',
                'password' => 'mypassword',
            ],
                        [
                'name' => 'Nasim', 
                'email' => 'nasim@gmebd.com',
                'password' => 'mypassword',
            ],
                        [
                'name' => 'Muniya', 
                'email' => 'muniya@gmebd.com',
                'password' => 'mypassword',
            ],
                        [
                'name' => 'Farhana', 
                'email' => 'farhana@gmebd.com',
                'password' => 'mypassword',
            ],
        ];

        foreach ($employees as $employee) {
            User::create([
                'name' => $employee['name'],
                'email' => $employee['email'],
                'password' => Hash::make($employee['password']),
                'role' => 'user',
                'email_verified_at' => now(),
            ]);
        }

        echo "Users created successfully!\n";
        echo "Total users: " . User::count() . "\n";
        echo "Admins: " . User::where('role', 'admin')->count() . "\n";
        echo "Employees: " . User::where('role', 'user')->count() . "\n";
        
        // Show login credentials
        echo "\n=== LOGIN CREDENTIALS ===\n";
        echo "Admin: admin@example.com / secret\n";
        echo "Employees: Use their email with the password you set\n";
    }
}