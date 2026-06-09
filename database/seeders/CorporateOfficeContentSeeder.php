<?php

namespace Database\Seeders;

use App\Models\Industry;
use Illuminate\Database\Seeder;

/**
 * Fills the "Corporate Offices" industry with a complete, mockup-matching
 * `content` payload. Serves as a worked example of the admin-managed industry
 * landing page so every section can be seen rendered end to end. Safe to re-run.
 */
class CorporateOfficeContentSeeder extends Seeder
{
    public function run(): void
    {
        $industry = Industry::where('slug', 'corporate-offices')->first();

        if (! $industry) {
            return;
        }

        $industry->update([
            'short_description' => 'We help corporate organizations modernise their workplace technology — secure, connected and built to scale.',
            'content' => [
                'hero' => [
                    'eyebrow' => 'CORPORATE OFFICE IT SOLUTIONS',
                    'heading' => 'Smart Technology. Stronger Corporate Offices.',
                    'highlight' => 'Corporate Offices.',
                    'intro' => 'We help corporate organizations build secure, connected and high-performing workplaces with technology that drives efficiency and growth.',
                    'cta_primary_label' => 'Talk to an Expert',
                    'cta_secondary_label' => 'Explore Solutions',
                    'features' => [
                        ['icon' => '', 'label' => 'Enhance Workplace Productivity'],
                        ['icon' => '', 'label' => 'Secure Data & Infrastructure'],
                        ['icon' => '', 'label' => 'Streamline IT Operations'],
                        ['icon' => '', 'label' => 'Enable Seamless Collaboration'],
                    ],
                    'badges' => [
                        ['icon' => '', 'label' => 'Smart Workspaces'],
                        ['icon' => '', 'label' => 'Secure Infrastructure'],
                        ['icon' => '', 'label' => 'Seamless Collaboration'],
                        ['icon' => '', 'label' => 'IT Management & Support'],
                    ],
                ],
                'trust' => ['enabled' => true],
                'challenges' => [
                    'enabled' => true,
                    'eyebrow' => 'CHALLENGES IN CORPORATE OFFICES',
                    'heading' => 'Modern Workplaces. New-Age Challenges.',
                    'subtitle' => 'Corporate offices face evolving demands as teams, tools and threats grow more complex every year.',
                    'items' => [
                        'Managing hybrid and remote workforces',
                        'Ensuring data security and compliance',
                        'Complex IT infrastructure to maintain',
                        'Enabling seamless team collaboration',
                        'Rising operational and IT costs',
                        'Compliance with industry regulations',
                    ],
                ],
                'solutions' => [
                    'enabled' => true,
                    'eyebrow' => 'HOW WE HELP',
                    'heading' => 'IT Solutions for High-Performing Corporate Offices',
                    'cards' => [
                        ['icon' => '', 'title' => 'IT Infrastructure Management', 'description' => 'Reliable, scalable infrastructure designed for uninterrupted business operations.'],
                        ['icon' => '', 'title' => 'Unified Communication', 'description' => 'Connect teams across locations with integrated voice, video and messaging.'],
                        ['icon' => '', 'title' => 'Cybersecurity Solutions', 'description' => 'Protect data and systems with multi-layered, proactive security.'],
                        ['icon' => '', 'title' => 'Cloud & Workplace Solutions', 'description' => 'Flexible cloud workspaces that support hybrid and remote teams.'],
                        ['icon' => '', 'title' => 'Helpdesk & IT Support', 'description' => 'Responsive support that keeps your people productive every day.'],
                        ['icon' => '', 'title' => 'IT Strategy & Consulting', 'description' => 'Align technology with business goals through expert guidance.'],
                    ],
                ],
                'stats' => [
                    'enabled' => true,
                    'subtitle' => 'PERFORMANCE',
                    'heading' => 'Proven Impact Across Workplaces',
                    'items' => [
                        ['icon' => '', 'value' => '250+', 'label' => 'Corporate Clients'],
                        ['icon' => '', 'value' => '650+', 'label' => 'Projects Delivered'],
                        ['icon' => '', 'value' => '40%', 'label' => 'Increase in Productivity'],
                        ['icon' => '', 'value' => '30%', 'label' => 'Reduction in IT Costs'],
                        ['icon' => '', 'value' => '99.9%', 'label' => 'System Uptime'],
                        ['icon' => '', 'value' => '24/7', 'label' => 'Support & Monitoring'],
                    ],
                ],
                'case_studies' => [
                    'enabled' => true,
                    'subtitle' => 'SUCCESS STORIES',
                    'heading' => 'Real Results. Real Impact.',
                ],
                'solutions_grid' => [
                    'enabled' => true,
                    'subtitle' => 'WHAT WE OFFER',
                    'heading' => 'Corporate Office Solutions We Offer',
                    'items' => [
                        ['icon' => '', 'label' => 'Workplace Technology'],
                        ['icon' => '', 'label' => 'Network & Infrastructure'],
                        ['icon' => '', 'label' => 'Cybersecurity Services'],
                        ['icon' => '', 'label' => 'Cloud Solutions'],
                        ['icon' => '', 'label' => 'Collaboration Tools'],
                        ['icon' => '', 'label' => 'IT Support & Helpdesk'],
                        ['icon' => '', 'label' => 'Data Backup & Recovery'],
                        ['icon' => '', 'label' => 'Compliance & Governance'],
                    ],
                ],
                'faq' => [
                    'enabled' => true,
                    'subtitle' => 'FAQS',
                    'heading' => 'Frequently Asked Questions',
                    'items' => [
                        ['question' => 'How do you ensure data security in our office network?', 'answer' => 'We deploy multi-layered security — firewalls, endpoint protection, access controls and continuous monitoring — tailored to your compliance needs.'],
                        ['question' => 'Can you support hybrid and remote teams?', 'answer' => 'Yes. We build secure cloud workspaces and unified communication tools so your teams stay productive from anywhere.'],
                        ['question' => 'What IT support services do you provide?', 'answer' => 'From 24/7 helpdesk to on-site support and proactive monitoring, we cover the full lifecycle of your IT environment.'],
                        ['question' => 'How do you reduce IT downtime?', 'answer' => 'Through proactive monitoring, redundant infrastructure and rapid-response support we keep uptime at 99.9% or better.'],
                    ],
                    'cta_heading' => 'Ready to Transform Your Corporate Office?',
                    'cta_text' => "Let's build a smart, secure and future-ready workplace tailored to your goals.",
                    'cta_label' => 'Get Started Today',
                ],
                'cta_band' => [
                    'enabled' => true,
                    'heading' => "Let's Build Smarter Workplaces, Together",
                    'subtitle' => 'Partner with our experts to design a workplace that works as hard as your team does.',
                    'button_primary_label' => 'Talk to an Expert',
                    'button_secondary_label' => 'Request a Consultation',
                ],
            ],
        ]);
    }
}
