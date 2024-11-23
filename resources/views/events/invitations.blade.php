@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Invitations</h1>

    @if ($invitations->isEmpty())
        <p>You have no invitations.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Organizer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($invitations as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->date }}</td>
                        <td>{{ $event->organizer->name }}</td>
                        <td>
                            <!-- Accept Invitation -->
                            <form action="{{ route('events.accept', ['id' => $event->event_id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Accept</button>
                            </form>

                            <!-- Reject Invitation -->
                            <form action="{{ route('events.reject', ['id' => $event->event_id]) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger">Decline</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
