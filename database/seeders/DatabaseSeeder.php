<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create several test users
        \App\Models\User::factory(5)->create();

        // Roles and manager user
        $this->call([
            RoleAndUserSeeder::class,
            \Database\Seeders\TicketSeeder::class,
        ]);
    }
}
