@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Polls for {{ $event->name }}</h1>

    @if ($event->polls->isEmpty())
        <p>No polls have been created for this event.</p>
    @else
        @foreach ($event->polls as $poll)
            <div class="card mb-4">
                <div class="card-header">
                    <h4>{{ $poll->question }}</h4>
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($poll->options as $option)
                            <li>{{ $option->option_text }} ({{ $option->votes }} votes)</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    @endif

    @if (Auth::id() === $event->organizer_id)
        <a href="{{ route('polls.create', $event->event_id) }}" class="btn btn-success mt-3">Create a Poll</a>
    @endif
</div>
@endsection
