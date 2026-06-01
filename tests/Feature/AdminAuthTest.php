<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('unauthenticated request to admin redirects to login', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

it('wrong credentials stay on login with error', function () {
    $user = User::factory()->create(['email' => 'admin@test.com', 'role' => 'admin']);

    $this->post(route('admin.login.store'), [
        'email' => 'admin@test.com',
        'password' => 'wrong-password',
    ])->assertSessionHasErrors();
});

it('correct admin credentials reach dashboard', function () {
    $user = User::factory()->create([
        'email' => 'admin@test.com',
        'password' => bcrypt('secret'),
        'role' => 'admin',
    ]);

    $this->post(route('admin.login.store'), [
        'email' => 'admin@test.com',
        'password' => 'secret',
    ])->assertRedirect(route('admin.dashboard'));
});

it('non-admin user cannot access admin dashboard', function () {
    $user = User::factory()->create(['role' => 'user']);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('admin can logout', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user)
        ->post(route('admin.logout'))
        ->assertRedirect();

    $this->assertGuest();
});
