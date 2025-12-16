@extends('layouts.app')

@section('content')
<div class="container py-4">
    <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-secondary mb-3">Back to list</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-header">Ticket #{{ $ticket->id }} — {{ $ticket->subject }}</div>
        <div class="card-body">
            <p><strong>Customer:</strong> {{ $ticket->customer->name }} — {{ $ticket->customer->email }} / {{ $ticket->customer->phone }}</p>
            <p><strong>Message:</strong><br>{{ nl2br(e($ticket->message)) }}</p>
            <p><strong>Status:</strong>
                @if($ticket->status == 'new')
                    <span class="badge bg-primary">New</span>
                @elseif($ticket->status == 'in_progress')
                    <span class="badge bg-warning">In Progress</span>
                @else
                    <span class="badge bg-success">Processed</span>
                @endif
            </p>

            <form method="post" action="{{ route('admin.tickets.updateStatus', $ticket) }}" class="row g-2 align-items-center">
                @csrf
                <div class="col-auto">
                    <select name="status" class="form-select">
                        <option value="new" {{ $ticket->status=='new' ? 'selected' : '' }}>New</option>
                        <option value="in_progress" {{ $ticket->status=='in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="processed" {{ $ticket->status=='processed' ? 'selected' : '' }}>Processed</option>
                    </select>
                </div>
                <div class="col-auto"><button class="btn btn-primary">Change</button></div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Attachments</div>
        <div class="card-body">
            @if($attachments->isEmpty())
                <p>No attachments</p>
            @else
                <ul>
                    @foreach($attachments as $media)
                        <li>
                            {{ $media->file_name }} — <a href="{{ route('admin.tickets.downloadAttachment', [$ticket, $media->id]) }}">Download</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
