<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function path()
    {
        return '/api/threads/' . $this->id;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $guarded = [];
}
