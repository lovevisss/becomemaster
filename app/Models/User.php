<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $table = 'users';

    public $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'role_id',
        'company_id',
        'first_name',
        'last_name',
        'city',
        'billing_id',
        'remember_token',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at'
    ];

    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'email_verified_at' => 'datetime',
        'password' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'city' => 'string',
        'billing_id' => 'string',
        'remember_token' => 'string',
        'stripe_id' => 'string',
        'pm_type' => 'string',
        'pm_last_four' => 'string',
        'trial_ends_at' => 'datetime'
    ];

    public static array $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'email_verified_at' => 'nullable',
        'password' => 'required|string|max:255',
        'role_id' => 'required',
        'company_id' => 'nullable',
        'first_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'billing_id' => 'nullable|string|max:255',
        'remember_token' => 'nullable|string|max:100',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'stripe_id' => 'nullable|string|max:255',
        'pm_type' => 'nullable|string|max:255',
        'pm_last_four' => 'nullable|string|max:4',
        'trial_ends_at' => 'nullable'
    ];

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Comment::class, 'user_id');
    }

    public function projectUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProjectUser::class, 'user_id');
    }

    public function taskUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\TaskUser::class, 'user_id');
    }

    public function tasks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Task::class, 'user_id');
    }
}
