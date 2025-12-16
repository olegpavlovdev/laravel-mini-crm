<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AdminTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('customer')->orderBy('created_at', 'desc');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        if ($email = $request->query('email')) {
            $query->whereHas('customer', function ($q) use ($email) {
                $q->where('email', 'like', '%' . $email . '%');
            });
        }

        if ($phone = $request->query('phone')) {
            $query->whereHas('customer', function ($q) use ($phone) {
                $q->where('phone', 'like', '%' . $phone . '%');
            });
        }

        if ($from = $request->query('from')) {
            $to = $request->query('to') ?: now();
            $query->whereBetween('created_at', [$from, $to]);
        }

        $tickets = $query->paginate(15)->withQueryString();

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('customer');
        $attachments = $ticket->getMedia('attachments');
        return view('admin.tickets.show', compact('ticket', 'attachments'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status' => 'required|in:new,in_progress,processed']);
        $ticket->status = $request->input('status');
        $ticket->manager_response_date = $request->input('status') === 'processed' ? now() : null;
        $ticket->save();

        return redirect()->back()->with('success', 'Status updated');
    }

    public function downloadAttachment(Ticket $ticket, $mediaId)
    {
        $media = $ticket->getMedia('attachments')->where('id', $mediaId)->first();
        if (! $media) {
            abort(404);
        }

        return response()->download($media->getPath(), $media->file_name);
    }
}
