@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manage Attendees for {{ $event->name }}</h1>
    <a href="{{ route('events.index') }}" class="btn btn-secondary mb-3">Back to Events</a>

    @if ($event->attendees->isEmpty())
        <p>No attendees found for this event.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($event->attendees as $attendee)
                    <tr>
                        <td>{{ $attendee->name }}</td>
                        <td>{{ $attendee->email }}</td>
                        <td>
                            <form action="{{ route('events.attendees.remove', $event->event_id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $attendee->user_id }}">
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
