@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create a Poll for {{ $event->name }}</h1>

    <form action="{{ route('polls.store', $event->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="question">Poll Question</label>
            <input type="text" name="question" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="options">Options</label>
            <div id="options-container">
                <input type="text" name="options[]" class="form-control mb-2" required>
                <input type="text" name="options[]" class="form-control mb-2" required>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addOption()">Add Option</button>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Create Poll</button>
    </form>
</div>

<script>
function addOption() {
    const container = document.getElementById('options-container');
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'options[]';
    input.className = 'form-control mb-2';
    input.required = true;
    container.appendChild(input);
}
</script>
@endsection
