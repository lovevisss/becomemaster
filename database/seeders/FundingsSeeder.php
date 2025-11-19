<?php

namespace Database\Seeders;

use App\Models\Funding;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FundingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Funding::create([
            'name' => '筹建及维护经费',
            'code' => '131202053002'
        ]);

        Funding::create([
            'name' => '校园网、网络机房业务费',
            'code' => '131202053001'
        ]);
          Funding::create([
            'name' => '数字化校园建设经费',
            'code' => '131202053007'
        ]);

    }
}
