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
        $event = Event::with([
            'comments.user',
            'comments.poll.options' => function ($query) {
                $query->orderBy('option_id'); 
            }
        ])->findOrFail($event_id);

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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to vote on polls.');
        }
    
        // Valida se a opção existe
        $validated = $request->validate([
            'option_id' => 'required|exists:poll_options,option_id',
        ]);

        // Garantir que a Poll pertence ao Comentário correto
        $poll = Poll::where('poll_id', $poll_id)
            ->where('comment_id', $comment_id)
            ->firstOrFail();

        // Verifica se o usuário já votou
        $alreadyVoted = PollVote::where('poll_id', $poll_id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyVoted) {
            return redirect()->back()->with('error', 'You have already voted in this poll.');
        }

        // Verifica se a opção pertence à poll
        $option = PollOption::where('poll_id', $poll_id)
            ->where('option_id', $validated['option_id'])
            ->firstOrFail();

        // Registra o voto
        PollVote::create([
            'poll_id' => $poll_id,
            'option_id' => $option->option_id,
            'user_id' => Auth::id(),
        ]);

        // Incrementa os votos para a opção
        $option->increment('votes');

        // Redireciona para a página de comentários com âncora no comentário específico
        return redirect()->route('comments.index', ['event_id' => $event_id])
            ->with('success', 'Your vote has been recorded.')
            ->withFragment('comment-' . $comment_id);
    }



    // delete vote from poll in a comment
    public function deleteCommentPollVote($event_id, $comment_id, $poll_id)
    {
        // Buscar a poll associada ao comentário
        $poll = Poll::where('poll_id', $poll_id)
            ->where('comment_id', $comment_id)
            ->first();
    
        if (!$poll) {
            return redirect()->back()->with('error', 'The poll does not exist or does not belong to this comment.');
        }
    
        // Buscar o voto do usuário
        $existingVote = PollVote::where('poll_id', $poll_id)
            ->where('user_id', Auth::id())
            ->first();
    
        if (!$existingVote) {
            return redirect()->back()->with('error', 'You have not voted in this poll.');
        }
    
        // Decrementar os votos da opção selecionada
        $option = PollOption::find($existingVote->option_id);
        if ($option) {
            $option->decrement('votes');
        }
    
        // Remover o registro do voto
        $existingVote->delete();
    
        // Redirecionar com sucesso
        return redirect()->route('comments.index', ['event_id' => $event_id])
            ->with('success', 'Your vote has been removed.')
            ->withFragment('comment-' . $comment_id);
    }
    



    /**
     * Deletar Poll and Comment
     */
    
public function deleteCommentPoll($event_id, $comment_id, $poll_id)
{
    // Buscar a Poll associada ao comentário e evento
    $poll = Poll::where('poll_id', $poll_id)
                ->where('comment_id', $comment_id)
                ->firstOrFail();

    // Verificar se o utilizador tem permissão
    if (Auth::id() !== $poll->user_id) {
        return redirect()->back()->with('error', 'You are not authorized to delete this poll.');
    }

    // Deletar os votos associados à Poll
    PollVote::where('poll_id', $poll->poll_id)->delete();

    // Deletar as opções associadas à Poll
    PollOption::where('poll_id', $poll->poll_id)->delete();

    // Deletar a Poll
    $poll->delete();

    // Deletar o Comentário associado
    $comment = Comment::findOrFail($comment_id);
    $comment->delete();

    return redirect()->route('comments.index', $event_id)
        ->with('success', 'Comment and Poll deleted successfully.');
}




}
