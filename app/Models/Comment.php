<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;
    protected $guarded = [];
    // get the parent commentable model (task or project)
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
