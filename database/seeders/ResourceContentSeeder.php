<?php

namespace Database\Seeders;

use App\Models\Download;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Insight;
use App\Models\JobOpening;
use Illuminate\Database\Seeder;

class ResourceContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedFaqs();
        $this->seedDownloads();
        $this->seedJobOpenings();
        $this->seedInsights();
    }

    private function seedFaqs(): void
    {
        $categories = [
            [
                'name' => 'Cloud Solutions',
                'icon' => 'fas fa-cloud',
                'faqs' => [
                    ['question' => 'How can cloud solutions benefit my business?', 'answer' => 'Cloud solutions reduce infrastructure costs, improve scalability, and let your team work securely from anywhere. We help you choose the right mix of public, private, or hybrid cloud so your systems grow with your business.'],
                    ['question' => 'Do you provide AWS, Azure and Google Cloud support?', 'answer' => 'Yes, we are experienced with AWS, Azure, and Google Cloud. We offer cloud architecture, deployment, migration, managed services, and cost optimization across all three platforms.'],
                    ['question' => 'Can you migrate our existing on-premise servers to cloud?', 'answer' => 'Yes, we provide end-to-end cloud migration services — assessment, planning, migration, testing, and post-migration support — with minimal business disruption.'],
                ],
            ],
            [
                'name' => 'Cyber Security',
                'icon' => 'fas fa-shield-alt',
                'faqs' => [
                    ['question' => 'How do you ensure data security?', 'answer' => 'We follow a defense-in-depth approach: network hardening, encryption in transit and at rest, role-based access control, and continuous monitoring — aligned with ISO and industry compliance standards.'],
                    ['question' => 'What is VAPT?', 'answer' => 'VAPT stands for Vulnerability Assessment and Penetration Testing. It is a security testing methodology that identifies weaknesses in your systems before attackers can exploit them. We provide comprehensive VAPT reports with remediation guidance.'],
                    ['question' => 'Do you provide cybersecurity audits?', 'answer' => 'Yes, we conduct thorough security audits covering network infrastructure, applications, and policies, delivering detailed reports and actionable recommendations.'],
                ],
            ],
            [
                'name' => 'AI & Automation',
                'icon' => 'fas fa-robot',
                'faqs' => [
                    ['question' => 'Can AI be integrated with our existing ERP?', 'answer' => 'Yes. We specialize in integrating AI capabilities — such as predictive analytics, intelligent reporting, and process automation — into existing ERP and business systems.'],
                    ['question' => 'Do your AI chatbots support WhatsApp?', 'answer' => 'Yes, we build WhatsApp-integrated AI chatbots using the WhatsApp Business API for automated customer support, lead generation, and business workflows.'],
                ],
            ],
            [
                'name' => 'IT Infrastructure',
                'icon' => 'fas fa-server',
                'faqs' => [
                    ['question' => 'Which CCTV system is best for my business?', 'answer' => 'The best system depends on your premises size, coverage requirements, and budget. We conduct a site survey and recommend IP/HD CCTV systems from leading brands with the right resolution, storage, and remote access capabilities.'],
                    ['question' => 'Do you provide Annual Maintenance Contracts (AMC)?', 'answer' => 'Yes, we offer comprehensive AMC plans for CCTV, access control, networking, and IT infrastructure — ensuring ongoing performance, support, and preventive maintenance.'],
                ],
            ],
            [
                'name' => 'Data & Analytics',
                'icon' => 'fas fa-chart-line',
                'faqs' => [
                    ['question' => 'How can data analytics help my business grow?', 'answer' => 'Data analytics turns raw business data into actionable insight — uncovering sales trends, operational bottlenecks, and customer behaviour so you can make confident, evidence-based decisions.'],
                    ['question' => 'Can you build custom dashboards and reports?', 'answer' => 'Yes, we design custom BI dashboards and automated reporting pipelines that consolidate data from your ERP, CRM, and other systems into a single real-time view.'],
                ],
            ],
            [
                'name' => 'General',
                'icon' => 'fas fa-info-circle',
                'faqs' => [
                    ['question' => 'What services does Tectignis IT Solutions provide?', 'answer' => 'We offer a wide range of IT services including Cloud Solutions, Cyber Security, AI & Automation, IT Infrastructure, Data & Analytics, and Managed IT Services to help businesses optimize operations and drive growth.'],
                    ['question' => 'What industries do you serve?', 'answer' => 'We serve Manufacturing, Healthcare, Education, Retail & E-commerce, Real Estate, Logistics, Hospitality, Banking & Finance, and Corporate sectors across India and worldwide.'],
                    ['question' => 'Do you provide 24/7 support?', 'answer' => 'Yes, our managed services and AMC plans include round-the-clock monitoring and support options, so critical issues are handled whenever they occur.'],
                    ['question' => 'How can I get started with Tectignis?', 'answer' => 'Simply reach out via our contact page or request a free consultation. Our experts will assess your requirements and propose a tailored solution with clear timelines and pricing.'],
                ],
            ],
        ];

        foreach ($categories as $sortOrder => $categoryData) {
            $category = FaqCategory::updateOrCreate(
                ['name' => $categoryData['name']],
                ['icon' => $categoryData['icon'], 'sort_order' => $sortOrder],
            );

            foreach ($categoryData['faqs'] as $faqSort => $faq) {
                Faq::updateOrCreate(
                    ['faq_category_id' => $category->id, 'question' => $faq['question']],
                    ['answer' => $faq['answer'], 'sort_order' => $faqSort, 'is_active' => true],
                );
            }
        }
    }

    private function seedDownloads(): void
    {
        $downloads = [
            ['title' => 'Company Profile', 'category' => 'brochure', 'file_type' => 'pdf', 'description' => 'Overview of Tectignis, our services, capabilities, and industry focus.'],
            ['title' => 'Cloud Solutions Overview', 'category' => 'brochure', 'file_type' => 'pdf', 'description' => 'Discover our cloud offerings and how we help businesses scale securely.'],
            ['title' => 'Cyber Security Best Practices', 'category' => 'whitepaper', 'file_type' => 'pdf', 'description' => 'Essential security strategies to protect your business and data.'],
            ['title' => 'Service Portfolio', 'category' => 'presentation', 'file_type' => 'ppt', 'description' => 'Comprehensive deck on our IT solutions and managed services.'],
            ['title' => 'Digital Transformation Journey', 'category' => 'case-study', 'file_type' => 'doc', 'description' => 'How we helped a client modernize their infrastructure and processes.'],
            ['title' => 'Infrastructure Solutions', 'category' => 'datasheet', 'file_type' => 'xls', 'description' => 'Technical specifications and key highlights of our solutions.'],
            ['title' => 'Migration Guide', 'category' => 'guide', 'file_type' => 'pdf', 'description' => 'Step-by-step guide to a seamless cloud migration experience.'],
            ['title' => 'The Future of IT', 'category' => 'whitepaper', 'file_type' => 'pdf', 'description' => 'Trends and technologies shaping the future of business IT.'],
            ['title' => 'Why Tectignis?', 'category' => 'presentation', 'file_type' => 'ppt', 'description' => 'Why businesses trust Tectignis as their technology partner.'],
        ];

        foreach ($downloads as $sortOrder => $download) {
            Download::updateOrCreate(
                ['title' => $download['title']],
                [...$download, 'sort_order' => $sortOrder, 'is_active' => true],
            );
        }
    }

    private function seedJobOpenings(): void
    {
        $openings = [
            ['title' => 'Senior Software Developer', 'department' => 'Development', 'icon' => 'fas fa-code'],
            ['title' => 'AI/ML Engineer', 'department' => 'AI & Automation', 'icon' => 'fas fa-robot'],
            ['title' => 'UI/UX Designer', 'department' => 'Design', 'icon' => 'fas fa-pen-nib'],
            ['title' => 'DevOps Engineer', 'department' => 'DevOps', 'icon' => 'fas fa-cogs'],
            ['title' => 'HR Executive', 'department' => 'Human Resources', 'icon' => 'fas fa-user-tie'],
        ];

        foreach ($openings as $sortOrder => $opening) {
            JobOpening::updateOrCreate(
                ['title' => $opening['title']],
                [
                    ...$opening,
                    'location' => 'Navi Mumbai, India',
                    'employment_type' => 'Full-time',
                    'sort_order' => $sortOrder,
                    'is_active' => true,
                ],
            );
        }
    }

    private function seedInsights(): void
    {
        $insights = [
            [
                'slug' => 'future-of-cloud-trends-2025',
                'title' => 'The Future of Cloud: Trends Shaping 2025 and Beyond',
                'topic' => 'Cloud Computing',
                'published_at' => '2025-05-20 00:00:00',
                'excerpt' => 'Explore emerging cloud trends, from hybrid strategies to serverless innovation, and what they mean for businesses.',
                'content' => '<p>Cloud computing continues to evolve at a remarkable pace. Hybrid and multi-cloud strategies are now the norm for enterprises that want flexibility without vendor lock-in, while serverless architectures are letting teams ship faster with less operational overhead.</p><h4>Key Trends to Watch</h4><ul><li>Hybrid and multi-cloud adoption for resilience and flexibility</li><li>Serverless computing and event-driven architectures</li><li>FinOps practices for cloud cost optimisation</li><li>AI-powered cloud management and automation</li></ul><p>Businesses that align their cloud strategy with these trends position themselves to scale efficiently while keeping costs predictable and security strong.</p>',
            ],
            [
                'slug' => 'how-ai-is-transforming-business-processes',
                'title' => 'How AI is Transforming Business Processes',
                'topic' => 'AI & Automation',
                'published_at' => '2025-05-16 00:00:00',
                'excerpt' => 'Discover how AI and automation are driving efficiency, reducing costs, and enabling smarter decision-making.',
                'content' => '<p>AI-powered systems are reshaping how organisations operate — from intelligent document processing to predictive analytics that anticipate demand before it happens.</p><h4>Where AI Delivers Value</h4><ul><li>Process automation that removes repetitive manual work</li><li>Predictive maintenance that reduces unplanned downtime</li><li>AI chatbots that handle support around the clock</li><li>Demand forecasting and supply chain optimisation</li></ul><p>The organisations that win with AI start small, prove value quickly, and scale what works across the business.</p>',
            ],
            [
                'slug' => 'cyber-security-best-practices-modern-businesses',
                'title' => 'Cyber Security Best Practices for Modern Businesses',
                'topic' => 'Cyber Security',
                'published_at' => '2025-05-10 00:00:00',
                'excerpt' => 'Learn essential security strategies to protect your digital assets and stay ahead of evolving cyber threats.',
                'content' => '<p>Security threats grow more sophisticated every year, and a reactive posture is no longer enough. Modern businesses need layered defences and a culture of security awareness.</p><h4>Essential Practices</h4><ul><li>Adopt a Zero Trust security model</li><li>Run regular VAPT assessments to find weaknesses first</li><li>Enforce multi-factor authentication everywhere</li><li>Train employees to recognise phishing and social engineering</li></ul><p>A strong security programme protects not just your data, but your reputation and customer trust.</p>',
            ],
            [
                'slug' => 'unlocking-business-value-with-data-analytics',
                'title' => 'Unlocking Business Value with Data Analytics',
                'topic' => 'Data & Analytics',
                'published_at' => '2025-05-05 00:00:00',
                'excerpt' => 'Understand how data analytics can turn raw data into actionable insights and drive growth.',
                'content' => '<p>Every business generates data, but few extract its full value. Analytics platforms turn scattered operational data into a single source of truth that drives better decisions.</p><h4>How Analytics Drives Growth</h4><ul><li>Real-time dashboards that surface what matters now</li><li>Customer behaviour analysis that sharpens marketing</li><li>Operational analytics that reveal cost-saving opportunities</li><li>Forecasting models that support confident planning</li></ul><p>Start with the questions that matter to your business, then build the data pipeline that answers them.</p>',
            ],
            [
                'slug' => 'building-scalable-it-infrastructure-for-growth',
                'title' => 'Building Scalable IT Infrastructure for Growth',
                'topic' => 'IT Infrastructure',
                'published_at' => '2025-04-28 00:00:00',
                'excerpt' => 'Key considerations and best practices for designing a resilient and future-ready IT infrastructure.',
                'content' => '<p>Infrastructure decisions made today determine how easily your business scales tomorrow. A future-ready foundation balances performance, reliability, and cost.</p><h4>Design Principles</h4><ul><li>Structured cabling and network design that supports growth</li><li>Redundancy and failover for business-critical systems</li><li>Proactive monitoring and preventive maintenance</li><li>Clear upgrade paths that avoid disruptive rebuilds</li></ul><p>Whether on-premise, cloud, or hybrid — the right architecture keeps your business running and ready for what is next.</p>',
            ],
            [
                'slug' => 'digital-transformation-roadmap-for-success',
                'title' => 'Digital Transformation: A Roadmap for Success',
                'topic' => 'Digital Transformation',
                'published_at' => '2025-04-20 00:00:00',
                'excerpt' => 'A step-by-step guide to help businesses embrace digital change and achieve long-term success.',
                'content' => '<p>Digital transformation is more than adopting new tools — it is rethinking how your business creates value with technology at the core.</p><h4>The Roadmap</h4><ul><li>Assess current systems, processes, and skill gaps</li><li>Prioritise initiatives by business impact</li><li>Modernise incrementally with quick, visible wins</li><li>Measure outcomes and iterate continuously</li></ul><p>With a clear roadmap and the right technology partner, transformation becomes a series of manageable steps rather than a leap of faith.</p>',
            ],
        ];

        foreach ($insights as $sortOrder => $insight) {
            Insight::updateOrCreate(
                ['slug' => $insight['slug']],
                [
                    ...$insight,
                    'author' => 'Tectignis Team',
                    'sort_order' => $sortOrder,
                    'is_published' => true,
                ],
            );
        }
    }
}
