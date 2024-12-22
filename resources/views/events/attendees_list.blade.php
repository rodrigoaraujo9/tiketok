@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Attendees for {{ $event->name }}</h1>
    <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-secondary mb-3">Back to Event</a>

    @if ($event->attendees->isEmpty())
        <p>No attendees found for this event.</p>
    @else
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($event->attendees as $attendee)
                    <tr>
                        <td>{{ $attendee->name }}</td>
                        <td>{{ $attendee->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Button to leave the event (only for the current user) -->
    @if ($event->attendees->contains(auth()->id()))
        <form action="{{ route('events.leave', $event->event_id) }}" method="POST" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-warning confirmation-button delete" data-confirm="Are you sure you want to leave this event?">
                Leave Event
            </button>
        </form>
    @endif
</div>
@endsection
