<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Seed the department lookup table.
     */
    public function run(): void
    {
        DB::table('option_department')->insert([
            ['name' => 'ADMINISTRATION'],
            ['name' => 'PRAISE & WORSHIP'],
            ['name' => 'EVANGELISM'],
            ['name' => 'SUNDAY SCHOOL'],
            ['name' => 'YOUTH DEPARTMENT'],
            ['name' => 'MOTHERS\' DEPARTMENT'],
            ['name' => 'FATHERS\' DEPARTMENT'],
            ['name' => 'SOCIAL CARE'],
            ['name' => 'SECURITY'],
        ]);
    }
}

