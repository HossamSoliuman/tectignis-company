<?php

namespace Database\Seeders;

use App\Models\LandingPage;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * The two Google Ads landing pages for the priority services. Existing pages
 * are left untouched so admin edits survive a re-run; a missing service link
 * is filled in once the service exists.
 */
class LandingPageSeeder extends Seeder
{
    /**
     * @var list<array<string, mixed>>
     */
    private const PAGES = [
        [
            'slug' => 'custom-software-development',
            'service_slug' => 'custom-software-development',
            'title' => 'Custom Software Development',
            'enquiry_service' => 'Custom Software',
            'headline' => 'Custom Software Development for Growing Businesses',
            'supporting_text' => 'Build tailored web applications, CRM solutions, business automation systems and software integrations with Tectignis IT Solutions.',
            'primary_cta_label' => 'Discuss Your Project',
            'secondary_cta_label' => 'Request a Consultation',
            'seo_title' => 'Custom Software Development for Growing Businesses | Tectignis',
            'seo_description' => 'Tailored web applications, CRM and ERP systems, business automation and integrations, designed and built by the Tectignis in-house team.',
            'sort_order' => 1,
            'content' => [
                'bullets' => [
                    'Web applications, CRM, ERP, portals and SaaS built around your workflows',
                    'In-house team with a clear, stage-by-stage delivery process',
                    'Source code handed over on final payment, as agreed in your contract',
                    'Support, maintenance and enhancements after launch',
                ],
                'form' => [
                    'heading' => 'Tell Us About Your Project',
                    'text' => 'Share a few details and our team will get back to you within one business day with the next steps.',
                ],
                'faq' => [
                    'heading' => 'Frequently Asked Questions',
                    'items' => [
                        ['question' => 'How much does custom software development cost?', 'answer' => 'The cost depends on the project scope, features, integrations, technologies and timeline. We send a detailed quotation after understanding your business requirements.'],
                        ['question' => 'How long does it take to develop custom software?', 'answer' => 'It depends on complexity. Small applications typically take 4–8 weeks, while larger business systems may take 3–12 months. You receive a project plan with milestones before development starts.'],
                        ['question' => 'Do you work with clients outside India?', 'answer' => 'Yes. We work with businesses in India and internationally, with online meetings, regular progress updates and a single point of contact throughout the project.'],
                        ['question' => 'Can you integrate the software with our existing systems?', 'answer' => 'Yes. We integrate with CRM and ERP platforms, accounting software, payment gateways, messaging services, cloud services and other third-party applications through their APIs.'],
                        ['question' => 'Will we own the source code?', 'answer' => 'Yes. On project completion and final payment you receive ownership of the source code and related project assets, as set out in the agreed contract.'],
                        ['question' => 'Do you provide support after launch?', 'answer' => 'Yes. We offer ongoing maintenance, bug fixes, security updates, performance improvements and new features after the software goes live.'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'website-development',
            'service_slug' => 'website-design-development',
            'title' => 'Website Development',
            'enquiry_service' => 'Website Development',
            'headline' => 'Professional Website Development for Your Business',
            'supporting_text' => 'Get a professional, responsive website designed to support your brand, customer experience and business growth.',
            'primary_cta_label' => 'Request a Website Quote',
            'secondary_cta_label' => 'Discuss Your Requirements',
            'seo_title' => 'Professional Website Development for Your Business | Tectignis',
            'seo_description' => 'Corporate and business websites, redesigns and landing pages that are responsive, fast and SEO-ready, designed and built by the Tectignis in-house team.',
            'sort_order' => 2,
            'content' => [
                'bullets' => [
                    'Corporate and business websites, redesigns and landing pages',
                    'Responsive, fast and SEO-ready on every device',
                    'An easy-to-use CMS so your team can update content',
                    'Hosting, security, backups and maintenance after launch',
                ],
                'form' => [
                    'heading' => 'Request Your Website Quote',
                    'text' => 'Tell us about your business and the website you need, and our team will get back to you within one business day.',
                ],
                'faq' => [
                    'heading' => 'Frequently Asked Questions',
                    'items' => [
                        ['question' => 'How much does a website cost?', 'answer' => 'The cost depends on the number of pages, design requirements, features and integrations. We send a detailed quotation after understanding your requirements.'],
                        ['question' => 'How long does it take to build a website?', 'answer' => 'A business website typically takes 1–2 weeks, while custom websites, e-commerce stores or web portals may take 4–8 weeks. You receive a timeline before development begins.'],
                        ['question' => 'Do you work with clients outside India?', 'answer' => 'Yes. We work with businesses in India and internationally, with online meetings, regular progress updates and a single point of contact throughout the project.'],
                        ['question' => 'Will my website be mobile-friendly?', 'answer' => 'Yes. Every website we build is fully responsive and tested on desktops, tablets and smartphones.'],
                        ['question' => 'Can we update the website ourselves?', 'answer' => 'Yes. We build on an easy-to-use content management system so your team can update text, images and pages without technical knowledge, and we provide training if required.'],
                        ['question' => 'Can you redesign our existing website?', 'answer' => 'Yes. We redesign outdated websites with a modern design, better performance, stronger security and improved SEO, keeping your existing content and branding where needed.'],
                    ],
                ],
            ],
        ],
    ];

    public function run(): void
    {
        foreach (self::PAGES as $page) {
            $serviceId = Service::where('slug', $page['service_slug'])->value('id');
            unset($page['service_slug']);

            $landingPage = LandingPage::firstOrCreate(['slug' => $page['slug']], [...$page, 'service_id' => $serviceId]);

            if (! $landingPage->service_id && $serviceId) {
                $landingPage->update(['service_id' => $serviceId]);
            }
        }
    }
}
