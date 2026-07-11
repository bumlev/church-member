<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CellSeeder extends Seeder
{

    public function run():void
    {
        DB::table("cell")->insert([
            ['id' => 1 ,'name' => 'Kanombe'],
            ['id' => 2 , 'name' => 'Kicukiro'],
            ['id' => 3, 'name' => 'Gikondo'],
            ['id' => 4, 'name' => 'Rebero'],
            ['id' => 5 , 'name' => 'Kimironko'],
            ['id'=> 6 , 'name' => 'Nyamirambo'],
            ['id'=> 7 , 'name' => 'Gisozi'],
            ['id'=> 8 , 'name' => 'Muhima'],
            ['id'=> 9 , 'name' => 'Kibagabaga'],
        ]);
    }
}
