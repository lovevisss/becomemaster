<?php

namespace App\Models;

use App\Acme\Parser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funding extends Model
{
    use HasFactory;

    protected $fillable = ["name", "code"];

    public static function CreateOrUpdate(array $rowData)
    {
        $results = Parser::ParseArray($rowData, ["经费名称", "经费代码"]);

        $funding = Funding::where("name", $results["经费代码"])->first();
        if ($funding) {
            $funding->code = $results["经费代码"];
            $funding->save();
        } else {
            $funding = Funding::create([
                "name" => $results["经费名称"],
                "code" => $results["经费代码"],
            ]);
        }

        return $funding;

    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
