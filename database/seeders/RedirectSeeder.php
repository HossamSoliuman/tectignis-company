<?php

namespace Database\Seeders;

use App\Models\Redirect;
use Illuminate\Database\Seeder;

class RedirectSeeder extends Seeder
{
    public function run(): void
    {
        $redirects = [
            // The /services listing page was removed; point old/indexed links at
            // the Capabilities listing so they 301 instead of 404.
            ['from_path' => '/services', 'to_path' => '/capabilities', 'status_code' => 301],
        ];

        foreach ($redirects as $redirect) {
            Redirect::firstOrCreate(
                ['from_path' => $redirect['from_path']],
                ['to_path' => $redirect['to_path'], 'status_code' => $redirect['status_code'], 'is_active' => true],
            );
        }
    }
}
