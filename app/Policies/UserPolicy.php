<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Check if the user is not blocked.
     */
    public function access(User $user): bool
    {
        return !$user->is_blocked;
    }
}
