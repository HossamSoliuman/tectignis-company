<?php

namespace Database\Seeders;

use App\Models\Capability;
use App\Models\Industry;
use App\Models\Service;
use App\Models\TechStack;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Seed the 24 design services across the 3 reconciled categories.
     *
     * Every service is fully populated with the rich Section A→J `content`
     * payload and its TechStack / Industry pivot attachments so the public
     * template renders the complete mockup-matching page out of the box.
     */
    public function run(): void
    {
        $capabilityIds = Capability::pluck('id', 'category');

        foreach ($this->services() as $service) {
            $techNames = $service['tech_stacks'] ?? [];
            $industryNames = $service['industries'] ?? [];

            // Only the real table columns get persisted; the remaining spec keys
            // (hero copy, sub_services, faqs, …) are folded into the JSON content.
            $attributes = [
                'title' => $service['title'],
                'capability_id' => $capabilityIds[$service['category']] ?? null,
                'category' => $service['category'],
                'icon' => $service['icon'] ? 'services/'.$service['icon'] : null,
                'banner_image' => $service['banner_image'] ? 'services/'.$service['banner_image'] : null,
                'sort_order' => $service['sort_order'],
                'short_description' => $service['short_description'],
                'seo_title' => $service['seo_title'],
                'seo_description' => $service['seo_description'],
                'content' => $this->buildContent($service),
                'is_active' => true,
            ];

            $model = Service::updateOrCreate(['slug' => $service['slug']], $attributes);

            $model->techStacks()->sync(TechStack::whereIn('name', $techNames)->pluck('id'));
            $model->industries()->sync(Industry::whereIn('name', $industryNames)->pluck('id'));
        }
    }

    /**
     * Compose the full rich-section content array for a service from its
     * compact spec, filling in sensible shared defaults for the process,
     * feature strip, catalog sections and CTA band.
     *
     * @param  array<string, mixed>  $s
     * @return array<string, mixed>
     */
    private function buildContent(array $s): array
    {
        $title = $s['title'];

        return [
            'hero' => [
                'eyebrow' => $s['eyebrow'] ?? $title,
                'heading' => $s['heading'],
                'intro' => $s['intro'],
                'bullets' => $s['bullets'],
                'cta_primary_label' => $s['cta_primary'] ?? 'Get Free Consultation',
                'cta_secondary_label' => $s['cta_secondary'] ?? 'View Our Work',
            ],
            'features_strip' => [
                'enabled' => true,
                'items' => $this->withNullIcons($s['features'] ?? $this->defaultFeatures(), 'label'),
            ],
            'sub_services' => [
                'enabled' => true,
                'subtitle' => $s['sub_subtitle'] ?? 'What We Offer',
                'heading' => $s['sub_heading'] ?? "Our {$title} Services",
                'items' => $this->withNullIcons($s['sub_services']),
            ],
            'process' => [
                'enabled' => true,
                'subtitle' => 'How We Work',
                'heading' => $s['process_heading'] ?? 'Our Simple Step-by-Step Process',
                'steps' => $this->withNullIcons($s['process'] ?? $this->defaultProcess()),
            ],
            'tech' => [
                'enabled' => true,
                'subtitle' => 'Our Stack',
                'heading' => 'Technologies We Use',
            ],
            'industries' => [
                'enabled' => true,
                'subtitle' => 'Who We Serve',
                'heading' => 'Industries We Serve',
            ],
            'case_studies' => [
                'enabled' => true,
                'subtitle' => 'Proof of Work',
                'heading' => 'Our Recent Success Stories',
            ],
            'why_choose' => [
                'enabled' => true,
                'subtitle' => 'The Tectignis Difference',
                'heading' => $s['why_heading'] ?? "Why Choose Tectignis for {$title}",
                'points' => $s['why_points'],
                'cta_label' => 'Get a Free Quote',
            ],
            'faq' => [
                'enabled' => true,
                'heading' => 'Frequently Asked Questions',
                'items' => $s['faqs'],
            ],
            'cta_band' => [
                'enabled' => true,
                'heading' => $s['cta_heading'] ?? "Ready to Kickstart Your {$title} Journey?",
                'button_label' => $s['cta_button'] ?? 'Get a Free Consultation',
            ],
        ];
    }

    /**
     * Attach a `null` icon to each repeater row that omits one, matching the
     * admin-stored shape (per-item icons are uploaded later from the panel).
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return array<int, array<string, mixed>>
     */
    private function withNullIcons(array $rows): array
    {
        return array_map(fn (array $row) => array_merge(['icon' => null], $row), $rows);
    }

    /**
     * @return array<int, array{label: string}>
     */
    private function defaultFeatures(): array
    {
        return [
            ['label' => 'On-Time Delivery'],
            ['label' => 'Agile Process'],
            ['label' => 'Dedicated Team'],
            ['label' => 'Post-Launch Support'],
        ];
    }

    /**
     * @return array<int, array{title: string, description: string}>
     */
    private function defaultProcess(): array
    {
        return [
            ['title' => 'Requirement Analysis', 'description' => 'We map your goals, users and requirements into a clear, costed project blueprint.'],
            ['title' => 'UI/UX Design', 'description' => 'Intuitive, on-brand interfaces validated with you before development begins.'],
            ['title' => 'Development', 'description' => 'Working software built in short, transparent sprints using modern technology.'],
            ['title' => 'Testing & QA', 'description' => 'Rigorous manual and automated testing for performance, security and reliability.'],
            ['title' => 'Deployment', 'description' => 'Smooth, low-risk launch with data migration and production hardening.'],
            ['title' => 'Support & Maintenance', 'description' => 'Ongoing updates, monitoring and enhancements that keep you ahead.'],
        ];
    }

    /**
     * The 24 service specs. Shared sections (process, features, catalogs, CTA)
     * fall back to sensible defaults; only the service-specific copy is listed.
     *
     * @return array<int, array<string, mixed>>
     */
    private function services(): array
    {
        return array_merge(
            $this->softwareDevelopmentServices(),
            $this->aiAutomationServices(),
            $this->businessApplicationServices(),
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function softwareDevelopmentServices(): array
    {
        $category = 'software_development';

        return [
            [
                'slug' => 'website-design-development',
                'title' => 'Website Design & Development',
                'category' => $category,
                'icon' => 'website-development-com.webp',
                'banner_image' => 'Web-Development-in-Mumbai.webp',
                'sort_order' => 1,
                'short_description' => 'We craft responsive, aesthetically pleasing websites that reflect your brand identity and deliver seamless user experiences across every device.',
                'seo_title' => 'Website Design & Development Services in Navi Mumbai | Tectignis',
                'seo_description' => 'Professional website design & development in Navi Mumbai. Responsive, SEO-friendly, fast-loading websites that turn visitors into customers.',
                'eyebrow' => 'Website Design & Development',
                'heading' => 'Website Design & Development in Navi Mumbai',
                'intro' => 'Your website is your most important salesperson. We design and build responsive, fast and conversion-focused websites that make your brand impossible to ignore.',
                'bullets' => [
                    'Pixel-perfect, mobile-first responsive design',
                    'SEO-ready, lightning-fast page speeds',
                    'CMS so you can update content yourself',
                ],
                'sub_heading' => 'Our Website Design & Development Services',
                'sub_services' => [
                    ['title' => 'Custom Website Design', 'description' => 'Unique, on-brand designs crafted around your audience and business goals.'],
                    ['title' => 'Responsive Development', 'description' => 'Flawless experiences on mobiles, tablets and desktops alike.'],
                    ['title' => 'WordPress Development', 'description' => 'Easy-to-manage WordPress sites with the plugins and flexibility you need.'],
                    ['title' => 'Landing Pages', 'description' => 'High-converting campaign and product landing pages built to perform.'],
                    ['title' => 'Website Redesign', 'description' => 'Modernise an ageing site into a fast, fresh, results-driven experience.'],
                    ['title' => 'Speed & SEO Optimisation', 'description' => 'Technical tuning that gets you ranking and loading in seconds.'],
                ],
                'why_points' => [
                    'Designs that balance beauty with real business results',
                    'Built on clean, standards-compliant, SEO-ready code',
                    'Self-service CMS hands you full control of content',
                    'Blazing performance scores and Core Web Vitals',
                    'Ongoing care plans to keep your site secure and current',
                ],
                'faqs' => [
                    ['question' => 'How long does it take to build a website?', 'answer' => 'A typical business website launches in 3-5 weeks depending on page count and custom features. We share a clear timeline after the discovery call.'],
                    ['question' => 'Will my website be mobile-friendly?', 'answer' => 'Always. Every site we build is mobile-first and tested across phones, tablets and desktops.'],
                    ['question' => 'Can I update the website myself?', 'answer' => 'Yes. We build on a CMS and train your team so you can edit content, images and pages without touching code.'],
                    ['question' => 'Do you provide SEO and hosting?', 'answer' => 'We deliver SEO-ready websites and can manage hosting, domains and ongoing maintenance under an affordable care plan.'],
                ],
                'tech_stacks' => ['WordPress', 'PHP', 'Laravel', 'React', 'Next.js', 'MySQL'],
                'industries' => ['Retail', 'Real Estate', 'Hospitality', 'Healthcare', 'Startups', 'Corporate Offices'],
                'cta_heading' => 'Ready to Launch a Website That Works as Hard as You Do?',
            ],
            [
                'slug' => 'web-application-development',
                'title' => 'Web Application Development',
                'category' => $category,
                'icon' => 'web-app-development1.webp',
                'banner_image' => 'Web-App-Development-in-Mumbai.webp',
                'sort_order' => 2,
                'short_description' => 'We build powerful, secure and intuitive web applications that streamline your operations and scale effortlessly with your business.',
                'seo_title' => 'Web Application Development Services in Navi Mumbai | Tectignis',
                'seo_description' => 'Expert web application development in Navi Mumbai. Secure, scalable, custom web apps and portals built to streamline your business.',
                'eyebrow' => 'Web Application Development',
                'heading' => 'Web Application Development in Navi Mumbai',
                'intro' => 'From customer portals to complex SaaS platforms, we engineer web applications that are fast, secure and built to grow with every new user.',
                'bullets' => [
                    'Custom, scalable cloud-ready architecture',
                    'Bank-grade security and data protection',
                    'Seamless integration with your existing tools',
                ],
                'sub_heading' => 'Our Web Application Development Services',
                'sub_services' => [
                    ['title' => 'Custom Web Apps', 'description' => 'Bespoke applications engineered around your exact business logic.'],
                    ['title' => 'Progressive Web Apps', 'description' => 'App-like, installable experiences that work even offline.'],
                    ['title' => 'SaaS Platforms', 'description' => 'Multi-tenant, subscription-ready products built to scale.'],
                    ['title' => 'Customer & Vendor Portals', 'description' => 'Secure self-service portals that cut manual work and delight users.'],
                    ['title' => 'API Development & Integration', 'description' => 'Robust APIs connecting your systems into one seamless ecosystem.'],
                    ['title' => 'Cloud Migration', 'description' => 'Move legacy apps to a faster, cheaper, more reliable cloud setup.'],
                ],
                'why_points' => [
                    'Architecture designed to handle real-world scale',
                    'Security and performance baked in from day one',
                    'Clean, documented code you fully own',
                    'Agile delivery with weekly visibility',
                    'Long-term support and enhancement plans',
                ],
                'faqs' => [
                    ['question' => 'What is the difference between a website and a web application?', 'answer' => 'A website mostly presents information, while a web application lets users perform tasks — dashboards, portals, booking systems and SaaS products are all web apps.'],
                    ['question' => 'Which technologies do you use?', 'answer' => 'We choose the right stack per project — typically Laravel, Node.js, React or Vue — and host on AWS or Azure for reliability.'],
                    ['question' => 'Can you integrate with our existing systems?', 'answer' => 'Yes. We build and consume APIs to connect your CRM, ERP, payment gateways and any third-party services.'],
                    ['question' => 'Is the application secure and scalable?', 'answer' => 'Security and scalability are core to our builds — role-based access, encryption, and cloud-native architecture that grows with demand.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'Node.js', 'React', 'Vue.js', 'PostgreSQL', 'AWS', 'Docker'],
                'industries' => ['Finance & Banking', 'Healthcare', 'Logistics', 'E-commerce', 'Startups', 'Corporate Offices'],
                'cta_heading' => "Let's Build Your Next Web Application",
            ],
            [
                'slug' => 'ecommerce-development',
                'title' => 'E-commerce Development',
                'category' => $category,
                'icon' => 'ecommerce-development.webp',
                'banner_image' => 'E-commerce-Development-Services.webp',
                'sort_order' => 3,
                'short_description' => 'We build customised e-commerce stores that help you launch, sell and scale online with a smooth, conversion-driven shopping experience.',
                'seo_title' => 'E-commerce Development Services in Navi Mumbai | Tectignis',
                'seo_description' => 'Custom e-commerce development in Navi Mumbai. Build fast, secure online stores on Shopify, WooCommerce or custom platforms that convert.',
                'eyebrow' => 'E-commerce Development',
                'heading' => 'E-commerce Development in Navi Mumbai',
                'intro' => 'Turn browsers into buyers with a fast, secure online store designed to convert. We build e-commerce experiences that grow average order value and customer loyalty.',
                'bullets' => [
                    'Conversion-optimised storefront design',
                    'Secure payments and multi-gateway support',
                    'Inventory, orders and shipping automated',
                ],
                'sub_heading' => 'Our E-commerce Development Services',
                'sub_services' => [
                    ['title' => 'Custom Online Stores', 'description' => 'Bespoke storefronts built around your products and brand.'],
                    ['title' => 'Shopify Development', 'description' => 'Beautiful, conversion-ready Shopify stores set up and customised.'],
                    ['title' => 'WooCommerce & Magento', 'description' => 'Flexible, scalable stores on the world\'s leading platforms.'],
                    ['title' => 'Marketplace Development', 'description' => 'Multi-vendor marketplaces with seller dashboards and commissions.'],
                    ['title' => 'Payment & Logistics Integration', 'description' => 'Connect gateways, couriers and tax tools for end-to-end automation.'],
                    ['title' => 'Store Migration & Upgrades', 'description' => 'Move or upgrade your store with zero data loss and no downtime.'],
                ],
                'why_points' => [
                    'Stores engineered to maximise conversions and AOV',
                    'Secure, PCI-aware checkout your customers trust',
                    'Automated inventory, orders and fulfilment',
                    'Mobile-first design where most sales happen',
                    'Analytics and marketing integrations built in',
                ],
                'faqs' => [
                    ['question' => 'Which e-commerce platform is best for me?', 'answer' => 'It depends on your catalogue size, budget and growth plans. We recommend Shopify, WooCommerce, Magento or a custom build after understanding your needs.'],
                    ['question' => 'Can you integrate Indian payment gateways?', 'answer' => 'Yes — Razorpay, PayU, CCAvenue, Stripe, PayPal and more, plus UPI and COD workflows.'],
                    ['question' => 'Will my store be mobile-friendly?', 'answer' => 'Absolutely. We design mobile-first, since the majority of online shopping now happens on phones.'],
                    ['question' => 'Do you help with launch and marketing?', 'answer' => 'We set you up with SEO, analytics and marketing integrations, and offer digital marketing services to drive traffic and sales.'],
                ],
                'tech_stacks' => ['Shopify', 'Magento', 'WordPress', 'Laravel', 'React', 'MySQL'],
                'industries' => ['Retail', 'E-commerce', 'Manufacturing', 'Hospitality', 'Startups'],
                'cta_heading' => 'Ready to Sell More Online?',
            ],
            [
                'slug' => 'mobile-app-development',
                'title' => 'Mobile App Development',
                'category' => $category,
                'icon' => 'mobile-app-development.webp',
                'banner_image' => 'App-Development-Company-in-Mumbai.webp',
                'sort_order' => 4,
                'short_description' => 'We create high-quality native and cross-platform mobile apps for Android and iOS, tailored to your business goals and your users.',
                'seo_title' => 'Mobile App Development Services in Navi Mumbai | Tectignis',
                'seo_description' => 'Android & iOS mobile app development in Navi Mumbai. Native and cross-platform apps built by expert developers for startups and enterprises.',
                'eyebrow' => 'Mobile App Development',
                'heading' => 'Mobile App Development in Navi Mumbai',
                'intro' => 'Put your business in every customer\'s pocket. We design and develop stunning, high-performance mobile apps that users love and businesses rely on.',
                'bullets' => [
                    'Native and cross-platform expertise',
                    'Intuitive UX that drives engagement',
                    'App Store and Play Store launch support',
                ],
                'sub_heading' => 'Our Mobile App Development Services',
                'sub_services' => [
                    ['title' => 'Android App Development', 'description' => 'High-performance native Android apps built in Kotlin.'],
                    ['title' => 'iOS App Development', 'description' => 'Polished native iOS apps crafted in Swift for the Apple ecosystem.'],
                    ['title' => 'Cross-Platform Apps', 'description' => 'One codebase, two platforms with Flutter and React Native.'],
                    ['title' => 'UI/UX App Design', 'description' => 'Engaging, intuitive interfaces designed for retention.'],
                    ['title' => 'App Modernisation', 'description' => 'Refresh and re-engineer ageing apps for speed and stability.'],
                    ['title' => 'Maintenance & Support', 'description' => 'Continuous updates, monitoring and OS-version compatibility.'],
                ],
                'why_points' => [
                    'Apps that feel fast, native and effortless',
                    'Designed for engagement, retention and reviews',
                    'Secure handling of user data and payments',
                    'End-to-end delivery from idea to store launch',
                    'Post-launch support that keeps apps healthy',
                ],
                'faqs' => [
                    ['question' => 'Should I build native or cross-platform?', 'answer' => 'Cross-platform (Flutter/React Native) is cost-effective and fast for most apps; native is ideal when you need maximum performance or deep device features. We advise based on your goals.'],
                    ['question' => 'How much does an app cost?', 'answer' => 'It varies with features and complexity. After a discovery session we provide a detailed, fixed estimate with no surprises.'],
                    ['question' => 'Do you handle App Store and Play Store submission?', 'answer' => 'Yes, we manage the entire submission, review and launch process on both stores.'],
                    ['question' => 'Will you maintain the app after launch?', 'answer' => 'We offer support plans covering updates, bug fixes, new features and compatibility with the latest OS versions.'],
                ],
                'tech_stacks' => ['Flutter', 'React Native', 'Kotlin', 'Swift', 'Node.js', 'MongoDB'],
                'industries' => ['Healthcare', 'Retail', 'Logistics', 'Hospitality', 'Startups', 'Finance & Banking'],
                'cta_heading' => 'Have an App Idea? Let\'s Bring It to Life',
            ],
            [
                'slug' => 'custom-software-development',
                'title' => 'Custom Software Development',
                'category' => $category,
                'icon' => 'Customize-Software-Development1.webp',
                'banner_image' => 'Custom-Software.webp',
                'sort_order' => 5,
                'short_description' => 'We design and develop tailored software solutions that address your unique business challenges, enhancing productivity and competitiveness.',
                'seo_title' => 'Custom Software Development Services in Navi Mumbai | Tectignis',
                'seo_description' => 'Customized software development in Navi Mumbai. ERP, CRM, SaaS and enterprise-grade software built to your exact requirements.',
                'eyebrow' => 'Custom Software Development',
                'heading' => 'Custom Software Development Built Around Your Business',
                'intro' => 'From ERP and CRM platforms to bespoke SaaS products, we build secure, scalable software tailored to the exact way your business works — not the other way around.',
                'bullets' => [
                    'Tailor-made solutions, zero off-the-shelf compromises',
                    'Agile delivery with transparent weekly progress',
                    'Scalable architecture that grows with you',
                ],
                'sub_heading' => 'Our Custom Software Development Services',
                'sub_subtitle' => 'What We Build',
                'sub_services' => [
                    ['title' => 'Enterprise Software', 'description' => 'Robust, secure systems that streamline complex operations across teams and departments.'],
                    ['title' => 'ERP Development', 'description' => 'Unify finance, inventory, HR and operations into a single source of truth built to your workflow.'],
                    ['title' => 'CRM Development', 'description' => 'Custom CRM platforms that centralise leads, customers and sales pipelines for measurable growth.'],
                    ['title' => 'SaaS Product Development', 'description' => 'Multi-tenant, subscription-ready products engineered to scale from your first customer to your thousandth.'],
                    ['title' => 'API & System Integration', 'description' => 'Connect your existing tools and third-party services into one seamless, automated ecosystem.'],
                    ['title' => 'Legacy Modernization', 'description' => 'Re-platform ageing systems into fast, secure, maintainable modern applications without losing data.'],
                ],
                'process_heading' => 'Our Proven Development Process',
                'process' => [
                    ['title' => 'Discovery & Analysis', 'description' => 'We map your goals, users and requirements into a clear, costed project blueprint.'],
                    ['title' => 'UI/UX Design', 'description' => 'Intuitive, on-brand interfaces validated with you before a line of code is written.'],
                    ['title' => 'Agile Development', 'description' => 'Working software shipped in short sprints with visibility at every step.'],
                    ['title' => 'QA & Testing', 'description' => 'Rigorous manual and automated testing for performance, security and reliability.'],
                    ['title' => 'Deployment', 'description' => 'Smooth, low-risk launch with data migration and production hardening.'],
                    ['title' => 'Support & Maintenance', 'description' => 'Ongoing updates, monitoring and enhancements that keep you ahead.'],
                ],
                'why_heading' => 'Why Choose Tectignis for Custom Software',
                'why_points' => [
                    'A dedicated team that learns your business inside out',
                    'Clean, documented, maintainable code you fully own',
                    'Security and scalability baked in from day one',
                    'Transparent fixed-scope and time-and-material engagement models',
                    'Long-term support that protects your investment',
                ],
                'faqs' => [
                    ['question' => 'How much does custom software development cost?', 'answer' => 'Cost depends on scope, complexity and integrations. After a free discovery call we provide a detailed, fixed estimate so there are no surprises.'],
                    ['question' => 'How long does a typical project take?', 'answer' => 'A focused MVP usually ships in 8-12 weeks, while larger enterprise platforms are delivered in phased agile releases so you see value early.'],
                    ['question' => 'Do I own the source code?', 'answer' => 'Yes. You receive full ownership of the source code, documentation and intellectual property on completion.'],
                    ['question' => 'Do you provide support after launch?', 'answer' => 'Absolutely. We offer flexible AMC and support plans covering monitoring, updates and feature enhancements.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'Python', 'React', 'Node.js', 'MySQL', 'PostgreSQL', 'AWS'],
                'industries' => ['Manufacturing', 'Healthcare', 'Retail', 'Logistics', 'Real Estate', 'Corporate Offices'],
                'cta_heading' => 'Ready to Build Software That Works the Way You Do?',
            ],
            [
                'slug' => 'crm-development',
                'title' => 'CRM Development',
                'category' => $category,
                'icon' => 'Customize-Software-Development1.webp',
                'banner_image' => 'Custom-Software.webp',
                'sort_order' => 6,
                'short_description' => 'We build custom CRM platforms that centralise your leads, customers and sales pipeline so your team sells smarter and retains more.',
                'seo_title' => 'CRM Development Services in Navi Mumbai | Custom CRM Software',
                'seo_description' => 'Custom CRM development in Navi Mumbai. Centralise leads, automate sales pipelines and grow customer relationships with bespoke CRM software.',
                'eyebrow' => 'CRM Development',
                'heading' => 'Custom CRM Development in Navi Mumbai',
                'intro' => 'Stop losing leads in spreadsheets. We build CRM platforms tailored to your sales process so every lead, customer and conversation lives in one place.',
                'bullets' => [
                    'A 360° view of every customer',
                    'Automated follow-ups and sales pipelines',
                    'Dashboards that turn data into decisions',
                ],
                'sub_heading' => 'Our CRM Development Services',
                'sub_services' => [
                    ['title' => 'Custom CRM Platforms', 'description' => 'Built precisely around your sales stages and workflows.'],
                    ['title' => 'Sales Automation', 'description' => 'Automated lead routing, reminders and follow-up sequences.'],
                    ['title' => 'Lead & Contact Management', 'description' => 'A single, searchable source of truth for every relationship.'],
                    ['title' => 'CRM Integrations', 'description' => 'Connect email, telephony, WhatsApp, ERP and marketing tools.'],
                    ['title' => 'Analytics & Reporting', 'description' => 'Real-time dashboards on pipeline, performance and forecasts.'],
                    ['title' => 'CRM Migration', 'description' => 'Move off rigid off-the-shelf CRMs without losing history.'],
                ],
                'why_points' => [
                    'A CRM that fits your process, not the reverse',
                    'Automation that frees your team to sell',
                    'Actionable analytics and accurate forecasting',
                    'Seamless integration with your existing stack',
                    'You own the platform and the data inside it',
                ],
                'faqs' => [
                    ['question' => 'Why build a custom CRM instead of using Salesforce or Zoho?', 'answer' => 'Off-the-shelf CRMs force you to adapt to them and charge per user forever. A custom CRM fits your exact process, integrates deeply with your tools, and you own it outright.'],
                    ['question' => 'Can you migrate our existing CRM data?', 'answer' => 'Yes. We migrate contacts, deals and history from spreadsheets or existing CRMs with full data integrity.'],
                    ['question' => 'Can the CRM integrate with WhatsApp and email?', 'answer' => 'Absolutely — we integrate email, telephony, WhatsApp, marketing tools and your ERP for a unified workflow.'],
                    ['question' => 'Will it work on mobile?', 'answer' => 'Yes, your team can manage leads and deals from any device, including dedicated mobile access.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'Vue.js', 'MySQL', 'Redis'],
                'industries' => ['Real Estate', 'Finance & Banking', 'Retail', 'Manufacturing', 'Corporate Offices', 'Startups'],
                'cta_heading' => 'Ready to Turn Leads Into Loyal Customers?',
            ],
            [
                'slug' => 'erp-development',
                'title' => 'ERP Development',
                'category' => $category,
                'icon' => 'Custom-Software.webp',
                'banner_image' => 'Software-Companies-in-Navi-Mumbai.webp',
                'sort_order' => 7,
                'short_description' => 'We develop custom ERP systems that unify finance, inventory, HR and operations into one connected platform built around your workflow.',
                'seo_title' => 'ERP Development Services in Navi Mumbai | Custom ERP Software',
                'seo_description' => 'Custom ERP development in Navi Mumbai. Unify finance, inventory, HR and operations into one scalable ERP platform built for your business.',
                'eyebrow' => 'ERP Development',
                'heading' => 'Custom ERP Development in Navi Mumbai',
                'intro' => 'Run your entire business from one dashboard. We build custom ERP systems that connect every department, eliminate data silos and give you real-time control.',
                'bullets' => [
                    'One platform for your whole business',
                    'Real-time visibility across departments',
                    'Modular — start small, scale as you grow',
                ],
                'sub_heading' => 'Our ERP Development Services',
                'sub_services' => [
                    ['title' => 'Finance & Accounting', 'description' => 'Automated invoicing, ledgers, GST and financial reporting.'],
                    ['title' => 'Inventory & Procurement', 'description' => 'Real-time stock, purchase orders and supplier management.'],
                    ['title' => 'HR & Payroll', 'description' => 'Employee records, attendance, leave and payroll in one module.'],
                    ['title' => 'Manufacturing & Production', 'description' => 'BOM, work orders and production planning that keep lines moving.'],
                    ['title' => 'Sales & CRM Module', 'description' => 'Quotations, orders and customer management tied to operations.'],
                    ['title' => 'ERP Integration', 'description' => 'Connect existing tools, e-commerce and third-party systems.'],
                ],
                'why_points' => [
                    'A single source of truth across the company',
                    'Modules tailored to how you actually operate',
                    'Real-time reports for faster, better decisions',
                    'Scales smoothly as you add users and locations',
                    'Lower long-term cost than per-seat licensing',
                ],
                'faqs' => [
                    ['question' => 'How is a custom ERP better than SAP or Tally?', 'answer' => 'A custom ERP includes only the modules you need, mirrors your workflow exactly, and avoids costly per-user licensing — and you own it.'],
                    ['question' => 'Can we roll it out module by module?', 'answer' => 'Yes. We deliver in phases so you can start with the highest-impact modules and expand over time.'],
                    ['question' => 'Will it integrate with our existing software?', 'answer' => 'Yes, we integrate with accounting tools, e-commerce, payment gateways and any system exposing an API.'],
                    ['question' => 'Is the ERP cloud-based?', 'answer' => 'We build cloud-ready ERPs accessible from anywhere, with on-premise options where compliance requires it.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'Python', 'PostgreSQL', 'MySQL', 'AWS', 'Docker'],
                'industries' => ['Manufacturing', 'Logistics', 'Retail', 'Healthcare', 'Corporate Offices'],
                'cta_heading' => 'Ready to Run Your Business From One Platform?',
            ],
            [
                'slug' => 'saas-product-development',
                'title' => 'SaaS Product Development',
                'category' => $category,
                'icon' => 'Cloud-Computing.webp',
                'banner_image' => 'Cloud-Computing.webp',
                'sort_order' => 8,
                'short_description' => 'We help founders and enterprises build scalable, multi-tenant SaaS products — from MVP to market-ready platform.',
                'seo_title' => 'SaaS Product Development Services in Navi Mumbai | Tectignis',
                'seo_description' => 'SaaS product development in Navi Mumbai. Build scalable, multi-tenant, subscription-ready SaaS platforms from MVP to launch.',
                'eyebrow' => 'SaaS Product Development',
                'heading' => 'SaaS Product Development in Navi Mumbai',
                'intro' => 'Have a product idea? We turn it into a scalable, subscription-ready SaaS platform — engineered for multi-tenancy, security and growth from your first user.',
                'bullets' => [
                    'MVP-first approach to reach market fast',
                    'Multi-tenant, subscription-ready architecture',
                    'Built to scale to thousands of users',
                ],
                'sub_heading' => 'Our SaaS Development Services',
                'sub_services' => [
                    ['title' => 'MVP Development', 'description' => 'Launch a lean, lovable product quickly and validate with real users.'],
                    ['title' => 'Multi-Tenant Architecture', 'description' => 'Secure tenant isolation that scales efficiently as you grow.'],
                    ['title' => 'Subscription & Billing', 'description' => 'Plans, trials, metering and gateways like Stripe and Razorpay.'],
                    ['title' => 'Admin & Analytics Dashboards', 'description' => 'Powerful dashboards for users, usage and revenue insights.'],
                    ['title' => 'API & Integrations', 'description' => 'Open APIs and webhooks so your product plays well with others.'],
                    ['title' => 'Scaling & DevOps', 'description' => 'Cloud-native infrastructure, CI/CD and monitoring for uptime.'],
                ],
                'why_points' => [
                    'Product thinking, not just code',
                    'Architecture that scales without rewrites',
                    'Secure multi-tenancy and data isolation',
                    'Faster time-to-market with an MVP-first plan',
                    'Ongoing partnership through every growth stage',
                ],
                'faqs' => [
                    ['question' => 'What is an MVP and why start there?', 'answer' => 'A Minimum Viable Product is the smallest version that delivers core value. Starting there gets you to market faster, cheaper, and lets real users guide what to build next.'],
                    ['question' => 'How do you handle multi-tenancy?', 'answer' => 'We design secure tenant isolation at the data and application layers so each customer\'s data stays private while sharing efficient infrastructure.'],
                    ['question' => 'Can you add subscription billing?', 'answer' => 'Yes — plans, free trials, usage metering and gateways such as Stripe, Razorpay and Paddle are all part of our SaaS builds.'],
                    ['question' => 'Will the product scale as we grow?', 'answer' => 'Absolutely. We use cloud-native, horizontally scalable architecture with CI/CD and monitoring so growth never breaks the platform.'],
                ],
                'tech_stacks' => ['Laravel', 'Node.js', 'React', 'Next.js', 'PostgreSQL', 'Redis', 'AWS', 'Kubernetes'],
                'industries' => ['Startups', 'Finance & Banking', 'Healthcare', 'E-commerce', 'Corporate Offices'],
                'cta_heading' => 'Ready to Build Your SaaS Product?',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function aiAutomationServices(): array
    {
        $category = 'ai_automation';

        return [
            [
                'slug' => 'ai-chatbot-development',
                'title' => 'AI Chatbot Development',
                'category' => $category,
                'icon' => 'Artificial-Intelligence.webp',
                'banner_image' => 'Artificial-Intelligence.webp',
                'sort_order' => 9,
                'short_description' => 'We build intelligent AI chatbots that engage customers 24/7, answer questions instantly and convert conversations into business.',
                'seo_title' => 'AI Chatbot Development Services in Navi Mumbai | Tectignis',
                'seo_description' => 'AI chatbot development in Navi Mumbai. Build smart, 24/7 conversational chatbots for support, sales and lead generation powered by GPT.',
                'eyebrow' => 'AI Chatbot Development',
                'heading' => 'AI Chatbot Development in Navi Mumbai',
                'intro' => 'Never miss a customer again. We build AI-powered chatbots that understand natural language, answer instantly around the clock and turn conversations into conversions.',
                'bullets' => [
                    '24/7 instant customer engagement',
                    'Powered by GPT and modern LLMs',
                    'Trained on your own business knowledge',
                ],
                'sub_heading' => 'Our AI Chatbot Development Services',
                'sub_services' => [
                    ['title' => 'Customer Support Bots', 'description' => 'Resolve common queries instantly and escalate the rest to humans.'],
                    ['title' => 'Lead Generation Bots', 'description' => 'Qualify and capture leads automatically, day and night.'],
                    ['title' => 'GPT-Powered Assistants', 'description' => 'Context-aware assistants trained on your documents and data.'],
                    ['title' => 'WhatsApp & Messenger Bots', 'description' => 'Meet customers on the channels they already use.'],
                    ['title' => 'Voice-Enabled Bots', 'description' => 'Natural voice interfaces for hands-free interactions.'],
                    ['title' => 'Chatbot Integration', 'description' => 'Plug into your CRM, helpdesk, website and apps.'],
                ],
                'why_points' => [
                    'Bots that actually understand intent, not keywords',
                    'Trained securely on your own knowledge base',
                    'Cuts support costs while improving response times',
                    'Seamless human handoff when it matters',
                    'Continuous tuning from real conversations',
                ],
                'faqs' => [
                    ['question' => 'What can an AI chatbot do for my business?', 'answer' => 'It can handle customer support, qualify leads, book appointments and answer FAQs 24/7 — reducing workload while improving customer experience.'],
                    ['question' => 'Is it powered by ChatGPT?', 'answer' => 'We use leading LLMs such as OpenAI\'s GPT models, tuned and grounded on your business data for accurate, on-brand responses.'],
                    ['question' => 'Can it handle my industry\'s specific questions?', 'answer' => 'Yes. We train the bot on your documents, products and policies so it answers with your knowledge, not generic guesses.'],
                    ['question' => 'Which channels does it support?', 'answer' => 'Website, WhatsApp, Messenger, mobile apps and more — all connected to your CRM or helpdesk.'],
                ],
                'tech_stacks' => ['OpenAI', 'LangChain', 'Python', 'Node.js', 'React', 'MongoDB'],
                'industries' => ['Retail', 'E-commerce', 'Healthcare', 'Finance & Banking', 'Travel & Tourism', 'Startups'],
                'cta_heading' => 'Ready to Automate Conversations With AI?',
            ],
            [
                'slug' => 'ai-integration',
                'title' => 'AI Integration',
                'category' => $category,
                'icon' => 'Artificial-Intelligence.webp',
                'banner_image' => 'Artificial-Intelligence.webp',
                'sort_order' => 10,
                'short_description' => 'We integrate AI capabilities into your existing apps and workflows — adding intelligence, automation and insight without rebuilding from scratch.',
                'seo_title' => 'AI Integration Services in Navi Mumbai | Tectignis',
                'seo_description' => 'AI integration services in Navi Mumbai. Add GPT, machine learning and intelligent automation to your existing software and workflows.',
                'eyebrow' => 'AI Integration',
                'heading' => 'AI Integration Services in Navi Mumbai',
                'intro' => 'Make the software you already use smarter. We embed AI — from GPT assistants to predictive models — into your existing apps, websites and workflows.',
                'bullets' => [
                    'Add AI without rebuilding your systems',
                    'GPT, vision and predictive capabilities',
                    'Secure, scalable API-based integration',
                ],
                'sub_heading' => 'Our AI Integration Services',
                'sub_services' => [
                    ['title' => 'GPT & LLM Integration', 'description' => 'Embed natural-language AI into your products and tools.'],
                    ['title' => 'Predictive Analytics', 'description' => 'Forecast demand, churn and trends from your own data.'],
                    ['title' => 'Computer Vision', 'description' => 'Image recognition, quality checks and document scanning.'],
                    ['title' => 'Recommendation Engines', 'description' => 'Personalised product and content suggestions that convert.'],
                    ['title' => 'Workflow Automation', 'description' => 'Let AI handle repetitive, rules-based tasks end to end.'],
                    ['title' => 'AI API Development', 'description' => 'Custom AI services exposed cleanly to your apps.'],
                ],
                'why_points' => [
                    'AI added to what you already own — no rip-and-replace',
                    'Practical use cases tied to real ROI',
                    'Secure handling of your proprietary data',
                    'Right-sized models for cost and performance',
                    'Ongoing tuning as your data grows',
                ],
                'faqs' => [
                    ['question' => 'Can you add AI to our existing software?', 'answer' => 'Yes. We integrate AI through APIs and modules so your current systems gain intelligence without a costly rebuild.'],
                    ['question' => 'What AI use cases give the best ROI?', 'answer' => 'Common wins include support automation, document processing, predictive analytics and personalised recommendations — we help you pick the highest-impact ones.'],
                    ['question' => 'Is our data safe?', 'answer' => 'We follow strict data-handling practices, support private deployments, and never train public models on your confidential data.'],
                    ['question' => 'Which AI providers do you work with?', 'answer' => 'OpenAI, Azure OpenAI, open-source LLMs and custom models — we choose based on accuracy, privacy and cost.'],
                ],
                'tech_stacks' => ['OpenAI', 'LangChain', 'Python', 'TensorFlow', 'PyTorch', 'AWS'],
                'industries' => ['Manufacturing', 'Finance & Banking', 'Healthcare', 'Retail', 'Logistics', 'Corporate Offices'],
                'cta_heading' => 'Ready to Make Your Software Intelligent?',
            ],
            [
                'slug' => 'generative-ai-solutions',
                'title' => 'Generative AI Solutions',
                'category' => $category,
                'icon' => 'Emerging-Technologies.webp',
                'banner_image' => 'Emerging-Technologies.webp',
                'sort_order' => 11,
                'short_description' => 'We build custom generative AI solutions — from content and image generation to intelligent assistants — tailored to your business.',
                'seo_title' => 'Generative AI Solutions in Navi Mumbai | Tectignis',
                'seo_description' => 'Generative AI development in Navi Mumbai. Custom GenAI solutions for content, images, code and intelligent assistants built on GPT and LLMs.',
                'eyebrow' => 'Generative AI Solutions',
                'heading' => 'Generative AI Solutions in Navi Mumbai',
                'intro' => 'Harness the power of generative AI for your business. We build custom GenAI solutions that create content, summarise knowledge and automate creative and cognitive work.',
                'bullets' => [
                    'Custom models grounded on your data',
                    'Content, image and code generation',
                    'Enterprise-grade privacy and control',
                ],
                'sub_heading' => 'Our Generative AI Services',
                'sub_services' => [
                    ['title' => 'Custom GenAI Applications', 'description' => 'Bespoke generative tools built around your use case.'],
                    ['title' => 'Content Generation', 'description' => 'On-brand copy, descriptions and marketing assets at scale.'],
                    ['title' => 'RAG & Knowledge Assistants', 'description' => 'Retrieval-augmented assistants that answer from your documents.'],
                    ['title' => 'Image & Media Generation', 'description' => 'Generate visuals, mockups and creative assets on demand.'],
                    ['title' => 'AI Document Summarisation', 'description' => 'Turn long reports and contracts into instant insights.'],
                    ['title' => 'LLM Fine-Tuning', 'description' => 'Tune models to your domain, tone and terminology.'],
                ],
                'why_points' => [
                    'Generative AI focused on measurable outcomes',
                    'Your data stays private and under your control',
                    'Grounded outputs that reduce hallucination',
                    'Scalable architecture for high-volume use',
                    'Expert guidance from use case to deployment',
                ],
                'faqs' => [
                    ['question' => 'What is generative AI used for in business?', 'answer' => 'Generating marketing content, summarising documents, drafting code, powering smart assistants and creating images — anything that produces new content or insight from data.'],
                    ['question' => 'How do you prevent inaccurate AI answers?', 'answer' => 'We use retrieval-augmented generation (RAG) to ground responses in your verified data, plus guardrails and review workflows.'],
                    ['question' => 'Can the AI be trained on our content?', 'answer' => 'Yes — through RAG and fine-tuning we adapt the model to your documents, tone and domain.'],
                    ['question' => 'Is our data shared with public models?', 'answer' => 'No. We support private and enterprise deployments so your confidential data is never used to train public models.'],
                ],
                'tech_stacks' => ['OpenAI', 'LangChain', 'Python', 'PyTorch', 'TensorFlow', 'AWS'],
                'industries' => ['Startups', 'E-commerce', 'Healthcare', 'Finance & Banking', 'Corporate Offices'],
                'cta_heading' => 'Ready to Put Generative AI to Work?',
            ],
            [
                'slug' => 'machine-learning-solutions',
                'title' => 'Machine Learning Solutions',
                'category' => $category,
                'icon' => 'Data-Science.webp',
                'banner_image' => 'Data-Science.webp',
                'sort_order' => 12,
                'short_description' => 'We develop machine learning models that turn your data into predictions, automation and a real competitive advantage.',
                'seo_title' => 'Machine Learning Solutions in Navi Mumbai | Tectignis',
                'seo_description' => 'Machine learning development in Navi Mumbai. Custom ML models for prediction, classification, forecasting and intelligent automation.',
                'eyebrow' => 'Machine Learning Solutions',
                'heading' => 'Machine Learning Solutions in Navi Mumbai',
                'intro' => 'Your data is an untapped asset. We build machine learning models that forecast, classify and automate — turning raw data into smarter decisions and new revenue.',
                'bullets' => [
                    'Custom models trained on your data',
                    'Forecasting, classification and detection',
                    'Production-ready MLOps deployment',
                ],
                'sub_heading' => 'Our Machine Learning Services',
                'sub_services' => [
                    ['title' => 'Predictive Modelling', 'description' => 'Forecast sales, demand, churn and maintenance needs.'],
                    ['title' => 'Classification & Detection', 'description' => 'Automatically categorise, score and flag what matters.'],
                    ['title' => 'Recommendation Systems', 'description' => 'Personalise products and content to lift engagement.'],
                    ['title' => 'Computer Vision', 'description' => 'Detect defects, read images and automate visual checks.'],
                    ['title' => 'NLP Solutions', 'description' => 'Extract meaning from text, reviews and documents.'],
                    ['title' => 'MLOps & Deployment', 'description' => 'Reliable pipelines that keep models accurate in production.'],
                ],
                'why_points' => [
                    'Models tied to clear business outcomes',
                    'Data engineering and ML under one roof',
                    'Transparent, explainable results',
                    'Production-grade deployment and monitoring',
                    'Continuous retraining as data evolves',
                ],
                'faqs' => [
                    ['question' => 'Do we have enough data for machine learning?', 'answer' => 'Often yes — we assess your data first and can also use augmentation, transfer learning or external datasets to get strong results.'],
                    ['question' => 'What problems can ML solve for us?', 'answer' => 'Demand forecasting, fraud and anomaly detection, customer churn, recommendations, predictive maintenance and more.'],
                    ['question' => 'How do you deploy models into production?', 'answer' => 'We use MLOps pipelines for versioning, automated retraining and monitoring so models stay accurate over time.'],
                    ['question' => 'Will the results be explainable?', 'answer' => 'Yes. We prioritise interpretable models and provide explanations so stakeholders trust the predictions.'],
                ],
                'tech_stacks' => ['Python', 'TensorFlow', 'PyTorch', 'AWS', 'Docker', 'PostgreSQL'],
                'industries' => ['Manufacturing', 'Finance & Banking', 'Retail', 'Healthcare', 'Logistics'],
                'cta_heading' => 'Ready to Turn Data Into Decisions?',
            ],
            [
                'slug' => 'business-process-automation',
                'title' => 'Business Process Automation',
                'category' => $category,
                'icon' => 'Emerging-Technologies.webp',
                'banner_image' => 'Emerging-Technologies.webp',
                'sort_order' => 13,
                'short_description' => 'We automate repetitive, manual business processes so your team can focus on high-value work — cutting costs and eliminating errors.',
                'seo_title' => 'Business Process Automation Services in Navi Mumbai | Tectignis',
                'seo_description' => 'Business process automation in Navi Mumbai. Automate manual workflows, approvals and data entry to cut costs and eliminate errors.',
                'eyebrow' => 'Business Process Automation',
                'heading' => 'Business Process Automation in Navi Mumbai',
                'intro' => 'Stop paying people to do what software can. We automate repetitive workflows — approvals, data entry, reporting and more — to save time, cut costs and remove errors.',
                'bullets' => [
                    'Eliminate manual, repetitive tasks',
                    'Fewer errors, faster turnaround',
                    'Connect the tools you already use',
                ],
                'sub_heading' => 'Our Automation Services',
                'sub_services' => [
                    ['title' => 'Workflow Automation', 'description' => 'Digitise approvals, handoffs and multi-step processes.'],
                    ['title' => 'Robotic Process Automation', 'description' => 'Software bots that handle rules-based, repetitive tasks.'],
                    ['title' => 'Document Automation', 'description' => 'Auto-generate, route and file documents and reports.'],
                    ['title' => 'Data Entry Automation', 'description' => 'Capture and sync data across systems without typing.'],
                    ['title' => 'System Integration', 'description' => 'Make disconnected apps talk to each other automatically.'],
                    ['title' => 'Automated Reporting', 'description' => 'Scheduled, accurate dashboards and reports on autopilot.'],
                ],
                'why_points' => [
                    'Rapid ROI from reclaimed staff hours',
                    'Error-free, consistent process execution',
                    'Automation mapped to your real workflows',
                    'Integrates with your existing software',
                    'Scales effortlessly as volume grows',
                ],
                'faqs' => [
                    ['question' => 'Which processes are worth automating first?', 'answer' => 'High-volume, repetitive, rules-based tasks like data entry, invoice processing, approvals and reporting give the fastest returns. We help you prioritise.'],
                    ['question' => 'Will automation replace our staff?', 'answer' => 'No — it removes drudgery so your team focuses on higher-value, judgement-based work while output and accuracy rise.'],
                    ['question' => 'Does it work with our current tools?', 'answer' => 'Yes. We integrate via APIs and RPA so your existing systems work together without replacement.'],
                    ['question' => 'How quickly will we see results?', 'answer' => 'Many automations pay for themselves within weeks through saved hours and reduced errors.'],
                ],
                'tech_stacks' => ['Python', 'Node.js', 'Laravel', 'OpenAI', 'AWS', 'Docker'],
                'industries' => ['Manufacturing', 'Finance & Banking', 'Logistics', 'Healthcare', 'Corporate Offices'],
                'cta_heading' => 'Ready to Automate the Busywork?',
            ],
            [
                'slug' => 'ocr-document-digitization',
                'title' => 'OCR & Document Digitization',
                'category' => $category,
                'icon' => 'Data-Science.webp',
                'banner_image' => 'Data-Science.webp',
                'sort_order' => 14,
                'short_description' => 'We digitise documents with AI-powered OCR — extracting, classifying and structuring data from paper and PDFs automatically.',
                'seo_title' => 'OCR & Document Digitization Services in Navi Mumbai | Tectignis',
                'seo_description' => 'AI-powered OCR and document digitization in Navi Mumbai. Extract, classify and structure data from invoices, forms and scanned documents.',
                'eyebrow' => 'OCR & Document Digitization',
                'heading' => 'OCR & Document Digitization in Navi Mumbai',
                'intro' => 'Free your business from paper. Our AI-powered OCR solutions read, extract and structure data from invoices, forms and documents — accurately and at scale.',
                'bullets' => [
                    'Extract data from any document',
                    'AI accuracy across formats and languages',
                    'Searchable, structured digital records',
                ],
                'sub_heading' => 'Our OCR & Digitization Services',
                'sub_services' => [
                    ['title' => 'Invoice & Receipt OCR', 'description' => 'Auto-capture line items, totals and vendor data.'],
                    ['title' => 'Form & KYC Processing', 'description' => 'Extract and validate data from forms and IDs.'],
                    ['title' => 'Document Classification', 'description' => 'Automatically sort documents by type and content.'],
                    ['title' => 'Handwriting Recognition', 'description' => 'Digitise handwritten notes and legacy records.'],
                    ['title' => 'Data Extraction APIs', 'description' => 'Plug OCR straight into your apps and workflows.'],
                    ['title' => 'Bulk Digitization', 'description' => 'Convert archives of paper into searchable digital data.'],
                ],
                'why_points' => [
                    'High accuracy with AI-powered extraction',
                    'Handles printed, handwritten and multilingual text',
                    'Eliminates manual data entry and errors',
                    'Secure processing of sensitive documents',
                    'Integrates directly with your systems',
                ],
                'faqs' => [
                    ['question' => 'How accurate is your OCR?', 'answer' => 'Our AI-powered OCR achieves high accuracy even on imperfect scans, and we add validation rules and human-in-the-loop review where precision is critical.'],
                    ['question' => 'Can it read handwritten or multilingual documents?', 'answer' => 'Yes — modern AI models handle handwriting and multiple languages, including regional Indian scripts.'],
                    ['question' => 'Can it extract data from invoices and forms?', 'answer' => 'Absolutely. We extract structured fields like totals, dates and line items and push them straight into your software.'],
                    ['question' => 'Is the data processed securely?', 'answer' => 'Yes. We support private, secure processing pipelines suitable for confidential and regulated documents.'],
                ],
                'tech_stacks' => ['Python', 'TensorFlow', 'PyTorch', 'OpenAI', 'AWS', 'MongoDB'],
                'industries' => ['Finance & Banking', 'Healthcare', 'Government', 'Logistics', 'Corporate Offices'],
                'cta_heading' => 'Ready to Go Paperless With AI?',
            ],
            [
                'slug' => 'voice-bot-solutions',
                'title' => 'Voice Bot Solutions',
                'category' => $category,
                'icon' => 'Artificial-Intelligence.webp',
                'banner_image' => 'Artificial-Intelligence.webp',
                'sort_order' => 15,
                'short_description' => 'We build AI voice bots that handle calls, answer queries and complete tasks through natural, human-like conversation.',
                'seo_title' => 'Voice Bot Solutions in Navi Mumbai | AI Voice Assistants',
                'seo_description' => 'AI voice bot development in Navi Mumbai. Automate calls and support with natural, human-like voice assistants in multiple languages.',
                'eyebrow' => 'Voice Bot Solutions',
                'heading' => 'AI Voice Bot Solutions in Navi Mumbai',
                'intro' => 'Answer every call, instantly. Our AI voice bots handle inbound and outbound calls with natural speech — booking, supporting and qualifying without hold times.',
                'bullets' => [
                    'Human-like, natural voice conversations',
                    'Handle thousands of calls at once',
                    'Multilingual, 24/7 availability',
                ],
                'sub_heading' => 'Our Voice Bot Services',
                'sub_services' => [
                    ['title' => 'Inbound Call Bots', 'description' => 'Answer FAQs, route calls and resolve queries automatically.'],
                    ['title' => 'Outbound Voice Campaigns', 'description' => 'Automated reminders, confirmations and follow-ups.'],
                    ['title' => 'IVR Modernisation', 'description' => 'Replace clunky menus with conversational AI.'],
                    ['title' => 'Appointment Booking Bots', 'description' => 'Let callers book, reschedule and confirm by voice.'],
                    ['title' => 'Multilingual Voice AI', 'description' => 'Serve customers in their preferred language.'],
                    ['title' => 'Telephony Integration', 'description' => 'Connect to your call centre, CRM and helpdesk.'],
                ],
                'why_points' => [
                    'Natural conversations, not robotic menus',
                    'Massive call capacity with zero wait time',
                    'Consistent, accurate responses every time',
                    'Lower call-centre costs and staffing pressure',
                    'Seamless escalation to live agents',
                ],
                'faqs' => [
                    ['question' => 'How natural do the voice bots sound?', 'answer' => 'Modern AI voices are remarkably human-like, with natural intonation and real-time understanding of caller intent.'],
                    ['question' => 'Can it handle multiple languages?', 'answer' => 'Yes, including English, Hindi and regional languages so you can serve a diverse customer base.'],
                    ['question' => 'Will it integrate with our phone system?', 'answer' => 'We integrate with major telephony providers, call centres and CRMs for a unified workflow.'],
                    ['question' => 'What happens for complex queries?', 'answer' => 'The bot handles routine calls and smoothly transfers complex ones to a human agent with full context.'],
                ],
                'tech_stacks' => ['OpenAI', 'Python', 'Node.js', 'LangChain', 'AWS', 'MongoDB'],
                'industries' => ['Healthcare', 'Finance & Banking', 'Travel & Tourism', 'Retail', 'Real Estate'],
                'cta_heading' => 'Ready to Let AI Answer Every Call?',
            ],
            [
                'slug' => 'whatsapp-automation',
                'title' => 'WhatsApp Automation',
                'category' => $category,
                'icon' => 'Emerging-Technologies.webp',
                'banner_image' => 'Emerging-Technologies.webp',
                'sort_order' => 16,
                'short_description' => 'We automate sales, support and marketing on WhatsApp with smart bots and the official WhatsApp Business API.',
                'seo_title' => 'WhatsApp Automation Services in Navi Mumbai | WhatsApp Business API',
                'seo_description' => 'WhatsApp automation in Navi Mumbai. Automate sales, support and notifications with chatbots and the official WhatsApp Business API.',
                'eyebrow' => 'WhatsApp Automation',
                'heading' => 'WhatsApp Automation in Navi Mumbai',
                'intro' => 'Meet your customers where they already are. We automate WhatsApp for sales, support and notifications using the official Business API and intelligent bots.',
                'bullets' => [
                    'Official WhatsApp Business API',
                    'Automated replies, alerts and campaigns',
                    'Connect with your CRM and store',
                ],
                'sub_heading' => 'Our WhatsApp Automation Services',
                'sub_services' => [
                    ['title' => 'WhatsApp Chatbots', 'description' => 'Automate FAQs, orders and support conversations.'],
                    ['title' => 'Business API Setup', 'description' => 'Official, verified WhatsApp Business API onboarding.'],
                    ['title' => 'Broadcast & Campaigns', 'description' => 'Send approved promotional and transactional messages at scale.'],
                    ['title' => 'Order & Booking Bots', 'description' => 'Let customers order, book and pay inside WhatsApp.'],
                    ['title' => 'Automated Notifications', 'description' => 'Order updates, reminders and OTPs delivered instantly.'],
                    ['title' => 'CRM Integration', 'description' => 'Sync every chat and lead with your CRM.'],
                ],
                'why_points' => [
                    'Reach customers on the app they check most',
                    'Official API means reliable, compliant messaging',
                    'Automated yet personal conversations',
                    'Higher open and response rates than email',
                    'Full integration with your sales tools',
                ],
                'faqs' => [
                    ['question' => 'Do I need the WhatsApp Business API?', 'answer' => 'For automation, broadcasts and integrations at scale, yes — we handle the official API setup and verification for you.'],
                    ['question' => 'Can customers place orders on WhatsApp?', 'answer' => 'Yes. We build catalogues, order flows and payment links so customers buy without leaving the chat.'],
                    ['question' => 'Will it send order and delivery updates?', 'answer' => 'Absolutely — automated, template-approved notifications for orders, bookings, reminders and OTPs.'],
                    ['question' => 'Does it integrate with our CRM?', 'answer' => 'Yes, every conversation and lead syncs to your CRM for a single view of the customer.'],
                ],
                'tech_stacks' => ['Node.js', 'Laravel', 'PHP', 'OpenAI', 'MySQL', 'Redis'],
                'industries' => ['Retail', 'E-commerce', 'Healthcare', 'Travel & Tourism', 'Real Estate', 'Startups'],
                'cta_heading' => 'Ready to Automate WhatsApp for Your Business?',
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function businessApplicationServices(): array
    {
        $category = 'business_application';

        return [
            [
                'slug' => 'hospital-management',
                'title' => 'Hospital Management Software',
                'category' => $category,
                'icon' => 'Custom-Software.webp',
                'banner_image' => 'Custom-Software.webp',
                'sort_order' => 17,
                'short_description' => 'We build hospital management systems that streamline patients, appointments, billing and records into one secure, connected platform.',
                'seo_title' => 'Hospital Management Software in Navi Mumbai | HMS Development',
                'seo_description' => 'Hospital management software development in Navi Mumbai. Manage patients, appointments, billing, pharmacy and records in one secure HMS.',
                'eyebrow' => 'Hospital Management',
                'heading' => 'Hospital Management Software in Navi Mumbai',
                'intro' => 'Run your hospital efficiently and compassionately. Our HMS connects patients, doctors, billing and records into one secure platform so your staff can focus on care.',
                'bullets' => [
                    'Patient records and EMR in one place',
                    'Online appointments and OPD management',
                    'Secure, compliance-ready architecture',
                ],
                'sub_heading' => 'Our Hospital Management Modules',
                'sub_subtitle' => 'Key Modules',
                'sub_services' => [
                    ['title' => 'Patient & EMR Management', 'description' => 'Complete electronic medical records and patient history.'],
                    ['title' => 'Appointment & OPD', 'description' => 'Online booking, scheduling and queue management.'],
                    ['title' => 'Billing & Insurance', 'description' => 'Automated billing, claims and insurance workflows.'],
                    ['title' => 'Pharmacy & Inventory', 'description' => 'Stock, expiry and dispensing tracked in real time.'],
                    ['title' => 'Lab & Diagnostics', 'description' => 'Order tests, capture results and share reports.'],
                    ['title' => 'Staff & Roster', 'description' => 'Manage doctors, shifts, departments and payroll.'],
                ],
                'why_points' => [
                    'One connected platform across departments',
                    'Secure, privacy-aware patient data handling',
                    'Reduces wait times and paperwork',
                    'Role-based access for staff and doctors',
                    'Scales from clinics to multi-branch hospitals',
                ],
                'faqs' => [
                    ['question' => 'Can the HMS handle multiple branches?', 'answer' => 'Yes. Our hospital management software supports multi-branch operations with centralised reporting and per-branch control.'],
                    ['question' => 'Is patient data secure?', 'answer' => 'Security and privacy are core — role-based access, encryption and audit trails protect sensitive medical records.'],
                    ['question' => 'Does it support online appointments?', 'answer' => 'Yes, patients can book online and your front desk manages OPD queues and schedules in real time.'],
                    ['question' => 'Can it integrate with lab and pharmacy systems?', 'answer' => 'Absolutely — lab, pharmacy, billing and insurance modules work together or integrate with your existing tools.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'MySQL', 'PostgreSQL', 'AWS'],
                'industries' => ['Healthcare', 'Government', 'Corporate Offices'],
                'cta_heading' => 'Ready to Digitise Your Hospital?',
            ],
            [
                'slug' => 'hrms-development',
                'title' => 'HRMS Development',
                'category' => $category,
                'icon' => 'Customize-Software-Development1.webp',
                'banner_image' => 'Custom-Software.webp',
                'sort_order' => 18,
                'short_description' => 'We build HRMS platforms that automate hiring, attendance, payroll and performance — all in one employee-friendly system.',
                'seo_title' => 'HRMS Development Services in Navi Mumbai | HR Software',
                'seo_description' => 'HRMS development in Navi Mumbai. Automate attendance, payroll, leave and performance with custom HR management software.',
                'eyebrow' => 'HRMS Development',
                'heading' => 'HRMS Development in Navi Mumbai',
                'intro' => 'Give HR its time back. Our HRMS automates attendance, payroll, leave and performance so your people team can focus on people, not paperwork.',
                'bullets' => [
                    'Attendance and payroll on autopilot',
                    'Self-service portal for employees',
                    'Compliance-ready payroll and reports',
                ],
                'sub_heading' => 'Our HRMS Modules',
                'sub_subtitle' => 'Key Modules',
                'sub_services' => [
                    ['title' => 'Attendance & Leave', 'description' => 'Biometric, geo and shift-based attendance with leave workflows.'],
                    ['title' => 'Payroll Management', 'description' => 'Automated, compliant payroll with payslips and statutory filings.'],
                    ['title' => 'Recruitment & Onboarding', 'description' => 'Track candidates and onboard new hires digitally.'],
                    ['title' => 'Performance Management', 'description' => 'Goals, reviews and appraisals that are fair and transparent.'],
                    ['title' => 'Employee Self-Service', 'description' => 'Let staff manage requests, payslips and documents.'],
                    ['title' => 'HR Analytics', 'description' => 'Insights on attrition, cost and workforce trends.'],
                ],
                'why_points' => [
                    'Cuts manual HR work dramatically',
                    'Accurate, compliant, on-time payroll',
                    'Empowers employees with self-service',
                    'Configurable to your policies and grades',
                    'Scales from SMEs to large enterprises',
                ],
                'faqs' => [
                    ['question' => 'Does the HRMS handle Indian payroll compliance?', 'answer' => 'Yes — PF, ESI, TDS, professional tax and statutory reports are built into the payroll module.'],
                    ['question' => 'Can it integrate biometric attendance?', 'answer' => 'Absolutely. We integrate biometric devices, geo-attendance and shift rosters.'],
                    ['question' => 'Is there an employee self-service portal?', 'answer' => 'Yes, employees can apply for leave, view payslips, update details and raise requests themselves.'],
                    ['question' => 'Can we customise approval workflows?', 'answer' => 'Yes, leave, expense and approval workflows are fully configurable to your hierarchy.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'Vue.js', 'MySQL', 'Redis', 'AWS'],
                'industries' => ['Corporate Offices', 'Manufacturing', 'Retail', 'Healthcare', 'Logistics'],
                'cta_heading' => 'Ready to Automate Your HR?',
            ],
            [
                'slug' => 'inventory-management',
                'title' => 'Inventory Management Software',
                'category' => $category,
                'icon' => 'Custom-Software.webp',
                'banner_image' => 'Custom-Software.webp',
                'sort_order' => 19,
                'short_description' => 'We develop inventory management systems that give you real-time stock visibility, automated reordering and accurate control across locations.',
                'seo_title' => 'Inventory Management Software in Navi Mumbai | Tectignis',
                'seo_description' => 'Inventory management software development in Navi Mumbai. Real-time stock tracking, barcode scanning and automated reordering across warehouses.',
                'eyebrow' => 'Inventory Management',
                'heading' => 'Inventory Management Software in Navi Mumbai',
                'intro' => 'Never run out — or overstock — again. Our inventory management software gives you real-time visibility, automated reordering and tight control across every location.',
                'bullets' => [
                    'Real-time stock across all locations',
                    'Barcode and QR scanning support',
                    'Automated low-stock reordering',
                ],
                'sub_heading' => 'Our Inventory Management Features',
                'sub_subtitle' => 'Key Features',
                'sub_services' => [
                    ['title' => 'Real-Time Stock Tracking', 'description' => 'Know exactly what you have, where, at any moment.'],
                    ['title' => 'Barcode & QR Scanning', 'description' => 'Fast, error-free inward and outward movement.'],
                    ['title' => 'Multi-Warehouse Management', 'description' => 'Control stock across branches and godowns centrally.'],
                    ['title' => 'Purchase & Reordering', 'description' => 'Automated reorder points and supplier purchase orders.'],
                    ['title' => 'Batch & Expiry Tracking', 'description' => 'Manage batches, serials and expiry with ease.'],
                    ['title' => 'Reports & Analytics', 'description' => 'Stock valuation, movement and demand insights.'],
                ],
                'why_points' => [
                    'Eliminate stockouts and dead inventory',
                    'Accurate, real-time stock numbers',
                    'Faster operations with barcode scanning',
                    'Integrates with billing, POS and accounting',
                    'Scales across warehouses and outlets',
                ],
                'faqs' => [
                    ['question' => 'Can it manage multiple warehouses?', 'answer' => 'Yes. Track and transfer stock across unlimited warehouses, branches and outlets from one dashboard.'],
                    ['question' => 'Does it support barcode scanning?', 'answer' => 'Absolutely — barcode and QR scanning speed up inward, outward and stock-take operations.'],
                    ['question' => 'Will it integrate with our billing or POS?', 'answer' => 'Yes, we integrate inventory with POS, billing, e-commerce and accounting for end-to-end accuracy.'],
                    ['question' => 'Can it alert us about low stock?', 'answer' => 'Yes, automated reorder points trigger alerts and even draft purchase orders to suppliers.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'MySQL', 'PostgreSQL', 'Docker'],
                'industries' => ['Retail', 'Manufacturing', 'Logistics', 'E-commerce', 'Healthcare'],
                'cta_heading' => 'Ready to Take Control of Your Inventory?',
            ],
            [
                'slug' => 'lms-development',
                'title' => 'LMS Development',
                'category' => $category,
                'icon' => 'Customize-Software-Development1.webp',
                'banner_image' => 'Custom-Software.webp',
                'sort_order' => 20,
                'short_description' => 'We build learning management systems that deliver courses, track progress and engage learners on any device.',
                'seo_title' => 'LMS Development Services in Navi Mumbai | Learning Management System',
                'seo_description' => 'LMS development in Navi Mumbai. Build custom learning management systems with courses, assessments, certificates and progress tracking.',
                'eyebrow' => 'LMS Development',
                'heading' => 'LMS Development in Navi Mumbai',
                'intro' => 'Teach, train and certify at scale. Our learning management systems deliver engaging courses, assessments and certificates with progress tracking on any device.',
                'bullets' => [
                    'Courses, quizzes and certificates',
                    'Live classes and recorded content',
                    'Detailed learner progress tracking',
                ],
                'sub_heading' => 'Our LMS Features',
                'sub_subtitle' => 'Key Features',
                'sub_services' => [
                    ['title' => 'Course Management', 'description' => 'Create, organise and sell structured courses easily.'],
                    ['title' => 'Assessments & Quizzes', 'description' => 'Automated grading, question banks and exams.'],
                    ['title' => 'Certificates', 'description' => 'Auto-issued, verifiable completion certificates.'],
                    ['title' => 'Live & Recorded Classes', 'description' => 'Virtual classrooms and on-demand video lessons.'],
                    ['title' => 'Progress Tracking', 'description' => 'Dashboards for learners, trainers and admins.'],
                    ['title' => 'Payments & Subscriptions', 'description' => 'Sell courses with one-time or subscription billing.'],
                ],
                'why_points' => [
                    'Engaging experience that boosts completion',
                    'Works on web and mobile, anywhere',
                    'Monetise courses with built-in payments',
                    'Detailed analytics on learning outcomes',
                    'Scales from a single trainer to an institution',
                ],
                'faqs' => [
                    ['question' => 'Who is your LMS for?', 'answer' => 'Schools, colleges, coaching centres, corporates and course creators — we tailor the LMS to your audience and goals.'],
                    ['question' => 'Does it support live classes?', 'answer' => 'Yes, we integrate live virtual classrooms alongside recorded video lessons and downloadable resources.'],
                    ['question' => 'Can we sell courses on it?', 'answer' => 'Absolutely — built-in payments support one-time purchases, subscriptions and coupons.'],
                    ['question' => 'Can learners get certificates?', 'answer' => 'Yes, the LMS auto-issues verifiable certificates on course or assessment completion.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'Next.js', 'MySQL', 'AWS'],
                'industries' => ['Education', 'Corporate Offices', 'Healthcare', 'Startups'],
                'cta_heading' => 'Ready to Launch Your Learning Platform?',
            ],
            [
                'slug' => 'pos-software',
                'title' => 'POS (Point of Sale) Software',
                'category' => $category,
                'icon' => 'Custom-Software.webp',
                'banner_image' => 'Custom-Software.webp',
                'sort_order' => 21,
                'short_description' => 'We build fast, reliable POS software that handles billing, inventory and customers across single and multi-outlet businesses.',
                'seo_title' => 'POS Software Development in Navi Mumbai | Point of Sale System',
                'seo_description' => 'POS software development in Navi Mumbai. Fast billing, inventory sync and multi-outlet point-of-sale systems for retail and restaurants.',
                'eyebrow' => 'POS Software',
                'heading' => 'POS Software Development in Navi Mumbai',
                'intro' => 'Sell faster at the counter. Our POS software makes billing effortless while keeping inventory, customers and reports in perfect sync across every outlet.',
                'bullets' => [
                    'Lightning-fast billing and checkout',
                    'Inventory synced in real time',
                    'Works online and offline',
                ],
                'sub_heading' => 'Our POS Features',
                'sub_subtitle' => 'Key Features',
                'sub_services' => [
                    ['title' => 'Fast Billing & Checkout', 'description' => 'Quick, barcode-driven billing with multiple payment modes.'],
                    ['title' => 'Inventory Sync', 'description' => 'Stock updates automatically with every sale.'],
                    ['title' => 'Multi-Outlet Management', 'description' => 'Centrally manage many stores and registers.'],
                    ['title' => 'Customer & Loyalty', 'description' => 'Track customers and run loyalty and discount programs.'],
                    ['title' => 'GST Invoicing', 'description' => 'Compliant GST bills and tax reports out of the box.'],
                    ['title' => 'Sales Reports', 'description' => 'Real-time insights on sales, staff and bestsellers.'],
                ],
                'why_points' => [
                    'Speeds up checkout and reduces queues',
                    'Always-accurate, real-time inventory',
                    'Works offline so sales never stop',
                    'Loyalty tools that bring customers back',
                    'Scales across registers and outlets',
                ],
                'faqs' => [
                    ['question' => 'Does the POS work for retail and restaurants?', 'answer' => 'Yes. We tailor the POS for retail, supermarkets, restaurants and quick-service with the right workflows for each.'],
                    ['question' => 'Will it work without internet?', 'answer' => 'Yes, our POS supports offline billing and syncs automatically once connectivity returns.'],
                    ['question' => 'Is it GST compliant?', 'answer' => 'Absolutely — GST-compliant invoicing, tax slabs and reports are built in.'],
                    ['question' => 'Can it manage multiple outlets?', 'answer' => 'Yes, manage unlimited outlets and registers with centralised inventory and reporting.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'Flutter', 'MySQL', 'Redis'],
                'industries' => ['Retail', 'E-commerce', 'Hospitality', 'Manufacturing'],
                'cta_heading' => 'Ready to Modernise Your Checkout?',
            ],
            [
                'slug' => 'real-estate-management',
                'title' => 'Real Estate Management Software',
                'category' => $category,
                'icon' => 'Customize-Software-Development1.webp',
                'banner_image' => 'Custom-Software.webp',
                'sort_order' => 22,
                'short_description' => 'We build real estate management software that handles properties, leads, bookings and clients in one powerful platform.',
                'seo_title' => 'Real Estate Management Software in Navi Mumbai | Tectignis',
                'seo_description' => 'Real estate management software development in Navi Mumbai. Manage properties, leads, bookings and clients with custom CRM and portals.',
                'eyebrow' => 'Real Estate Management',
                'heading' => 'Real Estate Management Software in Navi Mumbai',
                'intro' => 'Close more deals with less chaos. Our real estate software unifies property listings, leads, bookings and clients so your team sells and serves better.',
                'bullets' => [
                    'Property and inventory management',
                    'Lead capture and sales CRM',
                    'Booking, payments and documents',
                ],
                'sub_heading' => 'Our Real Estate Modules',
                'sub_subtitle' => 'Key Modules',
                'sub_services' => [
                    ['title' => 'Property Listings', 'description' => 'Manage inventory, units, pricing and availability.'],
                    ['title' => 'Lead & Sales CRM', 'description' => 'Capture, assign and nurture leads to closure.'],
                    ['title' => 'Booking & Payments', 'description' => 'Track bookings, instalments and payment schedules.'],
                    ['title' => 'Client & Broker Portal', 'description' => 'Self-service access for buyers and channel partners.'],
                    ['title' => 'Document Management', 'description' => 'Agreements, KYC and approvals stored securely.'],
                    ['title' => 'Reports & Dashboards', 'description' => 'Live insights on sales, collections and pipeline.'],
                ],
                'why_points' => [
                    'One platform from lead to handover',
                    'Never lose a lead or follow-up again',
                    'Transparent booking and payment tracking',
                    'Portals for clients and channel partners',
                    'Insightful dashboards for decisions',
                ],
                'faqs' => [
                    ['question' => 'Can it manage multiple projects and properties?', 'answer' => 'Yes — manage multiple projects, towers, units and inventory with real-time availability.'],
                    ['question' => 'Does it include a CRM for leads?', 'answer' => 'Absolutely. Capture leads from portals and ads, assign to agents and track every interaction to closure.'],
                    ['question' => 'Can clients and brokers log in?', 'answer' => 'Yes, dedicated portals give buyers and channel partners self-service access to bookings and documents.'],
                    ['question' => 'Does it track payments and instalments?', 'answer' => 'Yes, booking schedules, instalments, demand letters and collections are all tracked.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'Vue.js', 'React', 'MySQL', 'AWS'],
                'industries' => ['Real Estate', 'Finance & Banking', 'Corporate Offices'],
                'cta_heading' => 'Ready to Streamline Your Real Estate Business?',
            ],
            [
                'slug' => 'school-management-software',
                'title' => 'School Management Software',
                'category' => $category,
                'icon' => 'Customize-Software-Development1.webp',
                'banner_image' => 'Custom-Software.webp',
                'sort_order' => 23,
                'short_description' => 'We build school management software that connects students, teachers, parents and admin with fees, attendance and academics in one place.',
                'seo_title' => 'School Management Software in Navi Mumbai | School ERP',
                'seo_description' => 'School management software development in Navi Mumbai. Manage admissions, attendance, fees, exams and parent communication in one school ERP.',
                'eyebrow' => 'School Management',
                'heading' => 'School Management Software in Navi Mumbai',
                'intro' => 'Run your institution effortlessly. Our school ERP connects students, teachers, parents and admin — automating admissions, attendance, fees and exams in one place.',
                'bullets' => [
                    'Admissions, attendance and exams',
                    'Online fee collection and receipts',
                    'Parent-teacher communication built in',
                ],
                'sub_heading' => 'Our School ERP Modules',
                'sub_subtitle' => 'Key Modules',
                'sub_services' => [
                    ['title' => 'Admissions & Records', 'description' => 'Digital admissions and complete student profiles.'],
                    ['title' => 'Attendance', 'description' => 'Student and staff attendance with instant alerts.'],
                    ['title' => 'Fee Management', 'description' => 'Online fee collection, receipts and reminders.'],
                    ['title' => 'Exams & Report Cards', 'description' => 'Grading, results and auto-generated report cards.'],
                    ['title' => 'Timetable & LMS', 'description' => 'Schedules, assignments and online learning.'],
                    ['title' => 'Parent App', 'description' => 'Keep parents informed with a dedicated mobile app.'],
                ],
                'why_points' => [
                    'Everything an institution needs in one ERP',
                    'Saves admin hours on fees and records',
                    'Keeps parents engaged and informed',
                    'Secure handling of student data',
                    'Scales from a single school to a group',
                ],
                'faqs' => [
                    ['question' => 'Does it include a parent mobile app?', 'answer' => 'Yes — parents get attendance, fees, homework, results and announcements on a dedicated app.'],
                    ['question' => 'Can we collect fees online?', 'answer' => 'Absolutely. Online fee collection with receipts, reminders and reconciliation is built in.'],
                    ['question' => 'Does it manage exams and report cards?', 'answer' => 'Yes, from grading schemes to auto-generated report cards and result analytics.'],
                    ['question' => 'Can it handle multiple branches?', 'answer' => 'Yes, manage multiple branches or schools under one group with centralised reporting.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'Flutter', 'React', 'MySQL', 'AWS'],
                'industries' => ['Education', 'Government', 'Corporate Offices'],
                'cta_heading' => 'Ready to Digitise Your School?',
            ],
            [
                'slug' => 'visitor-management-software',
                'title' => 'Visitor Management Software',
                'category' => $category,
                'icon' => 'Custom-Software.webp',
                'banner_image' => 'Cybersecurity.webp',
                'sort_order' => 24,
                'short_description' => 'We build visitor management systems that digitise check-ins, improve security and give you a complete record of everyone on site.',
                'seo_title' => 'Visitor Management Software in Navi Mumbai | Tectignis',
                'seo_description' => 'Visitor management software development in Navi Mumbai. Digital check-in, badge printing, pre-registration and security for offices and facilities.',
                'eyebrow' => 'Visitor Management',
                'heading' => 'Visitor Management Software in Navi Mumbai',
                'intro' => 'Make a great — and secure — first impression. Our visitor management system digitises check-ins, prints badges and keeps a complete, searchable record of every visit.',
                'bullets' => [
                    'Fast, paperless digital check-in',
                    'Pre-registration and host alerts',
                    'Complete audit trail for security',
                ],
                'sub_heading' => 'Our Visitor Management Features',
                'sub_subtitle' => 'Key Features',
                'sub_services' => [
                    ['title' => 'Digital Check-In', 'description' => 'Self-service kiosk or tablet check-in in seconds.'],
                    ['title' => 'Pre-Registration', 'description' => 'Invite and pre-register guests with QR passes.'],
                    ['title' => 'Badge Printing', 'description' => 'Auto-printed visitor badges with photo and details.'],
                    ['title' => 'Host Notifications', 'description' => 'Instant alerts to hosts when guests arrive.'],
                    ['title' => 'Access & Security', 'description' => 'Integrate with access control and watchlists.'],
                    ['title' => 'Visitor Analytics', 'description' => 'Reports on footfall, hosts and visit history.'],
                ],
                'why_points' => [
                    'Professional, frictionless visitor experience',
                    'Stronger security with full audit trails',
                    'Paperless, GDPR-aware data capture',
                    'Integrates with access control systems',
                    'Works for offices, factories and facilities',
                ],
                'faqs' => [
                    ['question' => 'How does digital check-in work?', 'answer' => 'Visitors check in via a kiosk, tablet or QR code, capture their details and photo, and the host is notified instantly.'],
                    ['question' => 'Can visitors pre-register before arriving?', 'answer' => 'Yes. Hosts send invites with QR passes so registered guests breeze through check-in.'],
                    ['question' => 'Does it print visitor badges?', 'answer' => 'Yes, badges with photo, name, host and validity can be auto-printed on arrival.'],
                    ['question' => 'Can it integrate with our access control?', 'answer' => 'Absolutely — integrate with access control, CCTV and security watchlists for end-to-end safety.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'Flutter', 'MySQL', 'Redis'],
                'industries' => ['Corporate Offices', 'Manufacturing', 'Government', 'Healthcare', 'Education'],
                'cta_heading' => 'Ready to Secure and Modernise Your Front Desk?',
            ],
        ];
    }
}
