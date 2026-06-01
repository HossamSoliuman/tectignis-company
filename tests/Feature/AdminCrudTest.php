<?php

use App\Models\BlogPost;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

uses(RefreshDatabase::class);

function adminUser(): User
{
    return User::factory()->create(['role' => 'admin']);
}

it('admin can view services index', function () {
    $this->actingAs(adminUser())
        ->get(route('admin.services.index'))
        ->assertOk();
});

it('admin can create a service', function () {
    $this->actingAs(adminUser())
        ->post(route('admin.services.store'), [
            'title' => 'Test Service',
            'slug' => 'test-service',
            'category' => 'digital_services',
            'short_description' => 'A test service description.',
            'is_active' => '1',
        ])->assertRedirect(route('admin.services.index'));

    $this->assertDatabaseHas('services', ['slug' => 'test-service']);
});

it('admin can update a service', function () {
    $service = Service::factory()->create();

    $this->actingAs(adminUser())
        ->put(route('admin.services.update', $service), [
            'title' => 'Updated Title',
            'slug' => $service->slug,
            'short_description' => 'Updated description.',
            'is_active' => '1',
        ])->assertRedirect(route('admin.services.index'));

    $this->assertDatabaseHas('services', ['id' => $service->id, 'title' => 'Updated Title']);
});

it('admin can delete a service', function () {
    $service = Service::factory()->create();

    $this->actingAs(adminUser())
        ->delete(route('admin.services.destroy', $service))
        ->assertRedirect(route('admin.services.index'));

    $this->assertDatabaseMissing('services', ['id' => $service->id]);
});

it('admin can create a blog post', function () {
    $this->actingAs(adminUser())
        ->post(route('admin.blog.store'), [
            'title' => 'Test Blog Post',
            'content' => '<p>Test content.</p>',
            'is_published' => '1',
            'published_at' => now()->format('Y-m-d\TH:i'),
        ])->assertRedirect(route('admin.blog.index'));

    $this->assertDatabaseHas('blog_posts', ['title' => 'Test Blog Post']);
});

it('admin can update a blog post', function () {
    $post = BlogPost::factory()->create();

    $this->actingAs(adminUser())
        ->put(route('admin.blog.update', $post), [
            'title' => 'Updated Post Title',
            'slug' => $post->slug,
            'content' => '<p>Updated content.</p>',
            'is_published' => '1',
        ])->assertRedirect(route('admin.blog.index'));

    $this->assertDatabaseHas('blog_posts', ['id' => $post->id, 'title' => 'Updated Post Title']);
});

it('admin can delete a blog post', function () {
    $post = BlogPost::factory()->create();

    $this->actingAs(adminUser())
        ->delete(route('admin.blog.destroy', $post))
        ->assertRedirect(route('admin.blog.index'));

    $this->assertDatabaseMissing('blog_posts', ['id' => $post->id]);
});

it('stores an uploaded image into public/uploads', function () {
    $this->actingAs(adminUser())
        ->post(route('admin.services.store'), [
            'title' => 'With Icon',
            'slug' => 'with-icon',
            'category' => 'digital_services',
            'short_description' => 'Has an uploaded icon.',
            'is_active' => '1',
            'icon' => UploadedFile::fake()->image('icon.png'),
        ])->assertRedirect(route('admin.services.index'));

    $service = Service::firstWhere('slug', 'with-icon');
    expect($service->icon)->not->toBeNull();
    expect(is_file(public_path('uploads/'.$service->icon)))->toBeTrue();

    @unlink(public_path('uploads/'.$service->icon));
});

it('deletes the old file and stores the new one when an image is replaced', function () {
    $service = Service::factory()->create();
    touch(public_path('uploads/'.$service->icon = 'old-icon.png'));
    $service->save();

    $this->actingAs(adminUser())
        ->put(route('admin.services.update', $service), [
            'title' => $service->title,
            'slug' => $service->slug,
            'short_description' => $service->short_description,
            'is_active' => '1',
            'icon' => UploadedFile::fake()->image('new-icon.png'),
        ])->assertRedirect(route('admin.services.index'));

    expect(is_file(public_path('uploads/old-icon.png')))->toBeFalse();

    $service->refresh();
    expect($service->icon)->not->toBe('old-icon.png');
    expect(is_file(public_path('uploads/'.$service->icon)))->toBeTrue();

    @unlink(public_path('uploads/'.$service->icon));
});

it('removes an image when the remove flag is set', function () {
    $service = Service::factory()->create();
    touch(public_path('uploads/'.$service->icon = 'removable-icon.png'));
    $service->save();

    $this->actingAs(adminUser())
        ->put(route('admin.services.update', $service), [
            'title' => $service->title,
            'slug' => $service->slug,
            'short_description' => $service->short_description,
            'is_active' => '1',
            'remove_icon' => '1',
        ])->assertRedirect(route('admin.services.index'));

    expect(is_file(public_path('uploads/removable-icon.png')))->toBeFalse();
    expect($service->fresh()->icon)->toBeNull();
});

it('deletes uploaded files when a record is deleted', function () {
    $service = Service::factory()->create();
    touch(public_path('uploads/'.$service->icon = 'doomed-icon.png'));
    $service->save();

    $this->actingAs(adminUser())
        ->delete(route('admin.services.destroy', $service))
        ->assertRedirect(route('admin.services.index'));

    expect(is_file(public_path('uploads/doomed-icon.png')))->toBeFalse();
});

it('orders blog posts by sort order then newest', function () {
    $second = BlogPost::factory()->create(['sort_order' => 5, 'published_at' => now()->subDay()]);
    $first = BlogPost::factory()->create(['sort_order' => 1, 'published_at' => now()->subWeek()]);

    expect(BlogPost::ordered()->pluck('id')->all())->toBe([$first->id, $second->id]);
});

it('unauthenticated user cannot access admin crud', function () {
    $this->get(route('admin.services.index'))->assertRedirect(route('login'));
    $this->get(route('admin.blog.index'))->assertRedirect(route('login'));
});
