@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My dashboard</h1>

    <div class="container">
    <h2>My Events</h2>
    
    @if($myEvents->isEmpty())
        <p>No events found.</p>
    @else
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($myEvents as $event)
                <tr>
                    <td>
                        <a href="{{ route('events.show', $event->event_id) }}" class="text-decoration-none">
                            {{ $event->name }}
                        </a>
                    </td>
                    <td>{{ $event->description }}</td>
                    <td>{{ $event->date }}</td>
                    <td>
                        <!-- Form for Edit -->
                        <form action="{{ route('events.edit', $event->event_id) }}" method="GET" style="display:inline;">
                            <button class="btn btn-warning btn-sm">Edit</button>
                        </form>

                        <!-- Form for Delete -->
                        <form action="{{ route('events.destroy', $event->event_id) }}" method="POST" style="display:inline; margin-left: 10px;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @endif
    
    <a href="{{ route('events.create') }}" class="btn btn-primary mb-3">+ Create New Event</a>
    </div>


    <br>
    <br>
    <br>

    <div class="section">
    <h2>Events that I´m a part of</h2>
    <ul>
        @forelse ($participatingEvents as $event)
            <li>
                <h3>{{ $event->title }}</h3>
                <p>{{ $event->description }}</p>
                <p>Data: {{ $event->date }}</p>
            </li>
        @empty
            <li>Nenhum evento encontrado.</li>
        @endforelse
    </ul>
    </div>     
</div>
@endsection
