<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('province')->insert([
            ['id' => 1, 'name' => 'Umujyi wa Kigali'],
            ['id' => 2, 'name' => 'Amajyepfo'],
            ['id' => 3, 'name' => 'Iburengerazuba'],
            ['id' => 4, 'name' => 'Amajyaruguru'],
            ['id' => 5, 'name' => 'Iburasirazuba'],
        ]);
    }
}

