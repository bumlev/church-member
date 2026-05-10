<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OccupationSeeder extends Seeder
{
    /**
     * Seed the occupation lookup table.
     */
    public function run(): void
    {
        DB::table('occupation')->insert([
            ['name' => 'FARMER'],
            ['name' => 'DOCTOR'],
            ['name' => 'PHARMACIST'],
            ['name' => 'DENTIST'],
            ['name' => 'ACCOUNTANT'],
            ['name' => 'ENGINEER'],
            ['name' => 'LABORATORY TECHNICIAN'],
            ['name' => 'PLUMBER'],
            ['name' => 'TEACHER'],
            ['name' => 'PASTOR'],
            ['name' => 'NURSE'],
            ['name' => 'FINANCIAL CONTROLLER'],
            ['name' => 'CONSULTANT'],
            ['name' => 'ELECTRONICIAN'],
            ['name' => 'PILOT'],
            ['name' => 'DRIVER'],
        ]);
    }
}

