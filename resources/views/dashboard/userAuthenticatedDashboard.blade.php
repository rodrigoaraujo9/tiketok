@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Dashboard</h1>

    <h2>My Events</h2>
    @if($myEvents->isEmpty())
        <p>You have no events.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($myEvents as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->date }}</td>
                        <td>
                            <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-primary">View</a>
                            <form action="{{ route('events.destroy', $event->event_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Events I’m Attending</h2>
    @if($participatingEvents->isEmpty())
        <p>You are not attending any events.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participatingEvents as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->date }}</td>
                        <td>
                            <form action="{{ route('events.leave', $event->event_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Leave</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
