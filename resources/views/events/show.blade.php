@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $event->name }}</h1>

    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</p>
    <p><strong>Description:</strong> {{ $event->description }}</p>
    <p><strong>Venue:</strong> {{ $event->venue->name ?? 'N/A' }}</p>
    <p><strong>Location:</strong> {{ $event->venue->location ?? 'N/A' }}</p>
    <p><strong>Capacity:</strong> {{ $event->venue->max_capacity ?? 'N/A' }}</p>

    <!-- Mensagens de Feedback -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Botão "Join Event" -->
    @if (!$event->participants->contains(Auth::id()))
        <form action="{{ route('events.join', $event->event_id) }}" method="POST">
            @csrf
            <button class="btn btn-primary">Join Event</button>
        </form>
    @else
        <p class="text-success">You are already part of this event.</p>
    @endif

    <!-- Botão para Voltar -->
    <a href="{{ route('events.index') }}" class="btn btn-secondary mt-3">Back to All Events</a>
</div>
@endsection
