@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Events</h1>
    <form action="{{ route('events.search') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input 
            type="text" 
            name="search" 
            class="form-control" 
            placeholder="Search events by name" 
            value="{{ request('search') }}"
        >
        <button type="submit" class="btn btn-primary">Search</button>
    </div>
</form>

    
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>

    
    @if($events->isEmpty())
        <p>No events found.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->description }}</td>
                        <td>{{ $event->date }}</td>
                        <td>{{ $event->venue->name }}</td>
                        <td>
                            <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-primary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
