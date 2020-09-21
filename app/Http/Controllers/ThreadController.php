<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function getAll()
    {
        return Thread::all();
    }

    public function create()
    {
        // Validate
        $attributes = request()->validate([
            'title' => 'required',
            'body' => 'required|max:2500',
        ]);

        return Thread::create([
            'user_id' => auth()->id(),
            'title' => $attributes['title'],
            'body' => $attributes['body']
        ]);
    }

    public function delete(Thread $thread)
    {
        $this->authorize('delete', $thread);
        return $thread->delete();
    }
}
