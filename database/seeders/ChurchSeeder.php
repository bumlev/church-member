<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChurchSeeder extends Seeder
{
    /**
     * Seed the churches table.
     * Uses insertOrIgnore on the unique name, so re-running never duplicates rows.
     */
    public function run(): void
    {
        DB::table('churches')->insertOrIgnore([
            ['name' => 'Eglise Vivante'],
            ['name' => 'Evangelical Restoration'],
            ['name' => 'Zion Temple'],
            ['name' => 'ADPR'],
            ['name' => 'Catholic Church'],
            ['name' => 'Anglican Church'],
            ['name' => 'Adventist Church'],
        ]);
    }
}
