<?php

namespace Database\Seeders;

use App\Models\Solution;
use Illuminate\Database\Seeder;

class SolutionContentSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = $this->profiles();

        Solution::query()
            ->select(['id', 'slug', 'title', 'short_description'])
            ->lazyById()
            ->each(function (Solution $solution) use ($profiles): void {
                $profile = $profiles[$solution->slug] ?? $this->genericProfile($solution);

                $solution->update([
                    'content' => $this->content($profile),
                    'seo_title' => $profile['seo_title'],
                    'seo_description' => $profile['seo_description'],
                    'seo_keywords' => $profile['seo_keywords'],
                ]);
            });
    }

    /**
     * @param  array<string, mixed>  $profile
     * @return array<string, mixed>
     */
    private function content(array $profile): array
    {
        return [
            'hero' => $profile['hero'],
            'stats' => array_merge(['enabled' => true, 'subtitle' => 'By the Numbers'], $profile['stats']),
            'modules' => array_merge(['enabled' => true, 'subtitle' => 'What We Offer'], $profile['modules']),
            'benefits' => array_merge(['enabled' => true], $profile['benefits']),
            'process' => array_merge(['enabled' => true, 'subtitle' => 'How We Work'], $profile['process']),
            'industries' => [
                'enabled' => true,
                'subtitle' => 'Industries',
                'heading' => $profile['industries_heading'],
                'cta_label' => 'View All Industries',
            ],
            'why_choose' => array_merge(['enabled' => true, 'subtitle' => 'The Tectignis Difference'], $profile['why_choose']),
            'cta_band' => array_merge(['enabled' => true], $profile['cta_band']),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function profiles(): array
    {
        return [
            'erp-solutions' => [
                'seo_title' => 'ERP Solutions | Tectignis',
                'seo_description' => 'Integrated ERP solutions that connect finance, operations, supply chain, and reporting in one scalable platform.',
                'seo_keywords' => 'ERP solutions, enterprise resource planning, business management software, ERP implementation',
                'hero' => [
                    'theme' => 'light',
                    'eyebrow' => 'ERP SOLUTIONS',
                    'highlight' => 'Smart ERP Solutions',
                    'heading' => 'Smart ERP Solutions to Power Your Business End-to-End',
                    'intro' => 'Integrate processes, automate operations, and gain real-time visibility with our intelligent ERP solutions built to scale with your business.',
                    'cta_primary_label' => 'Request Free Consultation',
                    'cta_secondary_label' => 'Talk to Our ERP Expert',
                    'benefits' => ['Unified Business Management', 'Real-time Insights & Reporting', 'Reduce Costs & Improve Efficiency', 'Scalable & Flexible Solutions', 'Better Collaboration', 'Secure & Compliant'],
                    'badges' => [['label' => 'Real-time Dashboards'], ['label' => '99.9% Uptime']],
                ],
                'stats' => [
                    'heading' => 'Proven ERP Delivery',
                    'items' => [['value' => '500+', 'label' => 'ERP Implementations'], ['value' => '100+', 'label' => 'Happy Clients'], ['value' => '15+', 'label' => 'Industry Verticals'], ['value' => '99.9%', 'label' => 'System Reliability'], ['value' => '24/7', 'label' => 'Support & Maintenance'], ['value' => '10+', 'label' => 'Years of Experience']],
                ],
                'modules' => [
                    'heading' => 'Our ERP Modules',
                    'cards' => [['title' => 'Finance & Accounting', 'description' => 'Manage financials, budgeting, taxation, and compliance.'], ['title' => 'Inventory Management', 'description' => 'Real-time inventory tracking, warehouse management, and stock optimization.'], ['title' => 'Procurement', 'description' => 'Streamline purchasing, vendor management, and procurement workflows.'], ['title' => 'Sales & CRM', 'description' => 'Manage leads, opportunities, customers, and after-sales relationships.'], ['title' => 'HR & Payroll', 'description' => 'Automate HR processes, payroll, attendance, and employee management.'], ['title' => 'Manufacturing', 'description' => 'Production planning, BOM, quality control, and shop floor management.'], ['title' => 'Supply Chain', 'description' => 'End-to-end visibility across logistics, distribution, and fulfilment.'], ['title' => 'Reports & Analytics', 'description' => 'Live dashboards and reports for confident, data-driven decisions.']],
                ],
                'benefits' => [
                    'subtitle' => 'Why ERP',
                    'heading' => 'Benefits of ERP Solutions',
                    'items' => [['title' => 'End-to-End Integration', 'description' => 'Connect every department on a single source of truth.'], ['title' => 'Real-time Analytics', 'description' => 'Make confident decisions with live dashboards and reports.'], ['title' => 'Process Automation', 'description' => 'Eliminate manual work and reduce costly errors.'], ['title' => 'Scalability', 'description' => 'Add users, modules, and locations as you grow.'], ['title' => 'Data Security', 'description' => 'Role-based access and enterprise-grade protection.'], ['title' => 'Cost Efficiency', 'description' => 'Lower operating costs and improve resource utilization.'], ['title' => 'Regulatory Compliance', 'description' => 'Stay compliant with built-in tax and audit controls.'], ['title' => 'Faster Operations', 'description' => 'Speed up day-to-day workflows across the organization.']],
                ],
                'process' => [
                    'heading' => 'Our ERP Implementation Process',
                    'steps' => [['title' => 'Discover', 'description' => 'Understand your business needs.'], ['title' => 'Plan', 'description' => 'Define requirements and roadmap.'], ['title' => 'Design', 'description' => 'Customize & configure the solution.'], ['title' => 'Implement', 'description' => 'Deploy and integrate the system.'], ['title' => 'Train', 'description' => 'User training and enablement.'], ['title' => 'Support', 'description' => 'Ongoing support & continuous improvement.']],
                ],
                'industries_heading' => 'ERP Solutions for Every Industry',
                'why_choose' => [
                    'heading' => 'Why Choose Tectignis for ERP?',
                    'points' => ['Domain expertise across multiple industries', 'Customized solutions to fit your business', 'On-time delivery with best practices', '24/7 support and continuous improvement', 'Future-ready with latest technologies'],
                    'testimonial_quote' => 'Tectignis ERP solution has transformed our operations. We now have real-time visibility, better control, and improved efficiency across our business.',
                    'testimonial_author' => 'Operations Head',
                    'testimonial_role' => 'Leading Manufacturing Company',
                    'testimonial_cta_label' => 'View Case Study',
                ],
                'cta_band' => ['heading' => 'Ready to Transform Your Business with ERP?', 'subtitle' => "Let's build an intelligent, integrated, and future-ready business together.", 'button_primary_label' => 'Request Free Consultation', 'button_secondary_label' => 'Talk To Our ERP Expert'],
            ],
            'crm-solutions' => [
                'seo_title' => 'CRM Solutions | Tectignis',
                'seo_description' => 'CRM solutions that centralize customer data, automate sales activity, and improve service at every touchpoint.',
                'seo_keywords' => 'CRM solutions, customer relationship management, sales automation, lead management, customer support software',
                'hero' => [
                    'theme' => 'light',
                    'eyebrow' => 'CRM SOLUTIONS',
                    'highlight' => 'Smart CRM Solutions',
                    'heading' => 'Build Stronger Relationships with Smart CRM Solutions',
                    'intro' => 'Engage customers, streamline your sales pipeline, and close more deals with CRM tailored to how your team works.',
                    'cta_primary_label' => 'Request Free Consultation',
                    'cta_secondary_label' => 'Talk to Our CRM Expert',
                    'benefits' => ['360° Customer View', 'Sales Pipeline Management', 'Marketing Automation', 'Customer Support & Service', 'Reports & Analytics', 'Mobile CRM Access'],
                    'badges' => [['label' => 'Sales Automation'], ['label' => '360° Customer View']],
                ],
                'stats' => [
                    'heading' => 'CRM That Drives Growth',
                    'items' => [['value' => '500+', 'label' => 'CRM Deployments'], ['value' => '100+', 'label' => 'Happy Clients'], ['value' => '35%', 'label' => 'Higher Conversions'], ['value' => '99.9%', 'label' => 'Platform Uptime'], ['value' => '24/7', 'label' => 'Support'], ['value' => '10+', 'label' => 'Years of Experience']],
                ],
                'modules' => [
                    'heading' => 'Our CRM Solutions',
                    'cards' => [['title' => 'Lead Management', 'description' => 'Capture, score, and nurture leads from every channel.'], ['title' => 'Contact Management', 'description' => 'Keep a complete view of every customer and interaction.'], ['title' => 'Sales Automation', 'description' => 'Automate follow-ups, quotes, tasks, and reminders.'], ['title' => 'Pipeline Management', 'description' => 'Track deals visually and forecast revenue accurately.'], ['title' => 'Marketing Automation', 'description' => 'Run targeted email, SMS, and campaign journeys.'], ['title' => 'Customer Support', 'description' => 'Manage tickets, SLAs, and service knowledge in one place.'], ['title' => 'Reports & Analytics', 'description' => 'Monitor sales, marketing, and service KPIs in real time.'], ['title' => 'Mobile CRM', 'description' => 'Keep teams productive from any device.']],
                ],
                'benefits' => [
                    'subtitle' => 'Why CRM',
                    'heading' => 'Why Businesses Choose Our CRM Solutions',
                    'items' => [['title' => 'Stronger Relationships', 'description' => 'Personalize engagement at every stage of the journey.'], ['title' => 'Higher Conversions', 'description' => 'Never miss a follow-up or an opportunity.'], ['title' => 'Complete Visibility', 'description' => 'See every lead, deal, and conversation in one view.'], ['title' => 'Automation & Efficiency', 'description' => 'Reduce manual work and free your teams to sell.'], ['title' => 'Better Experience', 'description' => 'Deliver faster, more consistent customer service.'], ['title' => 'Data-Driven Decisions', 'description' => 'Use accurate forecasts and actionable insights.'], ['title' => 'Seamless Integrations', 'description' => 'Connect email, telephony, ERP, and more.'], ['title' => 'Scalable & Customizable', 'description' => 'Fit the way you sell today and grow tomorrow.']],
                ],
                'process' => [
                    'heading' => 'Our CRM Implementation Process',
                    'steps' => [['title' => 'Discover', 'description' => 'Map sales and service processes.'], ['title' => 'Plan', 'description' => 'Define workflows, fields, and goals.'], ['title' => 'Configure', 'description' => 'Set up pipelines, roles, and automation.'], ['title' => 'Integrate', 'description' => 'Connect email, ERP, and business tools.'], ['title' => 'Train', 'description' => 'Onboard every CRM user.'], ['title' => 'Optimize', 'description' => 'Improve adoption and performance over time.']],
                ],
                'industries_heading' => 'CRM for Every Industry',
                'why_choose' => [
                    'heading' => 'Turn Every Interaction into a Better Relationship',
                    'points' => ['A CRM tailored to your sales process', 'Unified customer data across teams', 'Automation that keeps every opportunity moving', 'Clear reporting for managers and leaders', 'Expert implementation, training, and support'],
                    'testimonial_quote' => 'Our team finally has one clear view of customers and opportunities. Follow-ups are faster, reporting is trusted, and our pipeline is much healthier.',
                    'testimonial_author' => 'Sales Director',
                    'testimonial_role' => 'B2B Services Company',
                    'testimonial_cta_label' => 'View Case Study',
                ],
                'cta_band' => ['heading' => 'Ready to Build Stronger Customer Relationships?', 'subtitle' => 'Engage customers, close more deals, and deliver exceptional service with Tectignis CRM.', 'button_primary_label' => 'Request Free Consultation', 'button_secondary_label' => 'Talk To Our CRM Expert'],
            ],
            'hrms-solutions' => [
                'seo_title' => 'HRMS Solutions | Tectignis',
                'seo_description' => 'HRMS solutions that automate employee management, attendance, payroll, performance, and compliance from hire to retire.',
                'seo_keywords' => 'HRMS solutions, HR software, payroll automation, employee management, attendance management',
                'hero' => [
                    'theme' => 'light',
                    'eyebrow' => 'HRMS SOLUTIONS',
                    'highlight' => 'Drive Performance',
                    'heading' => 'Empower Your Workforce. Simplify HR. Drive Performance.',
                    'intro' => 'Streamline HR operations, automate employee management, and improve productivity from hire to retire in one platform.',
                    'cta_primary_label' => 'Request Free Consultation',
                    'cta_secondary_label' => 'Talk to Our HR Expert',
                    'benefits' => ['Employee Management', 'Attendance & Leave', 'Payroll Automation', 'Performance Management', 'Recruitment & Onboarding', 'Employee Self-Service'],
                    'badges' => [['label' => 'Automated Payroll'], ['label' => 'Self-Service Portal']],
                ],
                'stats' => [
                    'heading' => 'HR, Simplified',
                    'items' => [['value' => '500+', 'label' => 'HR Deployments'], ['value' => '50K+', 'label' => 'Employees Managed'], ['value' => '98%', 'label' => 'Payroll Accuracy'], ['value' => '99.9%', 'label' => 'Platform Uptime'], ['value' => '24/7', 'label' => 'Support'], ['value' => '10+', 'label' => 'Years of Experience']],
                ],
                'modules' => [
                    'heading' => 'Our HRMS Modules',
                    'cards' => [['title' => 'Employee Management', 'description' => 'Maintain a central, secure record for every employee.'], ['title' => 'Attendance & Leave', 'description' => 'Automate attendance, shifts, holidays, and leave.'], ['title' => 'Payroll Management', 'description' => 'Run accurate, compliant payroll with statutory deductions.'], ['title' => 'Performance Management', 'description' => 'Manage goals, reviews, and continuous feedback.'], ['title' => 'Recruitment & Onboarding', 'description' => 'Create a smoother journey from applicant to employee.'], ['title' => 'Learning & Development', 'description' => 'Track training, skills, and certifications.'], ['title' => 'Self-Service Portal', 'description' => 'Let employees manage requests independently.'], ['title' => 'Reports & Analytics', 'description' => 'Use workforce insights to make better people decisions.']],
                ],
                'benefits' => [
                    'subtitle' => 'Why HRMS',
                    'heading' => 'Benefits of HRMS Solutions',
                    'items' => [['title' => 'Save Time & Effort', 'description' => 'Automate repetitive HR and payroll work.'], ['title' => 'Accurate Payroll', 'description' => 'Deliver error-free, on-time pay every cycle.'], ['title' => 'Empowered Employees', 'description' => 'Reduce HR back-and-forth with self-service.'], ['title' => 'Better Compliance', 'description' => 'Stay aligned with policies and statutory rules.'], ['title' => 'Data-Driven HR', 'description' => 'Use insights to improve retention and performance.'], ['title' => 'Improved Productivity', 'description' => 'Spend less time on admin and more on people.'], ['title' => 'Scalable Platform', 'description' => 'Grow across teams, locations, and policies.'], ['title' => 'Anywhere Access', 'description' => 'Support hybrid teams with secure cloud access.']],
                ],
                'process' => [
                    'heading' => 'Our HRMS Implementation Process',
                    'steps' => [['title' => 'Discover', 'description' => 'Understand HR policies and needs.'], ['title' => 'Plan', 'description' => 'Define modules, workflows, and roles.'], ['title' => 'Configure', 'description' => 'Set up payroll, leave, and policy rules.'], ['title' => 'Migrate', 'description' => 'Import employee data securely.'], ['title' => 'Train', 'description' => 'Enable HR teams and employees.'], ['title' => 'Support', 'description' => 'Refine the platform as your workforce evolves.']],
                ],
                'industries_heading' => 'HRMS for Every Industry',
                'why_choose' => [
                    'heading' => 'Transform Your HR Operations',
                    'points' => ['One people platform from hire to retire', 'Automated payroll and statutory compliance', 'Employee self-service that reduces HR workload', 'Real-time dashboards and workforce analytics', 'Secure, role-based access and data privacy'],
                    'testimonial_quote' => 'Tectignis HRMS automated our HR and payroll process. What took days now takes minutes, and employees love the self-service portal.',
                    'testimonial_author' => 'HR Director',
                    'testimonial_role' => 'Leading IT Services Company',
                    'testimonial_cta_label' => 'View Case Study',
                ],
                'cta_band' => ['heading' => 'Simplify HR Operations. Elevate Employee Experience.', 'subtitle' => 'Build a smarter, people-first workplace with Tectignis HRMS.', 'button_primary_label' => 'Request Free Consultation', 'button_secondary_label' => 'Talk To Our HR Expert'],
            ],
            'ai-solutions' => [
                'seo_title' => 'AI Solutions | Tectignis',
                'seo_description' => 'Practical AI solutions for automation, intelligent chatbots, OCR, predictive analytics, and smarter business decisions.',
                'seo_keywords' => 'AI solutions, artificial intelligence, AI automation, chatbots, OCR, machine learning',
                'hero' => [
                    'theme' => 'dark',
                    'eyebrow' => 'AI SOLUTIONS',
                    'highlight' => 'Smarter Decisions. Better Outcomes.',
                    'heading' => 'Intelligent AI Solutions. Smarter Decisions. Better Outcomes.',
                    'intro' => 'Harness artificial intelligence to automate processes, uncover insights, and create better experiences for customers and teams.',
                    'cta_primary_label' => 'Explore AI Solutions',
                    'cta_secondary_label' => 'Talk to an AI Expert',
                    'benefits' => ['AI-Powered Automation', 'Intelligent Chatbots', 'Document Intelligence', 'Predictive Analytics', 'Machine Learning Models', 'Seamless Integration'],
                    'badges' => [['label' => 'AI Automation'], ['label' => 'Data-Driven Insights'], ['label' => 'Scalable Intelligence']],
                ],
                'stats' => [
                    'heading' => 'AI That Delivers Business Value',
                    'items' => [['value' => '100+', 'label' => 'AI Projects Delivered'], ['value' => '40%', 'label' => 'Average Time Saved'], ['value' => '24/7', 'label' => 'Intelligent Assistance'], ['value' => '95%', 'label' => 'Document Accuracy'], ['value' => '10+', 'label' => 'Industry Use Cases'], ['value' => '10+', 'label' => 'Years of Experience']],
                ],
                'modules' => [
                    'heading' => 'Our AI Solutions',
                    'cards' => [['title' => 'AI Chatbots', 'description' => 'Deliver conversational support and qualified lead capture around the clock.'], ['title' => 'Business Process Automation', 'description' => 'Automate repetitive work with intelligent workflows.'], ['title' => 'OCR & Document Intelligence', 'description' => 'Extract, classify, and validate data from documents.'], ['title' => 'Predictive Analytics', 'description' => 'Forecast demand, risk, and next-best actions from your data.'], ['title' => 'Machine Learning', 'description' => 'Build models that learn from real business outcomes.'], ['title' => 'Generative AI', 'description' => 'Accelerate content, knowledge access, and internal productivity.'], ['title' => 'AI Integration', 'description' => 'Connect AI capabilities to your existing business systems.'], ['title' => 'AI Strategy & Governance', 'description' => 'Adopt AI safely with clear priorities and guardrails.']],
                ],
                'benefits' => [
                    'subtitle' => 'Why AI',
                    'heading' => 'Benefits of Practical AI Adoption',
                    'items' => [['title' => 'Faster Operations', 'description' => 'Remove bottlenecks from high-volume business work.'], ['title' => 'Better Decisions', 'description' => 'Turn data into timely, useful recommendations.'], ['title' => 'Always-On Service', 'description' => 'Support customers and teams beyond business hours.'], ['title' => 'Reduced Costs', 'description' => 'Focus people on work that needs human judgment.'], ['title' => 'Higher Accuracy', 'description' => 'Standardize data processing and routine decisions.'], ['title' => 'Personalized Experiences', 'description' => 'Respond with relevant service at every touchpoint.'], ['title' => 'Scalable Innovation', 'description' => 'Expand proven use cases across the organization.'], ['title' => 'Responsible Delivery', 'description' => 'Implement AI with security and governance in mind.']],
                ],
                'process' => [
                    'heading' => 'Our AI Delivery Process',
                    'steps' => [['title' => 'Discover', 'description' => 'Identify high-value AI opportunities.'], ['title' => 'Assess', 'description' => 'Review data, systems, and readiness.'], ['title' => 'Design', 'description' => 'Shape the use case and success measures.'], ['title' => 'Build', 'description' => 'Develop, test, and integrate the solution.'], ['title' => 'Deploy', 'description' => 'Launch safely with user enablement.'], ['title' => 'Improve', 'description' => 'Monitor outcomes and continuously refine.']],
                ],
                'industries_heading' => 'AI Solutions for Every Industry',
                'why_choose' => [
                    'heading' => 'Move from AI Ideas to Measurable Outcomes',
                    'points' => ['Business-first use cases with clear value', 'Secure integration with your existing systems', 'Human-centered workflows that teams adopt', 'Flexible solutions from pilots to production', 'Ongoing optimization as your needs evolve'],
                    'testimonial_quote' => 'The team helped us move from a broad AI idea to a solution that saves time every day and gives our customers quicker answers.',
                    'testimonial_author' => 'Digital Transformation Lead',
                    'testimonial_role' => 'Enterprise Services Company',
                    'testimonial_cta_label' => 'View Case Study',
                ],
                'cta_band' => ['heading' => 'Ready to Put AI to Work for Your Business?', 'subtitle' => 'Find the right AI use cases and turn them into secure, scalable results.', 'button_primary_label' => 'Explore AI Solutions', 'button_secondary_label' => 'Talk To an AI Expert'],
            ],
            'cloud-solutions' => [
                'seo_title' => 'Cloud Solutions | Tectignis',
                'seo_description' => 'Cloud consulting, migration, modernization, managed services, security, and cost optimization across AWS, Azure, and Google Cloud.',
                'seo_keywords' => 'cloud solutions, cloud migration, AWS consulting, Azure consulting, Google Cloud, managed cloud services',
                'hero' => [
                    'theme' => 'dark',
                    'eyebrow' => 'CLOUD SOLUTIONS',
                    'highlight' => 'Built for Growth.',
                    'heading' => 'Secure. Scalable. Cloud Solutions Built for Growth.',
                    'intro' => 'Modernize infrastructure, migrate workloads, and build cloud foundations that scale reliably with your business.',
                    'cta_primary_label' => 'Plan Your Cloud Journey',
                    'cta_secondary_label' => 'Talk to a Cloud Expert',
                    'benefits' => ['Cloud Strategy & Consulting', 'Migration & Modernization', 'Multi-Cloud & Hybrid Cloud', 'Cloud Security', 'DevOps & Automation', 'Managed Cloud Services'],
                    'badges' => [['label' => 'AWS, Azure & Google Cloud'], ['label' => '24/7 Monitoring'], ['label' => 'Secure by Design']],
                ],
                'stats' => [
                    'heading' => 'Cloud Expertise You Can Rely On',
                    'items' => [['value' => '200+', 'label' => 'Cloud Projects'], ['value' => '99.9%', 'label' => 'Platform Availability'], ['value' => '40%', 'label' => 'Potential Cost Savings'], ['value' => '24/7', 'label' => 'Managed Support'], ['value' => '3', 'label' => 'Major Cloud Platforms'], ['value' => '10+', 'label' => 'Years of Experience']],
                ],
                'modules' => [
                    'heading' => 'Our Cloud Solutions',
                    'cards' => [['title' => 'Cloud Strategy & Consulting', 'description' => 'Create a roadmap for the right cloud model and workload priorities.'], ['title' => 'Cloud Migration & Modernization', 'description' => 'Move and modernize applications with controlled risk.'], ['title' => 'Multi-Cloud & Hybrid Cloud', 'description' => 'Run workloads effectively across private and public environments.'], ['title' => 'DevOps & Automation', 'description' => 'Use CI/CD and infrastructure as code for reliable releases.'], ['title' => 'Cloud Security & Compliance', 'description' => 'Protect identities, workloads, data, and configurations.'], ['title' => 'Managed Cloud Services', 'description' => 'Monitor, optimize, and support cloud operations continuously.'], ['title' => 'Cloud-Native Development', 'description' => 'Build with containers, Kubernetes, and serverless patterns.'], ['title' => 'Backup & Disaster Recovery', 'description' => 'Protect critical data and recover quickly from disruption.']],
                ],
                'benefits' => [
                    'subtitle' => 'Why Cloud',
                    'heading' => 'Why Choose Tectignis for Cloud Solutions?',
                    'items' => [['title' => 'Certified Cloud Experts', 'description' => 'Work with specialists across leading cloud platforms.'], ['title' => 'Vendor-Agnostic Approach', 'description' => 'Choose what best fits your workloads and goals.'], ['title' => 'Cost Optimization', 'description' => 'Right-size resources and manage cloud spend.'], ['title' => 'Security First', 'description' => 'Build in security and compliance from the start.'], ['title' => '24/7 Managed Support', 'description' => 'Keep critical services monitored around the clock.'], ['title' => 'Scalability on Demand', 'description' => 'Adapt capacity quickly as demand changes.'], ['title' => 'Faster Deployment', 'description' => 'Use automation to accelerate time to value.'], ['title' => 'Resilient Operations', 'description' => 'Design for availability, backup, and recovery.']],
                ],
                'process' => [
                    'heading' => 'Our Cloud Implementation Process',
                    'steps' => [['title' => 'Assess', 'description' => 'Evaluate workloads and cloud readiness.'], ['title' => 'Plan', 'description' => 'Design the target architecture and roadmap.'], ['title' => 'Migrate', 'description' => 'Move applications and data securely.'], ['title' => 'Optimize', 'description' => 'Tune performance and cloud cost.'], ['title' => 'Secure', 'description' => 'Harden the environment and confirm compliance.'], ['title' => 'Manage', 'description' => 'Monitor, support, and continuously improve.']],
                ],
                'industries_heading' => 'Cloud Solutions for Every Industry',
                'why_choose' => [
                    'heading' => 'Build a Cloud Foundation That Keeps Pace',
                    'points' => ['Clear cloud roadmaps tied to business outcomes', 'Experience across AWS, Azure, and Google Cloud', 'Security and governance integrated from day one', 'Automation that improves speed and consistency', 'Managed support for long-term confidence'],
                    'testimonial_quote' => 'Tectignis migrated our infrastructure with careful planning and no disruption. We now scale confidently during peak demand.',
                    'testimonial_author' => 'Chief Technology Officer',
                    'testimonial_role' => 'Fast-Growing SaaS Company',
                    'testimonial_cta_label' => 'View Case Study',
                ],
                'cta_band' => ['heading' => 'Ready to Accelerate Your Business with Cloud?', 'subtitle' => 'Build a secure, scalable, and future-ready cloud foundation with Tectignis.', 'button_primary_label' => 'Plan Your Cloud Journey', 'button_secondary_label' => 'Talk To a Cloud Expert'],
            ],
            'cybersecurity-solutions' => [
                'seo_title' => 'Cybersecurity Solutions | Tectignis',
                'seo_description' => 'Cybersecurity solutions for threat detection, VAPT, network and endpoint security, SOC monitoring, and compliance.',
                'seo_keywords' => 'cybersecurity solutions, VAPT, SOC monitoring, network security, endpoint security, data protection',
                'hero' => [
                    'theme' => 'dark',
                    'eyebrow' => 'CYBERSECURITY SOLUTIONS',
                    'highlight' => 'Always Protected.',
                    'heading' => 'Stronger Security. Safer Business. Always Protected.',
                    'intro' => 'Protect digital assets, infrastructure, and data with comprehensive security solutions that identify threats, reduce risk, and support compliance.',
                    'cta_primary_label' => 'Request a Security Assessment',
                    'cta_secondary_label' => 'Talk to a Security Expert',
                    'benefits' => ['Threat Detection & Response', 'Vulnerability Management', 'Network & Endpoint Security', 'Data Protection & Encryption', 'Security Monitoring (SOC)', 'Compliance & Risk Management'],
                    'badges' => [['label' => '24/7 SOC Monitoring'], ['label' => 'Threat Intelligence'], ['label' => 'Zero Trust']],
                ],
                'stats' => [
                    'heading' => 'Security You Can Trust',
                    'items' => [['value' => '500+', 'label' => 'Endpoints Secured'], ['value' => '200+', 'label' => 'Security Assessments'], ['value' => '24/7', 'label' => 'SOC Monitoring'], ['value' => '99.9%', 'label' => 'Threat Detection Rate'], ['value' => '100+', 'label' => 'Clients Protected'], ['value' => '10+', 'label' => 'Years of Experience']],
                ],
                'modules' => [
                    'heading' => 'Our Cybersecurity Solutions',
                    'cards' => [['title' => 'Threat Detection & Response', 'description' => 'Detect, investigate, and respond to threats in real time.'], ['title' => 'Vulnerability Management', 'description' => 'Find and close security gaps with VAPT and continuous scanning.'], ['title' => 'Network Security', 'description' => 'Protect networks with firewalls, segmentation, and intrusion prevention.'], ['title' => 'Data Security & Encryption', 'description' => 'Protect sensitive data at rest and in transit.'], ['title' => 'Security Monitoring (SOC)', 'description' => 'Use 24/7 monitoring, SIEM, and informed alerting.'], ['title' => 'Compliance & Risk Management', 'description' => 'Meet regulatory obligations and stay audit ready.'], ['title' => 'Endpoint Security', 'description' => 'Secure user devices with modern endpoint protection.'], ['title' => 'Cloud Security', 'description' => 'Secure cloud workloads, identities, and configurations.']],
                ],
                'benefits' => [
                    'subtitle' => 'Why Cybersecurity',
                    'heading' => 'Benefits of a Stronger Security Posture',
                    'items' => [['title' => 'Proactive Protection', 'description' => 'Address threats before they cause damage.'], ['title' => 'Expert Security Team', 'description' => 'Work with experienced analysts and ethical hackers.'], ['title' => '24/7 Monitoring', 'description' => 'Keep a constant watch over critical systems.'], ['title' => 'Reduced Risk', 'description' => 'Identify and remediate vulnerabilities faster.'], ['title' => 'Regulatory Compliance', 'description' => 'Keep policies, controls, and evidence audit ready.'], ['title' => 'Rapid Incident Response', 'description' => 'Contain and recover with minimal business impact.'], ['title' => 'Tailored Defense', 'description' => 'Design controls around your real risk profile.'], ['title' => 'Complete Visibility', 'description' => 'Understand your attack surface across environments.']],
                ],
                'process' => [
                    'heading' => 'Our Cybersecurity Implementation Process',
                    'steps' => [['title' => 'Assess', 'description' => 'Audit the current security posture.'], ['title' => 'Identify', 'description' => 'Find vulnerabilities and high-priority risks.'], ['title' => 'Protect', 'description' => 'Deploy hardening and protective controls.'], ['title' => 'Detect', 'description' => 'Monitor with SOC and SIEM capabilities.'], ['title' => 'Respond', 'description' => 'Contain and remediate incidents quickly.'], ['title' => 'Recover', 'description' => 'Restore services and strengthen defences.']],
                ],
                'industries_heading' => 'Cybersecurity Solutions for Every Industry',
                'why_choose' => [
                    'heading' => 'Make Security a Business Advantage',
                    'points' => ['Security services aligned to real business risk', 'Proactive assessments and continuous improvement', 'Protection across on-premises and cloud environments', 'Clear reporting for technical and business leaders', 'Trusted support when every minute matters'],
                    'testimonial_quote' => 'Tectignis gave us visibility into risks we could not see before and a practical plan to improve security without slowing the business down.',
                    'testimonial_author' => 'Information Security Manager',
                    'testimonial_role' => 'Financial Services Company',
                    'testimonial_cta_label' => 'View Case Study',
                ],
                'cta_band' => ['heading' => 'Secure Today. Protect Tomorrow.', 'subtitle' => 'Build a resilient security posture before a threat becomes a breach.', 'button_primary_label' => 'Request a Security Assessment', 'button_secondary_label' => 'Talk To a Security Expert'],
            ],
            'automation-solutions' => [
                'seo_title' => 'Business Automation Solutions | Tectignis',
                'seo_description' => 'Business automation solutions that connect workflows, reduce repetitive tasks, and give teams more time for high-value work.',
                'seo_keywords' => 'business automation, workflow automation, process automation, RPA, intelligent automation',
                'hero' => [
                    'theme' => 'light',
                    'eyebrow' => 'AUTOMATION SOLUTIONS',
                    'highlight' => 'Work Smarter.',
                    'heading' => 'Automate the Routine. Help Your Teams Work Smarter.',
                    'intro' => 'Replace repetitive, error-prone work with connected workflows that move faster, stay visible, and scale with your operations.',
                    'cta_primary_label' => 'Automate Your Workflows',
                    'cta_secondary_label' => 'Talk to an Automation Expert',
                    'benefits' => ['Workflow Automation', 'Document Processing', 'System Integration', 'Approvals & Notifications', 'Robotic Process Automation', 'Operational Analytics'],
                    'badges' => [['label' => 'Faster Workflows'], ['label' => 'Fewer Manual Errors']],
                ],
                'stats' => [
                    'heading' => 'Automation That Frees Teams to Do More',
                    'items' => [['value' => '60%', 'label' => 'Less Manual Work'], ['value' => '3x', 'label' => 'Faster Approvals'], ['value' => '24/7', 'label' => 'Workflow Availability'], ['value' => '99%', 'label' => 'Process Accuracy'], ['value' => '100+', 'label' => 'Processes Automated'], ['value' => '10+', 'label' => 'Years of Experience']],
                ],
                'modules' => [
                    'heading' => 'Our Automation Solutions',
                    'cards' => [['title' => 'Workflow Automation', 'description' => 'Design and automate the steps that keep work moving.'], ['title' => 'Robotic Process Automation', 'description' => 'Automate rule-based tasks across existing applications.'], ['title' => 'Document Automation', 'description' => 'Route, process, and validate documents without rekeying data.'], ['title' => 'Approval Automation', 'description' => 'Keep requests moving with clear, auditable approvals.'], ['title' => 'System Integration', 'description' => 'Connect ERP, CRM, HRMS, and line-of-business tools.'], ['title' => 'Customer Communication', 'description' => 'Trigger reliable email, SMS, and messaging updates.'], ['title' => 'Data Synchronization', 'description' => 'Keep information consistent across critical platforms.'], ['title' => 'Automation Analytics', 'description' => 'Measure time saved, process health, and bottlenecks.']],
                ],
                'benefits' => [
                    'subtitle' => 'Why Automation',
                    'heading' => 'Benefits of Business Automation',
                    'items' => [['title' => 'Faster Execution', 'description' => 'Complete routine work in less time.'], ['title' => 'Fewer Errors', 'description' => 'Standardize processes and reduce rework.'], ['title' => 'Lower Operating Cost', 'description' => 'Use team time where it creates the most value.'], ['title' => 'Better Visibility', 'description' => 'Track every step, owner, and bottleneck.'], ['title' => 'Consistent Service', 'description' => 'Deliver reliable experiences at every touchpoint.'], ['title' => 'Scalable Operations', 'description' => 'Handle more volume without matching headcount growth.'], ['title' => 'Connected Systems', 'description' => 'Eliminate data silos between business applications.'], ['title' => 'Continuous Improvement', 'description' => 'Use workflow data to improve how work gets done.']],
                ],
                'process' => [
                    'heading' => 'Our Automation Delivery Process',
                    'steps' => [['title' => 'Discover', 'description' => 'Identify high-impact repetitive work.'], ['title' => 'Map', 'description' => 'Document the current process and exceptions.'], ['title' => 'Design', 'description' => 'Create an efficient future-state workflow.'], ['title' => 'Build', 'description' => 'Configure automation and integrations.'], ['title' => 'Launch', 'description' => 'Test, deploy, and enable users.'], ['title' => 'Improve', 'description' => 'Monitor results and automate the next opportunity.']],
                ],
                'industries_heading' => 'Automation Solutions for Every Industry',
                'why_choose' => [
                    'heading' => 'Make Every Process More Efficient',
                    'points' => ['Automation designed around the way your teams work', 'Integration expertise across core business systems', 'Phased delivery that proves value early', 'Clear metrics for time, accuracy, and throughput', 'Support to evolve workflows as the business changes'],
                    'testimonial_quote' => 'We removed manual handoffs from a process that used to take days. Our team can now focus on exceptions and customers instead of data entry.',
                    'testimonial_author' => 'Operations Manager',
                    'testimonial_role' => 'Multi-Location Services Business',
                    'testimonial_cta_label' => 'View Case Study',
                ],
                'cta_band' => ['heading' => 'Ready to Automate What Slows You Down?', 'subtitle' => 'Turn repetitive work into faster, more reliable workflows with Tectignis.', 'button_primary_label' => 'Automate Your Workflows', 'button_secondary_label' => 'Talk To an Automation Expert'],
            ],
            'smart-security-solutions' => [
                'seo_title' => 'Smart Security Solutions | Tectignis',
                'seo_description' => 'Integrated smart security solutions for CCTV, access control, visitor management, biometrics, and real-time monitoring.',
                'seo_keywords' => 'smart security solutions, CCTV, access control, visitor management, biometric security, surveillance systems',
                'hero' => [
                    'theme' => 'dark',
                    'eyebrow' => 'SMART SECURITY SOLUTIONS',
                    'highlight' => 'Always in Control.',
                    'heading' => 'Smarter Security. Safer Spaces. Always in Control.',
                    'intro' => 'Protect people, property, and operations with connected security systems that give your team real-time visibility and control.',
                    'cta_primary_label' => 'Plan Your Security System',
                    'cta_secondary_label' => 'Talk to a Security Expert',
                    'benefits' => ['Intelligent CCTV', 'Access Control', 'Visitor Management', 'Biometric Authentication', 'Centralized Monitoring', 'Scalable Site Security'],
                    'badges' => [['label' => '24/7 Visibility'], ['label' => 'Centralized Control'], ['label' => 'Smart Alerts']],
                ],
                'stats' => [
                    'heading' => 'Security Systems Built for Confidence',
                    'items' => [['value' => '500+', 'label' => 'Sites Secured'], ['value' => '10K+', 'label' => 'Devices Deployed'], ['value' => '24/7', 'label' => 'Monitoring Ready'], ['value' => '99.9%', 'label' => 'System Availability'], ['value' => '100+', 'label' => 'Enterprise Clients'], ['value' => '10+', 'label' => 'Years of Experience']],
                ],
                'modules' => [
                    'heading' => 'Our Smart Security Solutions',
                    'cards' => [['title' => 'CCTV Surveillance', 'description' => 'Capture clear, reliable video across every critical area.'], ['title' => 'Video Analytics', 'description' => 'Use intelligent alerts to focus attention on what matters.'], ['title' => 'Access Control', 'description' => 'Control who can access each location, door, and zone.'], ['title' => 'Visitor Management', 'description' => 'Create a safer, more professional visitor experience.'], ['title' => 'Biometric Systems', 'description' => 'Verify identity with fast, secure biometric authentication.'], ['title' => 'Command & Control', 'description' => 'Monitor multiple sites from a centralized security view.'], ['title' => 'Intrusion Detection', 'description' => 'Detect unauthorized access and trigger timely response.'], ['title' => 'Security System AMC', 'description' => 'Keep every component maintained, monitored, and ready.']],
                ],
                'benefits' => [
                    'subtitle' => 'Why Smart Security',
                    'heading' => 'Benefits of Connected Physical Security',
                    'items' => [['title' => 'Real-Time Visibility', 'description' => 'Know what is happening across your premises.'], ['title' => 'Faster Response', 'description' => 'Receive timely alerts and act with confidence.'], ['title' => 'Stronger Access Control', 'description' => 'Protect sensitive areas with precise permissions.'], ['title' => 'Centralized Management', 'description' => 'Manage locations, devices, and incidents in one place.'], ['title' => 'Safer Visitor Experience', 'description' => 'Screen and track every visitor professionally.'], ['title' => 'Scalable Design', 'description' => 'Extend security as your sites and requirements grow.'], ['title' => 'Audit-Ready Records', 'description' => 'Keep video, access, and visitor information accessible.'], ['title' => 'Reliable Support', 'description' => 'Maintain security performance long after installation.']],
                ],
                'process' => [
                    'heading' => 'Our Smart Security Implementation Process',
                    'steps' => [['title' => 'Survey', 'description' => 'Assess sites, risks, and operating requirements.'], ['title' => 'Design', 'description' => 'Plan coverage, controls, and system architecture.'], ['title' => 'Specify', 'description' => 'Select the right devices and integrations.'], ['title' => 'Install', 'description' => 'Deploy equipment with minimal disruption.'], ['title' => 'Commission', 'description' => 'Test coverage, alerts, and user access.'], ['title' => 'Support', 'description' => 'Maintain, monitor, and expand the system.']],
                ],
                'industries_heading' => 'Smart Security for Every Industry',
                'why_choose' => [
                    'heading' => 'Protect What Matters with Connected Security',
                    'points' => ['Site designs based on real security and operational needs', 'Integrated CCTV, access, visitor, and biometric systems', 'Scalable technology for one site or many', 'Clear training for security and facility teams', 'Dependable maintenance and long-term support'],
                    'testimonial_quote' => 'Tectignis gave our facilities team a single view of access, visitors, and surveillance across sites. We are much faster to spot and respond to issues.',
                    'testimonial_author' => 'Facilities Director',
                    'testimonial_role' => 'Multi-Site Enterprise',
                    'testimonial_cta_label' => 'View Case Study',
                ],
                'cta_band' => ['heading' => 'Ready to Build a Smarter Security Environment?', 'subtitle' => 'Bring visibility, control, and confidence to every location with Tectignis.', 'button_primary_label' => 'Plan Your Security System', 'button_secondary_label' => 'Talk To a Security Expert'],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function genericProfile(Solution $solution): array
    {
        $expertLabel = str($solution->title)->singular()->toString();

        return [
            'seo_title' => "{$solution->title} | Tectignis",
            'seo_description' => $solution->short_description,
            'seo_keywords' => str($solution->title)->lower()->append(', business solutions, Tectignis')->toString(),
            'hero' => [
                'theme' => 'light',
                'eyebrow' => str($solution->title)->upper()->toString(),
                'highlight' => $solution->title,
                'heading' => "Tailored {$solution->title} for Better Business Outcomes",
                'intro' => $solution->short_description,
                'cta_primary_label' => 'Request Free Consultation',
                'cta_secondary_label' => "Talk to Our {$expertLabel} Expert",
                'benefits' => ['Tailored implementation', 'Connected workflows', 'Real-time visibility', 'Secure and scalable delivery', 'Expert guidance', 'Ongoing support'],
                'badges' => [['label' => 'Business-Ready'], ['label' => 'Scalable Delivery']],
            ],
            'stats' => ['heading' => "{$solution->title} That Deliver Results", 'items' => [['value' => '100+', 'label' => 'Projects Delivered'], ['value' => '24/7', 'label' => 'Support'], ['value' => '99.9%', 'label' => 'System Availability'], ['value' => '10+', 'label' => 'Industry Use Cases'], ['value' => '100+', 'label' => 'Happy Clients'], ['value' => '10+', 'label' => 'Years of Experience']]],
            'modules' => ['heading' => "Our {$solution->title}", 'cards' => [['title' => 'Strategy & Planning', 'description' => 'Align the solution to your business priorities.'], ['title' => 'Implementation', 'description' => 'Deploy the right capabilities with confidence.'], ['title' => 'Integration', 'description' => 'Connect essential systems and workflows.'], ['title' => 'Automation', 'description' => 'Reduce repetitive work and improve consistency.'], ['title' => 'Analytics', 'description' => 'Turn activity into useful operational insight.'], ['title' => 'Security', 'description' => 'Protect users, data, and critical processes.'], ['title' => 'Training', 'description' => 'Give users the confidence to succeed.'], ['title' => 'Ongoing Support', 'description' => 'Keep improving as the business evolves.']]],
            'benefits' => ['subtitle' => 'Why Tectignis', 'heading' => "Benefits of {$solution->title}", 'items' => [['title' => 'Tailored Delivery', 'description' => 'A solution shaped around your business needs.'], ['title' => 'Faster Operations', 'description' => 'Improve the way work gets done every day.'], ['title' => 'Clear Visibility', 'description' => 'Make confident decisions with better information.'], ['title' => 'Scalable Technology', 'description' => 'Grow capability as your requirements change.'], ['title' => 'Secure Foundation', 'description' => 'Protect operations with thoughtful controls.'], ['title' => 'Connected Teams', 'description' => 'Keep people and systems working together.'], ['title' => 'Expert Support', 'description' => 'Get guidance from planning through improvement.'], ['title' => 'Long-Term Value', 'description' => 'Create sustainable operational improvements.']]],
            'process' => ['heading' => "Our {$solution->title} Delivery Process", 'steps' => [['title' => 'Discover', 'description' => 'Understand the opportunity and goals.'], ['title' => 'Plan', 'description' => 'Set the roadmap and success measures.'], ['title' => 'Design', 'description' => 'Shape the solution around real workflows.'], ['title' => 'Implement', 'description' => 'Build and integrate the required capabilities.'], ['title' => 'Enable', 'description' => 'Prepare users and launch with confidence.'], ['title' => 'Improve', 'description' => 'Refine the solution over time.']]],
            'industries_heading' => "{$solution->title} for Every Industry",
            'why_choose' => ['heading' => "Why Choose Tectignis for {$solution->title}?", 'points' => ['Business-first planning and delivery', 'Solutions tailored to your operations', 'Secure, scalable implementation', 'Practical user enablement', 'Long-term support and improvement'], 'testimonial_quote' => 'Tectignis helped us turn a complex requirement into a solution our teams can use confidently every day.', 'testimonial_author' => 'Business Leader', 'testimonial_role' => 'Tectignis Client', 'testimonial_cta_label' => 'View Case Study'],
            'cta_band' => ['heading' => "Ready to Explore {$solution->title}?", 'subtitle' => 'Talk to our team about the right path for your business.', 'button_primary_label' => 'Request Free Consultation', 'button_secondary_label' => "Talk To Our {$expertLabel} Expert"],
        ];
    }
}
