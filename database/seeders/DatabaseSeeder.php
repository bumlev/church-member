<?php

namespace Database\Seeders;

use App\Models\User;
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
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);

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
            TalentSeeder::class,
            DepartmentSeeder::class,
            ResponsibilitySeeder::class,
        ]);
    }
}
