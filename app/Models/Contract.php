<?php

namespace App\Models;

use App\Acme\Parser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'fulfillmentDeposit',
        'paid_amount',
    ];

    public static function CreateOrUpdate(array $rowData)
    {

        $results = Parser::ParseArray($rowData, ["合同名称", "合同金额", "收取履约金", "已累计付款金额（第  次）"]);
        $contract = Contract::where('name', $results["合同名称"])->first();
//        dd($contract);
        if(!$contract->exists()){
            $contract = Contract::create([
                'name' => $results["合同名称"],
                'amount' => $results["合同金额"],
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
}
