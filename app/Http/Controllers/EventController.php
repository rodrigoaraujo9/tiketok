<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index()
    {
        // Retrieve all events, including related venues and organizers
        $events = Event::with(['venue', 'organizer'])->get();

        // Return the view with the events data
        return view('events.index', compact('events'));
    }

    /**
     * Show details for a specific event.
     */
    public function show($event_id)
    {
        // Fetch event by its primary key (event_id)
        $event = Event::with(['venue', 'organizer'])->findOrFail($event_id);

        return view('events.show', compact('event'));
    }

    /**
     * Store a newly created event in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'postal_code' => 'required|string|max:10',
            'max_event_capacity' => 'required|integer|min:1',
            'country' => 'required|string',
            'visibility' => 'required|in:public,private', // Ensure enum values
            'venue_id' => 'required|exists:venues,venue_id',
        ]);
    
        $validated['organizer_id'] = auth()->id();
    
        $event = Event::create($validated);
    
        return redirect()->route('events.show', $event->event_id)
            ->with('success', 'Event created successfully!');
    }
    
    /**
     * Display the form for creating a new event.
     */
    public function create()
    {
        // Fetch all venues to populate the venue dropdown
        $venues = Venue::all();
        return view('events.create', compact('venues'));
    }

    /**
     * Display the form for editing an existing event.
     */
    public function edit($event_id)
    {
        // Fetch the event to edit
        $event = Event::findOrFail($event_id);

        // Fetch all venues for the venue dropdown
        $venues = Venue::all();

        return view('events.edit', compact('event', 'venues'));
    }

    /**
     * Update an existing event in the database.
     */
    public function update(Request $request, $event_id)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'postal_code' => 'required|string|max:10',
            'max_event_capacity' => 'required|integer|min:1',
            'country' => 'required|string',
            'visibility' => 'required|boolean',
            'venue_id' => 'required|exists:venues,venue_id', // Corrected column name
        ]);

        // Find and update the event
        $event = Event::findOrFail($event_id);
        $event->update($validated);

        return redirect()->route('events.show', $event->event_id)
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Delete an event from the database.
     */
    public function destroy($event_id)
    {
        // Find and delete the event
        $event = Event::findOrFail($event_id);
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
