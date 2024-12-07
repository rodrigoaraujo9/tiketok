@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $event->name }}</h1>
    <p><strong>Visibility:</strong> {{ $event->visibility }}</p>

    <!-- Display visibility instead of Event ID -->
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
    <!-- Join Event (moved below Report and Back buttons) -->
     @if (!$event->attendees->contains(Auth::id()))
        @if ($event->attendees->count() < $event->max_event_capacity)
            <form action="{{ route('events.join', $event->event_id) }}" method="POST" style="margin-top: 1rem;">
                @csrf
                @if (Auth::check() && (!Auth::user()->isAdmin() || !Auth::id() === $event->organizer_id))
            <button type="submit" class="btn btn-primary">Join Event</button>
                @endif
        </form>
        @else
            <button class="logout-button" style="margin-top: 1rem;" disabled>AT FULL CAPACITY</button>
        @endif
    @else
        <p class="text-success" style="margin-top:2rem;">You are already part of this event.</p>
    @endif

    @if (Auth::check() && (Auth::user()->isAdmin() || Auth::id() === $event->organizer_id))
    <form action="{{ route('events.destroy', $event->event_id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="delete btn btn-danger btn-sm" style="margin-bottom:2rem;">Delete</button>
    </form>
    @endif

    @if ($hasJoined)
        <a href="{{ route('events.attendees', $event->event_id) }}" class="btn btn-primary" style="margin-top: 1rem;">
            Ver Participantes
        </a>
    @endif

    
    <h2>Comments</h2>
    @foreach ($event->comments as $comment)
        <div class="card mb-2">
            <div class="card-body">
                <p id="comment-{{ $comment->comment_id }}" class="comment-content">{{ $comment->content }}</p>
                <p class="text-muted">By {{ $comment->user->name }} on {{ $comment->date }}</p>

                @if ($comment->user_id === Auth::id())
                    <button class="btn btn-warning btn-sm" onclick="toggleEditForm({{ $comment->comment_id }})">Edit</button>

                    <form action="{{ route('comments.edit', $comment->comment_id) }}" method="POST" id="edit-form-{{ $comment->comment_id }}" class="edit-form" style="display: none;">
                        @csrf
                        @method('PUT')
                        <textarea name="content" class="form-control mb-2" rows="3" required>{{ $comment->content }}</textarea>
                        <button type="submit" class="btn btn-primary btn-sm">Confirm</button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEditForm({{ $comment->comment_id }})">Cancel</button>
                    </form>
                @endif
                <!-- Delete comment -->
                @if (Auth::id() === $comment->user_id) 
                    <form action="{{ route('comments.delete', $comment->comment_id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>
        <br>
        <br>
    @endforeach

    @guest
        <p class="text-info">You need to <a href="{{ route('login') }}">log in</a> to add a comment.</p>
    @else
    @if (!Auth::user()->isAdmin())
        <form action="{{ route('comments.add', $event->event_id) }}" method="POST">
            @csrf
            <textarea name="content" class="form-control mb-2" rows="3" required></textarea>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>
    @endif
    @endguest


<script>
    function toggleEditForm(commentId) {
        var contentElement = document.getElementById('comment-' + commentId);
        var editForm = document.getElementById('edit-form-' + commentId);
        
        if (editForm.style.display === 'none') {
            editForm.style.display = 'block';
            contentElement.style.display = 'none';
        } else {
            editForm.style.display = 'none';
            contentElement.style.display = 'block';
        }
    }
</script>

@endsection
