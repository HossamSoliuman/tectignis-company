@php
    $cs = $caseStudy ?? null;
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
    $label = 'block text-sm font-medium text-slate-700 mb-1';
    $lines = fn (string $field): string => old($field) !== null
        ? (is_array(old($field)) ? implode("\n", old($field)) : (string) old($field))
        : implode("\n", $cs?->{$field} ?? []);
@endphp

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Project</p>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="cs-title" class="{{ $label }}">Title *</label>
            <input id="cs-title" type="text" name="title" value="{{ old('title', $cs?->title) }}" required class="{{ $input }}">
        </div>
        <div>
            <label for="cs-client" class="{{ $label }}">Client name or anonymised label</label>
            <input id="cs-client" type="text" name="client_name" value="{{ old('client_name', $cs?->client_name) }}" placeholder="e.g. A Mumbai-based logistics company" class="{{ $input }}">
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div>
            <label for="cs-status" class="{{ $label }}">Publication *</label>
            <select id="cs-status" name="status" class="{{ $input }}">
                @foreach (\App\Enums\CaseStudyStatus::options() as $value => $text)
                    <option value="{{ $value }}" @selected(old('status', $cs?->status?->value ?? 'draft') === $value)>{{ $text }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="cs-project-status" class="{{ $label }}">Project stage *</label>
            <select id="cs-project-status" name="project_status" class="{{ $input }}">
                @foreach (\App\Enums\ProjectStatus::options() as $value => $text)
                    <option value="{{ $value }}" @selected(old('project_status', $cs?->project_status?->value ?? 'completed') === $value)>{{ $text }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="cs-date" class="{{ $label }}">Project date</label>
            <input id="cs-date" type="date" name="project_date" value="{{ old('project_date', $cs?->project_date?->format('Y-m-d')) }}" class="{{ $input }}">
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="cs-category" class="{{ $label }}">Service category</label>
            <select id="cs-category" name="case_study_category_id" class="{{ $input }}">
                <option value="">Select category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) old('case_study_category_id', $cs?->case_study_category_id) === $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="cs-service" class="{{ $label }}">Related service page</label>
            <select id="cs-service" name="service_id" class="{{ $input }}">
                <option value="">None</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" @selected((int) old('service_id', $cs?->service_id) === $service->id)>{{ $service->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="cs-industry" class="{{ $label }}">Industry</label>
            <select id="cs-industry" name="industry_id" class="{{ $input }}">
                <option value="">None</option>
                @foreach ($industries as $industry)
                    <option value="{{ $industry->id }}" @selected((int) old('industry_id', $cs?->industry_id) === $industry->id)>{{ $industry->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="cs-location" class="{{ $label }}">Location</label>
            <input id="cs-location" type="text" name="location" value="{{ old('location', $cs?->location) }}" placeholder="e.g. Navi Mumbai, India" class="{{ $input }}">
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div>
            <label for="cs-slug" class="{{ $label }}">Slug</label>
            <input id="cs-slug" type="text" name="slug" value="{{ old('slug', $cs?->slug) }}" class="{{ $input }}">
        </div>
        <div>
            <label for="cs-order" class="{{ $label }}">Display order</label>
            <input id="cs-order" type="number" name="sort_order" value="{{ old('sort_order', $cs?->sort_order ?? 0) }}" min="0" class="{{ $input }}">
        </div>
        <div class="flex items-end pb-2">
            <input type="hidden" name="is_featured" value="0">
            <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $cs?->is_featured)) class="rounded border-slate-300">
                Featured
            </label>
        </div>
    </div>
</div>

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Case study</p>

    <div>
        <label for="cs-summary" class="{{ $label }}">Summary *</label>
        <textarea id="cs-summary" name="short_description" rows="2" required class="{{ $input }}">{{ old('short_description', $cs?->short_description) }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="cs-challenge" class="{{ $label }}">Challenge</label>
            <textarea id="cs-challenge" name="challenge" rows="5" class="{{ $input }}">{{ old('challenge', $cs?->challenge) }}</textarea>
        </div>
        <div>
            <label for="cs-solution" class="{{ $label }}">Solution</label>
            <textarea id="cs-solution" name="solution" rows="5" class="{{ $input }}">{{ old('solution', $cs?->solution) }}</textarea>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="cs-modules" class="{{ $label }}">Modules and features (one per line)</label>
            <textarea id="cs-modules" name="modules" rows="5" class="{{ $input }}">{{ $lines('modules') }}</textarea>
        </div>
        <div>
            <label for="cs-tech" class="{{ $label }}">Technology stack (one per line)</label>
            <textarea id="cs-tech" name="technologies" rows="5" placeholder="Laravel&#10;MySQL&#10;AWS" class="{{ $input }}">{{ $lines('technologies') }}</textarea>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="cs-features" class="{{ $label }}">Highlights (one per line)</label>
            <textarea id="cs-features" name="features" rows="4" placeholder="Responsive storefront&#10;Secure checkout" class="{{ $input }}">{{ $lines('features') }}</textarea>
        </div>
        <div>
            <label for="cs-outcome" class="{{ $label }}">Verified outcome</label>
            <textarea id="cs-outcome" name="outcome" rows="4" placeholder="Only results the client has confirmed" class="{{ $input }}">{{ old('outcome', $cs?->outcome) }}</textarea>
        </div>
    </div>

    <div>
        <label for="cs-testimonial" class="{{ $label }}">Approved testimonial</label>
        <select id="cs-testimonial" name="testimonial_id" class="{{ $input }}">
            <option value="">None</option>
            @foreach ($testimonials as $testimonial)
                <option value="{{ $testimonial->id }}" @selected((int) old('testimonial_id', $cs?->testimonial_id) === $testimonial->id)>{{ $testimonial->name }}: {{ Str::limit($testimonial->quote, 70) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="cs-content" class="{{ $label }}">Additional content (HTML)</label>
        <textarea id="cs-content" name="content" rows="6" class="{{ $input }} font-mono">{{ old('content', $cs?->content) }}</textarea>
    </div>
</div>

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Images</p>

    <x-admin.image-field name="image" label="Cover image" :current="$cs?->image" />

    <div>
        <label for="cs-image-alt" class="{{ $label }}">Cover image alt text</label>
        <input id="cs-image-alt" type="text" name="image_alt" value="{{ old('image_alt', $cs?->image_alt) }}" class="{{ $input }}">
    </div>

    @if ($cs && $cs->galleryImages() !== [])
        <div class="space-y-3">
            <p class="text-sm font-medium text-slate-700">Screenshots</p>
            @foreach ($cs->gallery as $index => $item)
                @continue(blank($item['image'] ?? null))
                <div class="flex flex-wrap items-center gap-3 rounded-lg border border-slate-200 p-3">
                    <img src="{{ asset('uploads/'.$item['image']) }}" alt="" class="h-14 w-24 rounded object-cover ring-1 ring-slate-100">
                    <input type="hidden" name="gallery[{{ $index }}][image]" value="{{ $item['image'] }}">
                    <input type="text" name="gallery[{{ $index }}][alt]" value="{{ old("gallery.$index.alt", $item['alt'] ?? '') }}" placeholder="Alt text" aria-label="Alt text for screenshot {{ $loop->iteration }}" class="{{ $input }} min-w-0 flex-1">
                    <label class="inline-flex items-center gap-1.5 text-xs font-medium text-rose-600">
                        <input type="hidden" name="gallery[{{ $index }}][remove]" value="0">
                        <input type="checkbox" name="gallery[{{ $index }}][remove]" value="1" class="rounded border-slate-300"> Remove
                    </label>
                </div>
            @endforeach
        </div>
    @endif

    <div>
        <label for="cs-gallery" class="{{ $label }}">Add screenshots</label>
        <input id="cs-gallery" type="file" name="gallery_uploads[]" accept="image/*" multiple
            class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-fuchsia-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-fuchsia-700 hover:file:bg-fuchsia-100">
    </div>
</div>

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">SEO</p>

    <div>
        <label for="cs-seo-title" class="{{ $label }}">SEO title</label>
        <input id="cs-seo-title" type="text" name="seo_title" value="{{ old('seo_title', $cs?->seo_title) }}" maxlength="255" class="{{ $input }}">
    </div>
    <div>
        <label for="cs-seo-description" class="{{ $label }}">Meta description</label>
        <textarea id="cs-seo-description" name="seo_description" rows="2" maxlength="500" class="{{ $input }}">{{ old('seo_description', $cs?->seo_description) }}</textarea>
    </div>
</div>

<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Home page card</p>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="cs-theme" class="{{ $label }}">Theme</label>
            <select id="cs-theme" name="theme" class="{{ $input }}">
                @foreach (['blue' => 'Blue', 'red' => 'Red', 'purple' => 'Purple'] as $value => $text)
                    <option value="{{ $value }}" @selected(old('theme', $cs?->theme) === $value)>{{ $text }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="cs-icon" class="{{ $label }}">Icon SVG path</label>
            <input id="cs-icon" type="text" name="icon" value="{{ old('icon', $cs?->icon) }}" placeholder="M9 17.25v1.007a3 3 0 0 1…" class="{{ $input }} font-mono">
        </div>
    </div>
</div>
