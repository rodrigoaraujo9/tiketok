<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use App\Models\Poll;
use App\Models\PollOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Exibir todos os comentários de um evento.
     */
    public function index($event_id)
    {
        $event = Event::with(['comments.user'])->findOrFail($event_id);

        return view('comments.index', compact('event'));
    }

    /**
     * Adicionar um novo comentário.
     */
    public function addComment(Request $request, $event_id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'content' => $validated['content'],
            'date' => now(),
            'event_id' => $event_id,
            'user_id' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Comment added successfully!', 'comment' => $comment]);
        }

        return redirect()->route('comments.index', $event_id)
            ->with('success', 'Comment added successfully!');
    }

    /**
     * Mostrar formulário para criar um comentário com Poll.
     */
    public function createCommentWithPoll($event_id)
    {
        $event = Event::findOrFail($event_id);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a comment with a poll.');
        }

        return view('comments.create_poll', compact('event'));
    }

    /**
     * Armazenar um comentário com Poll.
     */
    public function storeCommentWithPoll(Request $request, $event_id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'question' => 'required|string|max:255',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string|max:255',
        ]);

        $comment = Comment::create([
            'content' => $validated['content'],
            'date' => now(),
            'event_id' => $event_id,
            'user_id' => Auth::id(),
        ]);

        $poll = Poll::create([
            'event_id' => $event_id,
            'question' => $validated['question'],
            'user_id' => Auth::id(),
        ]);

        foreach ($validated['options'] as $option) {
            PollOption::create([
                'poll_id' => $poll->poll_id,
                'option_text' => $option,
                'votes' => 0,
            ]);
        }

        return redirect()->route('comments.index', $event_id)
            ->with('success', 'Comment with Poll created successfully!');
    }

    /**
     * Editar um comentário.
     */
    public function editComment(Request $request, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);

        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update(['content' => $validated['content']]);

        return redirect()->route('comments.index', $comment->event_id)
            ->with('success', 'Comment updated successfully!');
    }

    /**
     * Deletar um comentário.
     */
    public function deleteComment(Request $request, $comment_id)
    {
        $comment = Comment::findOrFail($comment_id);

        if ($comment->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $comment->delete();

        return redirect()->route('comments.index', $comment->event_id)
            ->with('success', 'Comment deleted successfully!');
    }
}
