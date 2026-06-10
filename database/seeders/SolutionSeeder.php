<?php

namespace Database\Seeders;

use App\Models\Solution;
use Illuminate\Database\Seeder;

class SolutionSeeder extends Seeder
{
    public function run(): void
    {
        $solutions = [
            ['slug' => 'erp-solutions', 'title' => 'ERP Solutions', 'icon' => 'fas fa-database', 'short_description' => 'Streamline operations with integrated enterprise resource planning systems tailored to your business.', 'sort_order' => 1, 'content' => $this->erpContent()],
            ['slug' => 'crm-solutions', 'title' => 'CRM Solutions', 'icon' => 'fas fa-handshake', 'short_description' => 'Manage customer relationships, sales pipelines, and support with powerful CRM platforms.', 'sort_order' => 2],
            ['slug' => 'hrms-solutions', 'title' => 'HRMS Solutions', 'icon' => 'fas fa-users', 'short_description' => 'Automate HR, payroll, attendance, and employee management end to end.', 'sort_order' => 3],
            ['slug' => 'ai-solutions', 'title' => 'AI Solutions', 'icon' => 'fas fa-robot', 'short_description' => 'Leverage AI chatbots, automation, OCR, and machine learning to scale your business.', 'sort_order' => 4],
            ['slug' => 'cloud-solutions', 'title' => 'Cloud Solutions', 'icon' => 'fas fa-cloud', 'short_description' => 'Migrate, host, and scale on AWS, Azure, and Google Cloud with confidence.', 'sort_order' => 5],
            ['slug' => 'cybersecurity-solutions', 'title' => 'Cybersecurity Solutions', 'icon' => 'fas fa-shield-alt', 'short_description' => 'Protect your business with VAPT, firewalls, SOC, and compliance services.', 'sort_order' => 6],
            ['slug' => 'automation-solutions', 'title' => 'Automation Solutions', 'icon' => 'fas fa-cogs', 'short_description' => 'Automate repetitive workflows and processes to boost productivity.', 'sort_order' => 7],
            ['slug' => 'smart-security-solutions', 'title' => 'Smart Security Solutions', 'icon' => 'fas fa-video', 'short_description' => 'CCTV, access control, visitor management, and biometric security systems.', 'sort_order' => 8],
        ];

        foreach ($solutions as $solution) {
            Solution::updateOrCreate(['slug' => $solution['slug']], array_merge($solution, ['is_active' => true]));
        }
    }

