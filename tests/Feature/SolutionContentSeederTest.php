<?php

use App\Models\Solution;
use Database\Seeders\IndustrySeeder;
use Database\Seeders\SolutionContentSeeder;
use Database\Seeders\SolutionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function solutionContentSlugs(): array
{
    return [
        'erp-solutions',
        'crm-solutions',
        'hrms-solutions',
        'ai-solutions',
        'cloud-solutions',
        'cybersecurity-solutions',
        'automation-solutions',
        'smart-security-solutions',
    ];
}

it('builds complete, tailored content for every solution page', function () {
    $this->seed([
        SolutionSeeder::class,
        SolutionContentSeeder::class,
        IndustrySeeder::class,
    ]);

    $requiredSections = [
        'hero',
        'stats',
        'modules',
        'benefits',
        'process',
        'industries',
        'why_choose',
        'cta_band',
    ];

    expect(Solution::query()->count())->toBe(8)
        ->and(Solution::query()->whereIn('slug', solutionContentSlugs())->count())->toBe(8);

    Solution::query()
        ->whereIn('slug', solutionContentSlugs())
        ->ordered()
        ->get()
        ->each(function (Solution $solution) use ($requiredSections): void {
            foreach ($requiredSections as $section) {
                expect($solution->content)->toHaveKey($section);
            }

            expect($solution->seo_title)->not->toBeEmpty()
                ->and($solution->seo_description)->not->toBeEmpty()
                ->and($solution->seo_keywords)->not->toBeEmpty()
                ->and($solution->content['hero']['benefits'])->toHaveCount(6)
                ->and($solution->content['stats']['items'])->toHaveCount(6)
                ->and($solution->content['modules']['cards'])->toHaveCount(8)
                ->and($solution->content['benefits']['items'])->toHaveCount(8)
                ->and($solution->content['process']['steps'])->toHaveCount(6)
                ->and($solution->content['why_choose']['points'])->toHaveCount(5);

            $this->get(route('solutions.show', $solution->slug))
                ->assertSuccessful()
                ->assertSeeText($solution->content['hero']['heading'])
                ->assertSeeText($solution->content['modules']['cards'][0]['title']);
        });
});

it('updates existing solutions without creating duplicates', function () {
    $this->seed(SolutionSeeder::class);

    $this->seed(SolutionContentSeeder::class);
    $this->seed(SolutionContentSeeder::class);

    expect(Solution::query()->count())->toBe(8)
        ->and(Solution::query()->whereIn('slug', solutionContentSlugs())->whereNotNull('content')->count())->toBe(8);
});
