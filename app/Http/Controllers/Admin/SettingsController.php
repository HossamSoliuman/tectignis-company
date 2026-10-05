<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use UploadsFiles;

    /**
     * Groups managed on their own screens. CAPTCHA keys are Super-Admin-only
     * (spec §28.2), so they can neither be shown nor written from here.
     *
     * @var list<string>
     */
    private const EXCLUDED_GROUPS = ['smtp', 'mail', 'captcha'];

    public function index(): View
    {
        $settings = Setting::whereNotIn('group', self::EXCLUDED_GROUPS)
            ->orderBy('group')->orderBy('key')->get()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'settings' => ['nullable', 'array'],
            'settings.*' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'max:4096'],
        ]);

        foreach ($data['settings'] ?? [] as $key => $value) {
            Setting::where('key', $key)->where('group', '!=', 'captcha')->update(['value' => $value]);
        }

        $imageKeys = array_unique(array_merge(
            array_keys($request->file('images', [])),
            array_keys((array) $request->input('remove_images', [])),
        ));

        foreach ($imageKeys as $key) {
            $this->syncImageSetting($request, $key);
        }

        return back()->with('status', 'Settings updated.');
    }

    /**
     * Apply a new upload or an explicit removal to a single image setting.
     * Old files are only unlinked when they live in public/uploads, so seeded
     * template assets are left untouched.
     */
    private function syncImageSetting(Request $request, string $key): void
    {
        $setting = Setting::where('key', $key)->whereNotIn('group', self::EXCLUDED_GROUPS)->first();

        if (! $setting) {
            return;
        }

        if ($request->hasFile("images.$key")) {
            $this->deleteUpload($setting->value);
            $setting->update(['value' => $this->storeUpload($request->file("images.$key"), 'site')]);

            return;
        }

        if ($request->boolean("remove_images.$key")) {
            $this->deleteUpload($setting->value);
            $setting->update(['value' => null]);
        }
    }
}
