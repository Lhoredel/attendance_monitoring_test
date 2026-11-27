<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
         $users = [
            // ADMIN ACCOUNTS
            [
                'name' => 'System Administrator',
                'email' => 'admin1@school.com',
                'password' => 'Admin123!',
                'role' => 'admin',
                'status' => 'Active',
            ],
            [
                'name' => 'Assistant Administrator',
                'email' => 'admin2@school.com',
                'password' => 'Admin123!',
                'role' => 'admin',
                'status' => 'Active',
            ],
            [
                'name' => 'School Administrator',
                'email' => 'admin3@school.com',
                'password' => 'Admin123!',
                'role' => 'admin',
                'status' => 'Active',
            ],

            // TEACHER ACCOUNTS
            [
                'name' => 'Teacher One',
                'email' => 'teacher1@school.com',
                'password' => 'Teacher123!',
                'role' => 'teacher',
                'status' => 'Active',
            ],
            [
                'name' => 'Teacher Two',
                'email' => 'teacher2@school.com',
                'password' => 'Teacher123!',
                'role' => 'teacher',
                'status' => 'Active',
            ],
            [
                'name' => 'Teacher Three',
                'email' => 'teacher3@school.com',
                'password' => 'Teacher123!',
                'role' => 'teacher',
                'status' => 'Active',
            ],
            [
                'name' => 'Teacher Four',
                'email' => 'teacher4@school.com',
                'password' => 'Teacher123!',
                'role' => 'teacher',
                'status' => 'Active',
            ],
            [
                'name' => 'Teacher Five',
                'email' => 'teacher5@school.com',
                'password' => 'Teacher123!',
                'role' => 'teacher',
                'status' => 'Active',
            ],
        ];

        foreach ($users as $user) {

            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'role' => $user['role'],
                    'status' => $user['status'],
                ]
            );

        }

        $this->command->info('Admin and Teacher accounts created successfully!');
    }

    }

