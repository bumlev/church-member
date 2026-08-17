<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('district')->insert([
            ['id' =>  1, 'province_id' => 1, 'name' => 'Nyarugenge'],
            ['id' =>  2, 'province_id' => 1, 'name' => 'Gasabo'],
            ['id' =>  3, 'province_id' => 1, 'name' => 'Kicukiro'],
            ['id' =>  4, 'province_id' => 2, 'name' => 'Nyanza'],
            ['id' =>  5, 'province_id' => 2, 'name' => 'Gisagara'],
            ['id' =>  6, 'province_id' => 2, 'name' => 'Nyaruguru'],
            ['id' =>  7, 'province_id' => 2, 'name' => 'Huye'],
            ['id' =>  8, 'province_id' => 2, 'name' => 'Nyamagabe'],
            ['id' =>  9, 'province_id' => 2, 'name' => 'Ruhango'],
            ['id' => 10, 'province_id' => 2, 'name' => 'Muhanga'],
            ['id' => 11, 'province_id' => 2, 'name' => 'Kamonyi'],
            ['id' => 12, 'province_id' => 3, 'name' => 'Karongi'],
            ['id' => 13, 'province_id' => 3, 'name' => 'Rutsiro'],
            ['id' => 14, 'province_id' => 3, 'name' => 'Rubavu'],
            ['id' => 15, 'province_id' => 3, 'name' => 'Nyabihu'],
            ['id' => 16, 'province_id' => 3, 'name' => 'Ngororero'],
            ['id' => 17, 'province_id' => 3, 'name' => 'Rusizi'],
            ['id' => 18, 'province_id' => 3, 'name' => 'Nyamasheke'],
            ['id' => 19, 'province_id' => 4, 'name' => 'Rulindo'],
            ['id' => 20, 'province_id' => 4, 'name' => 'Gakenke'],
            ['id' => 21, 'province_id' => 4, 'name' => 'Musanze'],
            ['id' => 22, 'province_id' => 4, 'name' => 'Burera'],
            ['id' => 23, 'province_id' => 4, 'name' => 'Gicumbi'],
            ['id' => 24, 'province_id' => 5, 'name' => 'Rwamagana'],
            ['id' => 25, 'province_id' => 5, 'name' => 'Nyagatare'],
            ['id' => 26, 'province_id' => 5, 'name' => 'Gatsibo'],
            ['id' => 27, 'province_id' => 5, 'name' => 'Kayonza'],
            ['id' => 28, 'province_id' => 5, 'name' => 'Kirehe'],
            ['id' => 29, 'province_id' => 5, 'name' => 'Ngoma'],
            ['id' => 30, 'province_id' => 5, 'name' => 'Bugesera'],
        ]);
    }
}

