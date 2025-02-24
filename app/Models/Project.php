<?php

namespace App\Models;

use App\Acme\Parser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Project extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $with = ['company'];

    // tasks
    public static function CreateOrUpdate(array $rowData)
    {
        $result = Parser::Parse($rowData, "项目名称");
        $project = Project::where('name', $result)->first();
        if(!$project){
            $project = Project::create([
                'name' => $result,
            ]) ;
        }

        return $project;
    }

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
    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // comments
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function contracts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function funding()
    {
        return $this->hasOne(Funding::class);
    }

    public function apply_forms()
    {
        return $this->hasMany(ApplyForm::class);
    }


}
