@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $event->name }}</h1>
    
    <!-- Display visibility instead of Event ID -->
    <p><strong>Visibility:</strong> {{ ucfirst($event->visibility) }}</p>

    <p><strong>Description:</strong> {{ $event->description }}</p>
    <p><strong>Date:</strong> {{ $event->date }}</p>
    <p><strong>Venue:</strong> {{ $event->venue->name }}</p>
    <p><strong>Organizer:</strong> {{ $event->organizer->name }}</p>
    <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>

    <!-- Check if the user is part of the attendees -->
    @if (!$event->attendees->contains(Auth::id()))
    <form action="{{ route('events.join', $event->event_id) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary">Join Event</button>
</form>

    @else
        <p class="text-success" style="margin-top:2rem;">You are already part of this event.</p>
    @endif
</div>
@endsection
