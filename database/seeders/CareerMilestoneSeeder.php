<?php

namespace Database\Seeders;

use App\Models\CareerMilestone;
use Illuminate\Database\Seeder;

class CareerMilestoneSeeder extends Seeder
{
    public function run(): void
    {
        $milestones = [
            ['period' => '2019 – 2020', 'role' => 'Social Media Intern', 'company' => null, 'sort_order' => 1],
            ['period' => '2020 – 2021', 'role' => 'Content Associate', 'company' => null, 'sort_order' => 2],
            ['period' => '2021', 'role' => 'Brand Engagement Manager', 'company' => null, 'sort_order' => 3],
            ['period' => '2021 – 2023', 'role' => 'Content Marketing Associate', 'company' => null, 'sort_order' => 4],
            ['period' => '2023 – 2024', 'role' => 'Senior Content Writer / Storyteller', 'company' => null, 'sort_order' => 5],
            ['period' => '2024 – 2025', 'role' => 'Growth & Product-Content Marketing Specialist', 'company' => null, 'sort_order' => 6],
            ['period' => '2025 – Present', 'role' => 'Product Marketing Specialist', 'company' => null, 'sort_order' => 7],
        ];

        foreach ($milestones as $milestone) {
            CareerMilestone::firstOrCreate(['period' => $milestone['period'], 'role' => $milestone['role']], $milestone);
        }
    }
}
