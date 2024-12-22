@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manage Attendees for {{ $event->name }}</h1>
    <a href="{{ route('events.index') }}" class="btn btn-secondary mb-3">Back to Events</a>

    @if ($event->attendees->isEmpty())
        <p>No attendees found for this event.</p>
    @else
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($event->attendees as $attendee)
                    <tr>
                        <td>{{ $attendee->name }}</td>
                        <td>{{ $attendee->email }}</td>
                        <td class="text-center">
                            <form action="{{ route('events.attendees.remove', $event->event_id) }}" method="POST" style="display: inline;">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $attendee->user_id }}">
                                <button type="submit" class="btn btn-danger btn-sm confirmation-button" data-confirm="Are you sure you want to remove this attendee?">
                                    Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
