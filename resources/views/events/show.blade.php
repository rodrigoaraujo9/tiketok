@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $event->name }}</h1>
    <p><strong>Visibility:</strong> {{ $event->visibility }}</p>
    <p><strong>Country:</strong> {{ ucfirst($event->country) }}</p>
    <p><strong>Description:</strong> {{ $event->description }}</p>
    <p><strong>Date:</strong> {{ $event->date }}</p>
    <p><strong>Venue:</strong> {{ $event->venue->name }}</p>
    <p><strong>Organizer:</strong> {{ $event->organizer->name }}</p>
    
    <!-- Organizer controls -->
    @if (Auth::id() === $event->organizer_id) 
        <a href="{{ route('events.edit', $event->event_id) }}" class="btn btn-warning">Edit Event</a>
    @endif
    <br>
    @if (Auth::check() && (!Auth::user()->isAdmin() || !Auth::id() === $event->organizer_id))
        <a href="{{ route('createReportForm', ['event_id' => $event->event_id]) }}" class="btn btn-danger">Report Event</a>
    @endif
    <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>
    <br>
    <!-- Join Event -->
    @if (!$event->attendees->contains(Auth::id()))
        @if ($event->attendees->count() < $event->max_event_capacity)
            <form action="{{ route('events.join', $event->event_id) }}" method="POST" style="margin-top: 1rem;">
                @csrf
                @if (Auth::check() && (!Auth::user()->isAdmin() || !Auth::id() === $event->organizer_id))
                    <button type="submit" class="btn btn-primary">Join Event</button>
                @endif
            </form>
        @else
            <button class="btn btn-secondary" style="margin-top: 1rem;" disabled>AT FULL CAPACITY</button>
        @endif
    @else
        <p class="text-success" style="margin-top:2rem;">You are already part of this event.</p>
    @endif

    @if (Auth::check() && (Auth::user()->isAdmin() || Auth::id() === $event->organizer_id))
        <form action="{{ route('events.destroy', $event->event_id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-sm confirmation-button" data-confirm="Are you sure you want to delete this event?" style="margin-bottom:2rem;">Delete</button>
        </form>
    @endif

    @if ($hasJoined)
        <a href="{{ route('events.attendees.list', $event->event_id) }}" class="btn btn-primary" style="margin-top: 1rem;">
            See other attendees :)
        </a>
    @endif

    <br>
    <h2>Comments</h2>

    <br>
    <h2>Polls</h2>

    <a href="{{ route('polls.index', $event->event_id) }}" class="btn btn-info">Check polls for {{ $event->name }} →</a>

    <br><br>
    <h2>Messages</h2>
    <a href="{{ route('message.show', $event->event_id) }}" class="btn btn-primary">Messages for {{ $event->name }} →</a>
</div>
@endsection
