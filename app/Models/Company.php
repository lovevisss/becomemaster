<?php

namespace App\Models;

use App\Acme\Parser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $with = ['user'];

    // user
    public static function CreateOrUpdate(array $rowData)
    {
// 假设 $rowData 是你提供的多维数组
        $companyName = Parser::Parse($rowData, "收款单位");

        if ($companyName !== null) {
//            echo "收款单位后面的值是: " . $companyName;
            $existingCompany = Company::where('name', $companyName)->first();
            if(!$existingCompany){
                $existingCompany =  Company::create([
                    'name' => $companyName
                ]);
            }
            return $existingCompany;

        } else {
            echo "未找到收款单位字段";
        }
    }

    public function user()
    {
        return $this->hasOne(User::class);
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

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }




}
