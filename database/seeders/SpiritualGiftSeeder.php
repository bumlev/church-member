<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpiritualGiftSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('spiritual_gift')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('spiritual_gift')->insert([
            ['name' => 'Prophecy'],
            ['name' => 'Serving'],
            ['name' => 'Teaching'],
            ['name' => 'Exhortation'],
            ['name' => 'Giving'],
            ['name' => 'Leadership'],
            ['name' => 'Mercy'],
            ['name' => 'Word of Wisdom'],
            ['name' => 'Word of Knowledge'],
            ['name' => 'Faith'],
            ['name' => 'Gifts of Healing'],
            ['name' => 'Miraculous Powers'],
            ['name' => 'Discernment'],
            ['name' => 'Speaking in Tongues'],
            ['name' => 'Interpretation of Tongues'],
            ['name' => 'Helps'],
            ['name' => 'Apostleship'],
            ['name' => 'Administration'],
            ['name' => 'Evangelism'],
            ['name' => 'Pastoring'],
            ['name' => 'Hospitality'],
            ['name' => 'Ministering'],
        ]);
    }
}
