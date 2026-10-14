<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Positions the two priority services (Custom Software Development and Website
 * Design & Development) for international buyers: adds the problems & solutions,
 * business benefits and related services sections, the missing technical
 * capabilities, and features both on the homepage. Admin-edited copy is
 * preserved: a value is only replaced while it still holds the previous copy,
 * and a section is only added while it is missing.
 */
return new class extends Migration
{
    /**
     * slug => the international content for that service.
     *
     * @var array<string, array<string, mixed>>
     */
    private const SERVICES = [
        'custom-software-development' => [
            'replace' => [
                'features_strip.items.*.label' => ['Lifetime Technical Support', 'Long-Term Technical Support'],
            ],
            'seo_title' => null,
            'seo_description' => null,
            'capabilities' => [
                ['title' => 'Custom Web Applications', 'description' => 'Browser-based business applications built around your processes, users and data.'],
                ['title' => 'Customer & Employee Portals', 'description' => 'Secure self-service portals for clients, partners and staff, with role-based access to documents, requests and reports.'],
                ['title' => 'Workflow Management Systems', 'description' => 'Digital approvals, task routing, notifications and audit trails that replace email chains and paper forms.'],
            ],
            'problems' => [
                'heading' => 'Business Problems We Solve',
                'subtitle' => 'Problems & Solutions',
                'items' => [
                    ['problem' => 'Teams re-key data between spreadsheets, email and disconnected tools.', 'solution' => 'One central platform with shared records, role-based access and automatic hand-offs between teams.'],
                    ['problem' => 'Off-the-shelf software forces you to change the way you work.', 'solution' => 'Software designed around your actual workflows, approval rules and reporting needs.'],
                    ['problem' => 'Managers cannot see what is happening without chasing reports.', 'solution' => 'Live dashboards and scheduled reports built on your own operational data.'],
                    ['problem' => 'Legacy systems are slow, insecure or hard to extend.', 'solution' => 'Planned modernisation onto a maintainable stack, with data migrated without stopping operations.'],
                    ['problem' => 'Business systems do not talk to each other.', 'solution' => 'API integrations with your CRM, accounting, payment, messaging and cloud services.'],
                    ['problem' => 'Manual, repetitive tasks slow the business down.', 'solution' => 'Workflow automation for approvals, notifications, documents and recurring tasks.'],
                ],
            ],
            'benefits' => [
                'heading' => 'Business Benefits of Custom Software',
                'subtitle' => 'What You Gain',
                'items' => [
                    ['title' => 'Lower Operating Costs', 'description' => 'Automating manual steps reduces errors, rework and time spent on administration.'],
                    ['title' => 'Faster Decisions', 'description' => 'Real-time reporting puts accurate numbers in front of managers when they need them.'],
                    ['title' => 'Room to Grow', 'description' => 'Scalable architecture supports more users, data and locations without a rebuild.'],
                    ['title' => 'No Vendor Lock-In', 'description' => 'You receive the source code on final payment, as agreed in your contract.'],
                    ['title' => 'Better Customer Experience', 'description' => 'Portals, faster responses and fewer errors improve how clients work with you.'],
                    ['title' => 'Secure by Design', 'description' => 'Role-based access, encrypted connections and regular backups protect business data.'],
                ],
            ],
            'related' => [
                'website-design-development', 'web-application-development', 'crm-development',
                'erp-development', 'saas-product-development', 'business-process-automation',
            ],
        ],
        'website-design-development' => [
            'replace' => [
                'hero.heading' => ['Professional Website Design & Development Services in Navi Mumbai', 'Professional Website Design & Development for Growing Businesses'],
            ],
            'seo_title' => ['Best Website Development Company in Navi Mumbai | Tectignis', 'Website Design & Development Company | Tectignis IT Solutions'],
            'seo_description' => [
                'Looking for a trusted website development company in Navi Mumbai? We create responsive, SEO-optimized, custom websites, eCommerce stores, and business web applications.',
                'Professional, responsive and SEO-ready business websites, redesigns, landing pages and web portals, designed and built by the Tectignis in-house team.',
            ],
            'capabilities' => [
                ['title' => 'Website Redesign & Modernisation', 'description' => 'Turn an outdated site into a fast, modern, mobile-friendly website while keeping the content and rankings that matter.'],
                ['title' => 'Landing Page Development', 'description' => 'Focused campaign pages with clear calls to action and enquiry forms, built to convert paid and organic traffic.'],
                ['title' => 'Web Portals & Business Platforms', 'description' => 'Member areas, client portals and content platforms that go beyond a brochure website.'],
            ],
            'problems' => [
                'heading' => 'Website Problems We Solve',
                'subtitle' => 'Problems & Solutions',
                'items' => [
                    ['problem' => 'An outdated website does not reflect the quality of your business.', 'solution' => 'A modern, on-brand design that builds trust with first-time visitors.'],
                    ['problem' => 'Visitors leave without getting in touch.', 'solution' => 'Clear page structure, strong calls to action and enquiry forms that capture every lead.'],
                    ['problem' => 'The site is slow and hard to use on mobile.', 'solution' => 'Mobile-first, performance-tuned builds optimised for Core Web Vitals.'],
                    ['problem' => 'Customers cannot find you on Google.', 'solution' => 'Clean, SEO-ready structure, metadata and fast pages that search engines can crawl.'],
                    ['problem' => 'Every content change needs a developer.', 'solution' => 'An easy-to-use CMS so your team can update pages, posts and images.'],
                    ['problem' => 'Nobody looks after security, updates or backups.', 'solution' => 'SSL, secure hosting, backups and ongoing maintenance after launch.'],
                ],
            ],
            'benefits' => [
                'heading' => 'Business Benefits of a Professional Website',
                'subtitle' => 'What You Gain',
                'items' => [
                    ['title' => 'More Qualified Enquiries', 'description' => 'Conversion-focused pages turn visitors into leads and customers.'],
                    ['title' => 'Stronger Credibility', 'description' => 'A professional website shows prospects you are an established, trustworthy business.'],
                    ['title' => 'Works on Every Device', 'description' => 'Responsive layouts give every visitor the same quality experience on phone, tablet or desktop.'],
                    ['title' => 'Better Search Visibility', 'description' => 'Technical SEO foundations help your pages rank and attract organic traffic.'],
                    ['title' => 'Less Day-to-Day Effort', 'description' => 'A CMS and maintenance plan keep the site current without a full-time developer.'],
                    ['title' => 'Ready to Grow', 'description' => 'Scalable builds that can add languages, portals, e-commerce or integrations later.'],
                ],
            ],
            'related' => [
                'custom-software-development', 'web-application-development', 'ecommerce-development', 'mobile-app-development',
            ],
        ],
    ];

    public function up(): void
    {
        foreach (self::SERVICES as $slug => $spec) {
            $service = DB::table('services')->where('slug', $slug)->first();

            if (! $service) {
                continue;
            }

            $content = json_decode((string) $service->content, true) ?: [];

            foreach ($spec['replace'] as $path => [$from, $to]) {
                $this->replaceValue($content, $path, $from, $to);
            }

            $content['problems'] ??= ['enabled' => true] + $spec['problems'];
            $content['benefits'] ??= ['enabled' => true] + $spec['benefits'];
            $content['related'] ??= [
                'enabled' => true,
                'heading' => 'Related Services',
                'subtitle' => 'Explore More',
                'service_ids' => $this->idsInOrder($spec['related']),
            ];
            $content['sub_services']['items'] = $this->appendMissing($content['sub_services']['items'] ?? [], $spec['capabilities']);

            $updates = ['content' => json_encode($content), 'is_featured' => true, 'updated_at' => now()];

            foreach (['seo_title', 'seo_description'] as $column) {
                if ($spec[$column] && $service->{$column} === $spec[$column][0]) {
                    $updates[$column] = $spec[$column][1];
                }
            }

            DB::table('services')->where('id', $service->id)->update($updates);
        }

        $this->flushCaches();
    }

    public function down(): void
    {
        foreach (self::SERVICES as $slug => $spec) {
            $service = DB::table('services')->where('slug', $slug)->first();

            if (! $service) {
                continue;
            }

            $content = json_decode((string) $service->content, true) ?: [];

            foreach ($spec['replace'] as $path => [$from, $to]) {
                $this->replaceValue($content, $path, $to, $from);
            }

            unset($content['problems'], $content['benefits'], $content['related']);

            $added = array_column($spec['capabilities'], 'title');
            $content['sub_services']['items'] = array_values(array_filter(
                $content['sub_services']['items'] ?? [],
                fn (array $item): bool => ! in_array($item['title'] ?? null, $added, true),
            ));

            $updates = ['content' => json_encode($content), 'is_featured' => false, 'updated_at' => now()];

            foreach (['seo_title', 'seo_description'] as $column) {
                if ($spec[$column] && $service->{$column} === $spec[$column][1]) {
                    $updates[$column] = $spec[$column][0];
                }
            }

            DB::table('services')->where('id', $service->id)->update($updates);
        }

        $this->flushCaches();
    }

    /**
     * Replace $from with $to at a dot path; a `*` segment matches every row of a list.
     *
     * @param  array<string, mixed>  $content
     */
    private function replaceValue(array &$content, string $path, string $from, string $to): void
    {
        if (! str_contains($path, '*')) {
            if (data_get($content, $path) === $from) {
                data_set($content, $path, $to);
            }

            return;
        }

        [$listPath, $key] = explode('.*.', $path);

        foreach (data_get($content, $listPath, []) as $index => $row) {
            if (($row[$key] ?? null) === $from) {
                data_set($content, "{$listPath}.{$index}.{$key}", $to);
            }
        }
    }

    /**
     * Append the rows whose title is not already in the list.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @param  array<int, array{title: string, description: string}>  $rows
     * @return array<int, array<string, mixed>>
     */
    private function appendMissing(array $items, array $rows): array
    {
        $titles = array_map('mb_strtolower', array_column($items, 'title'));

        foreach ($rows as $row) {
            if (! in_array(mb_strtolower($row['title']), $titles, true)) {
                $items[] = ['icon' => null] + $row;
            }
        }

        return $items;
    }

    /**
     * Resolve service slugs to ids, keeping the given order and skipping missing ones.
     *
     * @param  list<string>  $slugs
     * @return list<int>
     */
    private function idsInOrder(array $slugs): array
    {
        $ids = DB::table('services')->whereIn('slug', $slugs)->pluck('id', 'slug');

        return array_values(array_map('intval', array_filter(array_map(fn (string $slug) => $ids[$slug] ?? null, $slugs))));
    }

    private function flushCaches(): void
    {
        foreach (['site.nav', 'site.footer_services', 'site.home', 'site.footer'] as $key) {
            Cache::forget($key);
        }
    }
};
