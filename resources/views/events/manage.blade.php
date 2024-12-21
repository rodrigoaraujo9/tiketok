@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Manage Your Events</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    @if ($events->isEmpty())
        <div class="alert alert-info text-center">
            <p>You have not created any events yet. <a href="{{ route('events.create') }}" class="btn btn-link">Create an Event</a></p>
        </div>
    @else
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->date }}</td>
                        <td class="text-center">
                            <form action="{{ route('events.invite', $event->event_id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="email" name="email" class="form-control" placeholder="Invite user by email" required>
                                <button class="btn btn-primary btn-sm mt-2">Send Invite</button>
                            </form>
                            <a href="{{ route('events.attendees', $event->event_id) }}" class="btn btn-outline-info btn-sm mt-2">
                                Manage Attendees
                            </a>
                            <a href="{{ route('events.edit', $event->event_id) }}" class="btn btn-outline-success btn-sm mt-2">
                                Edit Event
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-container d-flex justify-content-center mt-4">
            {{ $events->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
@endsection
