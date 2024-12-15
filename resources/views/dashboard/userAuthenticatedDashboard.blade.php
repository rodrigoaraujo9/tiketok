@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4 text-primary fw-bold">My Dashboard</h1>

    <!-- Navigation bar -->
    @include('partials.nav')

    <!-- My Events Section -->
    @if (Auth::check() && !Auth::user()->isAdmin())
    <h2 class="mt-4 text-secondary">Events</h2>

    <!-- Busiest Events -->
    <h3 class="mt-3 text-primary">Hosting</h3>
    @if($busiestEvents->isEmpty())
        <p>You have no events.</p>
    @else
        <table class="table table-striped" style="padding-bottom: 4rem;">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Participants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($busiestEvents as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->date }}</td>
                        <td>{{ $event->participants_count ?? 0 }}</td>
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

    <!-- Closest-to-Happening Events -->

    <!-- Events I'm Attending Section -->

<!-- Busiest Attending Events -->


<!-- Closest-to-Happening Attending Events -->
<h3 class="mt-3 text-primary">Attending</h3>
@if($closestAttendingEvents->isEmpty())
    <p>You are not attending any upcoming events.</p>
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
            @foreach ($closestAttendingEvents as $event)
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

    @endif
</div>
@endsection
