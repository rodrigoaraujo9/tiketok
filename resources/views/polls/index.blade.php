@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Polls for {{ $event->name }}</h1>

    @if ($event->polls->isEmpty())
        <p>No polls have been created for this event.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Options</th>
                    <th>Votes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($event->polls as $poll)
                    <tr>
                        <td>{{ $poll->question }}</td>
                        <td>
                            <ul>
                                @foreach ($poll->options as $option)
                                    <li>{{ $option->option_text }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <ul>
                                @foreach ($poll->options as $option)
                                    <li>{{ $option->votes }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <ul>
                                @foreach ($poll->options as $option)
                                    <li>
                                        @if (!$poll->userHasVoted(Auth::id()))
                                            <!-- Formulário para votar -->
                                            <form action="{{ route('polls.vote', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="option_id" value="{{ $option->option_id }}">
                                                <button type="submit" class="btn btn-primary btn-sm">Vote</button>
                                            </form>
                                        @else
                                            <!-- Formulários para alterar ou remover o voto -->
                                            <form action="{{ route('polls.updateVote', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="option_id" value="{{ $option->option_id }}">
                                                <button type="submit" class="btn btn-warning btn-sm">Change</button>
                                            </form>

                                            <form action="{{ route('polls.deleteVote', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                            </form>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <br>

    <!-- Botão para criar uma nova poll (apenas para o organizador) -->
    @if (Auth::id() === $event->organizer_id)
        <a href="{{ route('polls.create', $event->event_id) }}" class="btn btn-success mt-3">Create a Poll</a>
    @endif
</div>
@endsection
