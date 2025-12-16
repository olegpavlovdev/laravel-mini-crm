<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function __construct(protected TicketService $service)
    {
    }

    public function store(StoreTicketRequest $request)
    {
        $ticket = $this->service->create($request->validated(), $request->file('files', []));

        return new TicketResource($ticket);
    }

    public function statistics(Request $request)
    {
        // Statistics handled in the service
        return response()->json($this->service->statistics());
    }
}
