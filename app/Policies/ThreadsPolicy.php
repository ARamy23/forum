<?php

namespace App\Policies;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadsPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Thread $thread)
    {
        return $user->is($thread->owner);
    }
}
