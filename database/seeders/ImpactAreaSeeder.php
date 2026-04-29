<?php

namespace Database\Seeders;

use App\Models\ImpactArea;
use Illuminate\Database\Seeder;

class ImpactAreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            [
                'title' => 'Product Marketing',
                'tagline' => 'Positioning products for the right market, at the right time, with the right message.',
                'bullets' => [
                    'Defined and refined Ideal Customer Profiles (ICP) and buyer personas across B2B and B2C audiences.',
                    'Built end-to-end GTM strategies for new feature launches, product reintroductions, and market expansions.',
                    'Developed sales enablement materials — pitch decks, one-pagers, and battle cards that directly supported revenue team performance.',
                    'Executed regulatory-compliant messaging for financial products including loans, earned wage access, and salary advance solutions.',
                    'Produced case studies, testimonials, and industry-specific use cases to support pipeline growth.',
                ],
                'sort_order' => 1,
            ],
            [
                'title' => 'Content Marketing & SEO',
                'tagline' => 'Building content systems that attract, educate, and convert.',
                'bullets' => [
                    'Led end-to-end content strategy aligned with revenue and organic growth goals.',
                    'Executed SEO-driven long-form content that consistently generated qualified inbound leads.',
                    'Conducted keyword research and clustering across multiple regions, ranking priority keywords at #1.',
                    'Produced full-funnel content across TOFU, MOFU, and BOFU stages for lead nurturing and activation.',
                    'Managed editorials, newsletters, and lead magnets across multiple countries.',
                ],
                'sort_order' => 2,
            ],
            [
                'title' => 'Growth & Lifecycle Marketing',
                'tagline' => 'Turning users into loyal customers through smart, targeted communication.',
                'bullets' => [
                    'Developed segmented storytelling campaigns tailored to customer personas and lifecycle stages.',
                    'Generated leads and moved them through cycles: Lead → MQL → SAL → SQL.',
                    'Pioneered push notification campaigns reaching 8,000+ users across different triggers.',
                    'Grew open rate, CTR, and conversion rate — and iterated accordingly.',
                ],
                'sort_order' => 3,
            ],
            [
                'title' => 'Brand & Campaign Marketing',
                'tagline' => 'Building brand presence and executing campaigns that cut through.',
                'bullets' => [
                    'Developed creative campaign copy for major brands: Globacom, Heritage Bank, AB InBev, Access Bank, AutochekNG, and Branch International.',
                    'Wrote OVC, TVC, and radio commercial scripts for Globacom, Malta Guinness, Access Bank, and Sterling Bank.',
                    'Coordinated the Fundall virtual dollar card launch — 500+ downloads, #1 on trend-table.',
                    'Managed influencer collaborations including scriptwriting and content direction.',
                ],
                'sort_order' => 4,
            ],
        ];

        foreach ($areas as $area) {
            ImpactArea::firstOrCreate(['title' => $area['title']], $area);
        }
    }
}
