@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $event->name }}</h1>
    
    <p><strong>Event ID:</strong> {{ $event->event_id }}</p>
    <p><strong>Description:</strong> {{ $event->description }}</p>
    <p><strong>Date:</strong> {{ $event->date }}</p>
    <p><strong>Venue:</strong> {{ $event->venue->name }}</p>
    <p><strong>Organizer:</strong> {{ $event->organizer->name }}</p>
    <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>

    <!-- Check if the user is part of the attendees -->
    @if (!$event->attendees->contains(Auth::id()))
    <form action="{{ route('events.join', $event->event_id) }}" method="POST">
        @csrf
        <button class="btn btn-primary">Join Event</button>
    </form>
    @else
        <p class="text-success">You are already part of this event.</p>
    @endif

    <!-- Comments Section -->
    <h2>Comments</h2>

    <!-- Display existing comments -->
    @forelse ($event->comments as $comment)
        <div class="card mb-2">
            <div class="card-body">
                <p>{{ $comment->content }}</p>
                <p class="text-muted">By {{ $comment->user->name }} on 
                    {{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : 'Date not available' }}</p>

                @if ($comment->user_id === Auth::id())
                    <!-- Edit Comment -->
                    <form action="{{ route('comments.edit', $comment->comment_id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="text" name="content" value="{{ $comment->content }}" required>
                        <button type="submit" class="btn btn-warning btn-sm">Edit</button>
                    </form>

                    <!-- Delete Comment -->
                    <form action="{{ route('comments.delete', $comment->comment_id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p>No comments yet.</p>
    @endforelse

    <!-- Add new comment -->
    <form action="{{ route('comments.add', $event->event_id) }}" method="POST">
        @csrf
        <textarea name="content" class="form-control mb-2" rows="3" required></textarea>
        <button type="submit" class="btn btn-primary">Add Comment</button>
    </form>

</div>
@endsection
