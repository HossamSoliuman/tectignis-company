<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;

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

it('redirects back to the login form with a message when the csrf token has expired', function () {
    Route::post('/_test/expired-token', fn () => throw new TokenMismatchException)->middleware('web');

    $this->from(route('login'))
        ->post('/_test/expired-token', ['email' => 'admin@test.com', 'password' => 'secret'])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('session')
        ->assertSessionHasInput('email', 'admin@test.com')
        ->assertSessionMissing('_old_input.password');
});

it('keeps the 419 status for json requests with an expired csrf token', function () {
    Route::post('/_test/expired-token', fn () => throw new TokenMismatchException)->middleware('web');

    $this->postJson('/_test/expired-token')->assertStatus(419);
});
