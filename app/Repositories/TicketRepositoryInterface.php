<?php

namespace App\Repositories;

use App\Models\Ticket;

interface TicketRepositoryInterface
{
    public function create(array $data): Ticket;
}
