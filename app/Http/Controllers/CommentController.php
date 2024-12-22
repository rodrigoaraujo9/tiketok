<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Exibir todos os comentários de um evento.
     */
    public function index($event_id)
    {
        $event = Event::with(['comments.user', 'comments.poll.options'])->findOrFail($event_id);

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

        $comment->refresh();

        $poll = Poll::create([
            'comment_id' => $comment->comment_id, 
            'question' => $validated['question'],
            'user_id' => Auth::id(),
            'end_date' => now()->addDays(30)
        ]);

        foreach ($validated['options'] as $option) {
            PollOption::create([
                'poll_id' => $poll->poll_id,
                'option_text' => $option,
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

    // vote on poll in a comment
    public function voteOnCommentPoll(Request $request, $event_id, $comment_id, $poll_id)
    {
        // Validação dos parâmetros recebidos
        $validated = $request->validate([
            'option_id' => 'required|exists:poll_options,option_id',
        ]);

        // Garantir que a poll está associada ao comentário correto
        $poll = Poll::where('poll_id', $poll_id)
            ->where('comment_id', $comment_id)
            ->firstOrFail();

        // Garantir que o comentário está associado ao evento correto
        $comment = Comment::where('comment_id', $comment_id)
            ->where('event_id', $event_id)
            ->firstOrFail();

        // Verificar se o usuário já votou na poll
        $alreadyVoted = PollVote::where('poll_id', $poll_id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyVoted) {
            return redirect()->back()->with('error', 'You have already voted in this poll.');
        }

        // Validar a opção de votação
        $option = PollOption::where('poll_id', $poll_id)
            ->where('option_id', $validated['option_id'])
            ->firstOrFail();

        // Registrar o voto
        PollVote::create([
            'poll_id' => $poll_id,
            'option_id' => $option->option_id,
            'user_id' => Auth::id(),
        ]);

        // Incrementar o contador de votos para a opção
        $option->increment('votes');

        // Redirecionar para a página de comentários com a âncora correta
        return redirect()->route('comments.index', ['event_id' => $event_id])
            ->with('success', 'Your vote has been recorded.')
            ->withFragment('comment-' . $comment_id);
    }


    // delete vote from poll in a comment
    public function deleteCommentPollVote($comment_id, $poll_id)
    {
        $poll = Poll::where('poll_id', $poll_id)
            ->where('comment_id', $comment_id)
            ->firstOrFail();

        if ($poll->comment_id !== $comment_id) {
            return redirect()->back()->with('error', 'The poll does not belong to this comment.');
        }
            
        $existingVote = PollVote::where('poll_id', $poll_id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$existingVote) {
            return redirect()->back()->with('error', 'You have not voted in this poll.');
        }

        $option = PollOption::find($existingVote->option_id);
        $option->decrement('votes');
        $existingVote->delete();

        return redirect()->route('comments.index', $poll->comment->event_id)
            ->with('success', 'Your vote has been removed.');
    }



    // delete poll from comment
    public function deleteCommentPoll($comment_id, $poll_id)
    {
        $poll = Poll::where('poll_id', $poll_id)
            ->where('comment_id', $comment_id)
            ->firstOrFail();

        if ($poll->comment_id !== $comment_id) {
            return redirect()->back()->with('error', 'The poll does not belong to this comment.');
        }

        if (Auth::id() !== $poll->user_id) {
            return redirect()->back()->with('error', 'You are not authorized to delete this poll.');
        }

        $poll->delete();

        return redirect()->route('comments.index', $poll->comment->event_id)
            ->with('success', 'Poll deleted successfully.');
    }


}
