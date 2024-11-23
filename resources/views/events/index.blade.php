@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Events</h2>
    
    @if ($events->isEmpty())
        <p>No events available.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <!-- Título -->
                        <td>{{ $event->name }}</td>
                        
                        <!-- Data -->
                        <td>{{ \Carbon\Carbon::parse($event->date)->format('d/m/Y') }}</td>
                        
                        <!-- Link "Saber Mais" -->
                        <td>
                            <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-link text-decoration-none">
                                Saber Mais →
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>


@endsection
