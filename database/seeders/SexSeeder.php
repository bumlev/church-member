<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SexSeeder extends Seeder
{
    /**
     * Seed the sex lookup table.
     */
    public function run(): void
    {
        DB::table('sex')->insert([
            ['name' => 'MALE'],
            ['name' => 'FEMALE'],
        ]);
    }
}

