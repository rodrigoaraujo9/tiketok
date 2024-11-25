@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $event->name }}</h1>

    <p><strong>Event ID:</strong> {{ $event->event_id }}</p>
    <p><strong>Description:</strong> {{ $event->description }}</p>
    <p><strong>Date:</strong> {{ $event->date }}</p>
    <p><strong>Venue:</strong> {{ $event->venue->name }}</p>
    <p><strong>Organizer:</strong> {{ $event->organizer->name }}</p>
    
    
    <a href="{{ route('events.edit', $event->event_id) }}" class="btn btn-warning">Edit Event</a>
    
    <a href="{{ route('createReportForm', ['event_id' => $event->event_id]) }}" class="btn btn-danger">Report Event</a>

    <a href="{{ route('events.index') }}" class="btn btn-secondary">Back to Events</a>

    <!-- Comentários -->
    <h2>Comments</h2>
    @foreach ($event->comments as $comment)
        <div class="card mb-2">
            <div class="card-body">
                <p id="comment-{{ $comment->comment_id }}" class="comment-content">{{ $comment->content }}</p>
                <p class="text-muted">By {{ $comment->user->name }} on {{ $comment->created_at->format('d/m/Y H:i') }}</p>

                <!-- Editar comentário (visível apenas para o autor) -->
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
            </div>
        </div>
    @endforeach

    <form action="{{ route('comments.add', $event->event_id) }}" method="POST">
        @csrf
        <textarea name="content" class="form-control mb-2" rows="3" required></textarea>
        <button type="submit" class="btn btn-primary">Add Comment</button>
    </form>
</div>

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
