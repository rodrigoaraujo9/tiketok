@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create comment with poll</h1>
    <br>

    <form action="{{ route('comments.storePoll', $event->event_id) }}" method="POST">
        @csrf

        <!-- Comment Content -->
        <div class="mb-3">
            <label for="content" class="form-label">Comment Content</label>
            <textarea name="content" id="content" class="form-control" rows="3" required></textarea>
        </div>
        <br>

        <!-- Poll Question -->
        <div class="mb-3">
            <label for="question" class="form-label">Poll Question</label>
            <input type="text" name="question" id="question" class="form-control" placeholder="Enter your poll question" required>
        </div>
        <br>

        <!-- Poll Options -->
        <div id="poll-options">
            <label class="form-label">Poll Options</label>
            <div class="mb-2">
                <input type="text" name="options[]" class="form-control mb-1" placeholder="Option 1" required>
                <input type="text" name="options[]" class="form-control mb-1" placeholder="Option 2" required>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="addPollOption()">Add another option</button>
        </div>
        <br>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-3">Create Comment with Poll</button>
    </form>
</div>

<script>
    function addPollOption() {
        const container = document.getElementById('poll-options');
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'options[]';
        input.className = 'form-control mb-1';
        input.placeholder = `Option ${container.querySelectorAll('input').length + 1}`;
        input.required = true;
        container.appendChild(input);
        const separator = document.createElement('hr');
        container.appendChild(separator);
        container.appendChild(input);
    }
</script>
@endsection
