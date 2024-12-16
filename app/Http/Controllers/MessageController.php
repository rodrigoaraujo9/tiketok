<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function show($eventId)
    {
        $messages = Message::where('event_id', $eventId)->with('user')->get();
        return view('events.message', compact('messages', 'eventId'));
    }

    public function store(Request $request, $eventId)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        Message::create([
            'event_id' => $eventId,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        return redirect()->back();
    }
}