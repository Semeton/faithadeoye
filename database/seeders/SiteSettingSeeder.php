<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Hero
            ['key' => 'hero_name', 'value' => 'Faith O. Adeoye', 'type' => 'text', 'group' => 'hero', 'label' => 'Name'],
            ['key' => 'hero_title', 'value' => 'Product Marketing & Content Specialist', 'type' => 'text', 'group' => 'hero', 'label' => 'Title'],
            ['key' => 'hero_specialisms', 'value' => 'B2B, B2C, DTC, SaaS, Marketplace', 'type' => 'text', 'group' => 'hero', 'label' => 'Specialisms (comma-separated)'],
            ['key' => 'hero_tenure', 'value' => '7+ Years in the Field', 'type' => 'text', 'group' => 'hero', 'label' => 'Tenure'],
            ['key' => 'hero_headline', 'value' => 'Your product solves a problem. I make sure the right people know it.', 'type' => 'text', 'group' => 'hero', 'label' => 'Headline'],
            ['key' => 'hero_subtext', 'value' => 'From positioning and GTM strategy to messaging that converts, I bridge the gap between what a product does and why a customer needs it.', 'type' => 'richtext', 'group' => 'hero', 'label' => 'Sub-text'],
            ['key' => 'hero_cta_primary_label', 'value' => 'View my work', 'type' => 'text', 'group' => 'hero', 'label' => 'Primary CTA Label'],
            ['key' => 'hero_cta_secondary_label', 'value' => 'Get in touch', 'type' => 'text', 'group' => 'hero', 'label' => 'Secondary CTA Label'],
            ['key' => 'hero_photo', 'value' => null, 'type' => 'image', 'group' => 'hero', 'label' => 'Profile Photo'],
            ['key' => 'hero_linkedin_url', 'value' => 'https://www.linkedin.com/in/faithadeoye', 'type' => 'url', 'group' => 'hero', 'label' => 'LinkedIn URL'],

            // Credibility
            ['key' => 'credibility_tagline', 'value' => 'Driven strategy for the most recognisable names across Africa, US, UK and beyond.', 'type' => 'text', 'group' => 'credibility', 'label' => 'Tagline'],
            ['key' => 'credibility_clients', 'value' => 'Globacom, AB InBev, Access Bank, Heritage Bank, Sterling Bank, SeamlessHR, Branch International, AutochekNG, FourthCanvas, Malta Guinness, Fundall', 'type' => 'text', 'group' => 'credibility', 'label' => 'Clients (comma-separated)'],

            // Projects section
            ['key' => 'projects_section_heading', 'value' => 'Every project started with a problem. Here\'s how I solved them.', 'type' => 'text', 'group' => 'projects', 'label' => 'Section Heading'],

            // Career
            ['key' => 'career_headline', 'value' => 'From an agency intern to a full-funnel product marketing specialist', 'type' => 'text', 'group' => 'career', 'label' => 'Timeline Headline'],
            ['key' => 'career_subtext', 'value' => 'Here\'s how the story unfolded.', 'type' => 'text', 'group' => 'career', 'label' => 'Timeline Sub-text'],

            // Contact
            ['key' => 'contact_heading', 'value' => 'Say Hello to Faith.', 'type' => 'text', 'group' => 'contact', 'label' => 'Section Heading'],
            ['key' => 'contact_email', 'value' => 'faithadeoye@gmail.com', 'type' => 'text', 'group' => 'contact', 'label' => 'Email Address'],

            // SEO
            ['key' => 'seo_title', 'value' => 'Faith O. Adeoye — Product Marketing & Content Specialist', 'type' => 'text', 'group' => 'seo', 'label' => 'Site Title'],
            ['key' => 'seo_description', 'value' => 'Faith O. Adeoye is a Product Marketing & Content Specialist with 7+ years driving GTM strategy, SEO, and content for B2B and B2C brands across Africa, US, and UK.', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Description'],
            ['key' => 'seo_og_image', 'value' => null, 'type' => 'image', 'group' => 'seo', 'label' => 'OG Image'],

            // Integrations
            ['key' => 'integration_ga4_id', 'value' => null, 'type' => 'text', 'group' => 'integrations', 'label' => 'Google Analytics 4 Measurement ID'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
