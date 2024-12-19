@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Events</h1>
    
    <!-- Barra de pesquisa por nome do evento -->
    <form action="{{ route('events.index') }}" method="GET" class="mb-3">
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

    <!-- Filtro por tags -->
    <div class="mb-3">
        <h5>Filter by Tag:</h5>
        <div>
            @foreach($tags as $tagItem)
                <a 
                    href="{{ route('events.index', ['tag' => $tagItem->name, 'search' => request('search')]) }}" 
                    class="badge {{ request('tag') === $tagItem->name ? 'bg-primary' : 'bg-secondary' }} text-decoration-none p-2 w-100 text-center mb-2"
                >
                    {{ $tagItem->name }}
                </a>
            @endforeach
        </div>
    </div>
    <br>
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
                        <td>{{ $event->description  }}</td>  
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
