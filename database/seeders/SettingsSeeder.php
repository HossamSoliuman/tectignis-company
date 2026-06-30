<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // general
            ['key' => 'site_name', 'value' => 'Tectignis IT Solutions', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Top IT Services & Solutions in Navi Mumbai', 'group' => 'general'],
            ['key' => 'site_phone', 'value' => '+91 9987705688', 'group' => 'general'],
            ['key' => 'site_email', 'value' => 'info@tectignis.in', 'group' => 'general'],
            ['key' => 'site_address', 'value' => 'Aashiyana CHS Shop no 05, Sector 11, Plot no 29, Kamothe, Navi Mumbai - 410206', 'group' => 'general'],
            ['key' => 'business_hours', 'value' => 'Mon – Sat: 9:30 AM – 6:30 PM', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => 'Tectignis-IT-solution-logo-white.webp', 'group' => 'general'],
            ['key' => 'site_logo_dark', 'value' => 'Tectignis-IT-solution-logo.webp', 'group' => 'general'],
            ['key' => 'site_favicon', 'value' => null, 'group' => 'general'],

            // integrations
            ['key' => 'site_ga_id', 'value' => 'G-MSJ8VY2D8Q', 'group' => 'integrations'],
            ['key' => 'site_gtm_id', 'value' => 'GTM-TFWTXHQ4', 'group' => 'integrations'],
            ['key' => 'google_search_console_verification', 'value' => null, 'group' => 'integrations'],
            ['key' => 'meta_pixel_id', 'value' => null, 'group' => 'integrations'],
            ['key' => 'recaptcha_site_key', 'value' => null, 'group' => 'integrations'],
            ['key' => 'recaptcha_secret_key', 'value' => null, 'group' => 'integrations'],

            // smtp
            ['key' => 'smtp_host', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_port', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_username', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_password', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_encryption', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_from_address', 'value' => null, 'group' => 'smtp'],
            ['key' => 'smtp_from_name', 'value' => null, 'group' => 'smtp'],

            // mail — per-form notification recipients
            ['key' => 'mail_to_default', 'value' => null, 'group' => 'mail'],
            ['key' => 'mail_to_contact', 'value' => null, 'group' => 'mail'],
            ['key' => 'mail_to_consultation', 'value' => null, 'group' => 'mail'],
            ['key' => 'mail_to_career', 'value' => null, 'group' => 'mail'],
            ['key' => 'mail_to_newsletter', 'value' => null, 'group' => 'mail'],

            // social
            ['key' => 'social_facebook', 'value' => 'https://www.facebook.com/tectignisofficial/', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://www.instagram.com/tectignisofficial/', 'group' => 'social'],
            ['key' => 'social_linkedin', 'value' => 'https://in.linkedin.com/company/tectignis', 'group' => 'social'],
            ['key' => 'social_twitter', 'value' => 'https://x.com/ItTectignis', 'group' => 'social'],
            ['key' => 'social_whatsapp', 'value' => '+919987705688', 'group' => 'social'],

            // legal
            ['key' => 'legal_privacy-policy', 'group' => 'legal', 'value' => '<h1>Privacy Policy</h1>
<p class="legal-last-updated">Last updated: June 2025</p>

<p>Tectignis IT Solutions Pvt Ltd ("Tectignis", "we", "us", or "our") is committed to protecting your personal information. This Privacy Policy explains what data we collect, how we use it, and your rights regarding that data when you use our website at <a href="https://tectignis.in">tectignis.in</a> or engage our services.</p>

<h2>1. Information We Collect</h2>
<p>We collect information that you provide directly to us and information collected automatically when you use our website.</p>
<h3>Information You Provide</h3>
<ul>
  <li><strong>Contact &amp; enquiry forms</strong> — name, email address, phone number, company name, and the message you send us.</li>
  <li><strong>Consultation requests</strong> — service requirements and project details you share.</li>
  <li><strong>Job applications</strong> — CV/résumé, cover letter, and contact details submitted via our Careers page.</li>
  <li><strong>Newsletter sign-ups</strong> — email address.</li>
</ul>
<h3>Information Collected Automatically</h3>
<ul>
  <li>IP address, browser type, operating system, and referring URLs.</li>
  <li>Pages visited, time spent on pages, and click interactions (via Google Analytics and Google Tag Manager).</li>
  <li>Cookies and similar tracking technologies (see Section 6).</li>
</ul>

<h2>2. How We Use Your Information</h2>
<ul>
  <li>To respond to your enquiries and provide the services you have requested.</li>
  <li>To send quotes, proposals, or project updates.</li>
  <li>To process job applications and communicate with candidates.</li>
  <li>To send newsletters and marketing communications (only where you have opted in).</li>
  <li>To improve our website content, user experience, and service offerings.</li>
  <li>To comply with legal obligations.</li>
</ul>

<h2>3. Legal Basis for Processing (GDPR / Indian DPDP Act)</h2>
<ul>
  <li><strong>Consent</strong> — where you have explicitly agreed (e.g., newsletter sign-ups).</li>
  <li><strong>Legitimate interests</strong> — responding to business enquiries and improving our services.</li>
  <li><strong>Contractual necessity</strong> — delivering services you have engaged us for.</li>
  <li><strong>Legal obligation</strong> — complying with applicable laws and regulations.</li>
</ul>

<h2>4. Data Sharing &amp; Disclosure</h2>
<p>We do not sell your personal data. We may share it with:</p>
<ul>
  <li><strong>Service providers</strong> — hosting, email delivery, analytics, and CRM tools operating under data-processing agreements.</li>
  <li><strong>Professional advisors</strong> — lawyers or accountants where legally required.</li>
  <li><strong>Authorities</strong> — government or regulatory bodies when required by law.</li>
</ul>

<h2>5. Data Retention</h2>
<p>We retain personal data only as long as necessary for the purposes set out in this policy or as required by law. Enquiry data is typically retained for up to 3 years; client project data is retained for the duration of the engagement plus 7 years for financial and legal compliance.</p>

<h2>6. Cookies</h2>
<p>Our website uses cookies to provide core functionality, analyse traffic (Google Analytics), and deliver relevant content. You may disable cookies in your browser settings; however, some parts of the site may not function correctly without them.</p>

<h2>7. Third-Party Links</h2>
<p>Our website may contain links to third-party websites. We are not responsible for the privacy practices of those sites and encourage you to review their privacy policies.</p>

<h2>8. Your Rights</h2>
<p>Depending on your location, you may have the right to:</p>
<ul>
  <li>Access the personal data we hold about you.</li>
  <li>Request correction of inaccurate data.</li>
  <li>Request deletion of your data ("right to be forgotten").</li>
  <li>Withdraw consent at any time (where processing is based on consent).</li>
  <li>Object to or restrict certain processing activities.</li>
  <li>Lodge a complaint with a data protection authority.</li>
</ul>
<p>To exercise any of these rights, please contact us at <a href="mailto:info@tectignis.in">info@tectignis.in</a>.</p>

<h2>9. Data Security</h2>
<p>We implement industry-standard technical and organisational measures to protect your personal data against unauthorised access, loss, or disclosure. These include HTTPS encryption, access controls, and regular security reviews.</p>

<h2>10. Children\'s Privacy</h2>
<p>Our website and services are not directed at children under the age of 18. We do not knowingly collect personal data from minors.</p>

<h2>11. Changes to This Policy</h2>
<p>We may update this Privacy Policy from time to time. The "Last updated" date at the top of this page indicates when the most recent revision was made. Continued use of the website after changes constitutes acceptance of the updated policy.</p>

<h2>12. Contact Us</h2>
<p>If you have any questions about this Privacy Policy or how we handle your data, please reach out:</p>
<ul>
  <li><strong>Email:</strong> <a href="mailto:info@tectignis.in">info@tectignis.in</a></li>
  <li><strong>Phone:</strong> +91 9987705688</li>
  <li><strong>Address:</strong> Aashiyana CHS Shop no 05, Sector 11, Plot no 29, Kamothe, Navi Mumbai – 410206, Maharashtra, India</li>
</ul>'],

            ['key' => 'legal_terms-and-conditions', 'group' => 'legal', 'value' => '<h1>Terms &amp; Conditions</h1>
<p class="legal-last-updated">Last updated: June 2025</p>

<p>Please read these Terms &amp; Conditions ("Terms") carefully before using the website at <a href="https://tectignis.in">tectignis.in</a> or engaging any services provided by Tectignis IT Solutions Pvt Ltd ("Tectignis", "we", "us", or "our"). By accessing the website or entering into a service agreement with us, you agree to be bound by these Terms.</p>

<h2>1. Acceptance of Terms</h2>
<p>By using this website or engaging our services, you confirm that you are at least 18 years old and have the authority to enter into these Terms on behalf of yourself or the organisation you represent.</p>

<h2>2. Services</h2>
<p>Tectignis provides custom software development, AI &amp; automation solutions, cloud infrastructure, cybersecurity, CCTV &amp; smart security systems, ERP/CRM development, and related IT consulting services. The specific scope, deliverables, timelines, and fees for any engagement are defined in a separate Statement of Work (SoW) or service agreement signed by both parties.</p>

<h2>3. Use of the Website</h2>
<p>You agree not to:</p>
<ul>
  <li>Use the website for any unlawful purpose or in violation of any applicable regulations.</li>
  <li>Attempt to gain unauthorised access to any part of the website or its underlying infrastructure.</li>
  <li>Transmit any malware, spam, or harmful code.</li>
  <li>Reproduce, distribute, or create derivative works from any content on this website without prior written consent from Tectignis.</li>
</ul>

<h2>4. Intellectual Property</h2>
<p>All content on this website — including text, graphics, logos, images, and software — is the property of Tectignis or its licensors and is protected by applicable intellectual property laws.</p>
<p>For client projects, intellectual property ownership is determined by the terms of the signed service agreement. Unless expressly stated otherwise, custom software developed for a client is assigned to the client upon full payment of all outstanding invoices.</p>

<h2>5. Confidentiality</h2>
<p>Both parties agree to keep confidential any non-public business or technical information shared during an engagement. This obligation survives the termination of any agreement for a period of three (3) years.</p>

<h2>6. Payment Terms</h2>
<p>Payment schedules and methods are specified in individual service agreements. Unless agreed otherwise, invoices are due within 15 days of issue. Tectignis reserves the right to suspend services on accounts with overdue balances.</p>

<h2>7. Limitation of Liability</h2>
<p>To the maximum extent permitted by applicable law, Tectignis shall not be liable for:</p>
<ul>
  <li>Indirect, incidental, or consequential damages arising from use of our website or services.</li>
  <li>Loss of data, revenue, or profits not directly caused by Tectignis\'s gross negligence or wilful misconduct.</li>
  <li>Interruptions to services caused by third-party infrastructure, force majeure events, or factors beyond our reasonable control.</li>
</ul>
<p>Our aggregate liability under any service agreement shall not exceed the total fees paid by the client in the three (3) months preceding the claim.</p>

<h2>8. Warranties &amp; Disclaimers</h2>
<p>The website and any information on it are provided "as is" without warranties of any kind. We do not warrant that the website will be error-free, uninterrupted, or free from viruses. Service-specific warranties are defined in individual service agreements.</p>

<h2>9. Third-Party Links &amp; Integrations</h2>
<p>Our website and solutions may link to or integrate with third-party services. Tectignis is not responsible for the content, availability, or practices of any third-party platforms.</p>

<h2>10. Termination</h2>
<p>Either party may terminate a service agreement in accordance with the notice provisions therein. Tectignis reserves the right to suspend or terminate website access for users who violate these Terms.</p>

<h2>11. Governing Law &amp; Dispute Resolution</h2>
<p>These Terms are governed by the laws of India. Any disputes arising from these Terms or a related service agreement shall be subject to the exclusive jurisdiction of the courts of Navi Mumbai, Maharashtra, India. Parties agree to first attempt to resolve disputes through good-faith negotiation before initiating legal proceedings.</p>

<h2>12. Amendments</h2>
<p>We may update these Terms at any time. The "Last updated" date at the top of this page reflects the most recent revision. Continued use of the website after changes are posted constitutes acceptance of the revised Terms.</p>

<h2>13. Contact Us</h2>
<p>For questions or concerns about these Terms, please contact us:</p>
<ul>
  <li><strong>Email:</strong> <a href="mailto:info@tectignis.in">info@tectignis.in</a></li>
  <li><strong>Phone:</strong> +91 9987705688</li>
  <li><strong>Address:</strong> Aashiyana CHS Shop no 05, Sector 11, Plot no 29, Kamothe, Navi Mumbai – 410206, Maharashtra, India</li>
</ul>'],

            ['key' => 'legal_cookie-policy', 'value' => null, 'group' => 'legal'],

            // home — hero
            ['key' => 'hero_sub_heading', 'value' => 'Serving Clients Across Navi Mumbai, Mumbai, Thane, Pune, India & Worldwide.', 'group' => 'home'],
            ['key' => 'hero_heading_line1', 'value' => 'Transforming Businesses Through', 'group' => 'home'],
            ['key' => 'hero_heading_line2', 'value' => 'Software, AI & Smart Technology Solutions', 'group' => 'home'],
            ['key' => 'hero_info_heading', 'value' => 'Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems.', 'group' => 'home'],
            ['key' => 'hero_btn_primary', 'value' => 'Request Consultation', 'group' => 'home'],
            ['key' => 'hero_btn_secondary', 'value' => 'Get a Quote', 'group' => 'home'],
            ['key' => 'hero_image', 'value' => 'custom-software-solution-pan-india.webp', 'group' => 'home'],
            ['key' => 'what_we_offer_image', 'value' => 'site/IT-Services-in-Nav-Mumbai.webp', 'group' => 'home'],

            // home — section headers
            ['key' => 'stats_sub_heading', 'value' => 'Trusted Technology Partner', 'group' => 'home'],
            ['key' => 'cap_sub_heading', 'value' => 'Our Expertise', 'group' => 'home'],
            ['key' => 'cap_heading', 'value' => 'End-to-End Solutions to Drive Your', 'group' => 'home'],
            ['key' => 'cap_heading_highlight', 'value' => 'Business Forward', 'group' => 'home'],
            ['key' => 'cap_subtitle', 'value' => 'We deliver innovative, reliable, and scalable solutions across industries to help you stay ahead.', 'group' => 'home'],
            ['key' => 'why_badge', 'value' => 'Why Choose Us', 'group' => 'home'],
            ['key' => 'why_heading', 'value' => 'Why Choose', 'group' => 'home'],
            ['key' => 'why_heading_highlight', 'value' => 'Tectignis?', 'group' => 'home'],
            ['key' => 'why_subtitle', 'value' => 'We combine expertise, technology, and a customer-first approach to deliver solutions that drive real results for your business.', 'group' => 'home'],
            ['key' => 'sol_overline', 'value' => '/// Solutions We Deliver ///', 'group' => 'home'],
            ['key' => 'sol_heading', 'value' => 'Business-focused Solutions Built for', 'group' => 'home'],
            ['key' => 'sol_heading_highlight', 'value' => 'Impact', 'group' => 'home'],
            ['key' => 'sol_subtitle', 'value' => 'We deliver end-to-end IT solutions tailored to your business needs, designed to drive efficiency, security, and growth.', 'group' => 'home'],
            ['key' => 'ind_pretitle', 'value' => 'INDUSTRIES WE SERVE', 'group' => 'home'],
            ['key' => 'ind_heading_line1', 'value' => 'Empowering Every Industry', 'group' => 'home'],
            ['key' => 'ind_heading_line2', 'value' => 'with Intelligent Solutions', 'group' => 'home'],
            ['key' => 'ind_subtitle', 'value' => 'We deliver tailored IT solutions to help businesses across industries innovate, optimize operations, and achieve sustainable growth.', 'group' => 'home'],
            ['key' => 'fs_pretitle', 'value' => 'FEATURED SERVICES', 'group' => 'home'],
            ['key' => 'fs_heading', 'value' => 'Our Most In-Demand Services', 'group' => 'home'],
            ['key' => 'fs_subtitle', 'value' => "Powering businesses with innovative, secure, and scalable solutions tailored to meet today's digital challenges.", 'group' => 'home'],
            ['key' => 'tech_sub_heading', 'value' => 'Tools & Platforms', 'group' => 'home'],
            ['key' => 'tech_heading', 'value' => 'Technology', 'group' => 'home'],
            ['key' => 'tech_heading_highlight', 'value' => 'Stack', 'group' => 'home'],
            ['key' => 'cs_badge', 'value' => 'CASE STUDIES', 'group' => 'home'],
            ['key' => 'cs_heading', 'value' => 'Real Stories. Real Impact.', 'group' => 'home'],
            ['key' => 'cs_subtitle', 'value' => 'Explore how our solutions have helped businesses overcome challenges, improve operations, and achieve measurable results.', 'group' => 'home'],
            ['key' => 'gp_pretitle', 'value' => 'Global Presence', 'group' => 'home'],
            ['key' => 'gp_heading', 'value' => 'Serving Clients Worldwide, Delivering Excellence', 'group' => 'home'],
            ['key' => 'gp_heading_highlight', 'value' => 'Everywhere.', 'group' => 'home'],
            ['key' => 'gp_subtitle', 'value' => 'We combine local expertise with a global mindset to deliver innovative IT solutions that help your business grow, scale and succeed.', 'group' => 'home'],
            ['key' => 'gp_india_projects', 'value' => '100+', 'group' => 'home'],
            ['key' => 'gp_countries_served', 'value' => '25+', 'group' => 'home'],
            ['key' => 'gp_map_image', 'value' => null, 'group' => 'home'],
            ['key' => 'res_sub_heading', 'value' => 'Stay Informed', 'group' => 'home'],
            ['key' => 'res_heading', 'value' => 'Resources &', 'group' => 'home'],
            ['key' => 'res_heading_highlight', 'value' => 'Insights', 'group' => 'home'],

            // home — final CTA
            ['key' => 'cta_heading', 'value' => 'Ready to', 'group' => 'home'],
            ['key' => 'cta_heading_highlight', 'value' => 'Transform', 'group' => 'home'],
            ['key' => 'cta_heading_suffix', 'value' => 'Your Business?', 'group' => 'home'],
            ['key' => 'cta_subheading', 'value' => "Let's discuss your software, AI, cloud, cybersecurity, or infrastructure requirements.", 'group' => 'home'],
            ['key' => 'cta_btn_primary', 'value' => 'Request Consultation', 'group' => 'home'],
            ['key' => 'cta_btn_secondary', 'value' => 'Get a Quote', 'group' => 'home'],

            // about — core values
            ['key' => 'about_values_label', 'value' => 'What Drives Us', 'group' => 'about'],
            ['key' => 'about_values_heading', 'value' => 'Our Core <span class="text-color-primary">Values</span>', 'group' => 'about'],
            ['key' => 'about_values_subtitle', 'value' => 'Our values define how we think, collaborate, innovate, and deliver. They guide every project, every client relationship, and every solution we build—ensuring lasting business value through trust, quality, and continuous innovation.', 'group' => 'about'],
            ['key' => 'about_value_1_title', 'value' => 'Integrity', 'group' => 'about'],
            ['key' => 'about_value_1_text', 'value' => "We operate with honesty, transparency, and accountability—earning our clients' trust through ethical business practices and dependable execution.", 'group' => 'about'],
            ['key' => 'about_value_2_title', 'value' => 'Innovation', 'group' => 'about'],
            ['key' => 'about_value_2_text', 'value' => 'We embrace emerging technologies, creative thinking, and continuous learning to develop future-ready digital solutions that drive business transformation.', 'group' => 'about'],
            ['key' => 'about_value_3_title', 'value' => 'Customer Success', 'group' => 'about'],
            ['key' => 'about_value_3_text', 'value' => "Our clients' success is our priority. We listen carefully, understand business challenges, and deliver tailored solutions that create measurable impact.", 'group' => 'about'],
            ['key' => 'about_value_4_title', 'value' => 'Excellence', 'group' => 'about'],
            ['key' => 'about_value_4_text', 'value' => 'We maintain the highest standards in design, development, quality assurance, and support to deliver reliable, secure, and scalable technology solutions.', 'group' => 'about'],
            ['key' => 'about_value_5_title', 'value' => 'Agility', 'group' => 'about'],
            ['key' => 'about_value_5_text', 'value' => 'We respond quickly to changing business needs, adapting with speed, flexibility, and proactive collaboration to keep every project moving forward.', 'group' => 'about'],

            // careers
            ['key' => 'careers_hero_image', 'value' => null, 'group' => 'careers'],

            // seo
            ['key' => 'robots_txt', 'value' => "User-agent: *\nAllow: /", 'group' => 'seo'],

            // seo — home page
            ['key' => 'home_meta_title', 'value' => 'Software, AI & IT Solutions Company | Navi Mumbai, Mumbai, Pune, India | Tectignis', 'group' => 'seo'],
            ['key' => 'home_meta_description', 'value' => 'Tectignis IT Solutions – Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems. Serving Navi Mumbai, Mumbai, Thane, Pune, Maharashtra & Worldwide.', 'group' => 'seo'],
            ['key' => 'home_meta_keywords', 'value' => 'software development company Navi Mumbai, IT solutions Mumbai, AI development India, cloud services Pune, cybersecurity company Thane, ERP development, CRM solutions, CCTV installation Mumbai', 'group' => 'seo'],
            ['key' => 'home_og_title', 'value' => 'Software, AI & IT Solutions Company | Navi Mumbai | Tectignis', 'group' => 'seo'],
            ['key' => 'home_og_description', 'value' => 'Custom Software Development, AI Automation, Cloud Infrastructure, Cybersecurity & Smart Security Systems. Serving Navi Mumbai, Mumbai, Thane, Pune, India & Worldwide.', 'group' => 'seo'],
            ['key' => 'home_og_image', 'value' => null, 'group' => 'seo'],
        ];

        foreach ($settings as $setting) {
            // Legal pages are always refreshed to the bundled copy. Every other
            // setting is only inserted when missing, so admin-managed values are
            // never overwritten while new keys still get seeded on deploy.
            if ($setting['group'] === 'legal') {
                Setting::updateOrCreate(['key' => $setting['key']], $setting);

                continue;
            }

            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