    /**
     * Full landing-page content for the flagship ERP solution. Acts as the
     * reference model for the other solution pages, which admins fill in via
     * the panel. Per-item `icon` is intentionally omitted so the components
     * fall back to their built-in FontAwesome glyphs.
     *
     * @return array<string, mixed>
     */
    private function erpContent(): array
    {
        return [
            'hero' => [
                'eyebrow' => 'ERP SOLUTIONS',
                'highlight' => 'Smart ERP Solutions',
                'heading' => 'Smart ERP Solutions to Power Your Business End-to-End',
                'intro' => 'Integrate processes, automate operations, and gain real-time visibility with our intelligent ERP solutions built to scale with your business.',
                'cta_primary_label' => 'Request Free Consultation',
                'cta_secondary_label' => 'Talk to Our ERP Expert',
                'benefits' => [
                    'Unified Business Management',
                    'Real-time Insights & Reporting',
                    'Reduce Costs & Improve Efficiency',
                    'Scalable & Flexible Solutions',
                    'Better Collaboration',
                    'Secure & Compliant',
                ],
                'badges' => [
                    ['label' => 'Real-time Dashboards'],
                    ['label' => '99.9% Uptime'],
                ],
            ],
            'stats' => [
                'enabled' => true,
                'subtitle' => 'By the Numbers',
                'heading' => 'Proven ERP Delivery',
                'items' => [
                    ['value' => '500+', 'label' => 'ERP Implementations'],
                    ['value' => '100+', 'label' => 'Happy Clients'],
                    ['value' => '15+', 'label' => 'Industry Verticals'],
                    ['value' => '99.9%', 'label' => 'System Reliability'],
                    ['value' => '24/7', 'label' => 'Support & Maintenance'],
                    ['value' => '10+', 'label' => 'Years of Experience'],
                ],
            ],
            'modules' => [
                'enabled' => true,
                'subtitle' => 'What We Offer',
                'heading' => 'Our ERP Modules',
                'cards' => [
                    ['title' => 'Finance & Accounting', 'description' => 'Manage financials, budgeting, taxation, and compliance.'],
                    ['title' => 'Inventory Management', 'description' => 'Real-time inventory tracking, warehouse management, and stock optimization.'],
                    ['title' => 'Procurement', 'description' => 'Streamline purchasing, vendor management, and procurement workflows.'],
                    ['title' => 'Sales & CRM', 'description' => 'Manage leads, opportunities, customers, and after-sales relationships.'],
                    ['title' => 'HR & Payroll', 'description' => 'Automate HR processes, payroll, attendance, and employee management.'],
                    ['title' => 'Manufacturing', 'description' => 'Production planning, BOM, quality control, and shop floor management.'],
                ],
            ],
            'benefits' => [
                'enabled' => true,
                'subtitle' => 'Why ERP',
                'heading' => 'Benefits of ERP Solutions',
                'items' => [
                    ['title' => 'End-to-End Integration', 'description' => 'Connect every department on a single source of truth.'],
                    ['title' => 'Real-time Analytics', 'description' => 'Make confident decisions with live dashboards and reports.'],
                    ['title' => 'Process Automation', 'description' => 'Eliminate manual work and reduce costly errors.'],
                    ['title' => 'Scalability', 'description' => 'Add users, modules, and locations as you grow.'],
                    ['title' => 'Data Security', 'description' => 'Role-based access and enterprise-grade protection.'],
                    ['title' => 'Cost Efficiency', 'description' => 'Lower operating costs and improve resource utilization.'],
                ],
            ],
            'process' => [
                'enabled' => true,
                'subtitle' => 'How We Work',
                'heading' => 'Our ERP Implementation Process',
                'steps' => [
                    ['title' => 'Discover', 'description' => 'Understand your business needs.'],
                    ['title' => 'Plan', 'description' => 'Define requirements and roadmap.'],
                    ['title' => 'Design', 'description' => 'Customize & configure the solution.'],
                    ['title' => 'Implement', 'description' => 'Deploy and integrate the system.'],
                    ['title' => 'Train', 'description' => 'User training and enablement.'],
                    ['title' => 'Support', 'description' => 'Ongoing support & continuous improvement.'],
                ],
            ],
            'industries' => [
                'enabled' => true,
                'subtitle' => 'Industries',
                'heading' => 'ERP Solutions for Every Industry',
                'cta_label' => 'View All Industries',
            ],
            'why_choose' => [
                'enabled' => true,
                'subtitle' => 'The Tectignis Difference',
                'heading' => 'Why Choose Tectignis for ERP?',
                'points' => [
                    'Domain expertise across multiple industries',
                    'Customized solutions to fit your business',
                    'On-time delivery with best practices',
                    '24/7 support and continuous improvement',
                    'Future-ready with latest technologies',
                ],
                'testimonial_quote' => 'Tectignis ERP solution has transformed our operations. We now have real-time visibility, better control, and improved efficiency across our business.',
                'testimonial_author' => 'Operations Head',
                'testimonial_role' => 'Leading Manufacturing Company',
                'testimonial_cta_label' => 'View Case Study',
            ],
            'cta_band' => [
                'enabled' => true,
                'heading' => 'Ready to Transform Your Business with ERP?',
                'subtitle' => "Let's build an intelligent, integrated, and future-ready business together.",
                'button_primary_label' => 'Request Free Consultation',
                'button_secondary_label' => 'Talk To Our ERP Expert',
            ],
        ];
    }
}
