<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];

    // tasks
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // users
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    // company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // comments
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }
}
