@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chat for Event {{ $event_id }}</h1>

    <div class="chat-messages">
        @foreach($messages as $message)
            <div class="mb-2 {{ Auth::id() === $message->user_id ? 'text-right' : 'text-left' }}">
                <strong>{{ $message->user ? $message->user->name : 'Unknown User' }}:</strong>
                <p class="{{ Auth::id() === $message->user_id ? 'bg-primary text-white' : 'bg-light' }} p-2 rounded">
                    {{ $message->message }}
                </p>
                <small class="text-muted mb-0 timestamp {{ Auth::id() === $message->user_id ? 'float-right' : 'float-left' }}">
                    {{ $message->created_at->format('d/m/Y H:i') }}
                </small>
            </div>
        @endforeach
    </div>

    <form action="{{ route('message.store', $event_id) }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea name="message" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Send</button>
    </form>
</div>
@endsection