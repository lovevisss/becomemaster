<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::truncate();
        Role::create([
            'name' => 'Admin'
        ]);
        Role::create([
            'name' => 'User'
        ]);

        Role::create([
            'name' => '教师'
        ]);

        Role::create([
            'name' => '厂家'
        ]);
    }
}
