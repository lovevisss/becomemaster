<?php

namespace App\Models;

use App\Acme\Parser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $guarded = [];

//    protected $with = ['payments'];

    public static function CreateOrUpdate(array $rowData)
    {

        $results = Parser::ParseArray($rowData, ["合同名称", "合同金额", "收取履约金", "已累计付款金额（第  次）"]);
        $contract = Contract::where('name', $results["合同名称"])->first();
//        dd($contract);
        if(!$contract){
            $contract = Contract::create([
                'name' => $results["合同名称"],
                'amount' => doubleval($results["合同金额"]),
                'fulfillmentDeposit' => doubleval($results["收取履约金"]),
                'paid_amount' =>doubleval($results["已累计付款金额（第  次）"]) ,
            ]);
        }
        return $contract;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isPayed()
    {
        return $this->amount == $this->paid_amount?"已付清":"未付清";
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function funding()
    {
        return $this->belongsTo(Funding::class);
    }


    public function contract_type()
    {
        return $this->belongsTo(ContractType::class);
    }

    public function project_type()
    {
        return $this->belongsTo(ProjectType::class);
    }
}
