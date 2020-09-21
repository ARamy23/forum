<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RepliesPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Reply $reply)
    {
        return $user->is($reply->thread->owner) || $user->is($reply->owner);
    }
}
