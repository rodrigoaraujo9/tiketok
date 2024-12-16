<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    public function manage(User $user, Event $event)
    {
        return $user->user_id === $event->organizer_id;
    }

    public function invite(User $user, Event $event)
    {
        return $user->user_id === $event->organizer_id;
    }

    public function delete(User $user, Event $event)
    {
        return $user->user_id === $event->organizer_id || $user->isAdmin();
    }
        /**
     * Determine if the given user can update the event.
     */
    public function update(User $user, Event $event)
    {
        // User can update if they are the organizer
        return $user->user_id === $event->organizer_id;
    }

    /**
     * Determine if the given user can view the event attendees.
     */
    public function viewAttendees(User $user, Event $event)
    {
        // User can view attendees if they are the organizer
        return $user->user_id === $event->organizer_id;
    }
}
