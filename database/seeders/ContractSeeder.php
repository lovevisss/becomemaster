<?php

namespace Database\Seeders;

use App\Models\Contract;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contract::create(
            [
                'name' => '浙江财经学院东方学院迁建工程智能化综合布线施工合同',
                'year' => '2009',
                'signing_date' => '2009-11-30',
                'amount' => 7749193,
                'company_id' => 1,
                'user_id' => 1,
                'status_id' => 4
            ]
        );


    }
}
