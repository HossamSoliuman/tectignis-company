<?php

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

/**
 * Moves the reCAPTCHA keys out of the general "Integrations" settings tab into
 * their own Super-Admin-only group (spec §28.1–28.2), encrypts the secret at
 * rest and adds the global on/off switch.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')
            ->whereIn('key', ['recaptcha_site_key', 'recaptcha_secret_key'])
            ->update(['group' => 'captcha']);

        $secret = DB::table('settings')->where('key', 'recaptcha_secret_key')->value('value');

        if (filled($secret) && ! $this->isEncrypted($secret)) {
            DB::table('settings')
                ->where('key', 'recaptcha_secret_key')
                ->update(['value' => Crypt::encryptString($secret)]);
        }

        // Starts switched off: any keys saved so far were v3 keys, which do not
        // work with the v2 checkbox and would reject every submission. A Super
        // Admin enables CAPTCHA after saving v2 keys.
        $this->insertMissing('recaptcha_enabled', '0', 'captcha');
        $this->insertMissing('recaptcha_updated_by', null, 'captcha');
        $this->insertMissing('recaptcha_updated_at', null, 'captcha');
        $this->insertMissing('lead_acknowledgement_enabled', '0', 'mail');

        Cache::forget('site.settings');
    }

    public function down(): void
    {
        $secret = DB::table('settings')->where('key', 'recaptcha_secret_key')->value('value');

        if (filled($secret) && $this->isEncrypted($secret)) {
            DB::table('settings')
                ->where('key', 'recaptcha_secret_key')
                ->update(['value' => Crypt::decryptString($secret)]);
        }

        DB::table('settings')
            ->whereIn('key', ['recaptcha_site_key', 'recaptcha_secret_key'])
            ->update(['group' => 'integrations']);

        DB::table('settings')
            ->whereIn('key', ['recaptcha_enabled', 'recaptcha_updated_by', 'recaptcha_updated_at', 'lead_acknowledgement_enabled'])
            ->delete();

        Cache::forget('site.settings');
    }

    private function insertMissing(string $key, ?string $value, string $group): void
    {
        if (DB::table('settings')->where('key', $key)->exists()) {
            return;
        }

        DB::table('settings')->insert([
            'key' => $key,
            'value' => $value,
            'group' => $group,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function isEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (DecryptException) {
            return false;
        }
    }
};
