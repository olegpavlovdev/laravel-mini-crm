<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::factory(5)->create();
        $this->call([
            RoleAndUserSeeder::class,
            \Database\Seeders\TicketSeeder::class,
        ]);
    }
}
