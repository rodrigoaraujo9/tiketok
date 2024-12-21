<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\Poll;
use App\Models\User;

class PollPolicy
{
    /**
     * Determine if the user can view the poll.
     */
    public function view(User $user, Event $event)
    {
        return $user->user_id === $event->organizer_id || $event->attendees->contains($user->user_id);
    }

    /**
     * Determine if the user can update the poll.
     */
    public function update(User $user, Event $event)
    {
        return $user->user_id === $event->organizer_id;
    }

    /**
     * Determine if the user can delete the poll.
     */
    public function delete(User $user, Poll $poll)
    {
        return $user->user_id === $poll->event->organizer_id;
    }
}
