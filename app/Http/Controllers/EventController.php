<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Correctly importing Auth facade
use Illuminate\Support\Facades\DB; // Import the DB facade


class EventController extends Controller
{

    public function dashboard()
    {
        $user = Auth::user();

        $myEvents = Event::where('organizer_id', $user->user_id)->get();
        $participatingEvents = $user->attendingEvents()->with('venue', 'organizer')->get();

        return view('/dashboard/userAuthenticatedDashboard', [
            'myEvents' => $myEvents,
            'participatingEvents' => $participatingEvents,
        ]);
    }

    public function joinEvent($event_id)
    {
        $event = Event::findOrFail($event_id);

        if ($event->attendees->contains(Auth::id())) {
            return redirect()->route('events.show', $event_id)
                ->with('error', 'You are already part of this event.');
        }

        DB::table('attends')->insert([
            'user_id' => Auth::id(),
            'event_id' => $event_id,
            'joined_at' => now(),
        ]);

        return redirect()->route('events.show', $event_id)
            ->with('success', 'You have joined the event!');
    }

    public function leaveEvent($event_id)
    {
        $event = Event::findOrFail($event_id);

        if (!$event->attendees->contains(Auth::id())) {
            return redirect()->route('dashboard')->with('error', 'You are not part of this event.');
        }

        DB::table('attends')
            ->where('user_id', Auth::id())
            ->where('event_id', $event_id)
            ->delete();

        return redirect()->route('dashboard')->with('success', 'You have left the event.');
    }

   /**
     * Manage events created by the authenticated user.
     */
    public function manage()
    {
        $events = Event::where('organizer_id', auth()->id())->get();
        return view('events.manage', compact('events'));
    }

    /**
     * Invite a user to an event.
     */
    public function invite(Request $request, $event_id)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
    
        $user = User::where('email', $validated['email'])->firstOrFail();
    
        $existingInvite = Invite::where('event_id', $event_id)
            ->where('user_id', $user->user_id)
            ->first();
    
        if ($existingInvite) {
            return back()->withErrors(['email' => 'This user has already been invited.']);
        }
    
        // Create the invite
        Invite::create([
            'event_id' => $event_id,
            'user_id' => $user->user_id,
            'status' => 'pending',
        ]);
    
        return back()->with('success', 'Invitation sent!');
    }
    

    /**
     * Accept an invitation.
     */
    public function acceptInvitation($event_id)
    {
        // Find the invite
        $invite = Invite::where('event_id', $event_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    
        // Update invite status
        $invite->update(['status' => 'accepted']);
    
        // Add the user to the attendees
        DB::table('attends')->insert([
            'user_id' => auth()->id(),
            'event_id' => $event_id,
            'joined_at' => now(),
        ]);
    
        return back()->with('success', 'You have accepted the invitation and are now attending the event.');
    }
    
    
    
    public function attending()
    {
        $events = auth()->user()->attendingEvents()->with('venue', 'organizer')->get();
    
        return view('events.attending', compact('events'));
    }
    
    
    


    /**
     * Reject an invitation.
     */
    public function rejectInvitation($event_id)
    {
        // Find the invite using the correct primary key
        $invite = Invite::where('event_id', $event_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    
        $invite->update(['status' => 'declined']);
    
        return back()->with('success', 'You have declined the invitation.');
    }
    

    /**
     * List invitations for the authenticated user.
     */
    public function listInvitations()
    {
        $invitations = Invite::with('event.organizer')
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->get();
    
        return view('events.invitations', compact('invitations'));
    }
    
    
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
        // Validate that the event_id is numeric
        if (!is_numeric($event_id)) {
            abort(404, 'Invalid event ID');
        }
    
        // Fetch event by its primary key
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
