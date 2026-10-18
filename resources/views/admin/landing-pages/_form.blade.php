@php
    $lp = $landingPage ?? null;
    $content = old('content', $lp?->content ?? []);
    $cat = fn (string $key, $default = '') => data_get($content, $key, $default);
    $inputClass = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
    $selectedEnquiryService = old('enquiry_service', $lp?->enquiry_service);
@endphp

{{-- ───────────────────────── Basics ───────────────────────── --}}
<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="lp-title" class="mb-1 block text-sm font-medium text-slate-700">Title *</label>
            <input id="lp-title" type="text" name="title" value="{{ old('title', $lp?->title) }}" required class="{{ $inputClass }}">
        </div>
        <div>
            <label for="lp-slug" class="mb-1 block text-sm font-medium text-slate-700">URL slug (/lp/…) *</label>
            <input id="lp-slug" type="text" name="slug" value="{{ old('slug', $lp?->slug) }}" required class="{{ $inputClass }}">
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="lp-service" class="mb-1 block text-sm font-medium text-slate-700">Linked service (benefits, process, projects)</label>
            <select id="lp-service" name="service_id" class="{{ $inputClass }}">
                <option value="">— None —</option>
                @foreach ($serviceOptions as $service)
                    <option value="{{ $service->id }}" @selected((int) old('service_id', $lp?->service_id) === $service->id)>{{ $service->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="lp-enquiry-service" class="mb-1 block text-sm font-medium text-slate-700">Form service preset *</label>
            <select id="lp-enquiry-service" name="enquiry_service" required class="{{ $inputClass }}">
                <option value="">— Select —</option>
                @foreach (\App\Support\EnquiryServices::grouped() as $group => $services)
                    <optgroup label="{{ $group }}">
                        @foreach ($services as $service)
                            <option value="{{ $service }}" @selected($selectedEnquiryService === $service)>{{ $service }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="lp-sort" class="mb-1 block text-sm font-medium text-slate-700">Sort Order</label>
            <input id="lp-sort" type="number" name="sort_order" value="{{ old('sort_order', $lp?->sort_order ?? 0) }}" min="0" class="{{ $inputClass }}">
        </div>
        <div class="flex items-end gap-2 pb-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $lp?->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300">
            <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
        </div>
    </div>
</div>

{{-- Hero --}}
<x-admin.service-panel title="Hero">
    <div>
        <label for="lp-headline" class="mb-1 block text-sm font-medium text-slate-700">Headline *</label>
        <input id="lp-headline" type="text" name="headline" value="{{ old('headline', $lp?->headline) }}" required class="{{ $inputClass }}">
    </div>
    <div>
        <label for="lp-supporting" class="mb-1 block text-sm font-medium text-slate-700">Supporting message</label>
        <textarea id="lp-supporting" name="supporting_text" rows="3" class="{{ $inputClass }}">{{ old('supporting_text', $lp?->supporting_text) }}</textarea>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="lp-primary-cta" class="mb-1 block text-sm font-medium text-slate-700">Primary button label *</label>
            <input id="lp-primary-cta" type="text" name="primary_cta_label" value="{{ old('primary_cta_label', $lp?->primary_cta_label) }}" required class="{{ $inputClass }}">
        </div>
        <div>
            <label for="lp-secondary-cta" class="mb-1 block text-sm font-medium text-slate-700">Secondary button label *</label>
            <input id="lp-secondary-cta" type="text" name="secondary_cta_label" value="{{ old('secondary_cta_label', $lp?->secondary_cta_label) }}" required class="{{ $inputClass }}">
        </div>
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Selling-point bullets</label>
        @include('admin.services.partials._repeater', [
            'prefix' => 'content[bullets]',
            'rows' => $cat('bullets', []),
            'rowView' => 'admin.services.partials._text-row',
            'empty' => '',
            'extra' => ['placeholder' => 'Selling point'],
            'addLabel' => 'Add bullet',
        ])
    </div>
</x-admin.service-panel>

{{-- Enquiry form --}}
<x-admin.service-panel title="Enquiry Form">
    <div>
        <label for="lp-form-heading" class="mb-1 block text-sm font-medium text-slate-700">Form heading</label>
        <input id="lp-form-heading" type="text" name="content[form][heading]" value="{{ $cat('form.heading') }}" placeholder="Tell Us About Your Project" class="{{ $inputClass }}">
    </div>
    <div>
        <label for="lp-form-text" class="mb-1 block text-sm font-medium text-slate-700">Form intro</label>
        <textarea id="lp-form-text" name="content[form][text]" rows="2" class="{{ $inputClass }}">{{ $cat('form.text') }}</textarea>
    </div>
</x-admin.service-panel>

{{-- FAQ --}}
<x-admin.service-panel title="FAQ">
    <div>
        <label for="lp-faq-heading" class="mb-1 block text-sm font-medium text-slate-700">Heading</label>
        <input id="lp-faq-heading" type="text" name="content[faq][heading]" value="{{ $cat('faq.heading') }}" placeholder="Frequently Asked Questions" class="{{ $inputClass }}">
    </div>
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[faq][items]',
        'rows' => $cat('faq.items', []),
        'rowView' => 'admin.services.partials._faq-row',
        'addLabel' => 'Add question',
    ])
</x-admin.service-panel>

{{-- SEO --}}
<x-admin.service-panel title="SEO" :open="false">
    <div>
        <label for="lp-seo-title" class="mb-1 block text-sm font-medium text-slate-700">SEO Title</label>
        <input id="lp-seo-title" type="text" name="seo_title" value="{{ old('seo_title', $lp?->seo_title) }}" class="{{ $inputClass }}">
    </div>
    <div>
        <label for="lp-seo-description" class="mb-1 block text-sm font-medium text-slate-700">SEO Description</label>
        <textarea id="lp-seo-description" name="seo_description" rows="2" class="{{ $inputClass }}">{{ old('seo_description', $lp?->seo_description) }}</textarea>
    </div>
</x-admin.service-panel>
