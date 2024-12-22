@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Comments for {{ $event->name }}</h1>

    <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-secondary">Back to Event</a>
    <br><br>

    <!-- Add New Comment -->
    @guest
        <p class="text-info">You need to <a href="{{ route('login') }}">log in</a> to add a comment.</p>
    @else
    <form action="{{ route('comments.add', $event->event_id) }}" method="POST">
        @csrf
        <textarea name="content" class="form-control mb-2" rows="3" required></textarea>
        <div class="text-center mb-2">
            <a href="{{ route('comments.createWithPoll', $event->event_id) }}" class="btn btn-secondary mb-2">Create Comment with a Poll →</a>
        </div>
        <button type="submit" class="btn btn-primary">Add Comment</button>
        <hr>
    </form>
    @endguest

    <!-- Display existing comments -->
    <div id="commentsContainer">
        @foreach ($event->comments as $comment)
            <hr class="comment-divider">
            <div class="card mb-2" id="comment-card-{{ $comment->comment_id }}">
                <div class="card-body">
                    <p id="comment-{{ $comment->comment_id }}" class="comment-content">{{ $comment->content }}</p>
                    <p class="text-muted">By {{ $comment->user->name }} on {{ $comment->date }}</p>

                    <!-- Exibir Poll Associada -->
                    @if ($comment->poll)
                        <h5 class="fw-bold">Poll: {{ $comment->poll->question }}</h5>
                        <table class="table table-bordered text-center align-middle">
                            <thead>
                                <tr>
                                    <th class="text-start">Option</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $userVote = $comment->poll->votes()->where('user_id', Auth::id())->first();
                                @endphp

                                @foreach ($comment->poll->options as $option)
                                    <tr>
                                        <td class="text-start fs-4 fw-bold">
                                            {{ $option->option_text }}
                                            <span class="text-muted fs-5">({{ $option->votes }} votes)</span>
                                        </td>

                                        <td>
                                            @if (!$userVote)
                                                <form action="{{ route('comments.votePoll', [
                                                    'event_id' => $event->event_id, 
                                                    'comment_id' => $comment->comment_id, 
                                                    'poll_id' => $comment->poll->poll_id
                                                ]) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="option_id" value="{{ $option->option_id }}">
                                                    <button type="submit" class="btn btn-primary btn-sm">Vote</button>
                                                </form>
                                            @elseif ($userVote->option_id == $option->option_id)
                                            <form action="{{ route('comments.deletePollVote', [
                                                'event_id' => $event->event_id,
                                                'comment_id' => $comment->comment_id,
                                                'poll_id' => $comment->poll->poll_id
                                            ]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Remove Vote</button>
                                            </form>


                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($userVote)
                            <div class="alert alert-info text-center mt-3">
                                <h4 class="fw-bold">Your Vote: <span class="text-primary">{{ $userVote->option->option_text }}</span></h4>
                            </div>
                        @endif

                        @if (Auth::id() === $comment->user_id)
                            <div class="mt-3">
                                <form action="{{ route('comments.deletePoll', [
                                    'event_id' => $event->event_id,
                                    'comment_id' => $comment->comment_id,
                                    'poll_id' => $comment->poll->poll_id
                                ]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Delete Poll</button>
                                </form>
                            </div>
                            <br>
                        @endif
                    @endif

                    <!-- Edit and Delete Comment -->
                    @if ($comment->user_id === Auth::id())
                        <button class="btn btn-warning btn-sm" onclick="toggleEditForm({{ $comment->comment_id }})">Edit</button>
                        <form action="{{ route('comments.edit', $comment->comment_id) }}" method="POST" id="edit-form-{{ $comment->comment_id }}" class="edit-form" style="display: none;">
                            @csrf
                            @method('PUT')
                            <textarea name="content" class="form-control mb-2" rows="3" required>{{ $comment->content }}</textarea>
                            <button type="submit" class="btn btn-primary btn-sm">Confirm</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEditForm({{ $comment->comment_id }})">Cancel</button>
                        </form>
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
</div>

<script>
    function toggleEditForm(commentId) {
        const form = document.getElementById(`edit-form-${commentId}`);
        form.style.display = form.style.display === "none" ? "block" : "none";
    }
</script>

<style>
    .comment-divider {
        border: none;
        height: 1px;
        background-color: #ccc;
        margin: 20px 0;
    }
</style>
@endsection
