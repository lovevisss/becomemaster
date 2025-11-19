<?php

namespace Database\Seeders;

use App\Models\ContractType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContractType::create(
            [
                'name' => '一次性',
            ]
        );

        ContractType::create(
            [
                'name' => '长期',
            ]
        );

        ContractType::create(
            [
                'name' => '补充',
            ]
        );
    }
}
