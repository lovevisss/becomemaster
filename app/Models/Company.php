<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];

    // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // projects
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
    // comments
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }
}
