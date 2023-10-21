<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Task extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function addComment($body)
    {
        $this->comments()->create(compact('body'));
    }

    public function path()
    {
        return "/tasks/{$this->id}";
    }

    // users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
