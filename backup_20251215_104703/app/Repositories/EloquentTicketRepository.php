<?php

namespace App\Repositories;

use App\Models\Ticket;

class EloquentTicketRepository implements TicketRepositoryInterface
{
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }
}
