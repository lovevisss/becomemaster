<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
//        User::factory(10)->create();
        User::create([
            'email' => '530822987@qq.com',
            'name' => 'loveisss',
            'role_id' => 3,
            'password' => bcrypt('dfxy@123')
        ]);

        User::create([
            'email' => '20160101@zufedfc.edu.cn',
            'name'  => '吴孝杰',
            'role_id' => 3,
            'password' => bcrypt('dfxy@123')
        ]);

    }
}
