<?php

use App\Models\Industry;
use App\Models\Service;
use App\Models\TechStack;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

/**
 * Remove any files referenced by a stored service's content + columns.
 */
function cleanupServiceUploads(Service $service): void
{
    $files = [$service->icon, $service->banner_image];

    $content = $service->content ?? [];
    $files[] = data_get($content, 'why_choose.image');
    foreach (['features_strip.items', 'sub_services.items', 'process.steps'] as $list) {
        foreach (data_get($content, $list, []) as $item) {
            $files[] = $item['icon'] ?? null;
        }
    }

    foreach (array_filter($files) as $file) {
        @unlink(public_path('uploads/'.$file));
    }
}

it('renders the create and edit forms with the section panels', function () {
    TechStack::factory()->count(2)->create();
    Industry::factory()->create();
    $service = Service::factory()->create([
        'content' => ['sub_services' => ['enabled' => true, 'items' => [['title' => 'Existing card', 'description' => 'x']]]],
    ]);

    $this->actingAs($this->admin)->get(route('admin.services.create'))
        ->assertOk()->assertSee('Landing page sections')->assertSee('Show this section');

    $this->actingAs($this->admin)->get(route('admin.services.edit', $service))
        ->assertOk()->assertSee('Existing card');
});

it('creates a service with every content section, toggles, pivots and uploads', function () {
    $tech = TechStack::factory()->count(2)->create();
    $industries = Industry::factory()->count(2)->create();

    $payload = [
        'title' => 'Custom Software Development',
        'slug' => 'custom-software-development',
        'category' => 'software_development',
        'short_description' => 'End-to-end custom software.',
        'is_active' => '1',
        'tech_stacks' => $tech->pluck('id')->all(),
        'industries' => $industries->pluck('id')->all(),
        'content' => [
            'hero' => [
                'eyebrow' => 'SOFTWARE DEVELOPMENT',
                'heading' => 'Custom Software Development',
                'intro' => 'We build tailored software.',
                'cta_primary_label' => 'Get Started',
                'cta_secondary_label' => 'Talk to an Expert',
                'bullets' => ['Scalable', 'Secure', ''],
            ],
            'features_strip' => [
                'enabled' => '1',
                'items' => [
                    ['label' => 'On-Time Delivery', 'icon_file' => UploadedFile::fake()->image('f1.png')],
                    ['label' => '', 'icon' => ''],
                ],
            ],
            'sub_services' => [
                'enabled' => '1',
                'heading' => 'Our Services',
                'subtitle' => 'What we offer',
                'items' => [
                    ['title' => 'API Development', 'description' => 'REST & GraphQL.', 'icon_file' => UploadedFile::fake()->image('s1.png')],
                    ['title' => '', 'description' => ''],
                ],
            ],
            'process' => [
                'enabled' => '1',
                'heading' => 'Our Process',
                'steps' => [
                    ['title' => 'Discovery', 'description' => 'Requirements.'],
                ],
            ],
            'tech' => ['enabled' => '1', 'heading' => 'Technologies We Use'],
            'industries' => ['enabled' => '1', 'heading' => 'Industries We Serve'],
            'case_studies' => ['enabled' => '0', 'heading' => 'Success Stories'],
            'why_choose' => [
                'enabled' => '1',
                'heading' => 'Why Choose Us',
                'cta_label' => 'Get a Quote',
                'image_file' => UploadedFile::fake()->image('why.png'),
                'points' => ['Experienced team', ''],
            ],
            'faq' => [
                'enabled' => '1',
                'items' => [
                    ['question' => 'How long?', 'answer' => 'It depends.'],
                    ['question' => '', 'answer' => ''],
                ],
            ],
            'cta_band' => ['enabled' => '1', 'heading' => 'Ready?', 'button_label' => 'Start'],
        ],
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.services.store'), $payload)
        ->assertRedirect(route('admin.services.index'));

    $service = Service::firstWhere('slug', 'custom-software-development');
    $content = $service->content;

    // Toggles normalized to booleans.
    expect($content['features_strip']['enabled'])->toBeTrue()
        ->and($content['case_studies']['enabled'])->toBeFalse();

    // Empty repeater rows stripped + reindexed.
    expect($content['hero']['bullets'])->toBe(['Scalable', 'Secure'])
        ->and($content['features_strip']['items'])->toHaveCount(1)
        ->and($content['sub_services']['items'])->toHaveCount(1)
        ->and($content['why_choose']['points'])->toBe(['Experienced team'])
        ->and($content['faq']['items'])->toHaveCount(1);

    // Per-item icon + why-choose image stored as files; transient keys dropped.
    expect($content['sub_services']['items'][0])->not->toHaveKey('icon_file')
        ->and($content['sub_services']['items'][0]['icon'])->not->toBeNull()
        ->and(is_file(public_path('uploads/'.$content['sub_services']['items'][0]['icon'])))->toBeTrue()
        ->and($content['why_choose'])->not->toHaveKey('image_file')
        ->and(is_file(public_path('uploads/'.$content['why_choose']['image'])))->toBeTrue();

    // Pivots synced.
    expect($service->techStacks)->toHaveCount(2)
        ->and($service->industries)->toHaveCount(2);

    cleanupServiceUploads($service);
});

it('rejects a category outside the three allowed values', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.services.store'), [
            'title' => 'Bad Category',
            'slug' => 'bad-category',
            'category' => 'it_services',
            'short_description' => 'Legacy category.',
            'is_active' => '1',
        ])
        ->assertSessionHasErrors('category');

    $this->assertDatabaseMissing('services', ['slug' => 'bad-category']);
});

it('round-trips a disabled section and detaches pivots on update', function () {
    $service = Service::factory()->create([
        'content' => ['faq' => ['enabled' => true, 'items' => [['question' => 'Q', 'answer' => 'A']]]],
    ]);
    $service->techStacks()->sync(TechStack::factory()->count(3)->create()->pluck('id'));
    $keep = TechStack::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.services.update', $service), [
            'title' => $service->title,
            'slug' => $service->slug,
            'category' => 'ai_automation',
            'short_description' => $service->short_description,
            'is_active' => '1',
            'tech_stacks' => [$keep->id],
            'content' => [
                'faq' => ['enabled' => '0', 'items' => [['question' => 'Q', 'answer' => 'A']]],
            ],
        ])
        ->assertRedirect(route('admin.services.index'));

    $service->refresh();

    expect($service->category)->toBe('ai_automation')
        ->and($service->content['faq']['enabled'])->toBeFalse()
        ->and($service->techStacks)->toHaveCount(1)
        ->and($service->techStacks->first()->id)->toBe($keep->id);
});

it('keeps an existing item icon when no new file is uploaded', function () {
    touch(public_path('uploads/existing-icon.png'));

    $service = Service::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.services.update', $service), [
            'title' => $service->title,
            'slug' => $service->slug,
            'category' => 'software_development',
            'short_description' => $service->short_description,
            'is_active' => '1',
            'content' => [
                'sub_services' => [
                    'enabled' => '1',
                    'items' => [
                        ['title' => 'Kept', 'description' => 'Still here', 'icon' => 'existing-icon.png'],
                    ],
                ],
            ],
        ])
        ->assertRedirect(route('admin.services.index'));

    expect($service->refresh()->content['sub_services']['items'][0]['icon'])->toBe('existing-icon.png');

    @unlink(public_path('uploads/existing-icon.png'));
});
