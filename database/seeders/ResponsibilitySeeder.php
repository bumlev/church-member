<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResponsibilitySeeder extends Seeder
{
    /**
     * Seed the responsibility lookup table.
     */
    public function run(): void
    {
        DB::table('church_responsibility')->insert([
            ['name' => 'INTERCESSOR'],
            ['name' => 'PASTOR'],
            ['name' => 'ADMINISTRATOR'],
            ['name' => 'EVANGELIST'],
            ['name' => 'TEACHER'],
            ['name' => 'PROTOCOL / USHER'],
            ['name' => 'TRADITIONAL DANCER'],
            ['name' => 'DRAMATIST'],
            ['name' => 'SUNDAY SCHOOL TEACHER'],
            ['name' => 'SOCIAL CARE'],
            ['name' => 'HIV/AIDS (UBUZIMA)'],
            ['name' => 'CLEANER'],
            ['name' => 'SECURITY'],
            ['name' => 'MOTHERS\' DEPARTMENT'],
            ['name' => 'FATHERS\' DEPARTMENT'],
            ['name' => 'YOUTH DEPARTMENT'],
        ]);
    }
}

