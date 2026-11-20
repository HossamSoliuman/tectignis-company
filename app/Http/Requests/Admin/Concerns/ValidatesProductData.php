<?php

namespace App\Http\Requests\Admin\Concerns;

use App\Models\Product;
use Closure;

trait ValidatesProductData
{
    /**
     * Turn the one-per-line features textarea into an array.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('features'))) {
            $this->merge([
                'features' => array_values(array_filter(
                    array_map('trim', preg_split('/\r\n|\r|\n/', $this->input('features'))),
                    fn (string $line): bool => $line !== '',
                )),
            ]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function productRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'features' => ['nullable', 'array', 'max:6'],
            'features.*' => ['string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:100'],
            'url' => ['nullable', 'url', 'max:255'],
            'case_study_id' => ['nullable', 'integer', 'exists:case_studies,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'gallery' => ['nullable', 'array'],
            'gallery.*.image' => ['required', 'string', 'max:255'],
            'gallery.*.alt' => ['nullable', 'string', 'max:255'],
            'gallery.*.remove' => ['nullable', 'boolean'],
            'gallery_uploads' => ['nullable', 'array', 'max:12'],
            'gallery_uploads.*' => ['file', 'image', 'max:4096'],
            'videos' => ['nullable', 'array'],
            'videos.*.source' => ['required', 'string', 'max:500'],
            'videos.*.title' => ['nullable', 'string', 'max:255'],
            'videos.*.remove' => ['nullable', 'boolean'],
            'new_video' => ['nullable', 'array'],
            'new_video.title' => ['nullable', 'string', 'max:255'],
            'new_video.link' => ['nullable', 'url', 'max:500', $this->videoLinkRule()],
            'new_video_file' => ['nullable', 'file', 'mimetypes:video/mp4,video/webm', 'max:102400'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'new_video_file.mimetypes' => 'The video must be an MP4 or WebM file.',
            'new_video_file.max' => 'The video may not be larger than 100 MB.',
        ];
    }

    /**
     * Only YouTube and Vimeo links can be played on the site.
     */
    private function videoLinkRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            if (is_string($value) && Product::embedFor($value) === null) {
                $fail('The video link must be a YouTube or Vimeo link.');
            }
        };
    }
}
