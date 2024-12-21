@extends('layouts.app')

@section('content')
<div id="content">
    <form action="{{ route('events.update', $event->event_id) }}" method="POST">
        @csrf
        @method('PUT')

        <h1>Edit Event</h1>

        <label for="name">Event Name</label>
        <input type="text" id="name" name="name" value="{{ old('name', $event->name) }}" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" required>{{ old('description', $event->description) }}</textarea>

        <label for="date">Date</label>
        <input type="date" id="date" name="date" value="{{ old('date', $event->date->format('Y-m-d')) }}" required>

        <label for="postal_code">Postal Code</label>
        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $event->postal_code) }}" required>

        <label for="max_event_capacity">Max Capacity</label>
        <input type="number" id="max_event_capacity" name="max_event_capacity" value="{{ old('max_event_capacity', $event->max_event_capacity) }}" required>

        <label for="country">Country</label>
        <input type="text" id="country" name="country" value="{{ old('country', $event->country) }}" required>

        <label for="visibility">Visibility</label>
        <select id="visibility" name="visibility" required>
            <option value="public" {{ old('visibility', $event->visibility) === 'public' ? 'selected' : '' }}>Public</option>
            <option value="private" {{ old('visibility', $event->visibility) === 'private' ? 'selected' : '' }}>Private</option>
        </select>

        <label for="venue_id">Venue</label>
        <select id="venue_id" name="venue_id" required>
            @foreach($venues as $venue)
                <option value="{{ $venue->venue_id }}" {{ old('venue_id', $event->venue_id) == $venue->venue_id ? 'selected' : '' }}>{{ $venue->name }}</option>
            @endforeach
        </select>

        <label for="tag_id">Tag</label>
        <select id="tag_id" name="tag_id" required>
            @foreach($tags as $tag)
                <option value="{{ $tag->tag_id }}" {{ old('tag_id', $tag->tag_id) == $tag->tag_id ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-danger btn-sm">Update Event</button>
    </form>
</div>
@endsection
