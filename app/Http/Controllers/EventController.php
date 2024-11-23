<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Event;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


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
        // Validate and save the new event
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'postal_code' => 'required|string|max:10',
            'max_event_capacity' => 'required|integer|min:1',
            'country' => 'required|string',
            'visibility' => 'required|boolean',
            'venue_id' => 'required|exists:venues,id',
        ]);

        $validated['organizer_id'] = auth()->id();

        $event = Event::create($validated);

        // Redirect to the event's detail page
        return redirect()->route('dashboard')->with('success', 'Event created successfully!');

    }

    /**
     * Display the form for creating a new event.
     */
    
    public function create()
    {
        $venues = Venue::all(); // Retrieve all venues to populate the dropdown
        return view('events.create', compact('venues'));
    }

    /**
     * Display the form for editing an existing event.
     */
    public function edit($event_id)
    {
        $event = Event::findOrFail($event_id);

        return view('events.edit', compact('event')); // Add a form for event editing
    }

    /**
     * Update an existing event in the database.
     */
    public function update(Request $request, $event_id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'postal_code' => 'required|string|max:10',
            'max_event_capacity' => 'required|integer|min:1',
            'country' => 'required|string',
            'visibility' => 'required|boolean',
            'venue_id' => 'required|exists:venues,id',
        ]);

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
        $event = Event::findOrFail($event_id);

        if ($event->organizer_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to delete this event.');
        }

        $event->delete();

        return redirect()->route('dashboard')->with('success', 'Event deleted successfully!');
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Meus eventos
        $myEvents = Event::where('organizer_id', $user->id)->get();


        // Eventos que estou participando
        $participatingEvents = $user->participatingEvents ?? collect();

        return view('userAuthenticatedDashboard', [
            'myEvents' => $myEvents,
            'participatingEvents' => $participatingEvents,
        ]);
    }

}
