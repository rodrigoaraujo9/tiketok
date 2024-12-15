<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Models\Event;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;

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
            'end_date' => now()->addDays(30),
        ]);

        foreach ($request->options as $option) {
            $poll->options()->create(['option_text' => $option]);
        }

        return redirect()->route('polls.index', $event_id)->with('success', 'Poll created successfully.');
    }

    public function vote(Request $request, $event_id, $poll_id)
    {
        $request->validate([
            'option_id' => 'required|exists:poll_options,option_id',
        ]);

        $poll = Poll::where('poll_id', $poll_id)
            ->where('event_id', $event_id)
            ->firstOrFail(); // Garante que a poll pertence ao evento

        // Verifica se o usuário já votou
        $alreadyVoted = PollVote::where('poll_id', $poll_id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyVoted) {
            return redirect()->back()->with('error', 'You have already voted in this poll.');
        }

        // Verifica se a opção pertence à poll
        $option = PollOption::where('poll_id', $poll_id)
            ->where('option_id', $request->option_id)
            ->first();

        if (!$option) {
            return redirect()->back()->with('error', 'Invalid poll option selected.');
        }

        // Registra o voto
        PollVote::create([
            'poll_id' => $poll_id,
            'option_id' => $option->option_id,
            'user_id' => Auth::id(),
        ]);

        // Incrementa os votos para a opção
        $option->increment('votes');

        // Redireciona para a página de polls
        return redirect()->route('polls.index', ['event_id' => $event_id])
            ->with('success', 'Your vote has been recorded.');
    }


    




    public function destroy($event_id, $poll_id)
    {
        $poll = Poll::findOrFail($poll_id);

        if (Auth::id() !== $poll->event->organizer_id) {
            return redirect()->route('polls.index', $event_id)
                ->with('error', 'You are not authorized to delete this poll.');
        }

        $poll->delete();

        return redirect()->route('polls.index', $event_id)
            ->with('success', 'Poll deleted successfully.');
    }

    public function updateVote(Request $request, $event_id, $poll_id)
    {
        $request->validate([
            'option_id' => 'required|exists:poll_options,option_id',
        ]);

        $poll = Poll::findOrFail($poll_id);

        $existingVote = PollVote::where('poll_id', $poll_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$existingVote) {
            return redirect()->back()->with('error', 'You have not voted in this poll.');
        }

        // Decrement the vote count for the previously selected option
        $oldOption = PollOption::find($existingVote->option_id);
        $oldOption->decrement('votes');

        // Update the vote to the new option
        $existingVote->update([
            'option_id' => $request->option_id,
        ]);

        // Increment the vote count for the new option
        $newOption = PollOption::find($request->option_id);
        $newOption->increment('votes');

        return redirect()->route('polls.index', $event_id)
            ->with('success', 'Your vote has been updated.');
    }

    public function deleteVote($event_id, $poll_id)
    {
        $poll = Poll::findOrFail($poll_id);

        $existingVote = PollVote::where('poll_id', $poll_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$existingVote) {
            return redirect()->back()->with('error', 'You have not voted in this poll.');
        }

        // Decrement the vote count for the selected option
        $option = PollOption::find($existingVote->option_id);
        $option->decrement('votes');

        // Delete the vote record
        $existingVote->delete();

        return redirect()->route('polls.index', $event_id)
            ->with('success', 'Your vote has been removed.');
    }



}
