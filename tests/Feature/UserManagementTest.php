<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('super admin creates a sales user with export permission', function () {
    $this->actingAs(User::factory()->role(UserRole::SuperAdmin)->create())
        ->post(route('admin.users.store'), [
            'name' => 'Sara Sales',
            'email' => 'sara@tectignis.example',
            'password' => 'longpassword',
            'password_confirmation' => 'longpassword',
            'role' => 'sales',
            'can_export_leads' => '1',
        ])
        ->assertRedirect(route('admin.users.index'));

    $user = User::where('email', 'sara@tectignis.example')->sole();

    expect($user->userRole())->toBe(UserRole::Sales)
        ->and($user->canExportLeads())->toBeTrue()
        ->and(Hash::check('longpassword', $user->password))->toBeTrue();
});

it('updates a user without changing the password when left blank', function () {
    $user = User::factory()->role(UserRole::Sales)->create();
    $originalHash = $user->password;

    $this->actingAs(User::factory()->role(UserRole::SuperAdmin)->create())
        ->put(route('admin.users.update', $user), [
            'name' => 'Renamed',
            'email' => $user->email,
            'password' => '',
            'password_confirmation' => '',
            'role' => 'readonly',
        ])
        ->assertRedirect(route('admin.users.index'));

    $user->refresh();

    expect($user->name)->toBe('Renamed')
        ->and($user->userRole())->toBe(UserRole::ReadOnly)
        ->and($user->can_export_leads)->toBeFalse()
        ->and($user->password)->toBe($originalHash);
});

it('a super admin cannot demote or delete themselves', function () {
    $admin = User::factory()->role(UserRole::SuperAdmin)->create();

    $this->actingAs($admin)
        ->put(route('admin.users.update', $admin), ['name' => $admin->name, 'email' => $admin->email, 'role' => 'sales'])
        ->assertSessionHasErrors('role');

    $this->actingAs($admin)->delete(route('admin.users.destroy', $admin))->assertSessionHasErrors('user');

    expect($admin->fresh()->userRole())->toBe(UserRole::SuperAdmin);
});

it('only super admins manage users', function (UserRole $role) {
    $user = User::factory()->role($role)->create();

    $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
    $this->actingAs($user)->post(route('admin.users.store'), [])->assertForbidden();
})->with([UserRole::Editor, UserRole::Sales, UserRole::ReadOnly]);
