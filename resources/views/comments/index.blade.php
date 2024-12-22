@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Comments for {{ $event->name }}</h1>
    <p><strong>Event ID:</strong> {{ $event->event_id }}</p>
    <p><strong>Date:</strong> {{ $event->date }}</p>
    <p><strong>Venue:</strong> {{ $event->venue->name }}</p>

    <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-secondary">Back to Event</a>

    <hr>

    <!-- Display existing comments -->
    <h2>Comments</h2>
    <div id="commentsContainer">
        @foreach ($event->comments as $comment)
            <div class="card mb-2" id="comment-card-{{ $comment->comment_id }}">
                <div class="card-body">
                    <p id="comment-{{ $comment->comment_id }}" class="comment-content">{{ $comment->content }}</p>
                    <p class="text-muted">By {{ $comment->user->name }} on {{ $comment->date }}</p>

                    @if ($comment->user_id === Auth::id())
                        <!-- Edit button -->
                        <button class="btn btn-warning btn-sm" onclick="toggleEditForm({{ $comment->comment_id }})">Edit</button>

                        <!-- Edit Form (Initially hidden) -->
                        <form action="{{ route('comments.edit', $comment->comment_id) }}" method="POST" id="edit-form-{{ $comment->comment_id }}" class="edit-form" style="display: none;">
                            @csrf
                            @method('PUT')
                            <textarea name="content" class="form-control mb-2" rows="3" required>{{ $comment->content }}</textarea>
                            <button type="submit" class="btn btn-primary btn-sm">Confirm</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEditForm({{ $comment->comment_id }})">Cancel</button>
                        </form>
                    @endif

                    <!-- Delete button -->
                    @if ($comment->user_id === Auth::id())
                        <form action="{{ route('comments.delete', $comment->comment_id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <hr>

    <!-- Add new comment -->
    @guest
        <p class="text-info">You need to <a href="{{ route('login') }}">log in</a> to add a comment.</p>
    @else
        <form action="{{ route('comments.add', $event->event_id) }}" method="POST">
            @csrf
            <textarea name="content" class="form-control mb-2" rows="3" required></textarea>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>
    @endguest
</div>

<script>
    function toggleEditForm(commentId) {
        const form = document.getElementById(`edit-form-${commentId}`);
        form.style.display = form.style.display === "none" ? "block" : "none";
    }
</script>
@endsection
