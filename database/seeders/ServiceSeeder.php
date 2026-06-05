<?php

namespace Database\Seeders;

use App\Models\Industry;
use App\Models\Service;
use App\Models\TechStack;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'slug' => 'website-design-development',
                'title' => 'Website Design & Development',
                'icon' => 'website-development-com.webp',
                'category' => 'software_development',
                'short_description' => 'We craft responsive, aesthetically pleasing websites that not only reflect your brand identity but also offer seamless user experiences.',
                'seo_title' => 'Website Design & Development Services in Navi Mumbai | Custom Websites Worldwide',
                'seo_description' => 'We are leading professional website design & development services in Navi Mumbai & worldwide. Build responsive, SEO-friendly, and user-focused websites today.',
                'sort_order' => 1,
            ],
            [
                'slug' => 'web-application-development',
                'title' => 'Web Application Development',
                'icon' => 'web-app-development1.webp',
                'category' => 'software_development',
                'short_description' => 'Our expert team develops powerful, secure, and intuitive web applications that streamline your business operations and improve overall efficiency.',
                'seo_title' => 'Web Application Development Services in Navi Mumbai',
                'seo_description' => 'Expert web application development services in Navi Mumbai. We build powerful, secure, and scalable web apps for businesses.',
                'sort_order' => 2,
            ],
            [
                'slug' => 'ecommerce-development',
                'title' => 'E-commerce Development',
                'icon' => 'ecommerce-development.webp',
                'category' => 'software_development',
                'short_description' => 'We deliver customized e-commerce solutions that help you build, launch, and grow your online business with ease of use and a smooth shopping experience.',
                'seo_title' => 'E-commerce Development Services in Navi Mumbai',
                'seo_description' => 'Build your online store with Tectignis. Custom e-commerce solutions for startups and enterprises in Navi Mumbai & India.',
                'sort_order' => 3,
            ],
            [
                'slug' => 'mobile-app-development',
                'title' => 'Mobile App Development',
                'icon' => 'mobile-app-development.webp',
                'category' => 'software_development',
                'short_description' => 'We specialize in creating high-quality native mobile applications for both Android and iOS platforms, tailored to your business needs.',
                'seo_title' => 'Mobile App Development Services in Navi Mumbai',
                'seo_description' => 'Android and iOS mobile app development in Navi Mumbai. Native and hybrid apps built by expert developers.',
                'sort_order' => 4,
            ],
            [
                'slug' => 'hybrid-app-development',
                'title' => 'Hybrid App Development',
                'icon' => 'hybrid-app-development2.webp',
                'category' => 'software_development',
                'short_description' => 'Our hybrid app development services offer the flexibility of cross-platform applications, delivering faster time-to-market and cost-effective solutions.',
                'seo_title' => 'Hybrid App Development Services in Navi Mumbai',
                'seo_description' => 'Cross-platform hybrid app development in Navi Mumbai. Fast, cost-effective apps for Android & iOS.',
                'sort_order' => 5,
            ],
            [
                'slug' => 'custom-software-development',
                'title' => 'Custom Software Development',
                'icon' => 'Customize-Software-Development1.webp',
                'category' => 'software_development',
                'short_description' => 'We design and develop tailored software solutions that address your unique business challenges, enhancing productivity and competitiveness.',
                'seo_title' => 'Custom Software Development Services in Navi Mumbai',
                'seo_description' => 'Customized software development in Navi Mumbai. ERP, CRM, and enterprise-grade software built to your requirements.',
                'sort_order' => 6,
                // Flagship reference: fully populated rich-section content (Sections A-J).
                'tech_stacks' => ['Laravel', 'PHP', 'Python', 'React', 'Node.js', 'MySQL', 'PostgreSQL', 'AWS'],
                'industries' => ['Manufacturing', 'Healthcare', 'Retail', 'Logistics', 'Real Estate', 'Corporate Offices'],
                'content' => [
                    'hero' => [
                        'eyebrow' => 'Custom Software Development',
                        'heading' => 'Custom Software Development Built Around Your Business',
                        'intro' => 'From ERP and CRM platforms to bespoke SaaS products, we build secure, scalable software tailored to the exact way your business works — not the other way around.',
                        'bullets' => [
                            'Tailor-made solutions, zero off-the-shelf compromises',
                            'Agile delivery with transparent weekly progress',
                            'Scalable architecture that grows with you',
                        ],
                        'cta_primary_label' => 'Start Your Project',
                        'cta_secondary_label' => 'Talk to an Expert',
                    ],
                    'features_strip' => [
                        'enabled' => true,
                        'items' => [
                            ['icon' => null, 'label' => '100+ Projects Delivered'],
                            ['icon' => null, 'label' => 'On-Time Delivery'],
                            ['icon' => null, 'label' => 'Dedicated Agile Teams'],
                            ['icon' => null, 'label' => '24/7 Support'],
                        ],
                    ],
                    'sub_services' => [
                        'enabled' => true,
                        'subtitle' => 'What We Build',
                        'heading' => 'Our Custom Software Development Services',
                        'items' => [
                            ['icon' => null, 'title' => 'Enterprise Software', 'description' => 'Robust, secure systems that streamline complex operations across teams and departments.'],
                            ['icon' => null, 'title' => 'ERP Development', 'description' => 'Unify finance, inventory, HR and operations into a single source of truth built to your workflow.'],
                            ['icon' => null, 'title' => 'CRM Development', 'description' => 'Custom CRM platforms that centralise leads, customers and sales pipelines for measurable growth.'],
                            ['icon' => null, 'title' => 'SaaS Product Development', 'description' => 'Multi-tenant, subscription-ready products engineered to scale from your first customer to your thousandth.'],
                            ['icon' => null, 'title' => 'API & System Integration', 'description' => 'Connect your existing tools and third-party services into one seamless, automated ecosystem.'],
                            ['icon' => null, 'title' => 'Legacy Modernization', 'description' => 'Re-platform ageing systems into fast, secure, maintainable modern applications without losing data.'],
                        ],
                    ],
                    'process' => [
                        'enabled' => true,
                        'subtitle' => 'How We Work',
                        'heading' => 'Our Proven Development Process',
                        'steps' => [
                            ['icon' => null, 'title' => 'Discovery & Analysis', 'description' => 'We map your goals, users and requirements into a clear, costed project blueprint.'],
                            ['icon' => null, 'title' => 'UI/UX Design', 'description' => 'Intuitive, on-brand interfaces validated with you before a line of code is written.'],
                            ['icon' => null, 'title' => 'Agile Development', 'description' => 'Working software shipped in short sprints with visibility at every step.'],
                            ['icon' => null, 'title' => 'QA & Testing', 'description' => 'Rigorous manual and automated testing for performance, security and reliability.'],
                            ['icon' => null, 'title' => 'Deployment', 'description' => 'Smooth, low-risk launch with data migration and production hardening.'],
                            ['icon' => null, 'title' => 'Support & Maintenance', 'description' => 'Ongoing updates, monitoring and enhancements that keep you ahead.'],
                        ],
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
                        'heading' => 'Why Choose Tectignis for Custom Software',
                        'points' => [
                            'A dedicated team that learns your business inside out',
                            'Clean, documented, maintainable code you fully own',
                            'Security and scalability baked in from day one',
                            'Transparent fixed-scope and time-and-material engagement models',
                            'Long-term support that protects your investment',
                        ],
                        'cta_label' => 'Get a Free Quote',
                    ],
                    'faq' => [
                        'enabled' => true,
                        'heading' => 'Frequently Asked Questions',
                        'items' => [
                            ['question' => 'How much does custom software development cost?', 'answer' => 'Cost depends on scope, complexity and integrations. After a free discovery call we provide a detailed, fixed estimate so there are no surprises.'],
                            ['question' => 'How long does a typical project take?', 'answer' => 'A focused MVP usually ships in 8-12 weeks, while larger enterprise platforms are delivered in phased agile releases so you see value early.'],
                            ['question' => 'Do I own the source code?', 'answer' => 'Yes. You receive full ownership of the source code, documentation and intellectual property on completion.'],
                            ['question' => 'Do you provide support after launch?', 'answer' => 'Absolutely. We offer flexible AMC and support plans covering monitoring, updates and feature enhancements.'],
                        ],
                    ],
                    'cta_band' => [
                        'enabled' => true,
                        'heading' => 'Ready to Build Software That Works the Way You Do?',
                        'button_label' => 'Get a Free Consultation',
                    ],
                ],
            ],
            [
                'slug' => 'digital-marketing',
                'title' => 'Digital Marketing Services',
                'icon' => 'digital-marketing-com.webp',
                'category' => 'software_development',
                'short_description' => 'Our digital marketing services include Social Media Marketing, SEO, and PPC advertising strategies that help you increase visibility and drive traffic.',
                'seo_title' => 'Digital Marketing Services in Navi Mumbai',
                'seo_description' => 'Result-oriented digital marketing in Navi Mumbai. SEO, social media, PPC and content marketing to grow your business.',
                'sort_order' => 7,
            ],
            [
                'slug' => 'cctv-security',
                'title' => 'CCTV & Security Solutions',
                'icon' => 'cctv-com.webp',
                'category' => 'business_application',
                'short_description' => 'We provide state-of-the-art CCTV systems and security solutions to protect your business premises, ensuring safety and integrity of your operations.',
                'seo_title' => 'CCTV Installation Services in Navi Mumbai | Tectignis IT Solutions',
                'seo_description' => 'Secure your business and home with advanced CCTV & security solutions in Navi Mumbai & across India. Expert installation, monitoring & maintenance.',
                'sort_order' => 8,
            ],
            [
                'slug' => 'access-control',
                'title' => 'Access Control Systems',
                'icon' => 'access-control-system.webp',
                'category' => 'business_application',
                'short_description' => 'We implement advanced access control systems that provide secure entry management, protecting your physical and digital assets with modern technology.',
                'seo_title' => 'Access Control Systems in Navi Mumbai',
                'seo_description' => 'Advanced access control systems in Navi Mumbai. Biometric, card-based and smart entry management solutions.',
                'sort_order' => 9,
            ],
            [
                'slug' => 'amc',
                'title' => 'Annual Maintenance Contracts (AMC)',
                'icon' => 'annual-maintenance-com.webp',
                'category' => 'business_application',
                'short_description' => 'Our AMC services offer proactive support and maintenance, ensuring your IT infrastructure remains optimized, secure and running at peak performance.',
                'seo_title' => 'Annual Maintenance Contracts (AMC) in Navi Mumbai',
                'seo_description' => 'Comprehensive hardware & software AMC services in Navi Mumbai. Keep your IT systems running with proactive maintenance.',
                'sort_order' => 10,
            ],
            [
                'slug' => 'software-licensing',
                'title' => 'Software Licensing',
                'icon' => 'software-licensing.webp',
                'category' => 'business_application',
                'short_description' => 'We assist with managing your software licenses, ensuring compliance and reducing the risk of legal or operational issues related to software usage.',
                'seo_title' => 'Software Licensing Services in Navi Mumbai',
                'seo_description' => 'Software licensing management in Navi Mumbai. Ensure compliance and optimize your software asset management.',
                'sort_order' => 11,
            ],
            [
                'slug' => 'network-storage',
                'title' => 'Network Storage Solutions',
                'icon' => 'Network-Storage-Solutions.webp',
                'category' => 'business_application',
                'short_description' => 'Our tailored network storage solutions are designed to help businesses securely manage and store data with high availability and performance.',
                'seo_title' => 'Network Storage Solutions in Navi Mumbai',
                'seo_description' => 'NAS and SAN network storage solutions in Navi Mumbai. Secure, scalable data storage for businesses.',
                'sort_order' => 12,
            ],
            [
                'slug' => 'workstation-solutions',
                'title' => 'Workstation Solutions',
                'icon' => 'workstation-solutions.webp',
                'category' => 'business_application',
                'short_description' => 'We provide high-performance workstations customized for various business needs, ensuring your team has the right tools for peak productivity.',
                'seo_title' => 'Workstation Solutions in Navi Mumbai',
                'seo_description' => 'High-performance workstation solutions in Navi Mumbai. Custom-configured desktops and laptops for your business needs.',
                'sort_order' => 13,
            ],
            [
                'slug' => 'networking-solutions',
                'title' => 'Networking Solutions',
                'icon' => 'network-solutions-com.webp',
                'category' => 'business_application',
                'short_description' => 'From secure infrastructure setup to network management, our networking solutions ensure reliable and efficient connectivity across your organization.',
                'seo_title' => 'Networking Solutions in Navi Mumbai',
                'seo_description' => 'Enterprise networking solutions in Navi Mumbai. Structured cabling, switches, routers and network management services.',
                'sort_order' => 14,
            ],
        ];

        foreach ($services as $service) {
            // Pivot attachments are defined by name and synced after the upsert.
            $techNames = $service['tech_stacks'] ?? [];
            $industryNames = $service['industries'] ?? [];
            unset($service['tech_stacks'], $service['industries']);

            $model = Service::updateOrCreate(['slug' => $service['slug']], array_merge($service, ['is_active' => true]));

            if ($techNames !== []) {
                $model->techStacks()->sync(TechStack::whereIn('name', $techNames)->pluck('id'));
            }

            if ($industryNames !== []) {
                $model->industries()->sync(Industry::whereIn('name', $industryNames)->pluck('id'));
            }
        }
    }
}
