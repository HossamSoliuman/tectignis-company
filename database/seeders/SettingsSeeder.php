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
            ['key' => 'site_logo', 'value' => null, 'group' => 'general'],
            ['key' => 'site_logo_dark', 'value' => null, 'group' => 'general'],
            ['key' => 'site_favicon', 'value' => null, 'group' => 'general'],

            // integrations
            ['key' => 'site_ga_id', 'value' => 'G-MSJ8VY2D8Q', 'group' => 'integrations'],
            ['key' => 'site_gtm_id', 'value' => 'GTM-TFWTXHQ4', 'group' => 'integrations'],
            ['key' => 'google_search_console_verification', 'value' => null, 'group' => 'integrations'],
            ['key' => 'meta_pixel_id', 'value' => null, 'group' => 'integrations'],
            ['key' => 'recaptcha_site_key', 'value' => null, 'group' => 'integrations'],
            ['key' => 'recaptcha_secret_key', 'value' => null, 'group' => 'integrations'],

            // smtp
            ['key' => 'smtp_host', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_port', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_username', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_password', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_encryption', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_from_address', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_from_name', 'value' => null, 'group' => 'smtp'],

            // social
            ['key' => 'social_facebook', 'value' => 'https://www.facebook.com/tectignisofficial/', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://www.instagram.com/tectignisofficial/', 'group' => 'social'],
            ['key' => 'social_linkedin', 'value' => 'https://in.linkedin.com/company/tectignis', 'group' => 'social'],
            ['key' => 'social_twitter', 'value' => 'https://x.com/ItTectignis', 'group' => 'social'],
            ['key' => 'social_whatsapp', 'value' => '+919987705688', 'group' => 'social'],

            // legal
            ['key' => 'legal_privacy-policy', 'value' => null, 'group' => 'legal'],
            ['key' => 'legal_terms-and-conditions', 'value' => null, 'group' => 'legal'],

            // home — hero
            ['key' => 'hero_sub_heading', 'value' => 'Serving Clients Across Navi Mumbai, Mumbai, Thane, Pune, India & Worldwide.', 'group' => 'home'],
            ['key' => 'hero_heading_line1', 'value' => 'Transforming Businesses Through', 'group' => 'home'],
            ['key' => 'hero_heading_line2', 'value' => 'Software, AI & Smart Technology Solutions', 'group' => 'home'],
            ['key' => 'hero_info_heading', 'value' => 'Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems.', 'group' => 'home'],
            ['key' => 'hero_btn_primary', 'value' => 'Request Consultation', 'group' => 'home'],
            ['key' => 'hero_btn_secondary', 'value' => 'Get a Quote', 'group' => 'home'],
            ['key' => 'hero_image', 'value' => 'custom-software-solution-pan-india.webp', 'group' => 'home'],
            ['key' => 'what_we_offer_image', 'value' => 'site/IT-Services-in-Nav-Mumbai.webp', 'group' => 'home'],

            // home — section headers
            ['key' => 'stats_sub_heading', 'value' => 'Trusted Technology Partner', 'group' => 'home'],
            ['key' => 'cap_sub_heading', 'value' => 'Our Expertise', 'group' => 'home'],
            ['key' => 'cap_heading', 'value' => 'End-to-End Solutions to Drive Your', 'group' => 'home'],
            ['key' => 'cap_heading_highlight', 'value' => 'Business Forward', 'group' => 'home'],
            ['key' => 'cap_subtitle', 'value' => 'We deliver innovative, reliable, and scalable solutions across industries to help you stay ahead.', 'group' => 'home'],
            ['key' => 'why_badge', 'value' => 'Why Choose Us', 'group' => 'home'],
            ['key' => 'why_heading', 'value' => 'Why Choose', 'group' => 'home'],
            ['key' => 'why_heading_highlight', 'value' => 'Tectignis?', 'group' => 'home'],
            ['key' => 'why_subtitle', 'value' => 'We combine expertise, technology, and a customer-first approach to deliver solutions that drive real results for your business.', 'group' => 'home'],
            ['key' => 'sol_overline', 'value' => '/// Solutions We Deliver ///', 'group' => 'home'],
            ['key' => 'sol_heading', 'value' => 'Business-focused Solutions Built for', 'group' => 'home'],
            ['key' => 'sol_heading_highlight', 'value' => 'Impact', 'group' => 'home'],
            ['key' => 'sol_subtitle', 'value' => 'We deliver end-to-end IT solutions tailored to your business needs, designed to drive efficiency, security, and growth.', 'group' => 'home'],
            ['key' => 'ind_pretitle', 'value' => 'INDUSTRIES WE SERVE', 'group' => 'home'],
            ['key' => 'ind_heading_line1', 'value' => 'Empowering Every Industry', 'group' => 'home'],
            ['key' => 'ind_heading_line2', 'value' => 'with Intelligent Solutions', 'group' => 'home'],
            ['key' => 'ind_subtitle', 'value' => 'We deliver tailored IT solutions to help businesses across industries innovate, optimize operations, and achieve sustainable growth.', 'group' => 'home'],
            ['key' => 'fs_pretitle', 'value' => 'FEATURED SERVICES', 'group' => 'home'],
            ['key' => 'fs_heading', 'value' => 'Our Most In-Demand Services', 'group' => 'home'],
            ['key' => 'fs_subtitle', 'value' => "Powering businesses with innovative, secure, and scalable solutions tailored to meet today's digital challenges.", 'group' => 'home'],
            ['key' => 'tech_sub_heading', 'value' => 'Tools & Platforms', 'group' => 'home'],
            ['key' => 'tech_heading', 'value' => 'Technology', 'group' => 'home'],
            ['key' => 'tech_heading_highlight', 'value' => 'Stack', 'group' => 'home'],
            ['key' => 'cs_badge', 'value' => 'CASE STUDIES', 'group' => 'home'],
            ['key' => 'cs_heading', 'value' => 'Real Stories. Real Impact.', 'group' => 'home'],
            ['key' => 'cs_subtitle', 'value' => 'Explore how our solutions have helped businesses overcome challenges, improve operations, and achieve measurable results.', 'group' => 'home'],
            ['key' => 'gp_pretitle', 'value' => 'Global Presence', 'group' => 'home'],
            ['key' => 'gp_heading', 'value' => 'Serving Clients Worldwide, Delivering Excellence', 'group' => 'home'],
            ['key' => 'gp_heading_highlight', 'value' => 'Everywhere.', 'group' => 'home'],
            ['key' => 'gp_subtitle', 'value' => 'We combine local expertise with a global mindset to deliver innovative IT solutions that help your business grow, scale and succeed.', 'group' => 'home'],
            ['key' => 'gp_india_projects', 'value' => '100+', 'group' => 'home'],
            ['key' => 'gp_countries_served', 'value' => '25+', 'group' => 'home'],
            ['key' => 'res_sub_heading', 'value' => 'Stay Informed', 'group' => 'home'],
            ['key' => 'res_heading', 'value' => 'Resources &', 'group' => 'home'],
            ['key' => 'res_heading_highlight', 'value' => 'Insights', 'group' => 'home'],

            // home — final CTA
            ['key' => 'cta_heading', 'value' => 'Ready to', 'group' => 'home'],
            ['key' => 'cta_heading_highlight', 'value' => 'Transform', 'group' => 'home'],
            ['key' => 'cta_heading_suffix', 'value' => 'Your Business?', 'group' => 'home'],
            ['key' => 'cta_subheading', 'value' => "Let's discuss your software, AI, cloud, cybersecurity, or infrastructure requirements.", 'group' => 'home'],
            ['key' => 'cta_btn_primary', 'value' => 'Request Consultation', 'group' => 'home'],
            ['key' => 'cta_btn_secondary', 'value' => 'Get a Quote', 'group' => 'home'],

            // careers
            ['key' => 'careers_hero_image', 'value' => null, 'group' => 'careers'],

            // seo
            ['key' => 'robots_txt', 'value' => "User-agent: *\nAllow: /", 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
