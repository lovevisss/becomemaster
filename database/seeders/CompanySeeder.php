<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
//                 $table->string('name')->unique();
//            $table->string('tax_id')->unique()->nullable();
//            $table->string('address')->nullable();
//            $table->string('telephone')->nullable();
//            $table->longText('description')->nullable();
            'name' => '中国电信股份有限公司海宁分公司',

        ]);
    }
}
