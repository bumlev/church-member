<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProvinceSeeder::class,
            DistrictSeeder::class,
            SectorSeeder::class,
            CelluleSeeder::class,
            VillageSeeder::class,
            SexSeeder::class,
            MaritalStatusSeeder::class,
            EducationSeeder::class,
            OccupationSeeder::class,
            DepartmentSeeder::class,
            ChurchResponsibilitySeeder::class,
            FacultySeeder::class,
            TalentSeeder::class,
            SpiritualGiftSeeder::class,
            CellSeeder::class
        ]);
    }
}
