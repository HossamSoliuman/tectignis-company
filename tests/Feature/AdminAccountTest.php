<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('admin can view the account page', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user)
        ->get(route('admin.account.edit'))
        ->assertOk()
        ->assertSee('My Account');
});

it('admin can update name and email with correct current password', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'password' => bcrypt('current-secret'),
    ]);

    $this->actingAs($user)
        ->put(route('admin.account.update'), [
            'name' => 'New Name',
            'email' => 'new@test.com',
            'current_password' => 'current-secret',
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    $user->refresh();
    expect($user->name)->toBe('New Name');
    expect($user->email)->toBe('new@test.com');
});

it('admin can change password', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'password' => bcrypt('current-secret'),
    ]);

    $this->actingAs($user)
        ->put(route('admin.account.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'current-secret',
            'password' => 'brand-new-password',
            'password_confirmation' => 'brand-new-password',
        ])
        ->assertSessionHas('status');

    expect(Hash::check('brand-new-password', $user->refresh()->password))->toBeTrue();
});

it('update fails with wrong current password', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'email' => 'admin@test.com',
        'password' => bcrypt('current-secret'),
    ]);

    $this->actingAs($user)
        ->put(route('admin.account.update'), [
            'name' => 'New Name',
            'email' => 'new@test.com',
            'current_password' => 'wrong-password',
        ])
        ->assertSessionHasErrors('current_password');

    expect($user->refresh()->email)->toBe('admin@test.com');
});

it('email must be unique across users', function () {
    User::factory()->create(['email' => 'taken@test.com']);
    $user = User::factory()->create([
        'role' => 'admin',
        'password' => bcrypt('current-secret'),
    ]);

    $this->actingAs($user)
        ->put(route('admin.account.update'), [
            'name' => $user->name,
            'email' => 'taken@test.com',
            'current_password' => 'current-secret',
        ])
        ->assertSessionHasErrors('email');
});
