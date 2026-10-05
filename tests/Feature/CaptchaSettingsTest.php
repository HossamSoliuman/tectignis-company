<?php

use App\Enums\UserRole;
use App\Models\Setting;
use App\Models\User;
use App\Services\RecaptchaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

function superAdmin(): User
{
    return User::factory()->role(UserRole::SuperAdmin)->create();
}

it('super admin can save the keys and the secret is encrypted at rest', function () {
    $admin = superAdmin();

    $this->actingAs($admin)
        ->put(route('admin.captcha.update'), ['site_key' => 'site-abc', 'secret_key' => 'secret-xyz-9876', 'enabled' => '1'])
        ->assertRedirect()
        ->assertSessionHas('status');

    $stored = Setting::where('key', 'recaptcha_secret_key')->value('value');

    expect($stored)->not->toBe('secret-xyz-9876')
        ->and(Crypt::decryptString($stored))->toBe('secret-xyz-9876')
        ->and(Setting::get('recaptcha_site_key'))->toBe('site-abc')
        ->and(Setting::get('recaptcha_enabled'))->toBe('1')
        ->and(Setting::get('recaptcha_updated_by'))->toBe((string) $admin->id)
        ->and(Setting::get('recaptcha_updated_at'))->not->toBeNull()
        ->and(app(RecaptchaService::class)->isEnabled())->toBeTrue();
});

it('masks the saved secret and shows the configuration status', function () {
    $admin = superAdmin();
    app(RecaptchaService::class)->saveConfiguration('site-abc', 'secret-xyz-9876', true, $admin);

    $this->actingAs($admin)
        ->get(route('admin.captcha.edit'))
        ->assertOk()
        ->assertSee('Configured')
        ->assertSee('9876')
        ->assertDontSee('secret-xyz-9876')
        ->assertSee('by '.$admin->name);
});

it('a blank secret keeps the existing one', function () {
    $admin = superAdmin();
    app(RecaptchaService::class)->saveConfiguration('site-abc', 'keep-me', true, $admin);

    $this->actingAs($admin)
        ->put(route('admin.captcha.update'), ['site_key' => 'site-new', 'secret_key' => '', 'enabled' => '1'])
        ->assertRedirect();

    expect(app(RecaptchaService::class)->secretKey())->toBe('keep-me')
        ->and(Setting::get('recaptcha_site_key'))->toBe('site-new');
});

it('cannot be enabled without both keys', function () {
    $this->actingAs(superAdmin())
        ->put(route('admin.captcha.update'), ['site_key' => 'site-only', 'enabled' => '1'])
        ->assertSessionHasErrors('enabled');

    expect(Setting::get('recaptcha_enabled'))->not->toBe('1');
});

it('denies CAPTCHA settings to everyone but super admins', function (UserRole $role) {
    $user = User::factory()->role($role)->create();

    $this->actingAs($user)->get(route('admin.captcha.edit'))->assertForbidden();
    $this->actingAs($user)->put(route('admin.captcha.update'), ['site_key' => 'x', 'secret_key' => 'y'])->assertForbidden();
    $this->actingAs($user)->post(route('admin.captcha.test'))->assertForbidden();

    expect(Setting::get('recaptcha_site_key'))->toBeNull();
})->with([UserRole::Editor, UserRole::Sales, UserRole::ReadOnly]);

it('the general settings screen can neither show nor overwrite CAPTCHA keys', function () {
    $editor = User::factory()->role(UserRole::Editor)->create();
    app(RecaptchaService::class)->saveConfiguration('site-abc', 'secret-xyz', true, superAdmin());

    $this->actingAs($editor)->get(route('admin.settings.index'))
        ->assertOk()
        ->assertDontSee('recaptcha_secret_key');

    $this->actingAs($editor)
        ->put(route('admin.settings.update'), ['settings' => ['recaptcha_secret_key' => 'hijacked', 'recaptcha_enabled' => '0']])
        ->assertRedirect();

    expect(app(RecaptchaService::class)->secretKey())->toBe('secret-xyz')
        ->and(Setting::get('recaptcha_enabled'))->toBe('1');
});

it('tests the secret key with Google', function (array $googleReply, string $flash) {
    $admin = superAdmin();
    app(RecaptchaService::class)->saveConfiguration('site-abc', 'secret-xyz', false, $admin);
    Http::fake([RecaptchaService::VERIFY_URL => Http::response($googleReply)]);

    $this->actingAs($admin)
        ->post(route('admin.captcha.test'))
        ->assertRedirect()
        ->assertSessionHas($flash);
})->with([
    'valid secret' => [['success' => false, 'error-codes' => ['invalid-input-response']], 'status'],
    'invalid secret' => [['success' => false, 'error-codes' => ['invalid-input-secret']], 'captcha_test_error'],
]);

it('runs a full round trip when the test checkbox was ticked', function () {
    $admin = superAdmin();
    app(RecaptchaService::class)->saveConfiguration('site-abc', 'secret-xyz', false, $admin);
    Http::fake([RecaptchaService::VERIFY_URL => Http::response(['success' => true])]);

    $this->actingAs($admin)
        ->post(route('admin.captcha.test'), ['g-recaptcha-response' => 'ticked'])
        ->assertSessionHas('status');

    Http::assertSent(fn ($request): bool => $request['response'] === 'ticked');
});

it('existing plain-text secrets are encrypted by the migration', function () {
    Setting::set('recaptcha_secret_key', 'legacy-plain', 'integrations');

    $migration = require database_path('migrations/2026_10_05_090222_move_recaptcha_settings_to_captcha_group.php');
    $migration->up();

    $row = Setting::where('key', 'recaptcha_secret_key')->first();

    expect($row->group)->toBe('captcha')
        ->and(Crypt::decryptString($row->value))->toBe('legacy-plain');
});
