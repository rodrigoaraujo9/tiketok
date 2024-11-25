<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class AdminPolicy
{
    public function admin(User $user){
        return Auth::check() && $user->isAdmin();
    }

}