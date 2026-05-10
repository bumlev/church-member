<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TalentSeeder extends Seeder
{
    /**
     * Seed the talent lookup table.
     */
    public function run(): void
    {
        DB::table('talent')->insert([
            ['name' => 'DRAMA'],
            ['name' => 'CULTURAL DANCE'],
            ['name' => 'PRAISE & WORSHIP'],
            ['name' => 'VOLLEYBALL'],
            ['name' => 'FOOTBALL'],
            ['name' => 'ART'],
            ['name' => 'CRAFT WORK'],
            ['name' => 'GROUP DISCUSSION'],
            ['name' => 'PREACHING'],
            ['name' => 'EVANGELISM'],
            ['name' => 'CRUSADE (CONFERENCE)'],
            ['name' => 'OUTREACH'],
            ['name' => 'VISITORS\' INVITATION'],
            ['name' => 'BUILDING BREACHES'],
            ['name' => 'CHRISTIAN LITERATURE (BOOKS, AUDIO, VIDEO)'],
            ['name' => 'DISCIPLESHIP'],
        ]);
    }
}

