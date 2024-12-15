@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Polls for {{ $event->name }}</h1>

    @if ($event->polls->isEmpty())
        <p>No polls have been created for this event.</p>
    @else
        <br>
        @foreach ($event->polls as $poll)
            <div class="card mb-4">
                <div class="card-header">
                    <h4>{{ $poll->question }}</h4>
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($poll->options as $option)
                            <li>
                                {{ $option->option_text }} ({{ $option->votes }} votes)
                                
                                <!-- Verifica se o usuário já votou -->
                                @if (!$poll->userHasVoted(Auth::id()))
                                    <!-- Formulário para votar -->
                                    <form action="{{ route('polls.vote', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="option_id" value="{{ $option->option_id }}">
                                        <button type="submit" class="btn btn-primary btn-sm">Vote</button>
                                    </form>

                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <!-- Mensagem de feedback se o usuário já votou -->
                    @if ($poll->userHasVoted(Auth::id()))
                        <p class="text-success mt-3">You already voted in this poll.</p>
                    @endif

                    <!-- Botão de excluir (somente para o organizador) -->
                    @if (Auth::id() === $event->organizer_id)
                        <form action="{{ route('polls.destroy', ['event_id' => $event->event_id, 'poll_id' => $poll->poll_id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm mt-2">Delete Poll</button>
                        </form>
                        <br>
                    @endif
                    <br>
                </div>
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
