<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\SeedsServiceContent;
use Illuminate\Database\Seeder;

class AiAutomationContentSeeder extends Seeder
{
    use SeedsServiceContent;

    public function run(): void
    {
        $capability = $this->seedCapability([
            'slug' => 'ai-automation',
            'category' => 'ai_automation',
            'title' => 'AI & Automation',
            'short_description' => 'AI, data, and workflow automation services that help teams respond faster, reduce manual work, and scale smarter.',
            'icon' => 'capabilities/ai-chatbot-hwuzfejk.png',
            'sort_order' => 3,
        ]);

        foreach ($this->services() as $service) {
            $this->seedService($capability, array_merge($service, ['category' => 'ai_automation']));
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function services(): array
    {
        return [
            [
                'slug' => 'ai-chatbot-development',
                'title' => 'AI Chatbot Development',
                'sort_order' => 1,
                'short_description' => 'AI chatbot development for websites, WhatsApp, support desks, lead capture, and internal knowledge assistance.',
                'seo_title' => 'AI Chatbot Development in Navi Mumbai | Tectignis',
                'seo_description' => 'AI chatbot development in Navi Mumbai for support, sales, WhatsApp, websites, and internal teams. Build smart assistants connected to your data and workflows.',
                'heading' => 'AI Chatbot Development That Handles Real Conversations',
                'intro' => 'Give customers and teams instant answers without overloading your staff. We design AI chatbots that understand your business context, collect useful information, and hand off smoothly when human support is needed.',
                'bullets' => [
                    'Website, WhatsApp, and helpdesk chatbot experiences',
                    'AI responses grounded in your documents and FAQs',
                    'CRM, ticketing, and lead capture integrations',
                ],
                'sub_services' => [
                    ['title' => 'Customer Support Chatbots', 'description' => 'Answer common questions, collect issue details, and route complex requests to the right team.'],
                    ['title' => 'Sales and Lead Assistants', 'description' => 'Qualify prospects, recommend services, and push captured leads into your CRM.'],
                    ['title' => 'Knowledge Base Bots', 'description' => 'Use your policies, manuals, FAQs, and documents to answer internal or customer questions.'],
                    ['title' => 'WhatsApp AI Chatbots', 'description' => 'Bring guided conversations, reminders, bookings, and support to WhatsApp.'],
                    ['title' => 'Agent Handoff Workflows', 'description' => 'Escalate conversations with history, intent, and customer details intact.'],
                    ['title' => 'Chatbot Analytics', 'description' => 'Track questions, unresolved topics, lead quality, and automation performance.'],
                ],
                'why_points' => [
                    'Conversation flows built around your actual customer journeys',
                    'Retrieval-based answers that reduce hallucinated or generic replies',
                    'Integrations with CRM, website forms, WhatsApp, helpdesk, and databases',
                    'Admin-friendly controls for FAQs, prompts, escalation, and reporting',
                    'Launch support that includes testing, tuning, and staff handover',
                ],
                'faqs' => [
                    ['question' => 'Can the chatbot answer from our company documents?', 'answer' => 'Yes. We can connect approved documents, FAQs, policies, and knowledge bases so answers stay relevant to your business.'],
                    ['question' => 'Can it transfer chats to a human?', 'answer' => 'Yes. We can add handoff rules, escalation triggers, and transcript sharing for support or sales teams.'],
                    ['question' => 'Can the chatbot work on WhatsApp?', 'answer' => 'Yes. We can build WhatsApp chatbot flows using the official Business API and connect them with your backend systems.'],
                    ['question' => 'How do you reduce wrong AI answers?', 'answer' => 'We use controlled prompts, retrieval from approved sources, fallback rules, testing sets, and clear human escalation paths.'],
                ],
                'tech_stacks' => ['OpenAI', 'LangChain', 'Python', 'Laravel', 'React', 'MySQL'],
                'industries' => ['Retail', 'Healthcare', 'Education', 'Real Estate', 'Corporate Offices', 'Startups'],
                'cta_heading' => 'Ready to Launch an AI Chatbot That Knows Your Business?',
            ],
            [
                'slug' => 'ai-integration',
                'title' => 'AI Integration',
                'sort_order' => 2,
                'short_description' => 'AI integration services that add intelligent search, recommendations, summaries, assistants, and predictions to existing systems.',
                'seo_title' => 'AI Integration Services in Navi Mumbai | Tectignis',
                'seo_description' => 'AI integration services in Navi Mumbai. Add AI search, automation, summaries, recommendations, analytics, and assistants to your existing software.',
                'heading' => 'AI Integration for the Tools You Already Use',
                'intro' => 'You do not always need a new platform to benefit from AI. We integrate intelligent features into your existing software, portals, CRMs, ERPs, and workflows so teams can make faster decisions with less manual effort.',
                'bullets' => [
                    'AI features added to existing products and portals',
                    'Secure API connections with your business data',
                    'Practical automation for search, summaries, and decisions',
                ],
                'sub_services' => [
                    ['title' => 'AI Feature Planning', 'description' => 'Identify high-value use cases where AI improves speed, accuracy, or customer experience.'],
                    ['title' => 'LLM API Integration', 'description' => 'Connect language models for summarization, drafting, classification, extraction, and Q&A.'],
                    ['title' => 'Smart Search', 'description' => 'Add semantic search across documents, records, product catalogs, or internal knowledge.'],
                    ['title' => 'Recommendation Logic', 'description' => 'Suggest products, content, next actions, or service options using user and business data.'],
                    ['title' => 'AI Workflow Hooks', 'description' => 'Trigger AI actions inside CRM, ERP, ticketing, reporting, or approval workflows.'],
                    ['title' => 'Monitoring and Guardrails', 'description' => 'Track usage, cost, response quality, and fallback behavior after launch.'],
                ],
                'why_points' => [
                    'Integration-first approach for companies that already have working systems',
                    'Security-minded design for data access, permissions, and API boundaries',
                    'Clear use case prioritization before model selection or feature buildout',
                    'Cost controls for token usage, caching, and high-volume workflows',
                    'Support for iterative improvement once real users start using the AI feature',
                ],
                'faqs' => [
                    ['question' => 'Can AI be added to our current Laravel or custom app?', 'answer' => 'Yes. We can integrate AI into existing Laravel, PHP, React, or API-based systems after reviewing the architecture.'],
                    ['question' => 'Do we need to share all company data with an AI model?', 'answer' => 'No. We scope access carefully and can limit AI to approved datasets, documents, APIs, and user permissions.'],
                    ['question' => 'Can you help choose the right AI model?', 'answer' => 'Yes. We compare cost, quality, latency, privacy, and use case fit before selecting a model or provider.'],
                    ['question' => 'Can AI outputs be reviewed before action is taken?', 'answer' => 'Yes. We can design human approval flows for emails, reports, classifications, and sensitive decisions.'],
                ],
                'tech_stacks' => ['OpenAI', 'LangChain', 'Python', 'Laravel', 'Node.js', 'React', 'PostgreSQL'],
                'industries' => ['Corporate Offices', 'Finance & Banking', 'Healthcare', 'E-commerce', 'Logistics'],
                'cta_heading' => 'Ready to Add AI to Your Existing Systems?',
            ],
            [
                'slug' => 'generative-ai-solutions',
                'title' => 'Generative AI Solutions',
                'sort_order' => 3,
                'short_description' => 'Generative AI solutions for content, document intelligence, knowledge assistants, product tools, and internal productivity.',
                'seo_title' => 'Generative AI Solutions in Navi Mumbai | Tectignis',
                'seo_description' => 'Generative AI solutions in Navi Mumbai for knowledge assistants, content workflows, document intelligence, product features, and business automation.',
                'heading' => 'Generative AI Solutions Built Around Your Use Case',
                'intro' => 'Move beyond experimentation and build GenAI tools that support real work. We create controlled, business-ready solutions for knowledge access, content production, document review, and customer-facing AI experiences.',
                'bullets' => [
                    'Custom GenAI tools for internal and customer workflows',
                    'Document-grounded assistants with clear guardrails',
                    'Content and reporting workflows designed for review',
                ],
                'sub_services' => [
                    ['title' => 'Knowledge Assistants', 'description' => 'Create assistants that answer from manuals, policies, SOPs, contracts, and internal documents.'],
                    ['title' => 'Content Workflow Tools', 'description' => 'Generate drafts, product descriptions, campaign copy, and summaries with brand controls.'],
                    ['title' => 'Document Review', 'description' => 'Summarize, compare, classify, and extract information from large document sets.'],
                    ['title' => 'GenAI Product Features', 'description' => 'Add AI drafting, explainers, search, or assistant features into your software product.'],
                    ['title' => 'Prompt and Evaluation Design', 'description' => 'Build prompts, test sets, scorecards, and review flows for consistent quality.'],
                    ['title' => 'Responsible AI Controls', 'description' => 'Set boundaries for data access, approvals, sensitive topics, and user permissions.'],
                ],
                'why_points' => [
                    'Business-first GenAI planning focused on measurable productivity gains',
                    'Grounded responses using approved documents and well-defined context',
                    'Human review options for marketing, legal, finance, and support workflows',
                    'Cost-aware architecture with caching, batching, and model routing where useful',
                    'Ongoing tuning based on user feedback, failed questions, and quality checks',
                ],
                'faqs' => [
                    ['question' => 'Can GenAI use our internal knowledge base?', 'answer' => 'Yes. We can connect approved documents and databases so answers and drafts are based on your own information.'],
                    ['question' => 'Can generated content follow our brand tone?', 'answer' => 'Yes. We can add tone rules, examples, templates, and review workflows for more consistent content.'],
                    ['question' => 'Can GenAI summarize long contracts or reports?', 'answer' => 'Yes. We can build document workflows for summaries, comparisons, extraction, and risk highlighting.'],
                    ['question' => 'How do you make GenAI safe for staff use?', 'answer' => 'We use access controls, approved data sources, clear disclaimers, human review steps, logging, and fallback behavior.'],
                ],
                'tech_stacks' => ['OpenAI', 'LangChain', 'Python', 'React', 'PostgreSQL', 'Google Cloud'],
                'industries' => ['Corporate Offices', 'Finance & Banking', 'Healthcare', 'Education', 'Startups'],
                'cta_heading' => 'Ready to Turn Generative AI Into a Working Tool?',
            ],
            [
                'slug' => 'machine-learning-solutions',
                'title' => 'Machine Learning Solutions',
                'sort_order' => 4,
                'short_description' => 'Machine learning solutions for prediction, classification, anomaly detection, recommendations, and data-driven decisions.',
                'seo_title' => 'Machine Learning Solutions in Navi Mumbai | Tectignis',
                'seo_description' => 'Machine learning solutions in Navi Mumbai for predictive analytics, classification, recommendation systems, anomaly detection, and intelligent automation.',
                'heading' => 'Machine Learning Solutions for Better Business Decisions',
                'intro' => 'Use your historical data to forecast, detect patterns, and prioritize action. We develop machine learning solutions that turn scattered data into practical models, dashboards, and automated decisions.',
                'bullets' => [
                    'Predictive models built from your business data',
                    'Classification, scoring, recommendations, and anomaly detection',
                    'Deployment planning for reliable day-to-day use',
                ],
                'sub_services' => [
                    ['title' => 'Data Readiness Assessment', 'description' => 'Review available data, quality gaps, labels, volume, and model feasibility before development.'],
                    ['title' => 'Predictive Analytics', 'description' => 'Forecast demand, sales, churn, lead conversion, maintenance needs, or operational load.'],
                    ['title' => 'Classification Models', 'description' => 'Automatically categorize tickets, documents, customers, transactions, products, or risk levels.'],
                    ['title' => 'Recommendation Systems', 'description' => 'Suggest products, content, actions, or next-best offers from behavior and history.'],
                    ['title' => 'Anomaly Detection', 'description' => 'Flag unusual activity, stock movement, payments, machine readings, or system behavior.'],
                    ['title' => 'Model Deployment', 'description' => 'Package models into APIs, dashboards, batch jobs, or application workflows.'],
                ],
                'why_points' => [
                    'Feasibility checks before investing in model development',
                    'Model choices guided by accuracy, explainability, data quality, and operating cost',
                    'Deployment paths that fit your existing applications and reporting workflows',
                    'Monitoring for drift, errors, outliers, and retraining needs',
                    'Clear communication of model assumptions, limits, and business impact',
                ],
                'faqs' => [
                    ['question' => 'How much data do we need for machine learning?', 'answer' => 'It depends on the use case, quality, labels, and expected accuracy. We start with a data assessment before recommending a model.'],
                    ['question' => 'Can machine learning work with Excel or ERP exports?', 'answer' => 'Yes. We can begin with exports, then plan database or API integrations for repeatable model updates.'],
                    ['question' => 'Can predictions be shown in our existing dashboard?', 'answer' => 'Yes. We can expose predictions through APIs, reports, alerts, or dashboard widgets.'],
                    ['question' => 'Do you monitor models after launch?', 'answer' => 'Yes. We can track accuracy, drift, failures, and retraining needs as fresh data comes in.'],
                ],
                'tech_stacks' => ['Python', 'TensorFlow', 'PyTorch', 'PostgreSQL', 'Docker', 'Google Cloud'],
                'industries' => ['Manufacturing', 'Retail', 'Finance & Banking', 'Healthcare', 'Logistics', 'E-commerce'],
                'cta_heading' => 'Ready to Build Intelligence From Your Data?',
            ],
            [
                'slug' => 'business-process-automation',
                'title' => 'Business Process Automation',
                'sort_order' => 5,
                'short_description' => 'Business process automation for approvals, data entry, reporting, notifications, documents, and system handoffs.',
                'seo_title' => 'Business Process Automation in Navi Mumbai | Tectignis',
                'seo_description' => 'Business process automation in Navi Mumbai for approvals, reporting, data entry, documents, notifications, CRM, ERP, and workflow integrations.',
                'heading' => 'Business Process Automation That Removes Repetitive Work',
                'intro' => 'Stop losing time to manual approvals, duplicate entries, and delayed follow-ups. We automate everyday workflows so information moves correctly between people, systems, and reports.',
                'bullets' => [
                    'Approval, notification, and document workflows',
                    'Data sync between CRM, ERP, sheets, and portals',
                    'Dashboards that show process status in real time',
                ],
                'sub_services' => [
                    ['title' => 'Workflow Mapping', 'description' => 'Document current steps, owners, exceptions, delays, and automation opportunities.'],
                    ['title' => 'Approval Automation', 'description' => 'Route requests, reminders, escalations, and decision logs through clear approval paths.'],
                    ['title' => 'Data Entry Automation', 'description' => 'Reduce duplicate typing by syncing data between forms, spreadsheets, CRMs, ERPs, and apps.'],
                    ['title' => 'Document Generation', 'description' => 'Create quotations, invoices, letters, reports, and certificates from approved templates.'],
                    ['title' => 'Notification Workflows', 'description' => 'Trigger email, SMS, WhatsApp, or in-app alerts based on status changes and deadlines.'],
                    ['title' => 'Process Dashboards', 'description' => 'Track pending items, SLA breaches, approvals, volumes, and team performance.'],
                ],
                'why_points' => [
                    'Automation plans based on actual bottlenecks rather than generic tools',
                    'Practical integrations with software your team already uses',
                    'Clear exception handling for approvals, missing data, and manual overrides',
                    'Audit trails that show who approved, changed, or completed each step',
                    'Phased delivery so teams can adopt automation without disruption',
                ],
                'faqs' => [
                    ['question' => 'Which processes can be automated first?', 'answer' => 'Approvals, data entry, reminders, reports, document generation, lead routing, and invoice workflows are usually strong starting points.'],
                    ['question' => 'Can automation connect multiple existing systems?', 'answer' => 'Yes. We can connect APIs, databases, files, spreadsheets, CRMs, ERPs, and notification channels where access is available.'],
                    ['question' => 'Will staff still be able to override steps?', 'answer' => 'Yes. We can include manual review, exception handling, and approval controls for sensitive workflows.'],
                    ['question' => 'Can dashboards show pending work?', 'answer' => 'Yes. We can build live dashboards for pending approvals, SLA status, volumes, delays, and completed tasks.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'Node.js', 'React', 'MySQL', 'Redis'],
                'industries' => ['Corporate Offices', 'Manufacturing', 'Logistics', 'Finance & Banking', 'Healthcare'],
                'cta_heading' => 'Ready to Automate Your Most Repetitive Work?',
            ],
            [
                'slug' => 'ocr-document-digitization',
                'title' => 'OCR & Document Digitization',
                'sort_order' => 6,
                'short_description' => 'OCR and document digitization for forms, invoices, KYC, receipts, archives, searchable records, and workflow automation.',
                'seo_title' => 'OCR & Document Digitization in Navi Mumbai | Tectignis',
                'seo_description' => 'OCR and document digitization services in Navi Mumbai for invoices, forms, KYC, receipts, records, archives, and searchable document workflows.',
                'heading' => 'OCR and Document Digitization for Searchable Business Records',
                'intro' => 'Turn paper, scans, and PDFs into structured data your teams can search, validate, and use. We build OCR workflows that reduce manual entry and make document-heavy operations faster.',
                'bullets' => [
                    'Extract text and fields from forms, PDFs, and images',
                    'Validation workflows for accuracy and exceptions',
                    'Searchable digital archives with role-based access',
                ],
                'sub_services' => [
                    ['title' => 'Invoice and Receipt OCR', 'description' => 'Capture vendor, date, tax, line item, amount, and payment details from invoices and receipts.'],
                    ['title' => 'Form Data Extraction', 'description' => 'Read structured and semi-structured forms for admissions, onboarding, claims, and requests.'],
                    ['title' => 'KYC Document Processing', 'description' => 'Extract identity details from approved documents and route exceptions for review.'],
                    ['title' => 'Archive Digitization', 'description' => 'Convert legacy files into organized, tagged, searchable digital records.'],
                    ['title' => 'Validation and Review Screens', 'description' => 'Let staff confirm uncertain fields and correct data before it enters core systems.'],
                    ['title' => 'Document Workflow Integration', 'description' => 'Send extracted data into ERP, CRM, HRMS, accounting, or custom applications.'],
                ],
                'why_points' => [
                    'OCR workflows designed for accuracy, review, and business usability',
                    'Support for scanned PDFs, camera images, forms, invoices, and archive files',
                    'Human verification screens for fields that need confidence checks',
                    'Search and tagging structures that make old documents easier to retrieve',
                    'Integration with downstream systems so extracted data actually gets used',
                ],
                'faqs' => [
                    ['question' => 'Can OCR read low-quality scanned documents?', 'answer' => 'We can improve many scans with preprocessing, but accuracy depends on image quality, layout, handwriting, and field clarity.'],
                    ['question' => 'Can staff verify extracted fields?', 'answer' => 'Yes. We can build review screens for uncertain values before data is pushed into your main system.'],
                    ['question' => 'Can documents become searchable?', 'answer' => 'Yes. We can store extracted text, tags, metadata, and files so teams can search and filter records quickly.'],
                    ['question' => 'Can OCR connect to accounting or ERP?', 'answer' => 'Yes. Extracted invoice, vendor, customer, or form data can be sent to ERP, accounting, CRM, or custom software.'],
                ],
                'tech_stacks' => ['Python', 'OpenAI', 'TensorFlow', 'Laravel', 'React', 'PostgreSQL'],
                'industries' => ['Finance & Banking', 'Healthcare', 'Education', 'Logistics', 'Corporate Offices'],
                'cta_heading' => 'Ready to Turn Documents Into Usable Data?',
            ],
            [
                'slug' => 'voice-bot-solutions',
                'title' => 'Voice Bot Solutions',
                'sort_order' => 7,
                'short_description' => 'Voice bot solutions for inbound support, outbound reminders, IVR modernization, appointment booking, and call workflows.',
                'seo_title' => 'Voice Bot Solutions in Navi Mumbai | Tectignis',
                'seo_description' => 'Voice bot solutions in Navi Mumbai for support calls, reminders, appointment booking, IVR, surveys, lead qualification, and call center automation.',
                'heading' => 'Voice Bot Solutions for Faster Phone Conversations',
                'intro' => 'Automate repetitive calls while keeping the experience natural and useful. We build voice bot workflows for support, reminders, confirmations, surveys, bookings, and lead qualification.',
                'bullets' => [
                    'Inbound and outbound voice automation',
                    'Appointment, reminder, survey, and support flows',
                    'Call logs, outcomes, and CRM updates',
                ],
                'sub_services' => [
                    ['title' => 'Inbound Voice Assistants', 'description' => 'Answer routine questions, identify intent, collect details, and route callers correctly.'],
                    ['title' => 'Outbound Reminder Calls', 'description' => 'Automate payment, appointment, renewal, delivery, or service reminders with status capture.'],
                    ['title' => 'Conversational IVR', 'description' => 'Replace long menu trees with spoken intent recognition and guided responses.'],
                    ['title' => 'Appointment Booking', 'description' => 'Let callers book, confirm, cancel, or reschedule appointments through voice workflows.'],
                    ['title' => 'Survey and Feedback Calls', 'description' => 'Collect ratings, responses, and follow-up flags after service or delivery.'],
                    ['title' => 'Call System Integration', 'description' => 'Connect voice outcomes with CRM, ticketing, calendars, or operations dashboards.'],
                ],
                'why_points' => [
                    'Voice flows planned around caller intent and escalation needs',
                    'Better call handling during high-volume periods or after business hours',
                    'Outcome tracking for every call, response, booking, and failed attempt',
                    'Integration with customer records so teams avoid repeat data entry',
                    'Tuning support for scripts, accents, fallback paths, and call quality',
                ],
                'faqs' => [
                    ['question' => 'Can a voice bot handle appointment booking?', 'answer' => 'Yes. It can check available slots, capture caller details, confirm bookings, and update your calendar or system.'],
                    ['question' => 'Can calls be transferred to staff?', 'answer' => 'Yes. Escalation rules can transfer urgent or complex calls with captured context.'],
                    ['question' => 'Can voice bots make outbound reminders?', 'answer' => 'Yes. We can automate reminders for payments, appointments, renewals, deliveries, and service visits.'],
                    ['question' => 'Can call outcomes be stored in CRM?', 'answer' => 'Yes. Call status, responses, recordings, notes, and next actions can be pushed into CRM or dashboards.'],
                ],
                'tech_stacks' => ['OpenAI', 'Python', 'Node.js', 'Laravel', 'React', 'Redis'],
                'industries' => ['Healthcare', 'Finance & Banking', 'Retail', 'Logistics', 'Real Estate'],
                'cta_heading' => 'Ready to Automate Repetitive Phone Calls?',
            ],
            [
                'slug' => 'whatsapp-automation',
                'title' => 'WhatsApp Automation',
                'sort_order' => 8,
                'short_description' => 'WhatsApp automation for verified business messaging, chatbots, notifications, lead capture, reminders, and support workflows.',
                'seo_title' => 'WhatsApp Automation Services in Navi Mumbai | Tectignis',
                'seo_description' => 'WhatsApp automation services in Navi Mumbai for Business API setup, chatbots, notifications, reminders, lead capture, support, and CRM integration.',
                'heading' => 'WhatsApp Automation for Sales, Support, and Updates',
                'intro' => 'Meet customers on the channel they already check every day. We set up WhatsApp automation for inquiries, notifications, reminders, bookings, support, and lead follow-up through compliant Business API workflows.',
                'bullets' => [
                    'Official WhatsApp Business API setup and workflows',
                    'Chatbots, notifications, reminders, and lead capture',
                    'CRM and backend integration for complete tracking',
                ],
                'sub_services' => [
                    ['title' => 'Business API Onboarding', 'description' => 'Plan verified WhatsApp setup, templates, numbers, permissions, and messaging rules.'],
                    ['title' => 'WhatsApp Chatbots', 'description' => 'Automate FAQs, product discovery, appointment booking, order updates, and support triage.'],
                    ['title' => 'Transactional Notifications', 'description' => 'Send approved reminders, confirmations, alerts, receipts, OTPs, and status updates.'],
                    ['title' => 'Lead Capture Flows', 'description' => 'Collect customer details, qualify interest, and push leads into CRM or sales dashboards.'],
                    ['title' => 'Campaign Workflows', 'description' => 'Run compliant broadcast flows using approved templates, segments, and response tracking.'],
                    ['title' => 'Conversation Reporting', 'description' => 'Track delivery, replies, conversions, opt-ins, handoffs, and team response times.'],
                ],
                'why_points' => [
                    'WhatsApp flows designed around compliance, opt-ins, and approved templates',
                    'Automation that supports both customer service and sales follow-up',
                    'CRM, booking, payment, order, and ticketing integrations where required',
                    'Clear handoff from bot to team members with conversation history',
                    'Reporting for message delivery, responses, leads, and campaign outcomes',
                ],
                'faqs' => [
                    ['question' => 'Do you set up the official WhatsApp Business API?', 'answer' => 'Yes. We can guide setup, templates, number planning, webhook integration, and automation workflows.'],
                    ['question' => 'Can WhatsApp messages connect to CRM?', 'answer' => 'Yes. Leads, chats, statuses, and follow-up tasks can be synced with CRM or a custom dashboard.'],
                    ['question' => 'Can we send reminders and alerts?', 'answer' => 'Yes. Approved transactional templates can support reminders, confirmations, alerts, invoices, and status updates.'],
                    ['question' => 'Can customers talk to a staff member?', 'answer' => 'Yes. Bot conversations can be handed off to sales or support teams with the previous chat context.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'Node.js', 'OpenAI', 'React', 'MySQL'],
                'industries' => ['Retail', 'Healthcare', 'Education', 'Real Estate', 'E-commerce', 'Hospitality'],
                'cta_heading' => 'Ready to Automate WhatsApp Conversations?',
            ],
        ];
    }
}
