@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Report Event: {{ $event->name }}</h1>

    <form action="{{ route('createReport') }}" method="POST">
        @csrf
        <input type="hidden" name="event_id" value="{{ $event->event_id }}">
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <div class="form-group">
            <label for="reason">Reason</label>
            <textarea id="reason" name="reason" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-danger">Submit Report</button>
    </form>

    <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-secondary">Back to Event</a>
</div>
@endsection