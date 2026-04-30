<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'slug' => 'm-kopa-website',
                'category' => 'Website',
                'title' => 'M-KOPA Website',
                'company' => 'FourthCanvas',
                'country' => 'Kenya',
                'year' => '2023',
                'the_problem' => 'M-KOPA, through FourthCanvas agency, needed a website that fully communicated their essence, impact, and offerings.',
                'key_result' => null,
                'what_i_did' => [
                    'Developed the information architecture as the strategic foundation.',
                    'Crafted market-led messaging in alignment with business goals.',
                    'Aligned with stakeholders to understand the nuances of the market.',
                ],
                'skills_tags' => ['Research', 'Information Architecture', 'Copywriting', 'SEO Optimisation', 'Stakeholder Alignment'],
                'cover_image' => null,
                'is_featured' => true,
                'published' => true,
                'sort_order' => 1,
            ],
            [
                'slug' => 'seamlesshr-inbound-marketing',
                'category' => 'SEO & Lead Generation',
                'title' => 'SeamlessHR Inbound Marketing',
                'company' => 'SeamlessHR',
                'country' => 'Kenya, Nigeria, Ghana, Uganda',
                'year' => '2024 – 2025',
                'the_problem' => 'SeamlessHR needed to generate leads through organic search while supporting broader revenue goals, but lacked the SEO foundation to compete for visibility in key markets.',
                'key_result' => '#1 ranked HR & Payroll Software across Nigeria, Ghana, Kenya & Uganda',
                'what_i_did' => [
                    'Audited the existing website for SEO gaps and opportunities.',
                    'Researched and mapped keywords by intent across target markets.',
                    'Executed a full SEO rollout — optimising web pages and all published content.',
                    'Ranked SeamlessHR\'s short-tail and long-tail keywords consistently across Nigeria, Ghana, Kenya, and Uganda.',
                    'Maintained SeamlessHR as the #1 ranked HR & Payroll Software in each target country.',
                ],
                'skills_tags' => ['SEO Strategy', 'Keyword Research & Clustering', 'On-Page Optimisation', 'Performance Tracking'],
                'cover_image' => null,
                'is_featured' => true,
                'published' => true,
                'sort_order' => 2,
            ],
        ];

        foreach ($projects as $project) {
            Project::firstOrCreate(['slug' => $project['slug']], $project);
        }
    }
}
