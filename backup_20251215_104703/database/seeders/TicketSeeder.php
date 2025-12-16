<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        // Create several tickets with customers
        \App\Models\Ticket::factory(20)->create();
    }
}
