<?php

namespace Database\Seeders;

use App\Models\ProjectType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProjectType::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        ProjectType::create([
            'name' => '部门自行采购项目'
        ]);
        ProjectType::create([
            'name' => '统一采购项目'
        ]);

    }
}
