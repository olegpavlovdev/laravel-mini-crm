@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3>Tickets</h3>

    <form method="get" class="row g-2 mb-3">
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                <option value="new" {{ request('status')=='new' ? 'selected' : '' }}>New</option>
                <option value="in_progress" {{ request('status')=='in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="processed" {{ request('status')=='processed' ? 'selected' : '' }}>Processed</option>
            </select>
        </div>
        <div class="col-md-2"><input name="email" value="{{ request('email') }}" class="form-control" placeholder="Email"></div>
        <div class="col-md-2"><input name="phone" value="{{ request('phone') }}" class="form-control" placeholder="Phone"></div>
        <div class="col-md-2"><input name="from" type="date" value="{{ request('from') }}" class="form-control"></div>
        <div class="col-md-2"><input name="to" type="date" value="{{ request('to') }}" class="form-control"></div>
        <div class="col-md-2"><button class="btn btn-primary">Filter</button></div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @foreach($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->customer->name }}<br><small>{{ $ticket->customer->email }} / {{ $ticket->customer->phone }}</small></td>
                <td>{{ $ticket->subject }}</td>
                <td>
                    @if($ticket->status == 'new')
                        <span class="badge bg-primary">New</span>
                    @elseif($ticket->status == 'in_progress')
                        <span class="badge bg-warning">In Progress</span>
                    @else
                        <span class="badge bg-success">Processed</span>
                    @endif
                </td>
                <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                <td><a href="{{ route('admin.tickets.show', $ticket) }}" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $tickets->links() }}
</div>
@endsection
