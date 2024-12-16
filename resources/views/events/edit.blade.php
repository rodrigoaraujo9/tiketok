@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 text-primary">Edit Event</h1>
    <a href="{{ route('events.manage') }}" class="btn btn-secondary mb-3">Back to Manage Events</a>

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

    <!-- Edit Event Form -->
    <form action="{{ route('events.update', $event->event_id) }}" method="POST" class="form">
        @csrf
        @method('PUT')

        <!-- Event Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Event Name</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="{{ old('name', $event->name) }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="4" required>{{ old('description', $event->description) }}</textarea>
        </div>

        <!-- Date -->
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" id="date" name="date" class="form-control" 
                   value="{{ old('date', $event->date->format('Y-m-d')) }}" required>
        </div>

        <!-- Postal Code -->
        <div class="mb-3">
            <label for="postal_code" class="form-label">Postal Code</label>
            <input type="text" id="postal_code" name="postal_code" class="form-control" 
                   value="{{ old('postal_code', $event->postal_code) }}" required>
        </div>

        <!-- Max Capacity -->
        <div class="mb-3">
            <label for="max_event_capacity" class="form-label">Max Capacity</label>
            <input type="number" id="max_event_capacity" name="max_event_capacity" class="form-control" 
                   value="{{ old('max_event_capacity', $event->max_event_capacity) }}" required>
        </div>

        <!-- Country -->
        <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <input type="text" id="country" name="country" class="form-control" 
                   value="{{ old('country', $event->country) }}" required>
        </div>

        <!-- Visibility -->
        <div class="mb-3">
            <label for="visibility" class="form-label">Visibility</label>
            <select id="visibility" name="visibility" class="form-select" required>
                <option value="public" {{ old('visibility', $event->visibility) === 'public' ? 'selected' : '' }}>Public</option>
                <option value="private" {{ old('visibility', $event->visibility) === 'private' ? 'selected' : '' }}>Private</option>
            </select>
        </div>

        <!-- Venue -->
        <div class="mb-3">
            <label for="venue_id" class="form-label">Venue</label>
            <select id="venue_id" name="venue_id" class="form-select" required>
                @foreach($venues as $venue)
                    <option value="{{ $venue->venue_id }}" {{ old('venue_id', $event->venue_id) == $venue->venue_id ? 'selected' : '' }}>
                        {{ $venue->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Event</button>
    </form>
</div>
@endsection
