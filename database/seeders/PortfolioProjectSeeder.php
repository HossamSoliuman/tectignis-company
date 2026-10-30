<?php

namespace Database\Seeders;

use App\Models\CaseStudy;
use App\Models\CaseStudyCategory;
use App\Models\Industry;
use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * The live and in-progress projects hosted on the Tectignis servers, with
 * their story, modules, stack and screenshots. Projects are only created
 * when their slug is missing, so admin edits are never overwritten.
 */
class PortfolioProjectSeeder extends Seeder
{
    /**
     * Industry and service for the original three projects, filled only
     * where an admin has not set them.
     *
     * @var array<string, array{industry: string, service: string}>
     */
    private const EXISTING = [
        'perfect-packaging-solution' => ['industry' => 'manufacturing', 'service' => 'website-design-development'],
        'shree-vakratunda-saree' => ['industry' => 'retail', 'service' => 'ecommerce-development'],
        'mangosteen-homestays' => ['industry' => 'hospitality', 'service' => 'website-design-development'],
    ];

    public function run(): void
    {
        foreach ($this->projects() as $index => $project) {
            if (CaseStudy::where('slug', $project['slug'])->exists()) {
                continue;
            }

            $slug = $project['slug'];
            $gallery = collect($project['gallery'])
                ->map(fn (string $alt, int $index): array => ['image' => "case-studies/{$slug}-".($index + 1).'.webp', 'alt' => $alt])
                ->all();

            CaseStudy::create([
                ...collect($project)->except(['category', 'industry', 'service', 'gallery'])->all(),
                'status' => 'published',
                'case_study_category_id' => CaseStudyCategory::where('name', $project['category'])->value('id'),
                'industry_id' => $project['industry'] ? Industry::where('slug', $project['industry'])->value('id') : null,
                'service_id' => Service::where('slug', $project['service'])->value('id'),
                'image' => "case-studies/{$slug}.webp",
                'gallery' => $gallery,
                'sort_order' => 10 + $index,
            ]);
        }

        foreach (self::EXISTING as $slug => $links) {
            $caseStudy = CaseStudy::firstWhere('slug', $slug);

            if (! $caseStudy) {
                continue;
            }

            $caseStudy->forceFill([
                'industry_id' => $caseStudy->industry_id ?? Industry::where('slug', $links['industry'])->value('id'),
                'service_id' => $caseStudy->service_id ?? Service::where('slug', $links['service'])->value('id'),
            ])->save();
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function projects(): array
    {
        return [
            [
                'slug' => 'tenderset-government-tender-platform',
                'title' => 'TenderSet Government Tender Platform',
                'category' => 'SaaS',
                'client_name' => 'Tectignis (own product)',
                'industry' => 'government-public-sector',
                'service' => 'saas-product-development',
                'location' => 'India',
                'project_status' => 'completed',
                'project_date' => '2026-10-01',
                'is_featured' => true,
                'short_description' => 'TenderSet brings government tenders from GeM, CPPP and state e-procurement portals into one search, matches them to what each company sells, and tracks every bid from shortlist to submission. Tectignis built it and runs it as its own SaaS product.',
                'challenge' => "Companies that bid on government work have to check GeM, CPPP and many state portals one by one every day, copy details into spreadsheets and chase documents, OEM letters and approvals over email.\n\nTectignis had the same problem with its own bids. Relevant tenders were easy to miss, and nobody could see where each bid stood.",
                'solution' => "We built TenderSet as a multi-tenant SaaS platform. It reads the public pages of the procurement portals several times a day, merges a tender published on two portals into one record and keeps every source link.\n\nEach tender gets a relevance score with the reasons shown, and saved searches send alerts by email and WhatsApp. Teams shortlist tenders, run bid or no-bid approvals, assign tasks and keep checklists, documents and OEM letters with each bid. An AI step reads the tender pack and lists the required documents, eligibility criteria and dates, each quoting its document and page.",
                'modules' => [
                    'Tender catalogue across GeM, CPPP and state portals',
                    'Search inside the text of tender documents',
                    'Relevance score from 0 to 100 with the reasons shown',
                    'Saved searches with email and WhatsApp alerts',
                    'Bid pipeline as a board, list and calendar',
                    'Bid or no-bid approvals and team tasks',
                    'Company document vault with expiry reminders',
                    'BOQ import and bid checklists',
                    'AI eligibility checks and document extraction',
                    'OEM MAF letters in DOCX and PDF',
                    'Reports, win and loss funnel and Excel exports',
                    'Roles, activity log and white-label branding',
                ],
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'Tailwind CSS', 'Vite', 'AI document extraction'],
                'features' => [
                    'Tenders from GeM, CPPP and state portals',
                    'Email and WhatsApp tender alerts',
                    'Bid pipeline with approvals and AI checks',
                ],
                'image_alt' => 'TenderSet home page with tender search and the latest government tenders',
                'gallery' => [
                    'TenderSet tenders grouped by sector, state and portal',
                    'TenderSet features page for tender discovery and alerts',
                    'TenderSet live tenders list with filters',
                ],
                'seo_title' => 'TenderSet: Government Tender SaaS Case Study | Tectignis',
                'seo_description' => 'How Tectignis built TenderSet, a SaaS platform that gathers Indian government tenders into one search with alerts, a bid pipeline and AI eligibility checks.',
            ],
            [
                'slug' => 'constituency-management-system',
                'title' => 'Constituency Management System',
                'category' => 'Software',
                'client_name' => 'Office of an elected representative',
                'industry' => 'government-public-sector',
                'service' => 'custom-software-development',
                'location' => 'Maharashtra, India',
                'project_status' => 'completed',
                'project_date' => '2026-07-01',
                'is_featured' => true,
                'short_description' => "A web and mobile system for an elected representative's office to manage citizen complaints, appointments, voter records and outreach, with a desktop version for office staff and a mobile version for field teams.",
                'challenge' => "A representative's office handles a steady flow of citizen complaints, meeting requests and events across the constituency.\n\nThis work lived in registers, phone notes and WhatsApp groups, so complaints were hard to follow up, appointments clashed and the representative had no single view of what needed attention.",
                'solution' => "We built one system with two front ends. Office staff use the desktop version to log and assign complaints, manage the appointment diary, keep the voter database and send messages.\n\nField staff and the representative use the mobile version to see the day's schedule, pending complaints and birthdays on the go. A dashboard shows complaints by status, messages sent over recent weeks and the day's schedule at a glance.",
                'modules' => [
                    'Dashboard with complaint status and outreach charts',
                    'Complaint registration, assignment and tracking',
                    'Appointment diary and daily schedule',
                    'Voter database',
                    'Birthday reminders and greetings',
                    'Team management',
                    'WhatsApp and SMS communication log',
                    'Reports',
                    'Mobile version for field staff',
                ],
                'technologies' => ['PHP', 'MySQL', 'JavaScript', 'HTML5', 'CSS3'],
                'features' => [
                    'Complaint tracking from entry to resolution',
                    'Appointments, schedule and birthday reminders',
                    'Desktop version for staff, mobile for the field',
                ],
                'image_alt' => 'Constituency Management System dashboard with appointments, complaints and outreach charts',
                'gallery' => [
                    'Constituency Management System platform choice between mobile and desktop',
                    'Constituency Management System mobile version with the day\'s schedule',
                ],
                'seo_title' => 'Constituency Management System Case Study | Tectignis',
                'seo_description' => "How Tectignis built a web and mobile system for a representative's office to manage complaints, appointments, voter records and outreach.",
            ],
            [
                'slug' => 'ganeshotsav-toll-free-pass-portal',
                'title' => 'Ganeshotsav Toll-Free Pass Portal',
                'category' => 'Software',
                'client_name' => 'Regional Transport Office, Panvel',
                'industry' => 'government-public-sector',
                'service' => 'web-application-development',
                'location' => 'Panvel, Maharashtra',
                'project_status' => 'completed',
                'project_date' => '2025-07-01',
                'is_featured' => true,
                'short_description' => 'An online application portal for the Konkan Ganeshotsav toll-free pass. Vehicle owners apply in Marathi or English, upload their vehicle documents and download their pass letter, and officers review every application from an admin panel.',
                'challenge' => "Every year, thousands of devotees drive to the Konkan for Ganeshotsav, and their vehicles can travel toll free with a pass from the transport office.\n\nCollecting these applications on paper meant long queues, missing documents and passes that were hard to verify.",
                'solution' => "We built a simple bilingual portal. The applicant enters the vehicle number, owner name, mobile number, route and travel dates, and uploads the RC book, insurance and PUC certificate.\n\nEach application gets a code, and the applicant can later download the pass letter using the vehicle number or that code. Officers sign in to an admin panel to review applications and documents, and the portal is reopened for each year's festival.",
                'modules' => [
                    'Bilingual Marathi and English application form',
                    'Vehicle, owner, route and travel date details',
                    'RC book, insurance and PUC upload in JPEG or PDF',
                    'Application code for every request',
                    'Pass letter download by vehicle number or application code',
                    'Admin panel to review applications and documents',
                ],
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'Tailwind CSS'],
                'features' => [
                    'Bilingual Marathi and English application',
                    'Online upload of RC, insurance and PUC',
                    'Pass letter download by application code',
                ],
                'image_alt' => 'Ganeshotsav toll-free pass application form in Marathi and English',
                'gallery' => [
                    'Document upload section of the toll-free pass application',
                    'Toll-free pass application form on a mobile phone',
                ],
                'seo_title' => 'Ganeshotsav Toll-Free Pass Portal Case Study | Tectignis',
                'seo_description' => 'How Tectignis built a bilingual online portal for Konkan Ganeshotsav toll-free pass applications, with document upload and pass letter download.',
            ],
            [
                'slug' => 'alignocare-dental-clinic-website',
                'title' => 'AlignoCare Dental Clinic Website',
                'category' => 'Website',
                'client_name' => 'AlignoCare Dental Clinic',
                'industry' => 'healthcare',
                'service' => 'website-design-development',
                'location' => 'Airoli, Navi Mumbai',
                'project_status' => 'completed',
                'project_date' => '2026-05-01',
                'is_featured' => true,
                'short_description' => "A website for AlignoCare, a dental and orthodontic clinic in Airoli, Navi Mumbai, with a page for every treatment, the doctor's profile and an appointment form where patients pick a treatment and a time slot.",
                'challenge' => 'AlignoCare needed patients searching for dental care in Navi Mumbai to find the clinic, understand each treatment and book a visit without having to call during clinic hours.',
                'solution' => "We built a fast, mobile-friendly website with a dedicated, search-optimised page for each of more than 20 treatments, from root canals and implants to braces and clear aligners.\n\nAn appointment form lets patients choose the treatment and a time slot, and WhatsApp and call buttons give them a quicker way to reach the clinic. The site also covers the clinic's story, Dr. Siddhesh Karanjekar's profile, a gallery, a blog and careers.",
                'modules' => [
                    'More than 20 treatment pages written for local search',
                    'Appointment booking with treatment and time slot',
                    'Clinic story and doctor profile',
                    'Gallery',
                    'Blog',
                    'Careers page',
                    'Contact page with clinic hours',
                    'WhatsApp and click-to-call buttons',
                ],
                'technologies' => ['PHP', 'Bootstrap', 'jQuery', 'HTML5', 'CSS3'],
                'features' => [
                    'A page for every dental treatment',
                    'Online appointment booking',
                    'Built for local search in Navi Mumbai',
                ],
                'image_alt' => 'AlignoCare dental clinic home page with clear aligner treatment',
                'gallery' => [
                    'AlignoCare clinic story and highlights',
                    'AlignoCare doctor profile section',
                    'AlignoCare braces and orthodontic treatment page',
                ],
                'seo_title' => 'AlignoCare Dental Clinic Website Case Study | Tectignis',
                'seo_description' => 'How Tectignis built a dental clinic website in Navi Mumbai with treatment pages, a doctor profile and online appointment booking.',
            ],
            [
                'slug' => 'back-to-campus-aura-agreement-microsite',
                'title' => 'Back To Campus Aura Agreement Microsite',
                'category' => 'Website',
                'client_name' => 'Flipkart',
                'industry' => 'retail',
                'service' => 'website-design-development',
                'location' => 'India',
                'project_status' => 'completed',
                'project_date' => '2026-06-01',
                'is_featured' => true,
                'short_description' => "A campaign microsite for Flipkart's Back To Campus sale. Students and parents pick the habits the student promises to keep and the gadget they want, and the site creates a personalised, shareable Aura Agreement that unlocks an extra discount code.",
                'challenge' => 'The Back To Campus campaign needed a playful way to get students and parents talking about gadget purchases and to bring them to the sale with a coupon. It had to work smoothly on phones, handle personal names with clear consent and hold up under the traffic of a national campaign.',
                'solution' => "We built a mobile-first microsite with a short guided flow. The user chooses one to three conditions, such as attendance above 95% or reading ten books a year, then picks a laptop, tablet, headphones or a smartwatch.\n\nAfter a consent step, the site creates a personalised agreement with the parent's and student's names, reveals the coupon code with one-tap copy and links to the Back To Campus store.",
                'modules' => [
                    'Guided condition picker with one to three conditions',
                    'Gadget choice: laptop, tablet, headphones or smartwatch',
                    'Consent step for personal data',
                    'Personalised agreement generation',
                    'Coupon reveal with one-tap copy',
                    'Link through to the sale store',
                    'Social sharing',
                ],
                'technologies' => ['PHP', 'JavaScript', 'HTML5', 'CSS3'],
                'features' => [
                    'Personalised agreement in a few taps',
                    'Coupon reveal with one-tap copy',
                    'Mobile-first, shareable campaign flow',
                ],
                'image_alt' => 'Back To Campus Aura Agreement microsite asking the visitor to choose parent or student',
                'gallery' => [
                    'Aura Agreement conditions screen on a mobile phone',
                ],
                'seo_title' => 'Flipkart Back To Campus Microsite Case Study | Tectignis',
                'seo_description' => "How Tectignis built the Aura Agreement microsite for Flipkart's Back To Campus sale, with a personalised agreement and coupon reveal.",
            ],
            [
                'slug' => 'ilccpl-liquid-bulk-chartering-website',
                'title' => 'ILCCPL Liquid Bulk Chartering Website',
                'category' => 'Website',
                'client_name' => 'Intracontinent Logichem Corporation Pvt Ltd',
                'industry' => 'logistics',
                'service' => 'website-design-development',
                'location' => 'Vashi, Navi Mumbai',
                'project_status' => 'completed',
                'project_date' => '2022-10-01',
                'is_featured' => true,
                'short_description' => 'A corporate website for ILCCPL, a liquid bulk chartering company serving the chemical, petrochemical, oil and gas industries, presenting its vessel chartering, shore reception and port agency services to tanker owners and charterers worldwide.',
                'challenge' => 'ILCCPL works with tanker owners and charterers across many regions, and needed a credible online presence that explains a specialised business clearly and makes it easy for international clients to get in touch.',
                'solution' => "We designed a clean corporate site led by maritime imagery, with sections on the company, why clients choose it, its services, its team and a blog.\n\nEach service, vessel chartering, shore reception facilities and port agency, has its own page, and the global reach section lists the shipping regions the company covers.",
                'modules' => [
                    'Company overview',
                    'Why choose us',
                    'Vessel chartering',
                    'Shore reception facilities',
                    'Port agency',
                    'Team',
                    'Blog',
                    'Contact',
                ],
                'technologies' => ['PHP', 'Bootstrap', 'jQuery', 'HTML5', 'CSS3'],
                'features' => [
                    'Pages for chartering, shore reception and port agency',
                    'Global reach across major shipping regions',
                    'Clean corporate design for international clients',
                ],
                'image_alt' => 'ILCCPL home page with a chemical tanker at sea',
                'gallery' => [
                    'ILCCPL global reach and personalised service section',
                    'ILCCPL about page with specialisations and vision',
                ],
                'seo_title' => 'ILCCPL Chartering Company Website Case Study | Tectignis',
                'seo_description' => 'How Tectignis built a corporate website for ILCCPL, a liquid bulk chartering company serving the chemical, oil and gas industries.',
            ],
            [
                'slug' => 'tectignis-qr-code-generator',
                'title' => 'Tectignis QR Code Generator',
                'category' => 'SaaS',
                'client_name' => 'Tectignis (own product)',
                'industry' => 'startups-smes-growing-businesses',
                'service' => 'saas-product-development',
                'location' => 'India',
                'project_status' => 'ongoing',
                'project_date' => '2026-10-01',
                'is_featured' => false,
                'short_description' => 'A QR code generator with more than 20 QR types, custom colours, logos and frames, and downloads in PNG, SVG and PDF. Free to use without sign-up, with a Pro plan for dynamic codes, hosted pages and scan statistics.',
                'challenge' => 'Small businesses use QR codes for menus, WiFi, reviews and contact cards, but most free generators add watermarks, expire codes or hide basic features behind a sign-up.',
                'solution' => "We are building Tectignis QR as a SaaS product. Anyone can create a static QR code without an account, style it and download it.\n\nSigned-in users get dynamic codes that can be changed after printing, hosted mobile pages such as menus, PDFs, business cards and multi-link pages, and scan statistics, with a monthly or yearly Pro plan.",
                'modules' => [
                    'More than 20 QR types, including URL, WiFi, vCard, WhatsApp and location',
                    'Hosted pages for menus, PDFs, images, video, coupons and Google reviews',
                    'Designer with templates, colours, shapes, logo and frames',
                    'PNG, SVG and PDF downloads',
                    'Dynamic QR codes that can be changed after printing',
                    'Scan statistics and CSV export',
                    'Free and Pro plans with monthly or yearly billing',
                    'A landing page for each QR type',
                ],
                'technologies' => ['Laravel', 'Livewire', 'Alpine.js', 'Tailwind CSS', 'Vite', 'MySQL'],
                'features' => [
                    'More than 20 QR types and hosted pages',
                    'Custom colours, logo and frames',
                    'Free static codes, Pro dynamic codes',
                ],
                'image_alt' => 'Tectignis QR generator with QR types and a live preview',
                'gallery' => [
                    'Tectignis QR design step with templates and colours',
                    'Tectignis QR pricing with Free and Pro plans',
                    'Tectignis QR WiFi QR code page',
                ],
                'seo_title' => 'Tectignis QR Code Generator Case Study | Tectignis',
                'seo_description' => 'How Tectignis is building a QR code SaaS with more than 20 QR types, custom designs, dynamic codes and scan statistics.',
            ],
            [
                'slug' => 'velo-corporate-gifting-catalogue',
                'title' => 'Velo Corporate Gifting Catalogue',
                'category' => 'E-commerce',
                'client_name' => 'Velo Printing & Gifting',
                'industry' => 'retail',
                'service' => 'ecommerce-development',
                'location' => 'Vashi, Navi Mumbai',
                'project_status' => 'ongoing',
                'project_date' => '2026-09-01',
                'is_featured' => false,
                'short_description' => 'An online catalogue for Velo, a corporate gifting and printing company in Navi Mumbai, where businesses browse branded gifts by category or budget and request a quote for bulk orders.',
                'challenge' => 'Velo sells hundreds of customisable products to companies, from welcome kits to diaries and power banks, with prices that depend on quantity and branding. A PDF catalogue and phone calls made it slow for buyers to shortlist products and ask for a quote.',
                'solution' => "We are building a fast product catalogue with categories, price range filters and search by product name or SKU.\n\nEach product page shows the price per unit, the minimum order quantity and a gallery, with Enquire Now and WhatsApp buttons. Buyers can also browse the e-catalogue online or ask for a custom product.",
                'modules' => [
                    'Product catalogue with more than 30 categories',
                    'Search by product name or SKU',
                    'Filters by price range',
                    'Product pages with unit price and minimum order quantity',
                    'Quote requests and WhatsApp enquiries',
                    'Online e-catalogue',
                    'Featured and new products',
                ],
                'technologies' => ['Laravel', 'Livewire', 'Alpine.js', 'Tailwind CSS', 'Vite'],
                'features' => [
                    'Gifts by category and budget',
                    'Search by product name or SKU',
                    'Quote and WhatsApp enquiries',
                ],
                'image_alt' => 'Velo corporate gifting home page with a branded diary',
                'gallery' => [
                    'Velo popular categories and featured products',
                    'Velo list of all gift categories',
                    'Velo executive gift set product page',
                ],
                'seo_title' => 'Velo Corporate Gifting Catalogue Case Study | Tectignis',
                'seo_description' => 'How Tectignis is building an online corporate gifting catalogue with category and budget filters, SKU search and quote requests.',
            ],
            [
                'slug' => 'waterfirst-engineering-consultancy-website',
                'title' => 'WaterFirst Engineering Consultancy Website',
                'category' => 'Website',
                'client_name' => 'WaterFirst',
                'industry' => 'real-estate',
                'service' => 'website-design-development',
                'location' => 'India and United States',
                'project_status' => 'ongoing',
                'project_date' => '2026-08-01',
                'is_featured' => false,
                'short_description' => 'A website for WaterFirst, an engineering and infrastructure consultancy serving clients in the Americas, Europe, Asia, the Middle East and Oceania, presenting its disciplines, regions, case studies and business models.',
                'challenge' => 'WaterFirst delivers multidisciplinary engineering for clients around the world, and needed a site that shows the breadth of its expertise and its regional presence while guiding each visitor to the right team.',
                'solution' => "We are building a content-rich site with a page for each engineering discipline, from water and wastewater to transport, structural and energy work, a page for each region, filterable case studies, insights and careers.\n\nStart a project buttons and regional contact details lead each enquiry to the right team.",
                'modules' => [
                    'Expertise pages for each engineering discipline',
                    'Regional pages for the Americas, Europe, Asia, Middle East and Oceania',
                    'Case studies filtered by category and sector',
                    'Business models',
                    'Insights',
                    'Careers',
                    'Start a project enquiries',
                ],
                'technologies' => ['Laravel', 'Alpine.js', 'Tailwind CSS'],
                'features' => [
                    'A page for each engineering discipline',
                    'Regional pages across five continents',
                    'Filterable case studies',
                ],
                'image_alt' => 'WaterFirst engineering consultancy home page',
                'gallery' => [
                    'WaterFirst company overview and strengths',
                    'WaterFirst case studies with category and sector filters',
                    'WaterFirst business models page',
                ],
                'seo_title' => 'WaterFirst Consultancy Website Case Study | Tectignis',
                'seo_description' => 'How Tectignis is building a global engineering consultancy website with discipline and region pages, filterable case studies and enquiries.',
            ],
            [
                'slug' => 'diva-junction-big-billion-days-microsite',
                'title' => 'Diva Junction Big Billion Days Microsite',
                'category' => 'Website',
                'client_name' => 'Flipkart',
                'industry' => 'retail',
                'service' => 'website-design-development',
                'location' => 'Diva, Thane, Maharashtra',
                'project_status' => 'completed',
                'project_date' => '2026-10-01',
                'is_featured' => false,
                'short_description' => "A location-checked campaign microsite for Flipkart's Big Billion Days fashion and beauty deals. Visitors at Diva Junction station confirm their location before the experience opens.",
                'challenge' => 'The Diva Junction activation wanted to reward people who were actually at the station, so the experience could not simply be opened from anywhere.',
                'solution' => 'We built a mobile-first microsite that asks for the visitor\'s location only to confirm they are at Diva Junction, using a geofence around the station. Once confirmed, the visitor enters the campaign with its fashion and beauty deals, in an illustrated design that matches the Big Billion Days look.',
                'modules' => [
                    'Location check with a geofence around Diva Junction',
                    'Clear message on how the location is used',
                    'Illustrated, mobile-first welcome flow',
                    'Big Billion Days fashion and beauty deals',
                ],
                'technologies' => ['PHP', 'JavaScript', 'Geolocation API', 'HTML5', 'CSS3'],
                'features' => [
                    'Geofenced entry at Diva Junction station',
                    'Mobile-first illustrated design',
                    'Big Billion Days fashion and beauty deals',
                ],
                'image_alt' => 'Diva Junction Big Billion Days microsite welcome screen',
                'gallery' => [
                    'Diva Junction microsite on a mobile phone',
                ],
                'seo_title' => 'Diva Junction Campaign Microsite Case Study | Tectignis',
                'seo_description' => "How Tectignis built a geofenced campaign microsite for Flipkart's Big Billion Days at Diva Junction station.",
            ],
            [
                'slug' => 'teachers-open-comedy-festival-microsite',
                'title' => "Teacher's Open Comedy Festival Microsite",
                'category' => 'Website',
                'client_name' => "Teacher's Open Comedy Festival",
                'industry' => null,
                'service' => 'website-design-development',
                'location' => 'India',
                'project_status' => 'completed',
                'project_date' => '2026-10-01',
                'is_featured' => false,
                'short_description' => "A mobile-first site for the Teacher's Open Comedy Festival. Visitors scan a QR code, pass an age check and send in their own joke, ready to be seen live at the festival.",
                'challenge' => 'The festival wanted the audience to take part, not just watch. People needed to send in jokes from their phones in seconds, and everyone taking part had to be 25 or older.',
                'solution' => 'We built a QR-driven, mobile-first site. A visitor on a computer sees a QR code to continue on the phone. An age gate with date of birth checks that the visitor is 25 or older before the joke form opens, and submitted jokes are collected so they can be shown live at the festival.',
                'modules' => [
                    'QR handoff from computer to phone',
                    'Age gate with date of birth check',
                    'Joke submission form',
                    'Submissions collected for the live show',
                ],
                'technologies' => ['PHP', 'JavaScript', 'HTML5', 'CSS3'],
                'features' => [
                    'QR handoff from computer to phone',
                    'Age gate with date of birth check',
                    'Quick joke submission on mobile',
                ],
                'image_alt' => "Teacher's Open Comedy Festival microsite with the Got a Joke QR code",
                'gallery' => [
                    "Teacher's Open Comedy Festival age check on a mobile phone",
                ],
                'seo_title' => "Teacher's Open Comedy Festival Microsite | Tectignis",
                'seo_description' => "How Tectignis built a QR-driven, mobile-first joke submission site with an age gate for the Teacher's Open Comedy Festival.",
            ],
            [
                'slug' => 'society-management-saas-platform',
                'title' => 'Society Management SaaS Platform',
                'category' => 'SaaS',
                'client_name' => null,
                'industry' => 'real-estate',
                'service' => 'saas-product-development',
                'location' => 'India',
                'project_status' => 'ongoing',
                'project_date' => '2026-09-01',
                'is_featured' => false,
                'short_description' => 'A SaaS platform for housing societies that manages several societies from one account, with subscription billing, real-time reports and role-based access for platform and society users.',
                'challenge' => 'Housing societies still run maintenance billing, notices and records on spreadsheets and paper, and companies that look after several societies have no single place to run them.',
                'solution' => 'We are building a multi-tenant SaaS platform where the platform owner manages many societies and each society has its own secure workspace. Subscriptions and billing are handled in the platform, reports update in real time, and roles control what each user can see and do.',
                'modules' => [
                    'Multi-society management',
                    'Subscription and billing management',
                    'Real-time analytics and reports',
                    'Role-based access for platform and society users',
                    'Secure sign-in with password reset',
                ],
                'technologies' => ['Laravel', 'PHP', 'MySQL'],
                'features' => [
                    'Many societies from one platform',
                    'Subscription and billing management',
                    'Role-based access and live reports',
                ],
                'image_alt' => 'Society Management SaaS sign-in page',
                'gallery' => [],
                'seo_title' => 'Society Management SaaS Case Study | Tectignis',
                'seo_description' => 'How Tectignis is building a multi-tenant SaaS platform for housing societies with billing, reports and role-based access.',
            ],
            [
                'slug' => 'shree-sudarshan-water-conditioner-website',
                'title' => 'Shree Sudarshan Water Conditioner Website',
                'category' => 'Website',
                'client_name' => 'Shree Sudarshan Water Conditioner',
                'industry' => 'manufacturing',
                'service' => 'website-design-development',
                'location' => 'Panvel, Navi Mumbai',
                'project_status' => 'completed',
                'project_date' => '2022-09-01',
                'is_featured' => false,
                'short_description' => 'A product website for Shree Sudarshan, a maker of electronic anti-scale water conditioners, explaining how the technology works and where it is used across industry, homes, businesses and agriculture.',
                'challenge' => "Shree Sudarshan's capacitance impulse technology is not easy to grasp, and buyers in very different sectors, from farmers to factories, needed to see how it applies to them.",
                'solution' => 'We built a site that explains the technology step by step, with separate application pages for industrial, residential and commercial, agricultural and other uses, a product gallery, a gallery of client installations and certifications, and a contact page for enquiries.',
                'modules' => [
                    'How it works',
                    'Industrial applications',
                    'Residential and commercial applications',
                    'Agriculture applications',
                    'Product gallery',
                    'Client installations gallery',
                    'Contact and enquiries',
                ],
                'technologies' => ['PHP', 'Bootstrap', 'jQuery', 'HTML5', 'CSS3'],
                'features' => [
                    'Clear explanation of the technology',
                    'Application pages for each sector',
                    'Product and installation galleries',
                ],
                'image_alt' => 'Shree Sudarshan water conditioner home page with product range',
                'gallery' => [
                    'Shree Sudarshan section explaining how the water conditioner works',
                    'Shree Sudarshan industrial applications and product gallery',
                ],
                'seo_title' => 'Shree Sudarshan Water Conditioner Website | Tectignis',
                'seo_description' => 'How Tectignis built a product website for an anti-scale water conditioner maker, with application pages for each sector and galleries.',
            ],
        ];
    }
}
