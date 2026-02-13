<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->firstOrFail();
        $employeeRole = Role::where('name', 'employee')->firstOrFail();

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );

        $employee = User::firstOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name' => 'Employee',
                'password' => Hash::make('password'),
            ]
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        $employee->roles()->syncWithoutDetaching([$employeeRole->id]);
    }
}
