@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Polls for {{ $event->name }}</h1>

    @if ($event->polls->isEmpty())
        <p>No polls have been created for this event.</p>
    @else
        @foreach ($event->polls as $poll)
            <div class="mb-5">
                <br>
                <h3 class="fw-bold">{{ $loop->iteration }}. {{ $poll->question }}</h3>

                <table class="table table-bordered text-center align-middle">
                    <thead>
                        <tr>
                            <th class="text-start">Option</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $userVote = $poll->votes()->where('user_id', Auth::id())->first();
                        @endphp

                        @foreach ($poll->options as $option)
                            <tr>
                                <td class="text-start fs-4 fw-bold">
                                    {{ $option->option_text }}
                                    <span class="text-muted fs-5">({{ $option->votes }} votes)</span>
                                </td>

                                <td>
                                    @if (!$userVote)
                                        <form action="{{ route('polls.vote', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="option_id" value="{{ $option->option_id }}">
                                            <button type="submit" class="btn btn-primary btn-sm">Vote</button>
                                        </form>
                                    @elseif ($userVote->option_id == $option->option_id)
                                        <form action="{{ route('polls.deleteVote', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm confirmation-button" data-confirm="Are you sure you want to remove your vote?">
                                                Remove Vote
                                            </button>
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

                @if (Auth::id() === $event->organizer_id)
                    <div class="mt-3">
                        <form action="{{ route('polls.destroy', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger confirmation-button" data-confirm="Are you sure you want to delete this poll?">
                                Delete Poll
                            </button>
                        </form>
                    </div>
                    <br>
                @endif
            </div>
        @endforeach
    @endif

    @if (Auth::id() === $event->organizer_id)
        <a href="{{ route('polls.create', $event->event_id) }}" class="btn btn-success mt-3">Create a Poll →</a>
    @endif
</div>
@endsection
