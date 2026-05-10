<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaritalStatusSeeder extends Seeder
{
    /**
     * Seed the marital_status lookup table.
     */
    public function run(): void
    {
        DB::table('marital_status')->insert([
            ['name' => 'SINGLE'],
            ['name' => 'MARRIED'],
            ['name' => 'ENGAGED'],
            ['name' => 'WIDOWED'],
            ['name' => 'DIVORCED'],
        ]);
    }
}

