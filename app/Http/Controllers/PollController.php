<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;

class PollController extends Controller
{
    public function index($event_id)
    {
        $event = Event::with('polls.options')->findOrFail($event_id);
        return view('polls.index', compact('event'));
    }

    public function create($event_id)
    {
        $event = Event::findOrFail($event_id);
        return view('polls.create_poll', compact('event'));
    }

    public function store(Request $request, $event_id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2', // At least 2 options
            'options.*' => 'required|string|max:255',
        ]);

        $poll = Poll::create([
            'event_id' => $event_id,
            'question' => $request->question,
        ]);

        foreach ($request->options as $option) {
            $poll->options()->create(['option' => $option]);
        }

        return redirect()->route('polls.index', $event_id)->with('success', 'Poll created successfully.');
    }

    public function vote(Request $request, $event_id, $poll_id)
    {
        $request->validate(['option_id' => 'required|exists:poll_options,id']);

        $option = PollOption::findOrFail($request->option_id);
        $option->increment('votes');

        return back()->with('success', 'Your vote has been recorded.');
    }
}
