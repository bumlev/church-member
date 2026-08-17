<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChurchResponsibilitySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('church_responsibility')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('church_responsibility')->insert([
            // STUDY & DEVELOPMENT (department_id = 1)
            ['name' => 'Street kids project',     'department_id' => 1],
            ['name' => 'HIV/AIDS (Ubuzima)',       'department_id' => 1],
            ['name' => 'Construction',             'department_id' => 1],
            ['name' => 'Income generation',        'department_id' => 1],

            // FINANCE (department_id = 2)
            ['name' => 'Accounting',                      'department_id' => 2],
            ['name' => 'Bank',                            'department_id' => 2],
            ['name' => 'Offering, Tithes & Donations',    'department_id' => 2],
            ['name' => 'Fundraising',                     'department_id' => 2],

            // WORSHIP (department_id = 3)
            ['name' => 'Kinyarwanda Sunday celebrations', 'department_id' => 3],
            ['name' => 'English Sunday celebrations',     'department_id' => 3],
            ['name' => 'Protocol/Ushering',               'department_id' => 3],
            ['name' => 'Praise & Worship',                'department_id' => 3],
            ['name' => 'Intercession',                    'department_id' => 3],
            ['name' => 'Decoration',                      'department_id' => 3],
            ['name' => 'Logistic & Maintenance',          'department_id' => 3],
            ['name' => 'Media - Sound',                   'department_id' => 3],
            ['name' => 'Media - Live Streaming',          'department_id' => 3],
            ['name' => 'Translation',                     'department_id' => 3],

            // FELLOWSHIP (department_id = 4)
            ['name' => 'Small fellowship groups (Ibicaniro)', 'department_id' => 4],
            ['name' => 'Fellowship (Avenement)',              'department_id' => 4],
            ['name' => 'Social care',                         'department_id' => 4],
            ['name' => 'Men',                                 'department_id' => 4],
            ['name' => 'Women',                               'department_id' => 4],
            ['name' => 'Youth',                               'department_id' => 4],

            // EVANGELISM (department_id = 5)
            ['name' => 'Crusade (Conference)',        'department_id' => 5],
            ['name' => 'Outreach',                   'department_id' => 5],
            ['name' => 'Visitors invitations',       'department_id' => 5],
            ['name' => 'Building breaches',          'department_id' => 5],
            ['name' => 'Christian Literature (Books)', 'department_id' => 5],

            // MINISTRY (department_id = 6)
            ['name' => 'Membership / Registration',                                                                            'department_id' => 6],
            ['name' => 'Management of Church members database update',                                                         'department_id' => 6],
            ['name' => 'Orientation',                                                                                          'department_id' => 6],
            ['name' => 'Follow up of new born again believers (converts)',                                                      'department_id' => 6],
            ['name' => 'Follow up of Christian maturity',                                                                      'department_id' => 6],
            ['name' => 'Follow up of Church activities',                                                                       'department_id' => 6],
            ['name' => 'Ministry awareness/Teaching',                                                                          'department_id' => 6],
            ['name' => 'Good relations with visitors (public relations)',                                                       'department_id' => 6],
            ['name' => 'Information and communication',                                                                        'department_id' => 6],
            ['name' => 'Need for Ministries enlargement/expansion and identification & setting up of the implementers',        'department_id' => 6],

            // DISCIPLESHIP (department_id = 7)
            ['name' => 'Alter call baptism (Consolidation)', 'department_id' => 7],
            ['name' => 'Seminars & Teachings',               'department_id' => 7],
            ['name' => 'Christian literature',               'department_id' => 7],
            ['name' => 'Morning Glory (Twibature)',          'department_id' => 7],
            ['name' => 'Sunday school Children',             'department_id' => 7],
        ]);
    }
}
