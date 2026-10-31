<?php

namespace Database\Seeders;

use App\Models\LandingPage;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * The Google Ads landing pages for the advertised services. Existing pages
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
        [
            'slug' => 'ecommerce-development',
            'service_slug' => 'ecommerce-development',
            'title' => 'E-commerce Development',
            'enquiry_service' => 'E-commerce',
            'headline' => 'Custom E-commerce Website Development for Your Business',
            'supporting_text' => 'Launch an online store or B2B catalogue with the products, payments, shipping and order management your business needs.',
            'primary_cta_label' => 'Request an E-commerce Quote',
            'secondary_cta_label' => 'Discuss Your Requirements',
            'seo_title' => 'Custom E-commerce Website Development Company | Tectignis',
            'seo_description' => 'Custom e-commerce websites and B2B catalogues with payments, shipping, inventory and order management, built on Laravel, Shopify or WooCommerce by the Tectignis team.',
            'sort_order' => 7,
            'content' => [
                'bullets' => [
                    'Online stores and B2B catalogues with product search, filters and quote requests',
                    'Payment gateway, UPI, cash on delivery and shipping integrations',
                    'Inventory, order and customer management in an easy admin panel',
                    'Custom builds on Laravel, or Shopify and WooCommerce where they fit better',
                ],
                'form' => [
                    'heading' => 'Tell Us About Your Store',
                    'text' => 'Share what you sell and how you want to sell it, and our team will get back to you within one business day.',
                ],
                'faq' => [
                    'heading' => 'Frequently Asked Questions',
                    'items' => [
                        ['question' => 'Should we build a custom store or use Shopify or WooCommerce?', 'answer' => 'It depends on your catalogue, order volume, integrations and budget. Shopify and WooCommerce suit standard stores, while a custom Laravel build suits B2B pricing, complex catalogues or workflows the platforms do not support. We recommend one after understanding your requirements.'],
                        ['question' => 'How much does an e-commerce website cost?', 'answer' => 'It depends on the platform, number of products, design, payment and shipping integrations and any custom features. We send a detailed quotation after understanding your requirements.'],
                        ['question' => 'Which payment gateways can you integrate?', 'answer' => 'We integrate gateways such as Razorpay, PayU, CCAvenue, Stripe and PayPal, along with UPI and cash on delivery, based on the markets you sell in.'],
                        ['question' => 'Can the store connect to our inventory or accounting software?', 'answer' => 'Yes. We connect stores to inventory, ERP, accounting, courier and messaging systems through their APIs, so orders and stock stay in sync.'],
                        ['question' => 'Will our store be mobile-friendly?', 'answer' => 'Yes. Every store we build is responsive and tested on desktops, tablets and smartphones, including the checkout.'],
                        ['question' => 'Do you provide support after launch?', 'answer' => 'Yes. We offer maintenance, security updates, backups and new features after the store goes live.'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'erp-crm-development',
            'service_slug' => 'erp-development',
            'title' => 'ERP & CRM Development',
            'enquiry_service' => 'ERP/CRM',
            'headline' => 'ERP and CRM Software Built Around Your Business',
            'supporting_text' => 'Bring sales, operations, inventory, finance and customer data into one system designed for the way your team works.',
            'primary_cta_label' => 'Discuss Your ERP or CRM',
            'secondary_cta_label' => 'Request a Consultation',
            'seo_title' => 'Custom ERP and CRM Development | Tectignis',
            'seo_description' => 'Custom ERP and CRM systems for sales, operations, inventory, finance and customer management, with integrations, role-based access and reporting.',
            'sort_order' => 3,
            'content' => [
                'bullets' => [
                    'Sales pipelines, customer records, inventory, purchasing, billing and reporting in one place',
                    'Role-based access, approvals and audit trails for every team',
                    'Integrations with accounting, payment, messaging and existing systems',
                    'Data migration from spreadsheets or your current software',
                ],
                'form' => [
                    'heading' => 'Tell Us About Your Processes',
                    'text' => 'Share how your team works today and our team will get back to you with the next steps.',
                ],
                'faq' => [
                    'heading' => 'Frequently Asked Questions',
                    'items' => [
                        ['question' => 'Why build a custom ERP or CRM instead of buying one?', 'answer' => 'Off-the-shelf systems make you adapt to their workflows and charge per user. A custom system follows your processes, includes only what you use, and can grow with your business.'],
                        ['question' => 'How much does ERP or CRM development cost?', 'answer' => 'It depends on the modules, users, integrations and data migration involved. We send a detailed quotation after understanding your processes.'],
                        ['question' => 'Can you move our existing data into the new system?', 'answer' => 'Yes. We migrate data from spreadsheets and existing software, check it with your team and run both side by side where needed before switching over.'],
                        ['question' => 'Can it connect to our accounting and other software?', 'answer' => 'Yes. We integrate with accounting platforms, payment gateways, email and messaging services and other systems through their APIs.'],
                        ['question' => 'Will we own the source code?', 'answer' => 'Yes. On project completion and final payment you receive ownership of the source code and related project assets, as set out in the agreed contract.'],
                        ['question' => 'Do you provide support after launch?', 'answer' => 'Yes. We offer ongoing maintenance, user support, security updates and new modules after the system goes live.'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'saas-development',
            'service_slug' => 'saas-product-development',
            'title' => 'SaaS Product Development',
            'enquiry_service' => 'SaaS',
            'headline' => 'SaaS Product Development from Idea to Launch',
            'supporting_text' => 'Design, build and launch a secure multi-tenant SaaS product with subscriptions, user management and the scale to grow.',
            'primary_cta_label' => 'Discuss Your SaaS Idea',
            'secondary_cta_label' => 'Request a Consultation',
            'seo_title' => 'SaaS Product Development Company | Tectignis',
            'seo_description' => 'Multi-tenant SaaS products with subscription billing, user management, admin dashboards, integrations and cloud hosting, designed and built by the Tectignis team.',
            'sort_order' => 4,
            'content' => [
                'bullets' => [
                    'Multi-tenant architecture with secure separation of customer data',
                    'Subscription plans, billing, trials and customer self-service',
                    'Admin dashboards, analytics and role-based user management',
                    'Cloud hosting, monitoring and ongoing product development',
                ],
                'form' => [
                    'heading' => 'Tell Us About Your Product',
                    'text' => 'Share your idea and who it is for, and our team will get back to you with the next steps.',
                ],
                'faq' => [
                    'heading' => 'Frequently Asked Questions',
                    'items' => [
                        ['question' => 'Can you help us plan the first version?', 'answer' => 'Yes. We work with you to define the core features for a first release, so you can launch, learn from real users and add features in later releases.'],
                        ['question' => 'How much does SaaS development cost?', 'answer' => 'It depends on the features, integrations, billing model and hosting needs. We send a detailed quotation after understanding your product.'],
                        ['question' => 'Do you handle subscriptions and payments?', 'answer' => 'Yes. We integrate payment gateways for subscription plans, trials, invoices and upgrades, suited to the markets you sell in.'],
                        ['question' => 'Where will the product be hosted?', 'answer' => 'We deploy on cloud platforms such as AWS, Azure or Google Cloud, chosen for your budget, customers and compliance needs.'],
                        ['question' => 'Will we own the source code?', 'answer' => 'Yes. On project completion and final payment you receive ownership of the source code and related project assets, as set out in the agreed contract.'],
                        ['question' => 'Do you continue development after launch?', 'answer' => 'Yes. We offer ongoing development, maintenance, monitoring and support as your product and customer base grow.'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'ai-automation',
            'service_slug' => 'business-process-automation',
            'title' => 'AI & Business Automation',
            'enquiry_service' => 'Business Automation',
            'headline' => 'AI and Business Automation for Repetitive Work',
            'supporting_text' => 'Automate data entry, document handling, approvals, customer replies and reporting with AI and workflow automation that fits your systems.',
            'primary_cta_label' => 'Discuss Your Automation',
            'secondary_cta_label' => 'Request a Consultation',
            'seo_title' => 'AI and Business Process Automation Services | Tectignis',
            'seo_description' => 'AI-powered workflow automation, document processing, chatbots and integrations that take repetitive work off your team, with human review where it matters.',
            'sort_order' => 5,
            'content' => [
                'bullets' => [
                    'Workflow automation for approvals, data entry, notifications and reports',
                    'AI document processing, chatbots and assistants trained on your content',
                    'Integrations with your CRM, ERP, email, WhatsApp and other tools',
                    'Human review steps where accuracy and accountability matter',
                ],
                'form' => [
                    'heading' => 'Tell Us What to Automate',
                    'text' => 'Describe the task your team repeats most and our team will get back to you with the next steps.',
                ],
                'faq' => [
                    'heading' => 'Frequently Asked Questions',
                    'items' => [
                        ['question' => 'Which tasks can be automated?', 'answer' => 'Repetitive, rule-based work is the best place to start: data entry, document handling, approvals, notifications, report generation and first-line customer replies.'],
                        ['question' => 'How much does automation cost?', 'answer' => 'It depends on the processes, systems and AI services involved. We send a detailed quotation after reviewing the workflow you want to automate.'],
                        ['question' => 'Will AI make decisions without our team?', 'answer' => 'Only where you choose. We add human review and approval steps wherever accuracy, compliance or customer impact require it.'],
                        ['question' => 'Can it work with our existing software?', 'answer' => 'Yes. We connect automations to your CRM, ERP, email, messaging and other tools through their APIs.'],
                        ['question' => 'Is our data kept secure?', 'answer' => 'Yes. We limit access to the data each automation needs, use secure connections, and agree with you which data may be sent to AI services.'],
                        ['question' => 'Do you support the automations after launch?', 'answer' => 'Yes. We monitor, maintain and improve automations as your processes and tools change.'],
                    ],
                ],
            ],
        ],
        [
            'slug' => 'it-infrastructure',
            'service_slug' => 'networking-solutions',
            'title' => 'IT Infrastructure',
            'enquiry_service' => 'Networking',
            'headline' => 'IT Infrastructure Setup and Support for Your Office',
            'supporting_text' => 'Plan, install and maintain networks, Wi-Fi, servers, CCTV, access control and structured cabling with one accountable team.',
            'primary_cta_label' => 'Request a Site Survey',
            'secondary_cta_label' => 'Discuss Your Requirements',
            'seo_title' => 'IT Infrastructure Services: Networking, CCTV and Servers | Tectignis',
            'seo_description' => 'Office networking, Wi-Fi, structured cabling, servers and storage, CCTV, access control and AMC support, planned, installed and maintained by Tectignis.',
            'sort_order' => 6,
            'content' => [
                'bullets' => [
                    'Networking, Wi-Fi, firewalls and structured cabling',
                    'Servers, storage, backups and workstations',
                    'CCTV, access control and visitor management',
                    'Annual maintenance contracts and on-site support',
                ],
                'form' => [
                    'heading' => 'Tell Us About Your Site',
                    'text' => 'Share your location and what you need, and our team will get back to you with the next steps.',
                ],
                'faq' => [
                    'heading' => 'Frequently Asked Questions',
                    'items' => [
                        ['question' => 'Do you survey the site before quoting?', 'answer' => 'Yes. We assess your site, existing equipment and requirements, then send a detailed quotation with the proposed design and bill of materials.'],
                        ['question' => 'Which areas do you cover for on-site work?', 'answer' => 'Tell us your location in the form and we will confirm on-site coverage for your project.'],
                        ['question' => 'Can you upgrade our existing network or CCTV?', 'answer' => 'Yes. We can reuse suitable equipment, replace what is outdated and document the setup so it is easier to maintain.'],
                        ['question' => 'Do you supply the hardware?', 'answer' => 'Yes. We supply, install and configure networking, server, storage, CCTV and access control equipment, or work with hardware you already have.'],
                        ['question' => 'What happens after installation?', 'answer' => 'You receive handover documentation and can choose an annual maintenance contract for preventive maintenance and support.'],
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
