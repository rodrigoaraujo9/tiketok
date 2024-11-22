@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create a New Event</h1>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Event Creation Form -->
    <form action="{{ route('events.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Event Name</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" required></textarea>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="datetime-local" id="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="postal_code" class="form-label">Postal Code</label>
            <input type="text" id="postal_Code" name="postal_code" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="max_event_capacity" class="form-label">Maximum Capacity</label>
            <input type="number" id="max_event_capacity" name="max_event_capacity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <input type="text" id="country" name="country" class="form-control" required>
        </div>

        <div class="mb-3">
    <label for="visibility" class="form-label">Visibility</label>
    <select id="visibility" name="visibility" class="form-control" required>
        <option value="1" {{ old('visibility', $event->visibility ?? true) ? 'selected' : '' }}>Public</option>
        <option value="0" {{ old('visibility', $event->visibility ?? false) ? 'selected' : '' }}>Private</option>
    </select>
</div>




        <div class="mb-3">
            <label for="venue_id" class="form-label">Venue</label>
            <select id="venue_id" name="venue_id" class="form-control" required>
                @foreach($venues as $venue)
                    <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>
</div>
@endsection
