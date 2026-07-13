<?php

use App\Models\Capability;
use App\Models\Service;
use Database\Seeders\IndustrySeeder;
use Database\Seeders\ProductionContentSeeder;
use Database\Seeders\TechStackSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function productionContentCapabilitySlugs(): array
{
    return [
        'business-applications',
        'ai-automation',
        'cloud-security',
        'infrastructure-surveillance',
    ];
}

function productionContentServiceSlugs(): array
{
    return [
        'hospital-management',
        'hrms-development',
        'inventory-management',
        'lms-development',
        'pos-software',
        'real-estate-management',
        'school-management-software',
        'visitor-management-software',
        'ai-chatbot-development',
        'ai-integration',
        'generative-ai-solutions',
        'machine-learning-solutions',
        'business-process-automation',
        'ocr-document-digitization',
        'voice-bot-solutions',
        'whatsapp-automation',
        'aws-consulting',
        'microsoft-azure-consulting',
        'google-cloud-services',
        'cloud-migration-services',
        'cyber-security-consulting',
        'vapt-services',
        'firewall-configuration-management',
        'soc-support',
        'cctv-security-solutions',
        'access-control-systems',
        'networking-solutions',
        'server-management',
        'storage-solutions',
        'structured-cabling',
        'workstation-solutions',
        'amc-services',
    ];
}

function productionContentSorted(array $values): array
{
    sort($values);

    return $values;
}

it('creates the production capabilities and services with expected slugs', function () {
    $this->seed([
        TechStackSeeder::class,
        IndustrySeeder::class,
        ProductionContentSeeder::class,
    ]);

    expect(Capability::query()->count())->toBe(4)
        ->and(Service::query()->count())->toBe(32)
        ->and(productionContentSorted(Capability::query()->pluck('slug')->all()))
        ->toBe(productionContentSorted(productionContentCapabilitySlugs()))
        ->and(productionContentSorted(Service::query()->pluck('slug')->all()))
        ->toBe(productionContentSorted(productionContentServiceSlugs()));
});

it('builds complete content for every service and renders each page', function () {
    $this->seed([
        TechStackSeeder::class,
        IndustrySeeder::class,
        ProductionContentSeeder::class,
    ]);

    $requiredSectionKeys = [
        'hero',
        'features_strip',
        'sub_services',
        'process',
        'tech',
        'industries',
        'case_studies',
        'why_choose',
        'faq',
        'cta_band',
    ];

    Service::query()
        ->whereIn('slug', productionContentServiceSlugs())
        ->ordered()
        ->get()
        ->each(function (Service $service) use ($requiredSectionKeys): void {
            foreach ($requiredSectionKeys as $sectionKey) {
                expect($service->content)->toHaveKey($sectionKey);
            }

            expect($service->content['hero']['bullets'])->toHaveCount(3)
                ->and($service->content['features_strip']['items'])->toHaveCount(4)
                ->and($service->content['sub_services']['items'])->toHaveCount(6)
                ->and($service->content['process']['steps'])->toHaveCount(6)
                ->and($service->content['why_choose']['points'])->toHaveCount(5)
                ->and($service->content['faq']['items'])->toHaveCount(4);

            $this->get(route('services.show', $service->slug))
                ->assertOk()
                ->assertSeeText($service->content['hero']['heading'])
                ->assertSeeText($service->content['sub_services']['items'][0]['title']);
        });
});

it('does not modify pre-existing software development content', function () {
    $softwareCapability = Capability::factory()->create([
        'slug' => 'software-development',
        'category' => 'software_development',
        'title' => 'Approved Software Development',
        'short_description' => 'Keep this capability untouched.',
        'sort_order' => 1,
        'is_active' => true,
        'show_in_menu' => true,
    ]);

    $softwareService = Service::factory()->create([
        'slug' => 'approved-software-service',
        'capability_id' => $softwareCapability->id,
        'category' => 'software_development',
        'title' => 'Approved Software Service',
        'short_description' => 'Existing approved page copy.',
        'seo_title' => 'Approved SEO Title',
        'seo_description' => 'Approved SEO description.',
        'content' => [
            'hero' => [
                'heading' => 'Approved Hero',
                'intro' => 'Approved intro.',
                'bullets' => ['Approved bullet'],
            ],
        ],
        'is_active' => true,
    ]);

    $this->seed(ProductionContentSeeder::class);

    $softwareCapability->refresh();
    $softwareService->refresh();

    expect($softwareCapability->title)->toBe('Approved Software Development')
        ->and($softwareCapability->short_description)->toBe('Keep this capability untouched.')
        ->and($softwareService->title)->toBe('Approved Software Service')
        ->and($softwareService->short_description)->toBe('Existing approved page copy.')
        ->and($softwareService->seo_title)->toBe('Approved SEO Title')
        ->and($softwareService->content['hero']['heading'])->toBe('Approved Hero');
});

it('can be run twice without creating duplicate content', function () {
    $this->seed([
        TechStackSeeder::class,
        IndustrySeeder::class,
        ProductionContentSeeder::class,
    ]);

    $this->seed(ProductionContentSeeder::class);

    expect(Capability::query()->count())->toBe(4)
        ->and(Service::query()->count())->toBe(32)
        ->and(Service::query()->whereIn('slug', productionContentServiceSlugs())->count())->toBe(32);
});
