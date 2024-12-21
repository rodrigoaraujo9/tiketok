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
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" required>{{ old('description') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="datetime-local" id="date" name="date" class="form-control" value="{{ old('date') }}" required>
        </div>

        <div class="mb-3">
            <label for="postal_code" class="form-label">Postal Code</label>
            <input type="text" id="postal_code" name="postal_code" class="form-control" value="{{ old('postal_code') }}" required>
        </div>

        <div class="mb-3">
            <label for="max_event_capacity" class="form-label">Maximum Capacity</label>
            <input type="number" id="max_event_capacity" name="max_event_capacity" class="form-control" value="{{ old('max_event_capacity') }}" required>
        </div>

        <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <input type="text" id="country" name="country" class="form-control" value="{{ old('country') }}" required>
        </div>

        <div class="mb-3">
            <label for="visibility" class="form-label">Visibility</label>
            <select id="visibility" name="visibility" class="form-control" required>
                <option value="public" {{ old('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                <option value="private" {{ old('visibility') == 'private' ? 'selected' : '' }}>Private</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="venue_id" class="form-label">Venue</label>
            <select id="venue_id" name="venue_id" class="form-control" required>
                <option value="">Select a Venue</option>
                @foreach($venues as $venue)
                    <option value="{{ $venue->venue_id }}" {{ old('venue_id') == $venue->venue_id ? 'selected' : '' }}>
                        {{ $venue->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
    <label for="tag" class="form-label">Tag</label>
    <select id="tag_id" name="tag_id" class="form-control" required>
        <option value="">Select a Tag</option>
        @foreach($tags as $tag)
            <option value="{{ $tag->tag_id }}" {{ old('tag_id') == $tag->tag_id ? 'selected' : '' }}>
                {{ $tag->name }}
            </option>
        @endforeach
    </select>
</div>


        <button type="submit" class="btn btn-primary">Create Event</button>
    </form>
</div>
@endsection
