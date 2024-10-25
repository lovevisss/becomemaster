<?php

namespace App\Models;

use App\Acme\Parser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'amount',
    ];

    public static function CreateOrUpdate(array $rowData)
    {
        $results = Parser::ParseArray($rowData, ["本次支付依据（项目完成情况或合同支付条款等）", "本期申请付款"]);
//        dd($results);
        $payment = Payment::create([
            'reason' => $results["本次支付依据（项目完成情况或合同支付条款等）"],
            'amount' => doubleval($results["本期申请付款"]),
        ]);

        return $payment;
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

}
