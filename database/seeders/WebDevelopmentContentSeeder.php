<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\SeedsServiceContent;
use Illuminate\Database\Seeder;

class WebDevelopmentContentSeeder extends Seeder
{
    use SeedsServiceContent;

    public function run(): void
    {
        $capability = $this->seedCapability([
            'slug' => 'business-applications',
            'category' => 'business_application',
            'title' => 'Web Development',
            'short_description' => 'Practical business software that brings daily operations, teams, and reporting into one reliable digital workflow.',
            'icon' => 'capabilities/web-development-990qixal.png',
            'sort_order' => 2,
        ]);

        foreach ($this->services() as $service) {
            $this->seedService($capability, array_merge($service, ['category' => 'business_application']));
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function services(): array
    {
        return [
            [
                'slug' => 'hospital-management',
                'title' => 'Hospital Management Software',
                'banner_image' => 'services/hospital-management-software-hero.png',
                'sort_order' => 1,
                'short_description' => 'Integrated hospital software for appointments, OPD, billing, pharmacy, laboratory, and patient records.',
                'seo_title' => 'Hospital Management Software in Navi Mumbai | Tectignis',
                'seo_description' => 'Hospital management software in Navi Mumbai for clinics and hospitals. Manage patients, OPD, billing, pharmacy, labs, and reports from one secure system.',
                'heading' => 'Hospital Management Software for Connected Care',
                'intro' => 'Run clinical, administrative, and financial workflows from one dependable platform. We build hospital management software that reduces queues, protects records, and gives teams faster access to the information they need.',
                'bullets' => [
                    'Single patient record across departments',
                    'Faster OPD, pharmacy, lab, and billing workflows',
                    'Secure role-based access for doctors and staff',
                ],
                'sub_services' => [
                    ['title' => 'Patient Registration', 'description' => 'Create clean patient profiles with visit history, documents, and department assignment.'],
                    ['title' => 'OPD and Appointment Flow', 'description' => 'Schedule consultations, manage queues, and keep front-desk teams aligned.'],
                    ['title' => 'Billing and Payments', 'description' => 'Generate invoices, receipts, package bills, and payment summaries without manual spreadsheets.'],
                    ['title' => 'Pharmacy Stock Control', 'description' => 'Track medicines, batches, expiry dates, purchase entries, and issue records.'],
                    ['title' => 'Lab and Diagnostic Reports', 'description' => 'Record test orders, results, and printable reports linked to each patient.'],
                    ['title' => 'Hospital Dashboards', 'description' => 'Monitor admissions, revenue, collections, occupancy, and department performance in real time.'],
                ],
                'why_points' => [
                    'Workflows shaped for Indian clinics, hospitals, and multi-specialty centers',
                    'Clear permissions for doctors, reception, billing, pharmacy, and admin teams',
                    'Reports that help owners track collections, visits, and operational bottlenecks',
                    'Expandable modules for lab, pharmacy, IPD, and branch management',
                    'Local implementation support for setup, migration, and staff training',
                ],
                'faqs' => [
                    ['question' => 'Can the system support both OPD and IPD workflows?', 'answer' => 'Yes. We can configure OPD, appointments, admissions, discharge summaries, pharmacy, lab, and billing based on your hospital process.'],
                    ['question' => 'Can old patient records be imported?', 'answer' => 'We review your existing Excel, software, or paper records and migrate usable data into the new system with validation checks.'],
                    ['question' => 'Will different staff roles see different screens?', 'answer' => 'Yes. Doctors, front desk, billing, pharmacy, lab, and administrators can each receive role-specific access.'],
                    ['question' => 'Can reports be customized for management?', 'answer' => 'We can add reports for collections, appointments, medicine stock, outstanding payments, occupancy, and department-wise activity.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'Vue.js', 'MySQL', 'PostgreSQL'],
                'industries' => ['Healthcare', 'Corporate Offices'],
                'cta_heading' => 'Ready to Digitize Your Hospital Operations?',
            ],
            [
                'slug' => 'hrms-development',
                'title' => 'HRMS Development',
                'sort_order' => 2,
                'short_description' => 'Custom HRMS platforms for attendance, payroll, leave, hiring, onboarding, and employee self-service.',
                'seo_title' => 'HRMS Development Services in Navi Mumbai | Tectignis',
                'seo_description' => 'HRMS development in Navi Mumbai for growing companies. Automate attendance, leave, payroll, onboarding, documents, and employee self-service.',
                'heading' => 'HRMS Development That Makes People Operations Easier',
                'intro' => 'Replace scattered HR files and manual follow-ups with a system your team can trust every day. We develop HRMS platforms that simplify attendance, payroll, leave, documents, approvals, and employee communication.',
                'bullets' => [
                    'Attendance, leave, payroll, and documents in one place',
                    'Employee and manager self-service portals',
                    'Custom rules for shifts, branches, and approvals',
                ],
                'sub_services' => [
                    ['title' => 'Employee Master Records', 'description' => 'Maintain personal, job, salary, document, and compliance details in a secure profile.'],
                    ['title' => 'Attendance Management', 'description' => 'Connect biometric, mobile, geo-tagged, or shift-based attendance into clean daily records.'],
                    ['title' => 'Leave and Approval Workflows', 'description' => 'Automate leave requests, balances, holidays, comp-offs, and manager approvals.'],
                    ['title' => 'Payroll Processing', 'description' => 'Calculate earnings, deductions, reimbursements, payslips, and statutory components with fewer errors.'],
                    ['title' => 'Recruitment and Onboarding', 'description' => 'Track candidates, offer letters, joining checklists, and induction tasks.'],
                    ['title' => 'Employee Self-Service', 'description' => 'Let staff download payslips, raise requests, update details, and view HR announcements.'],
                ],
                'why_points' => [
                    'Flexible HR rules for small teams, factories, offices, and multi-branch companies',
                    'Cleaner payroll inputs through attendance, leave, and shift integration',
                    'Secure document handling for IDs, contracts, appraisals, and letters',
                    'Dashboards that reveal absenteeism, headcount, attrition, and payroll trends',
                    'Implementation support that helps HR teams move away from Excel gradually',
                ],
                'faqs' => [
                    ['question' => 'Can you connect biometric attendance devices?', 'answer' => 'Yes. We can integrate supported biometric systems or import attendance files when direct connectivity is not available.'],
                    ['question' => 'Can payroll rules match our company policy?', 'answer' => 'Yes. Salary heads, deductions, leave encashment, overtime, shifts, and approval rules can be configured for your process.'],
                    ['question' => 'Do employees get their own login?', 'answer' => 'They can. We build employee self-service access for payslips, requests, documents, attendance, and leave balances.'],
                    ['question' => 'Can HRMS be rolled out branch by branch?', 'answer' => 'Yes. We can launch core modules first and then add payroll, recruitment, onboarding, and analytics in phases.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'MySQL', 'Microsoft'],
                'industries' => ['Corporate Offices', 'Manufacturing', 'Healthcare', 'Education', 'Startups'],
                'cta_heading' => 'Ready to Build a Smarter HRMS?',
            ],
            [
                'slug' => 'inventory-management',
                'title' => 'Inventory Management Software',
                'sort_order' => 3,
                'short_description' => 'Inventory software for stock visibility, purchases, transfers, barcode workflows, and warehouse reporting.',
                'seo_title' => 'Inventory Management Software in Navi Mumbai | Tectignis',
                'seo_description' => 'Inventory management software in Navi Mumbai for retailers, warehouses, and manufacturers. Track stock, purchases, transfers, barcodes, and reports.',
                'heading' => 'Inventory Management Software With Real Stock Visibility',
                'intro' => 'Know what is available, where it sits, and when to reorder without calling every store or warehouse. We build inventory systems that bring purchasing, stock movement, and reporting under control.',
                'bullets' => [
                    'Live stock across stores, warehouses, and departments',
                    'Barcode-ready inward, outward, and transfer workflows',
                    'Reorder alerts that prevent shortages and overstocking',
                ],
                'sub_services' => [
                    ['title' => 'Stock Ledger Management', 'description' => 'Track item-wise inward, outward, adjustments, returns, and current balances.'],
                    ['title' => 'Purchase and Vendor Flow', 'description' => 'Manage requisitions, purchase orders, GRN, vendor invoices, and pending deliveries.'],
                    ['title' => 'Warehouse Transfers', 'description' => 'Move stock between locations with approval, dispatch, receipt, and variance tracking.'],
                    ['title' => 'Barcode and QR Operations', 'description' => 'Speed up scanning, counting, picking, and dispatch with barcode or QR labels.'],
                    ['title' => 'Batch and Expiry Tracking', 'description' => 'Control perishable, medical, or batch-based items with expiry alerts and traceability.'],
                    ['title' => 'Inventory Analytics', 'description' => 'Review fast-moving items, dead stock, valuation, purchase trends, and reorder requirements.'],
                ],
                'why_points' => [
                    'Built around your stock categories, units, warehouses, and approval levels',
                    'Better purchase planning through reorder points and supplier visibility',
                    'Barcode workflows that reduce counting mistakes and dispatch delays',
                    'Reports for valuation, ageing, movement, margins, and branch-wise stock',
                    'Integration options for POS, accounting, e-commerce, and ERP systems',
                ],
                'faqs' => [
                    ['question' => 'Can this manage multiple warehouses?', 'answer' => 'Yes. You can track stock by warehouse, rack, store, department, or branch depending on your operation.'],
                    ['question' => 'Do you support barcode scanning?', 'answer' => 'Yes. We can add barcode or QR workflows for inward, stock counts, picking, dispatch, and returns.'],
                    ['question' => 'Can inventory connect with POS or accounting?', 'answer' => 'Yes. We can integrate inventory with POS, invoicing, accounting, e-commerce, or ERP tools.'],
                    ['question' => 'Can reports show slow-moving stock?', 'answer' => 'Yes. We can build reports for slow-moving, non-moving, near-expiry, high-value, and fast-selling items.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'MySQL', 'PostgreSQL'],
                'industries' => ['Retail', 'Manufacturing', 'Logistics', 'E-commerce', 'Healthcare'],
                'cta_heading' => 'Ready to Take Control of Your Stock?',
            ],
            [
                'slug' => 'lms-development',
                'title' => 'LMS Development',
                'sort_order' => 4,
                'short_description' => 'Learning management systems for courses, assessments, student progress, certificates, and online training delivery.',
                'seo_title' => 'LMS Development Services in Navi Mumbai | Tectignis',
                'seo_description' => 'LMS development in Navi Mumbai for schools, training institutes, and companies. Manage courses, assessments, learners, certificates, and progress tracking.',
                'heading' => 'LMS Development for Modern Learning Programs',
                'intro' => 'Deliver courses, track progress, and keep learners engaged through a platform built around your teaching model. We create LMS solutions for institutions, training businesses, and corporate learning teams.',
                'bullets' => [
                    'Course, batch, learner, and trainer management',
                    'Assessments, progress tracking, and certificates',
                    'Responsive learning experience for web and mobile users',
                ],
                'sub_services' => [
                    ['title' => 'Course Builder', 'description' => 'Organize lessons, videos, documents, quizzes, assignments, and downloadable resources.'],
                    ['title' => 'Learner Enrollment', 'description' => 'Manage students, batches, groups, access periods, and course eligibility rules.'],
                    ['title' => 'Assessment Engine', 'description' => 'Run quizzes, tests, assignments, grading, question banks, and result publishing.'],
                    ['title' => 'Progress Tracking', 'description' => 'Show completion, scores, attendance, activity logs, and learner performance trends.'],
                    ['title' => 'Certificate Management', 'description' => 'Issue branded certificates automatically after course or assessment completion.'],
                    ['title' => 'Trainer and Admin Panels', 'description' => 'Give faculty and administrators focused tools for content, learners, and reports.'],
                ],
                'why_points' => [
                    'LMS workflows tailored for schools, coaching centers, and corporate training teams',
                    'Flexible content support for video, PDF, quizzes, live links, and assignments',
                    'Learner dashboards that make progress visible without manual tracking',
                    'Payment, certificate, and notification integrations when needed',
                    'Scalable structure for adding new courses, batches, and branches',
                ],
                'faqs' => [
                    ['question' => 'Can the LMS support paid courses?', 'answer' => 'Yes. We can add course pricing, coupons, payment gateways, invoices, and access rules for paid programs.'],
                    ['question' => 'Can trainers upload their own material?', 'answer' => 'Yes. Trainer roles can manage lessons, resources, quizzes, assignments, and learner feedback based on permissions.'],
                    ['question' => 'Can learners access courses from mobile?', 'answer' => 'Yes. We design responsive LMS portals and can also plan mobile apps when your audience needs them.'],
                    ['question' => 'Can certificates be generated automatically?', 'answer' => 'Yes. Certificates can be issued after completion, passing marks, attendance thresholds, or admin approval.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'Vue.js', 'MySQL'],
                'industries' => ['Education', 'Corporate Offices', 'Healthcare', 'Startups'],
                'cta_heading' => 'Ready to Launch Your Learning Platform?',
            ],
            [
                'slug' => 'pos-software',
                'title' => 'POS (Point of Sale) Software',
                'sort_order' => 5,
                'short_description' => 'POS software for billing, stock, customers, discounts, GST invoices, and store-level reporting.',
                'seo_title' => 'POS Software Development in Navi Mumbai | Tectignis',
                'seo_description' => 'POS software in Navi Mumbai for retail stores, restaurants, and chains. Manage billing, GST invoices, stock, customers, discounts, and sales reports.',
                'heading' => 'POS Software That Keeps Checkout Fast and Accurate',
                'intro' => 'Give cashiers, owners, and store managers a faster way to bill, track stock, and understand sales. We build POS systems that work for single outlets, growing chains, and retail teams that need reliable day-end control.',
                'bullets' => [
                    'Fast billing with GST-ready invoices',
                    'Stock updates after every sale and return',
                    'Customer, loyalty, and discount management',
                ],
                'sub_services' => [
                    ['title' => 'Counter Billing', 'description' => 'Create quick invoices, returns, exchanges, discounts, and payment splits at checkout.'],
                    ['title' => 'Product and Price Management', 'description' => 'Maintain SKUs, categories, variants, taxes, offers, and outlet-wise pricing.'],
                    ['title' => 'Stock and Purchase Sync', 'description' => 'Update inventory automatically from sales, returns, purchases, and transfers.'],
                    ['title' => 'Customer Loyalty', 'description' => 'Capture customer profiles, purchase history, points, credits, and personalized offers.'],
                    ['title' => 'Multi-Outlet Control', 'description' => 'Manage registers, branches, users, stock, and sales from a central dashboard.'],
                    ['title' => 'Sales and Cash Reports', 'description' => 'Review day closing, payment mode totals, staff sales, tax, and product performance.'],
                ],
                'why_points' => [
                    'Simple billing screens that keep queues moving during peak hours',
                    'Custom tax, discount, and pricing logic for Indian retail operations',
                    'Better stock accuracy through POS and inventory integration',
                    'Management reports for sales, margins, staff, counters, and stores',
                    'Hardware-friendly planning for printers, scanners, cash drawers, and displays',
                ],
                'faqs' => [
                    ['question' => 'Can the POS work for multiple outlets?', 'answer' => 'Yes. We can support branch-wise stock, pricing, billing counters, staff permissions, and consolidated reports.'],
                    ['question' => 'Can it print GST invoices?', 'answer' => 'Yes. Invoice formats, tax rules, receipt printers, and day-end reports can be configured for your billing needs.'],
                    ['question' => 'Can customer loyalty be included?', 'answer' => 'Yes. We can add points, wallet, credits, membership discounts, purchase history, and promotional offers.'],
                    ['question' => 'Can POS connect to inventory?', 'answer' => 'Yes. Every sale, return, and exchange can update stock automatically across your stores or warehouse.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'MySQL', 'Microsoft'],
                'industries' => ['Retail', 'Hospitality', 'E-commerce', 'Manufacturing'],
                'cta_heading' => 'Ready to Speed Up Your Billing Counters?',
            ],
            [
                'slug' => 'real-estate-management',
                'title' => 'Real Estate Management Software',
                'sort_order' => 6,
                'short_description' => 'Real estate software for listings, leads, site visits, bookings, payments, documents, and broker coordination.',
                'seo_title' => 'Real Estate Management Software in Navi Mumbai | Tectignis',
                'seo_description' => 'Real estate management software in Navi Mumbai for builders and agencies. Manage properties, leads, site visits, bookings, payments, documents, and brokers.',
                'heading' => 'Real Estate Management Software for Faster Sales Operations',
                'intro' => 'Bring properties, prospects, brokers, site visits, bookings, and payment follow-ups into one organized system. We develop real estate software that helps teams respond faster and manage every deal with better visibility.',
                'bullets' => [
                    'Property inventory, availability, and pricing in one dashboard',
                    'Lead capture, assignment, follow-up, and site visit tracking',
                    'Booking, payment, document, and broker workflows',
                ],
                'sub_services' => [
                    ['title' => 'Property Inventory', 'description' => 'Maintain projects, towers, units, plots, amenities, pricing, status, and availability.'],
                    ['title' => 'Lead and CRM Flow', 'description' => 'Capture inquiries, assign sales owners, record conversations, and schedule follow-ups.'],
                    ['title' => 'Site Visit Management', 'description' => 'Plan visits, track attendance, update outcomes, and notify sales teams instantly.'],
                    ['title' => 'Booking and Payment Tracking', 'description' => 'Monitor reservations, instalments, receipts, dues, cancellations, and collection reports.'],
                    ['title' => 'Document Repository', 'description' => 'Store KYC, agreements, allotment letters, NOCs, and customer documents securely.'],
                    ['title' => 'Broker and Channel Partner Portal', 'description' => 'Give partners controlled access to inventory, lead updates, and commission status.'],
                ],
                'why_points' => [
                    'Designed for builders, agencies, brokers, and property management teams',
                    'Clear pipeline visibility from inquiry to booking and possession',
                    'Less manual follow-up through reminders, statuses, and activity history',
                    'Document workflows that keep customer files organized and searchable',
                    'Reports for source performance, sales velocity, collections, and inventory status',
                ],
                'faqs' => [
                    ['question' => 'Can the software manage multiple projects?', 'answer' => 'Yes. You can manage projects, towers, units, plots, pricing, availability, and sales activity across locations.'],
                    ['question' => 'Can brokers access the system?', 'answer' => 'Yes. We can build a channel partner portal with controlled access, lead status, inventory visibility, and commission tracking.'],
                    ['question' => 'Can site visits be tracked?', 'answer' => 'Yes. Sales teams can schedule visits, record outcomes, assign next steps, and view conversion reports.'],
                    ['question' => 'Can payment schedules be customized?', 'answer' => 'Yes. Instalments, milestones, receipts, dues, reminders, and collection reports can match your booking process.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'Vue.js', 'MySQL'],
                'industries' => ['Real Estate', 'Corporate Offices', 'Finance & Banking'],
                'cta_heading' => 'Ready to Organize Your Real Estate Pipeline?',
            ],
            [
                'slug' => 'school-management-software',
                'title' => 'School Management Software',
                'sort_order' => 7,
                'short_description' => 'School management software for admissions, attendance, fees, exams, timetables, communication, and reports.',
                'seo_title' => 'School Management Software in Navi Mumbai | Tectignis',
                'seo_description' => 'School management software in Navi Mumbai for schools and institutes. Manage admissions, attendance, fees, exams, timetables, parents, and reports.',
                'heading' => 'School Management Software for Connected Campuses',
                'intro' => 'Simplify administration for students, parents, teachers, and office teams through one school platform. We build systems that reduce paperwork and make daily academic operations easier to manage.',
                'bullets' => [
                    'Admissions, student records, attendance, and fees',
                    'Teacher, parent, and admin portals',
                    'Exam, timetable, communication, and report workflows',
                ],
                'sub_services' => [
                    ['title' => 'Admissions and Student Records', 'description' => 'Manage applications, documents, student profiles, classes, sections, and roll numbers.'],
                    ['title' => 'Attendance Management', 'description' => 'Record student and staff attendance with daily summaries and parent alerts.'],
                    ['title' => 'Fee Collection', 'description' => 'Track fee structures, invoices, online payments, receipts, concessions, and dues.'],
                    ['title' => 'Exam and Result Management', 'description' => 'Create exams, enter marks, calculate grades, and publish report cards.'],
                    ['title' => 'Timetable and Homework', 'description' => 'Share class schedules, assignments, notices, and academic updates with students.'],
                    ['title' => 'Parent Communication', 'description' => 'Send circulars, attendance alerts, payment reminders, and teacher updates through the portal.'],
                ],
                'why_points' => [
                    'Modules mapped to common Indian school administration workflows',
                    'Role-specific access for admin staff, teachers, parents, and students',
                    'Better fee visibility through dues, receipts, concessions, and reminders',
                    'Academic reports for attendance, marks, subjects, classes, and sections',
                    'Phased rollout support for schools moving from paper or spreadsheets',
                ],
                'faqs' => [
                    ['question' => 'Can parents log in to view updates?', 'answer' => 'Yes. Parent access can include attendance, homework, fees, notices, exam results, and teacher communication.'],
                    ['question' => 'Can fee receipts be generated online?', 'answer' => 'Yes. We can add fee invoices, receipts, payment gateway integration, concessions, dues, and reminders.'],
                    ['question' => 'Can report cards match our format?', 'answer' => 'Yes. We can configure subjects, marks, grades, remarks, and printable report card layouts.'],
                    ['question' => 'Can the system handle multiple branches?', 'answer' => 'Yes. Branches, classes, sections, users, fee structures, and reports can be managed centrally or separately.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'MySQL', 'Flutter'],
                'industries' => ['Education', 'Corporate Offices'],
                'cta_heading' => 'Ready to Modernize School Administration?',
            ],
            [
                'slug' => 'visitor-management-software',
                'title' => 'Visitor Management Software',
                'sort_order' => 8,
                'short_description' => 'Visitor management software for digital check-ins, approvals, badges, host alerts, access logs, and security reports.',
                'seo_title' => 'Visitor Management Software in Navi Mumbai | Tectignis',
                'seo_description' => 'Visitor management software in Navi Mumbai for offices, factories, schools, and hospitals. Manage check-ins, badges, approvals, host alerts, and security logs.',
                'heading' => 'Visitor Management Software for Secure Front Desks',
                'intro' => 'Move visitor entry from paper registers to a cleaner, faster, and more secure workflow. We build visitor management systems that help reception, security, and hosts manage every arrival with confidence.',
                'bullets' => [
                    'Digital check-in with photo, ID, and visit purpose',
                    'Host notifications, approvals, and badge printing',
                    'Searchable visit history and security reports',
                ],
                'sub_services' => [
                    ['title' => 'Digital Visitor Check-In', 'description' => 'Capture visitor details, photos, IDs, purpose, belongings, and consent at reception.'],
                    ['title' => 'Pre-Registration', 'description' => 'Let employees invite guests and issue QR-based entry passes before arrival.'],
                    ['title' => 'Host Alerts and Approvals', 'description' => 'Notify hosts instantly and record approval before visitors enter restricted areas.'],
                    ['title' => 'Badge and Gate Pass Printing', 'description' => 'Print visitor badges with photo, host, validity, and access instructions.'],
                    ['title' => 'Security Watchlists', 'description' => 'Flag blocked visitors, repeated entries, expired passes, and suspicious patterns.'],
                    ['title' => 'Visit Analytics', 'description' => 'Review footfall, peak hours, host activity, visit duration, and location-wise entries.'],
                ],
                'why_points' => [
                    'Cleaner visitor records than manual registers and loose paperwork',
                    'Better security through approval logs, badges, watchlists, and exit tracking',
                    'Flexible flows for offices, factories, schools, hospitals, and gated premises',
                    'Integration options for access control, QR scanners, and notifications',
                    'Reports that help admin and security teams audit every entry',
                ],
                'faqs' => [
                    ['question' => 'Can visitors pre-register before arriving?', 'answer' => 'Yes. Hosts can invite visitors, share QR passes, and speed up entry at reception or gate.'],
                    ['question' => 'Can the system print badges?', 'answer' => 'Yes. We can support badge or gate pass printing with visitor photo, host name, time, and validity.'],
                    ['question' => 'Can it work at a factory gate?', 'answer' => 'Yes. We can include vehicle details, material entries, security approvals, belongings, and exit checks for factory use.'],
                    ['question' => 'Can reports be exported?', 'answer' => 'Yes. Visitor logs and reports can be filtered and exported for admin, compliance, or security review.'],
                ],
                'tech_stacks' => ['Laravel', 'PHP', 'React', 'MySQL', 'Flutter'],
                'industries' => ['Corporate Offices', 'Manufacturing', 'Healthcare', 'Education', 'Hospitality'],
                'cta_heading' => 'Ready to Secure Your Visitor Entry Process?',
            ],
        ];
    }
}
