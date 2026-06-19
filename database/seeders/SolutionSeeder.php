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
            ['slug' => 'crm-solutions', 'title' => 'CRM Solutions', 'icon' => 'fas fa-handshake', 'short_description' => 'Manage customer relationships, sales pipelines, and support with powerful CRM platforms.', 'sort_order' => 2, 'content' => $this->crmContent()],
            ['slug' => 'hrms-solutions', 'title' => 'HRMS Solutions', 'icon' => 'fas fa-users', 'short_description' => 'Automate HR, payroll, attendance, and employee management end to end.', 'sort_order' => 3, 'content' => $this->hrmsContent()],
            ['slug' => 'ai-solutions', 'title' => 'AI Solutions', 'icon' => 'fas fa-robot', 'short_description' => 'Leverage AI chatbots, automation, OCR, and machine learning to scale your business.', 'sort_order' => 4, 'content' => $this->aiContent()],
            ['slug' => 'cloud-solutions', 'title' => 'Cloud Solutions', 'icon' => 'fas fa-cloud', 'short_description' => 'Migrate, host, and scale on AWS, Azure, and Google Cloud with confidence.', 'sort_order' => 5, 'content' => $this->cloudContent()],
            ['slug' => 'cybersecurity-solutions', 'title' => 'Security Solutions', 'icon' => 'fas fa-shield-alt', 'short_description' => 'Protect your business with VAPT, firewalls, SOC, and compliance services.', 'sort_order' => 6, 'content' => $this->securityContent()],
            ['slug' => 'automation-solutions', 'title' => 'Automation Solutions', 'icon' => 'fas fa-cogs', 'short_description' => 'Automate repetitive workflows and processes to boost productivity.', 'sort_order' => 7],
            ['slug' => 'smart-security-solutions', 'title' => 'Smart Security Solutions', 'icon' => 'fas fa-video', 'short_description' => 'CCTV, access control, visitor management, and biometric security systems.', 'sort_order' => 8],
        ];

        foreach ($solutions as $solution) {
            Solution::updateOrCreate(['slug' => $solution['slug']], array_merge($solution, ['is_active' => true]));
        }
    }

    /**
     * Full landing-page content for the ERP solution (light hero / dashboard mockup).
     *
     * @return array<string, mixed>
     */
    private function erpContent(): array
    {
        return [
            'hero' => [
                'theme' => 'light',
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
                    ['title' => 'Supply Chain', 'description' => 'End-to-end visibility across logistics, distribution, and fulfilment.'],
                    ['title' => 'Reports & Analytics', 'description' => 'Live dashboards and reports for confident, data-driven decisions.'],
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
                    ['title' => 'Regulatory Compliance', 'description' => 'Stay compliant with built-in tax and audit controls.'],
                    ['title' => 'Faster Operations', 'description' => 'Speed up day-to-day workflows across the organization.'],
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

    /**
     * Full landing-page content for the CRM solution (light hero / dashboard mockup).
     *
     * @return array<string, mixed>
     */
    private function crmContent(): array
    {
        return [
            'hero' => [
                'theme' => 'light',
                'eyebrow' => 'CRM SOLUTIONS',
                'highlight' => 'With Smart CRM Solutions.',
                'heading' => 'Build Stronger Relationships. Drive Better Results. With Smart CRM Solutions.',
                'intro' => 'Engage customers, streamline your sales pipeline, and close more deals with our powerful CRM solutions tailored to your business.',
                'cta_primary_label' => 'Request Free Consultation',
                'cta_secondary_label' => 'Talk to Our CRM Expert',
                'benefits' => [
                    '360° Customer View',
                    'Sales Pipeline Management',
                    'Marketing Automation',
                    'Customer Support & Service',
                    'Reports & Analytics',
                    'Mobile CRM Access',
                ],
                'badges' => [
                    ['label' => 'Sales Automation'],
                    ['label' => '360° Customer View'],
                ],
            ],
            'stats' => [
                'enabled' => true,
                'subtitle' => 'By the Numbers',
                'heading' => 'CRM That Drives Growth',
                'items' => [
                    ['value' => '500+', 'label' => 'CRM Deployments'],
                    ['value' => '100+', 'label' => 'Happy Clients'],
                    ['value' => '35%', 'label' => 'Higher Conversions'],
                    ['value' => '99.9%', 'label' => 'Platform Uptime'],
                    ['value' => '24/7', 'label' => 'Support'],
                    ['value' => '10+', 'label' => 'Years of Experience'],
                ],
            ],
            'modules' => [
                'enabled' => true,
                'subtitle' => 'What We Offer',
                'heading' => 'Our CRM Solutions',
                'cards' => [
                    ['title' => 'Lead Management', 'description' => 'Capture, score, and nurture leads from every channel in one place.'],
                    ['title' => 'Contact Management', 'description' => 'A single, 360° view of every customer, contact, and interaction.'],
                    ['title' => 'Sales Automation', 'description' => 'Automate follow-ups, quotes, and tasks so reps sell more.'],
                    ['title' => 'Pipeline Management', 'description' => 'Visual pipelines to track deals and forecast revenue accurately.'],
                    ['title' => 'Marketing Automation', 'description' => 'Run email, SMS, and campaign journeys that convert.'],
                    ['title' => 'Customer Support', 'description' => 'Tickets, SLAs, and a knowledge base for faster resolutions.'],
                    ['title' => 'Reports & Analytics', 'description' => 'Real-time dashboards on sales, marketing, and service KPIs.'],
                    ['title' => 'Mobile CRM', 'description' => 'Manage relationships on the go from any device.'],
                ],
            ],
            'benefits' => [
                'enabled' => true,
                'subtitle' => 'Why CRM',
                'heading' => 'Why Businesses Choose Our CRM Solutions?',
                'items' => [
                    ['title' => 'Stronger Relationships', 'description' => 'Personalized engagement at every stage of the journey.'],
                    ['title' => 'Higher Conversions', 'description' => 'Never miss a follow-up and close more deals.'],
                    ['title' => 'Complete Visibility', 'description' => 'See every lead, deal, and conversation in one view.'],
                    ['title' => 'Automation & Efficiency', 'description' => 'Cut manual work and free up your sales team.'],
                    ['title' => 'Better Experience', 'description' => 'Faster, more consistent customer service.'],
                    ['title' => 'Data-Driven Decisions', 'description' => 'Accurate forecasts and actionable insights.'],
                    ['title' => 'Seamless Integrations', 'description' => 'Connect email, telephony, ERP, and more.'],
                    ['title' => 'Scalable & Customizable', 'description' => 'Tailored to your process and ready to grow.'],
                ],
            ],
            'process' => [
                'enabled' => true,
                'subtitle' => 'How We Work',
                'heading' => 'Our CRM Implementation Process',
                'steps' => [
                    ['title' => 'Discover', 'description' => 'Map your sales and service processes.'],
                    ['title' => 'Plan', 'description' => 'Define workflows, fields, and goals.'],
                    ['title' => 'Configure', 'description' => 'Set up pipelines, automation, and roles.'],
                    ['title' => 'Integrate', 'description' => 'Connect email, ERP, and other tools.'],
                    ['title' => 'Train', 'description' => 'Onboard your teams for adoption.'],
                    ['title' => 'Support', 'description' => 'Optimize and support as you scale.'],
                ],
            ],
            'industries' => [
                'enabled' => true,
                'subtitle' => 'Industries',
                'heading' => 'CRM for Every Industry',
                'cta_label' => 'View All Industries',
            ],
            'cta_band' => [
                'enabled' => true,
                'heading' => "Let's Build Stronger Relationships and Grow Your Business",
                'subtitle' => 'Engage customers, close more deals, and deliver exceptional service with Tectignis CRM.',
                'button_primary_label' => 'Request Free Consultation',
                'button_secondary_label' => 'Talk To Our CRM Expert',
            ],
        ];
    }

    /**
     * Full landing-page content for the HRMS solution (light hero / dashboard mockup).
     *
     * @return array<string, mixed>
     */
    private function hrmsContent(): array
    {
        return [
            'hero' => [
                'theme' => 'light',
                'eyebrow' => 'HRMS SOLUTIONS',
                'highlight' => 'Drive Performance.',
                'heading' => 'Empower Your Workforce. Simplify HR. Drive Performance.',
                'intro' => 'Our HRMS solution streamlines HR operations, automates employee management, and boosts productivity — from hire to retire, all in one platform.',
                'cta_primary_label' => 'Request Free Consultation',
                'cta_secondary_label' => 'Talk to Our HR Expert',
                'benefits' => [
                    'Employee Management',
                    'Attendance & Leave',
                    'Payroll Automation',
                    'Performance Management',
                    'Recruitment & Onboarding',
                    'Employee Self-Service',
                ],
                'badges' => [
                    ['label' => 'Automated Payroll'],
                    ['label' => 'Self-Service Portal'],
                ],
            ],
            'stats' => [
                'enabled' => true,
                'subtitle' => 'By the Numbers',
                'heading' => 'HR, Simplified',
                'items' => [
                    ['value' => '500+', 'label' => 'HR Deployments'],
                    ['value' => '50K+', 'label' => 'Employees Managed'],
                    ['value' => '98%', 'label' => 'Payroll Accuracy'],
                    ['value' => '99.9%', 'label' => 'Platform Uptime'],
                    ['value' => '24/7', 'label' => 'Support'],
                    ['value' => '10+', 'label' => 'Years of Experience'],
                ],
            ],
            'modules' => [
                'enabled' => true,
                'subtitle' => 'What We Offer',
                'heading' => 'Our HRMS Modules',
                'cards' => [
                    ['title' => 'Employee Management', 'description' => 'A central, secure database for your entire workforce.'],
                    ['title' => 'Attendance & Leave', 'description' => 'Automated attendance, shifts, and leave management.'],
                    ['title' => 'Payroll Management', 'description' => 'Accurate, compliant payroll with statutory deductions.'],
                    ['title' => 'Performance Management', 'description' => 'Goals, reviews, and continuous feedback in one place.'],
                    ['title' => 'Recruitment & Onboarding', 'description' => 'Streamline hiring and welcome new joiners with ease.'],
                    ['title' => 'Learning & Development', 'description' => 'Upskill employees with training and certifications.'],
                    ['title' => 'Self-Service Portal', 'description' => 'Empower employees to manage requests themselves.'],
                    ['title' => 'Reports & Analytics', 'description' => 'Workforce insights and real-time HR dashboards.'],
                ],
            ],
            'benefits' => [
                'enabled' => true,
                'subtitle' => 'Why HRMS',
                'heading' => 'Why Businesses Choose Our HRMS Solutions?',
                'items' => [
                    ['title' => 'Save Time & Effort', 'description' => 'Automate repetitive HR and payroll tasks.'],
                    ['title' => 'Accurate Payroll', 'description' => 'Error-free, on-time pay runs every cycle.'],
                    ['title' => 'Empowered Employees', 'description' => 'Self-service reduces HR back-and-forth.'],
                    ['title' => 'Better Compliance', 'description' => 'Stay aligned with labour laws and statutes.'],
                    ['title' => 'Data-Driven HR', 'description' => 'Insights to improve retention and performance.'],
                    ['title' => 'Improved Productivity', 'description' => 'Less admin, more focus on people.'],
                    ['title' => 'Scalable Platform', 'description' => 'Grows with your headcount and locations.'],
                    ['title' => 'Anywhere Access', 'description' => 'Cloud and mobile access for hybrid teams.'],
                ],
            ],
            'process' => [
                'enabled' => true,
                'subtitle' => 'How We Work',
                'heading' => 'Our HRMS Implementation Process',
                'steps' => [
                    ['title' => 'Discover', 'description' => 'Understand your HR policies and needs.'],
                    ['title' => 'Plan', 'description' => 'Define modules, workflows, and roles.'],
                    ['title' => 'Configure', 'description' => 'Set up payroll, leave, and policies.'],
                    ['title' => 'Migrate', 'description' => 'Import employee data securely.'],
                    ['title' => 'Train', 'description' => 'Enable HR teams and employees.'],
                    ['title' => 'Support', 'description' => 'Ongoing support and improvements.'],
                ],
            ],
            'industries' => [
                'enabled' => true,
                'subtitle' => 'Industries',
                'heading' => 'HRMS for Every Industry',
                'cta_label' => 'View All Industries',
            ],
            'why_choose' => [
                'enabled' => true,
                'subtitle' => 'The Tectignis Difference',
                'heading' => 'Transform Your HR Operations',
                'points' => [
                    'All-in-one HR platform from hire to retire',
                    'Automated payroll and statutory compliance',
                    'Employee self-service that reduces HR workload',
                    'Real-time dashboards and workforce analytics',
                    'Secure, role-based access and data privacy',
                ],
                'testimonial_quote' => 'Tectignis HRMS automated our entire HR and payroll process. What used to take days now takes minutes, and our employees love the self-service portal.',
                'testimonial_author' => 'HR Director',
                'testimonial_role' => 'Leading IT Services Company',
                'testimonial_cta_label' => 'View Case Study',
            ],
            'cta_band' => [
                'enabled' => true,
                'heading' => 'Simplify HR Operations. Elevate Employee Experience.',
                'subtitle' => "Let's build a smarter, people-first workplace with Tectignis HRMS.",
                'button_primary_label' => 'Request Free Consultation',
                'button_secondary_label' => 'Talk To Our HR Expert',
            ],
        ];
    }

    /**
     * Full landing-page content for the AI solution (dark hero / glowing visual mockup).
     *
     * @return array<string, mixed>
     */
    private function aiContent(): array
    {
        return [
            'hero' => [
                'theme' => 'dark',
                'eyebrow' => 'AI SOLUTIONS',
                'highlight' => 'Smarter Decisions. Better Outcomes.',
                'heading' => 'Intelligent AI Solutions. Smarter Decisions. Better Outcomes.',
                'intro' => 'Harness the power of Artificial Intelligence to automate processes, gain deep insights, and create exceptional customer experiences. We build AI solutions that drive real business value.',
                'cta_primary_label' => 'Request Free Consultation',
                'cta_secondary_label' => 'Talk to Our AI Expert',
                'benefits' => [
                    'AI Strategy & Consulting',
                    'Machine Learning Development',
                    'Natural Language Processing',
                    'Computer Vision Solutions',
                    'Predictive Analytics',
                    'Intelligent Automation',
                ],
                'badges' => [
                    ['label' => 'Predictive Analytics'],
                    ['label' => 'Neural Networks'],
                    ['label' => 'Smart Automation'],
                ],
            ],
            'stats' => [
                'enabled' => true,
                'subtitle' => 'By the Numbers',
                'heading' => 'AI That Delivers Results',
                'items' => [
                    ['value' => '50+', 'label' => 'AI Projects Delivered'],
                    ['value' => '95%', 'label' => 'Prediction Accuracy'],
                    ['value' => '40%', 'label' => 'Avg. Efficiency Gain'],
                    ['value' => '99.9%', 'label' => 'Model Uptime'],
                    ['value' => '24/7', 'label' => 'AI Support'],
                    ['value' => '10+', 'label' => 'Years of Expertise'],
                ],
            ],
            'modules' => [
                'enabled' => true,
                'subtitle' => 'What We Offer',
                'heading' => 'Our AI Solutions',
                'cards' => [
                    ['title' => 'AI Strategy & Consulting', 'description' => 'Define a clear AI roadmap aligned with your goals and data readiness.'],
                    ['title' => 'Machine Learning Development', 'description' => 'Custom ML models that predict, classify, and recommend from your data.'],
                    ['title' => 'Natural Language Processing', 'description' => 'Chatbots, sentiment analysis, and document intelligence.'],
                    ['title' => 'Computer Vision', 'description' => 'Image and video analysis for recognition and quality control.'],
                    ['title' => 'Predictive Analytics', 'description' => 'Forecast demand, churn, and risk with data-driven models.'],
                    ['title' => 'Intelligent Process Automation', 'description' => 'Combine AI with RPA to automate complex, decision-heavy workflows.'],
                    ['title' => 'Generative AI Solutions', 'description' => 'LLM-powered content, summarization, and knowledge assistants.'],
                    ['title' => 'AI Integration & Deployment', 'description' => 'Embed AI into your apps, systems, and cloud infrastructure.'],
                ],
            ],
            'benefits' => [
                'enabled' => true,
                'subtitle' => 'Why AI',
                'heading' => 'Why Businesses Choose Our AI Solutions?',
                'items' => [
                    ['title' => 'Smarter Decisions', 'description' => 'Turn raw data into actionable, confident decisions.'],
                    ['title' => 'Proven Expertise', 'description' => 'A seasoned team of data scientists and ML engineers.'],
                    ['title' => 'Custom AI Solutions', 'description' => 'Models tailored to your data, domain, and objectives.'],
                    ['title' => 'Faster Time-to-Market', 'description' => 'Rapid prototyping and MLOps for reliable delivery.'],
                    ['title' => 'Scalable & Future-Ready', 'description' => 'Architectures that scale from pilot to enterprise.'],
                    ['title' => 'Data-Driven Insights', 'description' => 'Uncover patterns and opportunities hidden in your data.'],
                    ['title' => 'Cost Optimization', 'description' => 'Automate work and reduce operational costs.'],
                    ['title' => 'Responsible AI', 'description' => 'Transparent, secure, and compliant AI you can trust.'],
                ],
            ],
            'process' => [
                'enabled' => true,
                'subtitle' => 'How We Work',
                'heading' => 'Our AI Development Process',
                'steps' => [
                    ['title' => 'Discovery & Consulting', 'description' => 'Understand goals, data, and feasibility.'],
                    ['title' => 'Data Preparation', 'description' => 'Gather, clean, and label quality data.'],
                    ['title' => 'Model Development', 'description' => 'Build, train, and tune AI/ML models.'],
                    ['title' => 'Integration', 'description' => 'Deploy models into your systems and apps.'],
                    ['title' => 'Testing & Validation', 'description' => 'Validate accuracy, fairness, and performance.'],
                    ['title' => 'Deployment & Support', 'description' => 'Launch, monitor, and continuously improve.'],
                ],
            ],
            'industries' => [
                'enabled' => true,
                'subtitle' => 'Industries',
                'heading' => 'AI Solutions for Every Industry',
                'cta_label' => 'View All Industries',
            ],
            'cta_band' => [
                'enabled' => true,
                'heading' => 'Ready to Unlock the Power of AI?',
                'subtitle' => "Let's turn your data into intelligent action. Talk to our AI experts today.",
                'button_primary_label' => 'Request Free Consultation',
                'button_secondary_label' => 'Talk To Our AI Expert',
            ],
        ];
    }

    /**
     * Full landing-page content for the Cloud solution (dark hero / glowing visual mockup).
     *
     * @return array<string, mixed>
     */
    private function cloudContent(): array
    {
        return [
            'hero' => [
                'theme' => 'dark',
                'eyebrow' => 'CLOUD SOLUTIONS',
                'highlight' => 'We Power Your Cloud Journey.',
                'heading' => 'Dream. Build. Scale. We Power Your Cloud Journey.',
                'intro' => 'Secure, scalable, and cost-effective cloud solutions that accelerate innovation and business growth — from strategy and migration to fully managed operations.',
                'cta_primary_label' => 'Request Free Consultation',
                'cta_secondary_label' => 'Talk to a Cloud Expert',
                'benefits' => [
                    'Cloud Strategy & Consulting',
                    'Cloud Migration & Modernization',
                    'Multi-Cloud & Hybrid Cloud',
                    'Managed Cloud Services',
                    'DevOps & Automation',
                    'Cloud Security & Compliance',
                ],
                'badges' => [
                    ['label' => 'High Availability'],
                    ['label' => 'Auto-Scaling'],
                    ['label' => '99.9% Uptime'],
                ],
            ],
            'stats' => [
                'enabled' => true,
                'subtitle' => 'By the Numbers',
                'heading' => 'Cloud at Scale',
                'items' => [
                    ['value' => '300+', 'label' => 'Cloud Deployments'],
                    ['value' => '100+', 'label' => 'Happy Clients'],
                    ['value' => '40%', 'label' => 'Avg. Cost Savings'],
                    ['value' => '99.9%', 'label' => 'Uptime SLA'],
                    ['value' => '24/7', 'label' => 'Monitoring'],
                    ['value' => '10+', 'label' => 'Years of Experience'],
                ],
            ],
            'modules' => [
                'enabled' => true,
                'subtitle' => 'What We Offer',
                'heading' => 'Our Cloud Solutions',
                'cards' => [
                    ['title' => 'Cloud Strategy & Consulting', 'description' => 'A roadmap to the right cloud model for your business.'],
                    ['title' => 'Cloud Migration & Modernization', 'description' => 'Move and modernize apps with zero-downtime migrations.'],
                    ['title' => 'Multi-Cloud & Hybrid Cloud', 'description' => 'Run seamlessly across AWS, Azure, and Google Cloud.'],
                    ['title' => 'DevOps & Automation', 'description' => 'CI/CD, IaC, and automation for faster, safer releases.'],
                    ['title' => 'Cloud Security & Compliance', 'description' => 'Harden your cloud and meet compliance requirements.'],
                    ['title' => 'Managed Cloud Services', 'description' => '24/7 monitoring, optimization, and support.'],
                    ['title' => 'Cloud-Native Development', 'description' => 'Containers, Kubernetes, and serverless architectures.'],
                    ['title' => 'Backup & Disaster Recovery', 'description' => 'Resilient backups and rapid recovery you can rely on.'],
                ],
            ],
            'benefits' => [
                'enabled' => true,
                'subtitle' => 'Why Cloud',
                'heading' => 'Why Choose Tectignis for Cloud Solutions?',
                'items' => [
                    ['title' => 'Certified Cloud Experts', 'description' => 'Certified architects across all major clouds.'],
                    ['title' => 'Vendor-Agnostic Approach', 'description' => 'The best fit for your workloads, not a single vendor.'],
                    ['title' => 'Cost Optimization', 'description' => 'Right-size resources and cut cloud spend.'],
                    ['title' => 'Security First', 'description' => 'Built-in security and compliance at every layer.'],
                    ['title' => '24/7 Managed Support', 'description' => 'Round-the-clock monitoring and response.'],
                    ['title' => 'Scalability On Demand', 'description' => 'Scale up or down instantly with demand.'],
                    ['title' => 'Faster Deployment', 'description' => 'Automation accelerates time to value.'],
                    ['title' => 'Proven Track Record', 'description' => 'Hundreds of successful cloud projects delivered.'],
                ],
            ],
            'process' => [
                'enabled' => true,
                'subtitle' => 'How We Work',
                'heading' => 'Our Cloud Implementation Process',
                'steps' => [
                    ['title' => 'Assess', 'description' => 'Evaluate workloads and readiness.'],
                    ['title' => 'Plan', 'description' => 'Design the target cloud architecture.'],
                    ['title' => 'Migrate', 'description' => 'Move apps and data securely.'],
                    ['title' => 'Optimize', 'description' => 'Tune for cost and performance.'],
                    ['title' => 'Secure', 'description' => 'Harden and ensure compliance.'],
                    ['title' => 'Manage', 'description' => 'Monitor, support, and improve.'],
                ],
            ],
            'industries' => [
                'enabled' => true,
                'subtitle' => 'Industries',
                'heading' => 'Cloud Solutions for Every Industry',
                'cta_label' => 'View All Industries',
            ],
            'why_choose' => [
                'enabled' => true,
                'subtitle' => 'Our Cloud Partners',
                'heading' => 'Trusted Across AWS, Azure & Google Cloud',
                'points' => [
                    'AWS Advanced Consulting Partner',
                    'Microsoft Azure certified specialists',
                    'Google Cloud delivery experience',
                    'ISO 27001-aligned security practices',
                ],
                'testimonial_quote' => 'Tectignis migrated our entire infrastructure to the cloud with zero downtime. We cut costs by 40% and now scale effortlessly during peak demand.',
                'testimonial_author' => 'Chief Technology Officer',
                'testimonial_role' => 'Fast-Growing SaaS Company',
                'testimonial_cta_label' => 'View Case Study',
            ],
            'cta_band' => [
                'enabled' => true,
                'heading' => 'Ready to Accelerate Your Business with Cloud?',
                'subtitle' => "Let's build a secure, scalable, and future-ready cloud foundation together.",
                'button_primary_label' => 'Request Free Consultation',
                'button_secondary_label' => 'Talk To a Cloud Expert',
            ],
        ];
    }

    /**
     * Full landing-page content for the Security solution (dark hero / glowing visual mockup).
     *
     * @return array<string, mixed>
     */
    private function securityContent(): array
    {
        return [
            'hero' => [
                'theme' => 'dark',
                'eyebrow' => 'SECURITY SOLUTIONS',
                'highlight' => 'Always Protected.',
                'heading' => 'Stronger Security. Safer Business. Always Protected.',
                'intro' => 'Protect your digital assets, infrastructure, and data with our comprehensive security solutions. We identify threats, reduce risk, and ensure compliance — around the clock.',
                'cta_primary_label' => 'Request a Security Assessment',
                'cta_secondary_label' => 'Talk to a Security Expert',
                'benefits' => [
                    'Threat Detection & Response',
                    'Vulnerability Management',
                    'Network & Endpoint Security',
                    'Data Protection & Encryption',
                    'Security Monitoring (SOC)',
                    'Compliance & Risk Management',
                ],
                'badges' => [
                    ['label' => '24/7 SOC Monitoring'],
                    ['label' => 'Threat Intelligence'],
                    ['label' => 'Zero Trust'],
                ],
            ],
            'stats' => [
                'enabled' => true,
                'subtitle' => 'By the Numbers',
                'heading' => 'Security You Can Trust',
                'items' => [
                    ['value' => '500+', 'label' => 'Endpoints Secured'],
                    ['value' => '200+', 'label' => 'Security Assessments'],
                    ['value' => '24/7', 'label' => 'SOC Monitoring'],
                    ['value' => '99.9%', 'label' => 'Threat Detection Rate'],
                    ['value' => '100+', 'label' => 'Clients Protected'],
                    ['value' => '10+', 'label' => 'Years of Experience'],
                ],
            ],
            'modules' => [
                'enabled' => true,
                'subtitle' => 'What We Offer',
                'heading' => 'Our Security Solutions',
                'cards' => [
                    ['title' => 'Threat Detection & Response', 'description' => 'Detect, investigate, and respond to threats in real time.'],
                    ['title' => 'Vulnerability Management', 'description' => 'VAPT and continuous scanning to close security gaps.'],
                    ['title' => 'Network Security', 'description' => 'Firewalls, segmentation, and intrusion prevention.'],
                    ['title' => 'Data Security & Encryption', 'description' => 'Protect sensitive data at rest and in transit.'],
                    ['title' => 'Security Monitoring (SOC)', 'description' => '24/7 SOC with SIEM-driven monitoring and alerts.'],
                    ['title' => 'Compliance & Risk Management', 'description' => 'Meet ISO, GDPR, and industry compliance standards.'],
                    ['title' => 'Endpoint Security', 'description' => 'Protect every device with EDR and zero-trust access.'],
                    ['title' => 'Cloud Security', 'description' => 'Secure your cloud workloads, identities, and data.'],
                ],
            ],
            'benefits' => [
                'enabled' => true,
                'subtitle' => 'Why Security',
                'heading' => 'Why Businesses Choose Our Security Solutions?',
                'items' => [
                    ['title' => 'Proactive Protection', 'description' => 'Stop threats before they cause damage.'],
                    ['title' => 'Expert Security Team', 'description' => 'Certified analysts and ethical hackers.'],
                    ['title' => '24/7 Monitoring', 'description' => 'Round-the-clock SOC keeps watch always.'],
                    ['title' => 'Reduced Risk', 'description' => 'Identify and remediate vulnerabilities fast.'],
                    ['title' => 'Regulatory Compliance', 'description' => 'Stay audit-ready and compliant.'],
                    ['title' => 'Rapid Incident Response', 'description' => 'Contain and recover with minimal impact.'],
                    ['title' => 'Tailored Defense', 'description' => 'Security designed around your risk profile.'],
                    ['title' => 'Complete Visibility', 'description' => 'Full insight across your attack surface.'],
                ],
            ],
            'process' => [
                'enabled' => true,
                'subtitle' => 'How We Work',
                'heading' => 'Our Security Implementation Process',
                'steps' => [
                    ['title' => 'Assess', 'description' => 'Audit your current security posture.'],
                    ['title' => 'Identify', 'description' => 'Find vulnerabilities and risks.'],
                    ['title' => 'Protect', 'description' => 'Deploy controls and hardening.'],
                    ['title' => 'Detect', 'description' => 'Monitor with SOC and SIEM.'],
                    ['title' => 'Respond', 'description' => 'Contain and remediate incidents.'],
                    ['title' => 'Recover', 'description' => 'Restore and strengthen defenses.'],
                ],
            ],
            'industries' => [
                'enabled' => true,
                'subtitle' => 'Industries',
                'heading' => 'Security Solutions for Every Industry',
                'cta_label' => 'View All Industries',
            ],
            'cta_band' => [
                'enabled' => true,
                'heading' => 'Secure Today. Protect Tomorrow.',
                'subtitle' => "Don't wait for a breach. Let's build a resilient security posture for your business.",
                'button_primary_label' => 'Request a Security Assessment',
                'button_secondary_label' => 'Talk To a Security Expert',
            ],
        ];
    }
}
