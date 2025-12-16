<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create manager role
        $manager = Role::firstOrCreate(['name' => 'manager']);

        // Create a manager user
        $user = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager',
                'password' => bcrypt('password'),
            ]
        );

        $user->assignRole($manager->name);
    }
}
