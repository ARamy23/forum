<?php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function getAll(Thread $thread)
    {
        return $thread->replies;
    }
}
