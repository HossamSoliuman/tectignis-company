<?php

use App\Models\Industry;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
});

/**
 * Remove any icon files referenced by a stored industry's content.
 */
function cleanupIndustryUploads(Industry $industry): void
{
    $files = [$industry->banner_image];

    $content = $industry->content ?? [];
    foreach (['hero.features', 'hero.badges', 'solutions.cards', 'stats.items', 'solutions_grid.items'] as $list) {
        foreach (data_get($content, $list, []) as $item) {
            $files[] = $item['icon'] ?? null;
        }
    }

    foreach (array_filter($files) as $file) {
        @unlink(public_path('uploads/'.$file));
    }
}

it('renders the create and edit forms with the section panels', function () {
    $industry = Industry::factory()->create([
        'content' => ['solutions' => ['enabled' => true, 'cards' => [['title' => 'Existing card', 'description' => 'x']]]],
    ]);

    $this->actingAs($this->admin)->get(route('admin.industries.create'))
        ->assertOk()->assertSee('Landing page sections')->assertSee('Show this section');

    $this->actingAs($this->admin)->get(route('admin.industries.edit', $industry))
        ->assertOk()->assertSee('Existing card');
});

it('creates an industry with every content section, toggles and uploads', function () {
    $payload = [
        'name' => 'Corporate Offices',
        'slug' => 'corporate-offices',
        'short_description' => 'Smart workplace technology.',
        'is_active' => '1',
        'content' => [
            'hero' => [
                'eyebrow' => 'CORPORATE OFFICE IT SOLUTIONS',
                'heading' => 'Smart Technology. Stronger Corporate Offices.',
                'highlight' => 'Corporate Offices.',
                'intro' => 'We help corporate organizations.',
                'cta_primary_label' => 'Talk to an Expert',
                'features' => [
                    ['label' => 'Secure Data', 'icon_file' => UploadedFile::fake()->image('f1.png')],
                    ['label' => '', 'icon' => ''],
                ],
                'badges' => [
                    ['label' => 'Smart Workspaces'],
                    ['label' => ''],
                ],
            ],
            'trust' => ['enabled' => '1'],
            'challenges' => [
                'enabled' => '1',
                'eyebrow' => 'CHALLENGES',
                'heading' => 'New-Age Challenges.',
                'items' => ['Hybrid workforces', 'Data security', ''],
            ],
            'solutions' => [
                'enabled' => '1',
                'eyebrow' => 'HOW WE HELP',
                'heading' => 'IT Solutions',
                'cards' => [
                    ['title' => 'Cybersecurity', 'description' => 'Protect data.', 'icon_file' => UploadedFile::fake()->image('c1.png')],
                    ['title' => '', 'description' => ''],
                ],
            ],
            'stats' => [
                'enabled' => '1',
                'items' => [
                    ['value' => '250+', 'label' => 'Clients'],
                    ['value' => '', 'label' => ''],
                ],
            ],
            'case_studies' => ['enabled' => '0', 'heading' => 'Success Stories'],
            'solutions_grid' => [
                'enabled' => '1',
                'items' => [
                    ['label' => 'Cloud Solutions'],
                    ['label' => ''],
                ],
            ],
            'faq' => [
                'enabled' => '1',
                'items' => [
                    ['question' => 'How secure?', 'answer' => 'Very.'],
                    ['question' => '', 'answer' => ''],
                ],
                'cta_heading' => 'Ready?',
                'cta_label' => 'Get Started',
            ],
            'cta_band' => ['enabled' => '1', 'heading' => "Let's build", 'button_primary_label' => 'Talk'],
        ],
    ];

    $this->actingAs($this->admin)
        ->post(route('admin.industries.store'), $payload)
        ->assertRedirect();

    $industry = Industry::firstWhere('slug', 'corporate-offices');
    $content = $industry->content;

    // Toggles normalized to booleans.
    expect($content['trust']['enabled'])->toBeTrue()
        ->and($content['case_studies']['enabled'])->toBeFalse();

    // Empty repeater rows stripped + reindexed.
    expect($content['hero']['features'])->toHaveCount(1)
        ->and($content['hero']['badges'])->toHaveCount(1)
        ->and($content['challenges']['items'])->toBe(['Hybrid workforces', 'Data security'])
        ->and($content['solutions']['cards'])->toHaveCount(1)
        ->and($content['stats']['items'])->toHaveCount(1)
        ->and($content['solutions_grid']['items'])->toHaveCount(1)
        ->and($content['faq']['items'])->toHaveCount(1);

    // Per-item icons stored as files; transient keys dropped.
    expect($content['hero']['features'][0])->not->toHaveKey('icon_file')
        ->and(is_file(public_path('uploads/'.$content['hero']['features'][0]['icon'])))->toBeTrue()
        ->and(is_file(public_path('uploads/'.$content['solutions']['cards'][0]['icon'])))->toBeTrue();

    cleanupIndustryUploads($industry);
});

