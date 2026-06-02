<?php

use App\Models\Capability;
use App\Models\Page;
use App\Models\Solution;
use App\Models\Stat;
use App\Models\TechStack;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function contentAdmin(): User
{
    return User::factory()->create(['role' => 'admin']);
}

it('admin can create a capability', function () {
    $this->actingAs(contentAdmin())
        ->post(route('admin.capabilities.store'), [
            'title' => 'New Capability',
            'slug' => 'new-capability',
            'short_description' => 'A capability short description.',
            'is_active' => '1',
        ])->assertRedirect(route('admin.capabilities.index'));

    $this->assertDatabaseHas('capabilities', ['slug' => 'new-capability']);
});

it('admin can update a capability', function () {
    $capability = Capability::factory()->create();

    $this->actingAs(contentAdmin())
        ->put(route('admin.capabilities.update', $capability), [
            'title' => 'Updated Capability',
            'slug' => $capability->slug,
            'short_description' => 'Updated description.',
            'is_active' => '1',
        ])->assertRedirect(route('admin.capabilities.index'));

    $this->assertDatabaseHas('capabilities', ['id' => $capability->id, 'title' => 'Updated Capability']);
});

it('admin can delete a capability', function () {
    $capability = Capability::factory()->create();

    $this->actingAs(contentAdmin())
        ->delete(route('admin.capabilities.destroy', $capability))
        ->assertRedirect(route('admin.capabilities.index'));

    $this->assertDatabaseMissing('capabilities', ['id' => $capability->id]);
});

it('admin can create a solution with a custom HTML body', function () {
    $this->actingAs(contentAdmin())
        ->post(route('admin.solutions.store'), [
            'title' => 'ERP Solution',
            'slug' => 'erp-solution',
            'short_description' => 'A solution short description.',
            'icon' => 'fas fa-database',
            'body' => '<section>Custom solution HTML</section>',
            'is_active' => '1',
        ])->assertRedirect(route('admin.solutions.index'));

    $this->assertDatabaseHas('solutions', ['slug' => 'erp-solution', 'body' => '<section>Custom solution HTML</section>']);
});

it('admin can delete a solution', function () {
    $solution = Solution::factory()->create();

    $this->actingAs(contentAdmin())
        ->delete(route('admin.solutions.destroy', $solution))
        ->assertRedirect(route('admin.solutions.index'));

    $this->assertDatabaseMissing('solutions', ['id' => $solution->id]);
});

it('admin can create an industry', function () {
    $this->actingAs(contentAdmin())
        ->post(route('admin.industries.store'), [
            'name' => 'Manufacturing',
            'slug' => 'manufacturing',
            'icon' => 'fas fa-industry',
            'is_active' => '1',
        ])->assertRedirect(route('admin.industries.index'));

    $this->assertDatabaseHas('industries', ['slug' => 'manufacturing']);
});

it('admin can create a technology', function () {
    $this->actingAs(contentAdmin())
        ->post(route('admin.tech-stacks.store'), [
            'name' => 'React',
            'sort_order' => '1',
            'is_active' => '1',
        ])->assertRedirect(route('admin.tech-stacks.index'));

    $this->assertDatabaseHas('tech_stacks', ['name' => 'React']);
});

it('admin can create a stat', function () {
    $this->actingAs(contentAdmin())
        ->post(route('admin.stats.store'), [
            'value' => '100',
            'label' => '+ Projects Delivered',
            'sort_order' => '1',
            'is_active' => '1',
        ])->assertRedirect(route('admin.stats.index'));

    $this->assertDatabaseHas('stats', ['label' => '+ Projects Delivered']);
});

it('admin can create a standalone page', function () {
    $this->actingAs(contentAdmin())
        ->post(route('admin.pages.store'), [
            'title' => 'Custom Landing',
            'slug' => 'custom-landing',
            'body' => '<div>Landing page content</div>',
            'is_active' => '1',
        ])->assertRedirect(route('admin.pages.index'));

    $this->assertDatabaseHas('pages', ['slug' => 'custom-landing']);
});

it('admin can update and delete a page', function () {
    $page = Page::factory()->create();

    $this->actingAs(contentAdmin())
        ->put(route('admin.pages.update', $page), [
            'title' => 'Updated Page',
            'slug' => $page->slug,
            'body' => '<div>Updated body</div>',
            'is_active' => '1',
        ])->assertRedirect(route('admin.pages.index'));

    $this->assertDatabaseHas('pages', ['id' => $page->id, 'title' => 'Updated Page']);

    $this->actingAs(contentAdmin())
        ->delete(route('admin.pages.destroy', $page))
        ->assertRedirect(route('admin.pages.index'));

    $this->assertDatabaseMissing('pages', ['id' => $page->id]);
});

it('unauthenticated user cannot access new content admin', function () {
    $this->get(route('admin.capabilities.index'))->assertRedirect(route('login'));
    $this->get(route('admin.solutions.index'))->assertRedirect(route('login'));
    $this->get(route('admin.industries.index'))->assertRedirect(route('login'));
    $this->get(route('admin.tech-stacks.index'))->assertRedirect(route('login'));
    $this->get(route('admin.stats.index'))->assertRedirect(route('login'));
    $this->get(route('admin.pages.index'))->assertRedirect(route('login'));
});

it('admin index pages render for all new content types', function () {
    $admin = contentAdmin();
    Stat::factory()->count(2)->create();
    TechStack::factory()->count(2)->create();

    $this->actingAs($admin)->get(route('admin.capabilities.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.solutions.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.industries.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.tech-stacks.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.stats.index'))->assertOk();
    $this->actingAs($admin)->get(route('admin.pages.index'))->assertOk();
});
