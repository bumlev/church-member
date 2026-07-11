<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EducationSeeder extends Seeder
{
    /**
     * Seed the education lookup table.
     */
    public function run(): void
    {
        DB::table('education')->insert([
            ['name' => 'PRIMARY'],
            ['name' => 'SECONDARY'],
            ['name' => 'BACHELOR'],
            ['name' => 'MASTERS'],
            ['name' => 'DOCTORATE'],
            ['name' => 'PROFESSOR'],
            ['name' => 'TECHNICAL SCHOOL'],
            ['name' => 'TECHNICAL INSTITUTE'],
        ]);
    }
}

