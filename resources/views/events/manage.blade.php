@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manage Your Events</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Back to Dashboard</a>

    @if ($events->isEmpty())
        <p>You have not created any events yet.</p>
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
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->date }}</td>
                        <td>
                            <!-- Form to Invite User -->
                            <form action="{{ route('events.invite', $event->event_id) }}" method="POST">
                                @csrf
                                <input type="email" name="email" class="form-control" placeholder="Invite user by email" required>
                                <button class="btn btn-primary mt-2">Send Invite</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