it('round-trips a disabled section on update', function () {
    $industry = Industry::factory()->create([
        'content' => ['faq' => ['enabled' => true, 'items' => [['question' => 'Q', 'answer' => 'A']]]],
    ]);

    $this->actingAs($this->admin)
        ->put(route('admin.industries.update', $industry), [
            'name' => $industry->name,
            'slug' => $industry->slug,
            'short_description' => $industry->short_description,
            'is_active' => '1',
            'content' => [
                'faq' => ['enabled' => '0', 'items' => [['question' => 'Q', 'answer' => 'A']]],
            ],
        ])
        ->assertRedirect();

    expect($industry->refresh()->content['faq']['enabled'])->toBeFalse();
});

it('keeps an existing item icon when no new file is uploaded', function () {
    touch(public_path('uploads/existing-industry-icon.png'));

    $industry = Industry::factory()->create();

    $this->actingAs($this->admin)
        ->put(route('admin.industries.update', $industry), [
            'name' => $industry->name,
            'slug' => $industry->slug,
            'short_description' => $industry->short_description,
            'is_active' => '1',
            'content' => [
                'solutions' => [
                    'enabled' => '1',
                    'cards' => [
                        ['title' => 'Kept', 'description' => 'Still here', 'icon' => 'existing-industry-icon.png'],
                    ],
                ],
            ],
        ])
        ->assertRedirect();

    expect($industry->refresh()->content['solutions']['cards'][0]['icon'])->toBe('existing-industry-icon.png');

    @unlink(public_path('uploads/existing-industry-icon.png'));
});

it('renders the public industry page with the rich sections', function () {
    $industry = Industry::factory()->create([
        'slug' => 'corporate-offices',
        'name' => 'Corporate Offices',
        'body' => null,
        'content' => [
            'hero' => ['heading' => 'Smart Technology. Stronger Corporate Offices.', 'highlight' => 'Corporate Offices.'],
            'challenges' => ['enabled' => true, 'heading' => 'New-Age Challenges.', 'items' => ['Hybrid workforces']],
            'solutions' => ['enabled' => true, 'heading' => 'IT Solutions', 'cards' => [['title' => 'Cybersecurity']]],
            'stats' => ['enabled' => true, 'items' => [['value' => '250+', 'label' => 'Clients']]],
            'solutions_grid' => ['enabled' => true, 'items' => [['label' => 'Cloud Solutions']]],
            'faq' => ['enabled' => true, 'items' => [['question' => 'How secure?', 'answer' => 'Very.']]],
            'cta_band' => ['enabled' => true, 'heading' => "Let's build smarter workplaces"],
        ],
    ]);

    $this->get(route('industries.show', $industry->slug))
        ->assertOk()
        ->assertSee('Stronger')
        ->assertSee('New-Age Challenges.')
        ->assertSee('Cybersecurity')
        ->assertSee('250+')
        ->assertSee('Cloud Solutions')
        ->assertSee('How secure?')
        ->assertSee('smarter workplaces', false);
});
