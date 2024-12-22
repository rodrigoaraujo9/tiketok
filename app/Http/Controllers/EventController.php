<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Tag;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Invite;
use App\Models\User;
use Illuminate\Support\Facades\Auth; // Correctly importing Auth facade
use Illuminate\Support\Facades\DB; // Import the DB facade
use App\Models\Comment;
use Carbon\Carbon;


class EventController extends Controller
{

    
    protected static function booted()
{
    static::addGlobalScope('exclude_deleted', function ($query) {
        $query->where('is_deleted', false);
    });
}


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
        $event = Event::with('attendees')->findOrFail($event_id);
    
        // Check if the user is already attending
        if ($event->attendees->contains(Auth::id())) {
            return redirect()->route('events.show', $event_id)
                ->with('error', 'You are already part of this event.');
        }
    
        // Check if the event has reached its maximum capacity
        if ($event->attendees->count() >= $event->max_event_capacity) {
            return redirect()->route('events.show', $event_id)
                ->with('error', 'The event has reached its maximum capacity.');
        }
    
        // Add the user to the attendees
        $event->attendees()->attach(Auth::id(), ['joined_at' => now()]);
    
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
    public function manage(Request $request)
    {
        $events = Event::where('organizer_id', auth()->id())
                       ->where('is_deleted', false)
                       ->paginate(5);
    
        if ($request->ajax()) {
            $html = view('partials.manage_table', compact('events'))->render();
            return response()->json(['html' => $html]);
        }
    
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
    
    
    
    public function attending(Request $request)
    {
        // Fetch paginated events with related data
        $events = auth()->user()->attendingEvents()
                     ->with('venue', 'organizer')
                     ->paginate(10);
    
        // For AJAX requests, return only the table HTML
        if ($request->ajax()) {
            $html = view('partials.attending_table', compact('events'))->render();
            return response()->json(['html' => $html]);
        }
    
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
    public function listInvitations(Request $request)
    {
        // Fetch paginated invitations for the authenticated user
        $invitations = Invite::with('event.organizer')
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->paginate(10);
    
        // For AJAX requests, return only the table HTML
        if ($request->ajax()) {
            $html = view('partials.invitations_table', compact('invitations'))->render();
            return response()->json(['html' => $html]);
        }
    
        return view('events.invitations', compact('invitations'));
    }
    
    
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $query = Event::query();
    
        // Filter by search term
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->input('search') . '%');
        }
    
        // Filter by tag
        if ($request->filled('tag')) {
            $tag = Tag::where('name', $request->input('tag'))->first();
            if ($tag) {
                $query->where('tag_id', $tag->tag_id);
            } else {
                // If the tag doesn't exist, return no events
                $query->whereRaw('1 = 0');
            }
        }
    
        // Paginate events and ensure query parameters are preserved
        $events = $query->with('venue')->paginate(10);
        $events->appends($request->only('search', 'tag'));
    
        // Fetch all tags for filtering
        $tags = Tag::all();
    
        // Handle AJAX requests
        if ($request->ajax()) {
            $html = view('partials.events_table', compact('events'))->render();
            return response()->json(['html' => $html]);
        }
    
        return view('events.index', compact('events', 'tags'));
    }
    
      
    
    

    
    /**
     * Show details for a specific event.
     */
    public function show($event_id)
    {
        // Validar o ID do evento
        if (!is_numeric($event_id)) {
            abort(404, 'Invalid event ID');
        }

        // Buscar o evento com os attendees e polls
        $event = Event::with([
            'venue',
            'organizer',
            'attendees',
            'polls.options' // Carregar as polls e as suas opções associadas
        ])->findOrFail($event_id);

        // Verificar se o utilizador autenticado está na lista de attendees
        $hasJoined = auth()->check() && $event->attendees->contains(auth()->id());

        return view('events.show', compact('event', 'hasJoined'));
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
            'visibility' => 'required|in:public,private', // Validate visibility
            'venue_id' => 'required|exists:venues,venue_id',
            'tag_id' => 'required|exists:tags,tag_id',
        ]);
    
        $validated['organizer_id'] = auth()->id();
    
        Event::create($validated);
        
        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }
    
    public function update(Request $request, $event_id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'postal_code' => 'required|string|max:10',
            'max_event_capacity' => 'required|integer|min:1',
            'country' => 'required|string',
            'visibility' => 'required|in:public,private', // Validate visibility
            'venue_id' => 'required|exists:venues,venue_id',
            'tag_id' => 'required|exists:tags,tag_id',
        ]);
    
        $event = Event::findOrFail($event_id);
        $event->update($validated);
    
        return redirect()->route('events.show', $event_id)->with('success', 'Event updated successfully!');
    }
    
    
    
    /**
     * Display the form for creating a new event.
     */
    public function create()
    {
        // Fetch all venues and tags to populate the venue/tags dropdown
        $venues = Venue::all();
        $tags = Tag::all();

        return view('events.create', compact('venues','tags'));
    }

    /**
     * Display the form for editing an existing event.
     */
    public function edit($event_id)
    {   
        
        $event = Event::findOrFail($event_id);
        $event->date = Carbon::parse($event->date); 
    
        $venues = Venue::all();
        $tags = Tag::all(); 
        return view('events.edit', compact('event', 'venues', 'tags'));
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

    public function adminDestroy($event_id)
    {
        {
            $event = Event::findOrFail($event_id);

            $delete_reports = DB::table('reports')->where('event_id', $event_id)->get();
            foreach ($delete_reports as $delete_report) {
                Report::destroy($delete_report->report_id);
            }
            $event->delete();
    
            return redirect()->route('events.index')
                ->with('success', 'Event deleted successfully!');
        }
    }
    
    public function search(Request $request)
{
    $search = $request->input('search');
    $tag = $request->input('tag');

    // Query de eventos
    $query = Event::query();

    if ($search) {
        $query->where('name', 'LIKE', '%' . $search . '%');
    }

    if ($tag) {
        $query->whereHas('tags', function ($q) use ($tag) {
            $q->where('name', $tag);
        });
    }

    // Obter os eventos filtrados
    $events = $query->get();

    // Obter todas as tags para exibição
    $tags = Tag::all();

    return view('events.index', compact('events', 'tags'));
}



    
    public function attendees($eventId)
    {
        $event = Event::with('attendees')->findOrFail($eventId);
        if (auth()->id() !== $event->organizer_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('events.attendees', compact('event'));
    }

    public function removeAttendee(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        if (auth()->id() !== $event->organizer_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate(['user_id' => 'required|exists:users,user_id',]);

        DB::table('attends')->where('event_id', $eventId)->where('user_id', $request->user_id)->delete();

        return redirect()->route('events.attendees', $eventId)->with('success', 'User removed successfully.');
    }

    public function viewAttendeesList($event_id)
    {
        $event = Event::with('attendees')->findOrFail($event_id);

        // Verificar se o utilizador é participante ou organizador
        if (!auth()->check() || (!$event->attendees->contains(auth()->id()) && auth()->id() !== $event->organizer_id)) {
            abort(403, 'Unauthorized action.');
        }

        return view('events.attendees_list', compact('event'));
    }

}