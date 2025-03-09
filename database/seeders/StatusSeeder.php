<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::create([
            'name' => '申请',

        ]);
        Status::create([
            'name' => '实施',

        ]);
        Status::create([
            'name' => '结算',

        ]);
        Status::create([
            'name' => '完成',

        ]);
        Status::create([
            'name' => '已退质保金',

        ]);
    }
}
