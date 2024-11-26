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

    <!-- Comments Section -->
    <h2>Comments</h2>

    <!-- Display existing comments -->
    <div id="commentsContainer">
        @foreach ($event->comments as $comment)
            <div class="card mb-2" id="comment-card-{{ $comment->comment_id }}">
                <div class="card-body">
                    <p id="comment-{{ $comment->comment_id }}" class="comment-content">{{ $comment->content }}</p>
                    <p class="text-muted">By {{ $comment->user->name }} on {{ $comment->created_at->format('d/m/Y H:i') }}</p>

                    <!-- Show edit and delete options if the current user is the one who posted the comment -->
                    @if ($comment->user_id === Auth::id())
                        <!-- Edit button -->
                        <button class="btn btn-warning btn-sm" onclick="toggleEditForm({{ $comment->comment_id }})">Edit</button>

                        <!-- Edit Form (Initially hidden) -->
                        <form id="edit-form-{{ $comment->comment_id }}" class="edit-form" style="display: none;">
                            @csrf
                            <textarea name="content" class="form-control mb-2" rows="3" required>{{ $comment->content }}</textarea>
                            <button type="button" class="btn btn-primary btn-sm" onclick="editComment({{ $comment->comment_id }})">Confirm</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEditForm({{ $comment->comment_id }})">Cancel</button>
                        </form>

                        <!-- Delete button -->
                        <button class="btn btn-danger btn-sm" onclick="deleteComment({{ $comment->comment_id }})">Delete</button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Add new comment -->
    <form id="addCommentForm">
        @csrf
        <textarea name="content" class="form-control mb-2" rows="3" required></textarea>
        <button type="button" class="btn btn-primary" id="addCommentButton">Add Comment</button>
    </form>
</div>

<script>
    // Toggle edit form visibility
    function toggleEditForm(commentId) {
        const form = document.getElementById(`edit-form-${commentId}`);
        form.style.display = form.style.display === "none" ? "block" : "none";
    }

    // Add a new comment
    document.getElementById('addCommentButton').addEventListener('click', function (e) {
        e.preventDefault();

        const content = document.querySelector('textarea[name="content"]').value;
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch("{{ route('comments.add', $event->event_id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({ content: content }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const comment = data.comment;

                // Add the new comment to the comments container
                const commentHtml = `
                <div class="card mb-2" id="comment-card-${comment.comment_id}">
                    <div class="card-body">
                        <p id="comment-${comment.comment_id}" class="comment-content">${comment.content}</p>
                        <p class="text-muted">By You just now</p>
                        <button class="btn btn-warning btn-sm" onclick="toggleEditForm(${comment.comment_id})">Edit</button>
                        <form id="edit-form-${comment.comment_id}" class="edit-form" style="display: none;">
                            @csrf
                            <textarea name="content" class="form-control mb-2" rows="3" required>${comment.content}</textarea>
                            <button type="button" class="btn btn-primary btn-sm" onclick="editComment(${comment.comment_id})">Confirm</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEditForm(${comment.comment_id})">Cancel</button>
                        </form>
                        <button class="btn btn-danger btn-sm" onclick="deleteComment(${comment.comment_id})">Delete</button>
                    </div>
                </div>`;
                document.getElementById('commentsContainer').insertAdjacentHTML('beforeend', commentHtml);

                // Clear the textarea
                document.querySelector('textarea[name="content"]').value = '';
            } else {
                alert('Error adding comment');
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Edit an existing comment
    function editComment(commentId) {
        const content = document.querySelector(`#edit-form-${commentId} textarea[name="content"]`).value;
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch(`/comments/${commentId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
            body: JSON.stringify({ content: content }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`comment-${commentId}`).textContent = content;
                toggleEditForm(commentId);
            } else {
                alert('Error updating comment');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Delete a comment
    function deleteComment(commentId) {
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch(`/comments/${commentId}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken,
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the comment card from the DOM
                const commentCard = document.getElementById(`comment-card-${commentId}`);
                commentCard.remove();
            } else {
                alert('Error deleting comment');
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>

@endsection