<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SiteSettingSeeder::class,
            CareerMilestoneSeeder::class,
            ImpactAreaSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}
