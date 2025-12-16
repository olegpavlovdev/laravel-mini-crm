<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'subject' => $this->faker->sentence(6),
            'message' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement([
                Ticket::STATUS_NEW,
                Ticket::STATUS_IN_PROGRESS,
                Ticket::STATUS_PROCESSED,
            ]),
            'manager_response_date' => $this->faker->optional()->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
