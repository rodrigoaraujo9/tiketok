@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My dashboard</h1>
    <br>

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

    <div class="container">
        <h2>Events Joined By Me</h2>
        
        @if($participatingEvents->isEmpty())
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
                    @foreach ($participatingEvents as $event)
                        <tr>
                            <td>
                                <a href="{{ route('events.show', $event->event_id) }}" class="text-decoration-none">
                                    {{ $event->name }}
                                </a>
                            </td>
                            <td>{{ $event->description }}</td>
                            <td>{{ $event->date }}</td>
                            <td>
                                <!-- Botão para deixar o evento -->
                                <form action="{{ route('events.leave', $event->event_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Leave Event</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Link estilizado para buscar mais eventos -->
        <a href="{{ route('events.index') }}" class="btn btn-primary mb-3">+ Search for Events</a>
    </div>




@endsection
