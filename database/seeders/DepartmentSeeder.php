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
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('department')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('department')->insert([
            ['name' => 'STUDY & DEVELOPMENT'],
            ['name' => 'FINANCE'],
            ['name' => 'WORSHIP'],
            ['name' => 'FELLOWSHIP'],
            ['name' => 'EVANGELISM'],
            ['name' => 'MINISTRY'],
            ['name' => 'DISCIPLESHIP'],
        ]);
    }
}

