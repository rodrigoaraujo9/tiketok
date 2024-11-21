<?php

namespace App\Http\Controllers;

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
    public function show($id)
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
        // Validate and save the new event
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'postalCode' => 'required|string|max:10',
            'maxEventCapacity' => 'required|integer|min:1',
            'country' => 'required|string',
            'visibility' => 'required|in:public,private',
            'venue_id' => 'required|exists:venues,venue_id',
        ]);

        $validated['organizer_id'] = auth()->id();

        $event = Event::create($validated);

        // Redirect to the event's detail page
        return redirect()->route('events.show', $event->event_id)
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the form for creating a new event.
     */
    public function create()
    {
        return view('events.create'); // Add a form for event creation
    }

    /**
     * Display the form for editing an existing event.
     */
    public function edit($id)
    {
        $event = Event::findOrFail($event_id);
        return view('events.edit', compact('event')); // Add a form for event editing
    }

    /**
     * Update an existing event in the database.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'postalCode' => 'required|string|max:10',
            'maxEventCapacity' => 'required|integer|min:1',
            'country' => 'required|string',
            'visibility' => 'required|in:public,private',
            'venue_id' => 'required|exists:venues,venue_id',
        ]);

        $event = Event::findOrFail($id);
        $event->update($validated);

        return redirect()->route('events.show', $event->event_id)
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Delete an event from the database.
     */
    public function destroy($id)
    {
        $event = Event::findOrFail($event_id);
        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
