@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-primary fw-bold">My Dashboard</h1>

    <!-- Navigation bar -->
    @include('partials.nav')

    <!-- My Events Section -->
    <h2 class="mt-4 text-secondary ">My Events</h2>
    @if($myEvents->isEmpty())
        <p>You have no events.</p>
    @else
        <table class="table table-striped" style="padding-bottom: 4rem;">
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
                            <a href="{{ route('events.show', $event->event_id) }}" class="btn btn-primary btn-sm">View</a>
                            <form action="{{ route('events.destroy', $event->event_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="delete btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Events I'm Attending Section -->
    <h2 class="mt-4 text-secondary">Events I’m Attending</h2>
    @if($participatingEvents->isEmpty())
        <p>You are not attending any events.</p>
    @else
        <table class="table table-striped">
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
                            <form action="{{ route('events.leave', $event->event_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="delete btn btn-danger btn-sm">Leave</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
