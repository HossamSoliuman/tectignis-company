<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // general
            ['key' => 'site_name', 'value' => 'Tectignis IT Solutions', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Top IT Services & Solutions in Navi Mumbai', 'group' => 'general'],
            ['key' => 'site_phone', 'value' => '+91 9987705688', 'group' => 'general'],
            ['key' => 'site_email', 'value' => 'info@tectignis.in', 'group' => 'general'],
            ['key' => 'site_address', 'value' => 'Aashiyana CHS Shop no 05, Sector 11, Plot no 29, Kamothe, Navi Mumbai - 410206', 'group' => 'general'],
            ['key' => 'site_ga_id', 'value' => 'G-MSJ8VY2D8Q', 'group' => 'general'],
            ['key' => 'site_gtm_id', 'value' => 'GTM-TFWTXHQ4', 'group' => 'general'],

            // home
            ['key' => 'hero_sub_heading', 'value' => 'Innovation across Pan India.', 'group' => 'home'],
            ['key' => 'hero_heading_line1', 'value' => 'Secure future with our', 'group' => 'home'],
            ['key' => 'hero_heading_line2', 'value' => 'Software', 'group' => 'home'],
            ['key' => 'hero_info_heading', 'value' => 'And IT solutions engineered for precision, performance, & peace of mind.', 'group' => 'home'],
            ['key' => 'hero_image', 'value' => 'site/custom-software-solution-pan-india.webp', 'group' => 'home'],
            ['key' => 'what_we_offer_sub', 'value' => 'OVER 500+ CLIENT', 'group' => 'home'],
            ['key' => 'what_we_offer_image', 'value' => 'site/IT-Services-in-Nav-Mumbai.webp', 'group' => 'home'],
            ['key' => 'stat_happy_clients', 'value' => '500', 'group' => 'home'],
            ['key' => 'stat_projects', 'value' => '350', 'group' => 'home'],
            ['key' => 'stat_growth', 'value' => '1M', 'group' => 'home'],
            ['key' => 'stat_users', 'value' => '1K', 'group' => 'home'],
            ['key' => 'tech_service_image', 'value' => 'site/Networking-Solutions-India.webp', 'group' => 'home'],

            // seo
            ['key' => 'robots_txt', 'value' => "User-agent: *\nAllow: /", 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
