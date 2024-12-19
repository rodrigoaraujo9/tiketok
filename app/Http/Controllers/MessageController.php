<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function show($event_id)
    {
        $messages = Message::where('event_id', $event_id)->with('user')->get();
        return view('events.message', compact('messages', 'event_id'));
    }

    public function store(Request $request, $event_id)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'event_id' => $event_id,
            'user_id' => Auth::id(),
            'message' => $validated['message'],
        ]);

        return redirect()->back();
    }
}