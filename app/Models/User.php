<?php

namespace App\Models;

use App\Acme\Parser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable;
    public $table = 'users';

    public $fillable = [
        'name',
        'email',
        'phone',
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

    public static function CreateOrUpdate(array $rowData)
    {
        $result = Parser::ParseArray($rowData, ["联系人", "联系电话"]);
//        dd($result["联系电话"]);
        $user = User::where('phone', $result["联系电话"])->first();
        if(!$user){
            $user = User::create([
                'name' => $result["联系人"],
                'phone' => $result["联系电话"],
            ]) ;
        }
        return $user;
    }

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

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
