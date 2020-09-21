<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function getAll(Thread $thread)
    {
        return $thread->replies;
    }

    public function create(Thread $thread)
    {
        // Validate
        $attributes = request()->validate([
            'body' => 'required|max:500',
        ]);

        return Reply::create([
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'body' => $attributes['body']
        ]);
    }

    public function delete($thread, Reply $reply)
    {
        $this->authorize('delete', $reply);

        return $reply->delete();
    }
}
