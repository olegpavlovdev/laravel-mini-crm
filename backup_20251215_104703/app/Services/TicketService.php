<?php

namespace App\Services;

use App\Repositories\TicketRepositoryInterface;
use App\Models\Customer;
use Illuminate\Support\Carbon;

class TicketService
{
    public function __construct(protected TicketRepositoryInterface $repository)
    {
    }

    public function create(array $data, array $files = [])
    {
        // Create or find customer by phone/email combination
        $customer = Customer::firstOrCreate([
            'phone' => $data['phone'],
        ], [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
        ]);

        $ticketData = [
            'customer_id' => $customer->id,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'status' => \App\Models\Ticket::STATUS_NEW,
        ];

        $ticket = $this->repository->create($ticketData);

        // Attach files via medialibrary if available
        if (!empty($files) && method_exists($ticket, 'addMedia')) {
            foreach ($files as $file) {
                $ticket->addMedia($file)->toMediaCollection('attachments');
            }
        }

        return $ticket->fresh('customer');
    }

    public function statistics(): array
    {
        $now = Carbon::now();
        $day = \App\Models\Ticket::whereBetween('created_at', [$now->copy()->subDay(), $now])->count();
        $week = \App\Models\Ticket::whereBetween('created_at', [$now->copy()->subWeek(), $now])->count();
        $month = \App\Models\Ticket::whereBetween('created_at', [$now->copy()->subMonth(), $now])->count();

        return compact('day', 'week', 'month');
    }
}
