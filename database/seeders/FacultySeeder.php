<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('education_faculty')->truncate();
        DB::table('faculty')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // BACHELOR faculties (education_id = 3) → faculty IDs 1–31
        DB::table('faculty')->insert([
            ['name' => 'Faculty of Arts and Humanities'],
            ['name' => 'Faculty of Social Sciences'],
            ['name' => 'Faculty of Science'],
            ['name' => 'Faculty of Engineering'],
            ['name' => 'Faculty of Information Technology / Computer Science'],
            ['name' => 'Faculty of Business and Management'],
            ['name' => 'Faculty of Economics'],
            ['name' => 'Faculty of Education'],
            ['name' => 'Faculty of Law'],
            ['name' => 'Faculty of Medicine'],
            ['name' => 'Faculty of Health Sciences'],
            ['name' => 'Faculty of Pharmacy'],
            ['name' => 'Faculty of Dentistry'],
            ['name' => 'Faculty of Agriculture'],
            ['name' => 'Faculty of Veterinary Medicine'],
            ['name' => 'Faculty of Environmental Studies'],
            ['name' => 'Faculty of Architecture and Urban Planning'],
            ['name' => 'Faculty of Fine Arts / Creative Arts'],
            ['name' => 'Faculty of Performing Arts'],
            ['name' => 'Faculty of Communication and Media Studies'],
            ['name' => 'Faculty of Theology / Religious Studies'],
            ['name' => 'Faculty of Tourism and Hospitality'],
            ['name' => 'Faculty of Natural Resources'],
            ['name' => 'Faculty of Public Administration'],
            ['name' => 'Faculty of International Relations'],
            ['name' => 'Faculty of Aviation and Aerospace'],
            ['name' => 'Faculty of Maritime Studies'],
            ['name' => 'Faculty of Sports and Physical Education'],
            ['name' => 'Faculty of Languages and Linguistics'],
            ['name' => 'Faculty of Mathematics and Statistics'],
            ['name' => 'Faculty of Public Health'],
        ]);

        // MASTER faculties (education_id = 4) → faculty IDs 32–71
        DB::table('faculty')->insert([
            ['name' => 'Faculty of Arts and Humanities'],
            ['name' => 'Faculty of Social Sciences'],
            ['name' => 'Faculty of Natural and Applied Sciences'],
            ['name' => 'Faculty of Engineering and Technology'],
            ['name' => 'Faculty of Computer Science / Information Technology'],
            ['name' => 'Faculty of Business Administration and Management'],
            ['name' => 'Faculty of Economics and Finance'],
            ['name' => 'Faculty of Education'],
            ['name' => 'Faculty of Law'],
            ['name' => 'Faculty of Medicine'],
            ['name' => 'Faculty of Health Sciences'],
            ['name' => 'Faculty of Nursing'],
            ['name' => 'Faculty of Pharmacy'],
            ['name' => 'Faculty of Dentistry'],
            ['name' => 'Faculty of Public Health'],
            ['name' => 'Faculty of Agriculture and Agricultural Sciences'],
            ['name' => 'Faculty of Veterinary Medicine'],
            ['name' => 'Faculty of Environmental Sciences'],
            ['name' => 'Faculty of Architecture and Urban Planning'],
            ['name' => 'Faculty of Fine Arts and Design'],
            ['name' => 'Faculty of Performing Arts'],
            ['name' => 'Faculty of Media and Communication Studies'],
            ['name' => 'Faculty of Theology and Religious Studies'],
            ['name' => 'Faculty of Tourism and Hospitality Management'],
            ['name' => 'Faculty of International Relations and Diplomacy'],
            ['name' => 'Faculty of Political Science and Public Administration'],
            ['name' => 'Faculty of Mathematics and Statistics'],
            ['name' => 'Faculty of Earth Sciences and Geography'],
            ['name' => 'Faculty of Marine and Maritime Studies'],
            ['name' => 'Faculty of Aviation and Aerospace Studies'],
            ['name' => 'Faculty of Sports Science and Physical Education'],
            ['name' => 'Faculty of Languages and Linguistics'],
            ['name' => 'Faculty of Psychology and Behavioral Sciences'],
            ['name' => 'Faculty of Data Science and Artificial Intelligence'],
            ['name' => 'Faculty of Biotechnology and Life Sciences'],
            ['name' => 'Faculty of Energy and Renewable Energy Studies'],
            ['name' => 'Faculty of Development Studies'],
            ['name' => 'Faculty of Peace and Conflict Studies'],
            ['name' => 'Faculty of Criminology and Security Studies'],
            ['name' => 'Faculty of Library and Information Sciences'],
        ]);

        // Pivot: BACHELOR (education_id=3) → faculty_id 1–31
        DB::table('education_faculty')->insert(
            array_map(fn($id) => ['education_id' => 3, 'faculty_id' => $id], range(1, 31))
        );

        // Pivot: MASTERS (education_id=4) → faculty_id 32–71
        DB::table('education_faculty')->insert(
            array_map(fn($id) => ['education_id' => 4, 'faculty_id' => $id], range(32, 71))
        );
    }
}
