<?php

namespace App\Models;

use App\Acme\Parser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    public static function CreateOrUpdate(array $rowData)
    {
        $results = Parser::ParseArray($rowData, ["汇入银行", "银行账号"]);
        $bankAccount = BankAccount::where("number", $results["银行账号"])->first();
        if (is_null($bankAccount)) {
            $bankAccount = new BankAccount();
            $bankAccount->name = $results["汇入银行"];
            $bankAccount->number = $results["银行账号"];
            $bankAccount->save();
        }
        return $bankAccount;

    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
