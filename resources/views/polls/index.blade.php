@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Polls for {{ $event->name }}</h1>

    @if ($event->polls->isEmpty())
        <p>No polls have been created for this event.</p>
    @else
        @foreach ($event->polls as $poll)
            <!-- Título da Poll -->
            <div class="mb-4">
                <h3>{{ $poll->question }}</h3>

                <!-- Tabela com opções de voto e ações -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Option</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $userVote = $poll->votes()->where('user_id', Auth::id())->first();
                        @endphp

                        @foreach ($poll->options as $option)
                            <tr>
                                <td>{{ $option->option_text }}</td>
                                <td>
                                    @if (!$userVote)
                                        <!-- Botão para votar -->
                                        <form action="{{ route('polls.vote', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="option_id" value="{{ $option->option_id }}">
                                            <button type="submit" class="btn btn-primary btn-sm">Vote</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Destaque do voto e botão para remover voto -->
                @if ($userVote)
                    <div class="alert alert-info text-center">
                        <h4>Your Vote: <strong>{{ $userVote->option->option_text }}</strong></h4>
                        <form action="{{ route('polls.deleteVote', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove Vote</button>
                        </form>
                    </div>
                @endif

                <!-- Ação para deletar a poll (somente para o organizador) -->
                @if (Auth::id() === $event->organizer_id)
                    <div class="mt-3">
                        <form action="{{ route('polls.destroy', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete Poll</button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <br>

    <!-- Botão para criar uma nova poll (apenas para o organizador) -->
    @if (Auth::id() === $event->organizer_id)
        <a href="{{ route('polls.create', $event->event_id) }}" class="btn btn-success mt-3">Create a Poll</a>
    @endif
</div>
@endsection
