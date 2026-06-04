@php
    $s = $service ?? null;
    $content = old('content', $s?->content ?? []);
    $cat = fn (string $key, $default = '') => data_get($content, $key, $default);

    $catalogTech = $techStacks ?? collect();
    $catalogIndustries = $industries ?? collect();
    $selectedTech = collect(old('tech_stacks', $s ? $s->techStacks->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
    $selectedIndustries = collect(old('industries', $s ? $s->industries->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();

    $categoryLabels = \App\Models\Service::CATEGORY_LABELS;

    $inputClass = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
@endphp

{{-- ───────────────────────── Basics ───────────────────────── --}}
<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title', $s?->title) }}" required class="{{ $inputClass }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Slug *</label>
            <input type="text" name="slug" value="{{ old('slug', $s?->slug) }}" required class="{{ $inputClass }}">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
            <select name="category" class="{{ $inputClass }}">
                <option value="">— Select category —</option>
                @foreach ($categoryLabels as $value => $label)
                    <option value="{{ $value }}" @selected(old('category', $s?->category) === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $s?->sort_order ?? 0) }}" min="0" class="{{ $inputClass }}">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Short Description *</label>
        <textarea name="short_description" rows="2" required class="{{ $inputClass }}">{{ old('short_description', $s?->short_description) }}</textarea>
    </div>

    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <x-admin.image-field name="icon" label="Icon Image" :current="$s?->icon" />
        <x-admin.image-field name="banner_image" label="Hero / Banner Image" :current="$s?->banner_image" />
    </div>

    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $s?->is_active ?? true) ? 'checked' : '' }} class="rounded border-slate-300">
        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
    </div>
</div>

{{-- ───────────────────────── Page sections ───────────────────────── --}}
<p class="px-1 pt-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Landing page sections</p>

{{-- A · Hero --}}
<x-admin.service-panel title="Hero" subtitle="Top banner: eyebrow, headline, intro & call-to-action.">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Eyebrow</label>
            <input type="text" name="content[hero][eyebrow]" value="{{ $cat('hero.eyebrow') }}" placeholder="SOFTWARE DEVELOPMENT" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Heading</label>
            <input type="text" name="content[hero][heading]" value="{{ $cat('hero.heading') }}" placeholder="{Service} Services in Navi Mumbai" class="{{ $inputClass }}">
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Intro paragraph</label>
        <textarea name="content[hero][intro]" rows="3" class="{{ $inputClass }}">{{ $cat('hero.intro') }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Primary button label</label>
            <input type="text" name="content[hero][cta_primary_label]" value="{{ $cat('hero.cta_primary_label') }}" placeholder="Get Started" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Secondary button label</label>
            <input type="text" name="content[hero][cta_secondary_label]" value="{{ $cat('hero.cta_secondary_label') }}" placeholder="Talk to an Expert" class="{{ $inputClass }}">
        </div>
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Selling-point bullets</label>
        @include('admin.services.partials._repeater', [
            'prefix' => 'content[hero][bullets]',
            'rows' => $cat('hero.bullets', []),
            'rowView' => 'admin.services.partials._text-row',
            'empty' => '',
            'extra' => ['placeholder' => 'Selling point'],
            'addLabel' => 'Add bullet',
        ])
    </div>
</x-admin.service-panel>

{{-- B · Feature strip --}}
<x-admin.service-panel title="Feature / Trust strip" :section="'features_strip'" :content="$content">
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[features_strip][items]',
        'rows' => $cat('features_strip.items', []),
        'rowView' => 'admin.services.partials._feature-row',
        'addLabel' => 'Add feature',
    ])
</x-admin.service-panel>

{{-- C · Sub-services --}}
<x-admin.service-panel title="Our Services (sub-services grid)" :section="'sub_services'" :content="$content">
    <x-admin.section-heading-fields prefix="sub_services" :content="$content" :inputClass="$inputClass" />
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[sub_services][items]',
        'rows' => $cat('sub_services.items', []),
        'rowView' => 'admin.services.partials._card-row',
        'addLabel' => 'Add service card',
    ])
</x-admin.service-panel>

{{-- D · Process --}}
<x-admin.service-panel title="Process stepper" :section="'process'" :content="$content">
    <x-admin.section-heading-fields prefix="process" :content="$content" :inputClass="$inputClass" />
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[process][steps]',
        'rows' => $cat('process.steps', []),
        'rowView' => 'admin.services.partials._card-row',
        'extra' => ['titlePlaceholder' => 'Step title'],
        'addLabel' => 'Add step',
    ])
</x-admin.service-panel>

{{-- E · Technologies --}}
<x-admin.service-panel title="Technologies We Use" :section="'tech'" :content="$content"
    note="Pick which technologies appear. Leave all unchecked to show every active technology.">
    <x-admin.section-heading-fields prefix="tech" :content="$content" :inputClass="$inputClass" />
    <x-admin.attach-list name="tech_stacks" :items="$catalogTech" :selected="$selectedTech" empty="No technologies in the catalog yet — add them under Tech Stack." />
</x-admin.service-panel>

{{-- F · Industries --}}
<x-admin.service-panel title="Industries We Serve" :section="'industries'" :content="$content"
    note="Pick which industries appear. Leave all unchecked to show every active industry.">
    <x-admin.section-heading-fields prefix="industries" :content="$content" :inputClass="$inputClass" />
    <x-admin.attach-list name="industries" :items="$catalogIndustries" :selected="$selectedIndustries" empty="No industries in the catalog yet — add them under Industries." />
</x-admin.service-panel>

{{-- G · Case studies --}}
<x-admin.service-panel title="Recent Success Stories" :section="'case_studies'" :content="$content"
    note="Shows the 3 most recent active case studies, managed under Case Studies.">
    <x-admin.section-heading-fields prefix="case_studies" :content="$content" :inputClass="$inputClass" />
</x-admin.service-panel>

{{-- H · Why choose --}}
<x-admin.service-panel title="Why Choose Tectignis" :section="'why_choose'" :content="$content">
    <x-admin.section-heading-fields prefix="why_choose" :content="$content" :inputClass="$inputClass" />
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Inline button label</label>
            <input type="text" name="content[why_choose][cta_label]" value="{{ $cat('why_choose.cta_label') }}" placeholder="Get a Quote" class="{{ $inputClass }}">
        </div>
        <x-admin.image-field name="content[why_choose][image_file]" label="Supporting image" :current="$cat('why_choose.image') ?: null" />
    </div>
    <input type="hidden" name="content[why_choose][image]" value="{{ $cat('why_choose.image') }}">
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Reasons (ticked points)</label>
        @include('admin.services.partials._repeater', [
            'prefix' => 'content[why_choose][points]',
            'rows' => $cat('why_choose.points', []),
            'rowView' => 'admin.services.partials._text-row',
            'empty' => '',
            'extra' => ['placeholder' => 'Reason to choose us'],
            'addLabel' => 'Add reason',
        ])
    </div>
</x-admin.service-panel>

{{-- I · FAQ --}}
<x-admin.service-panel title="FAQ" :section="'faq'" :content="$content">
    <x-admin.section-heading-fields prefix="faq" :content="$content" :inputClass="$inputClass" />
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[faq][items]',
        'rows' => $cat('faq.items', []),
        'rowView' => 'admin.services.partials._faq-row',
        'addLabel' => 'Add question',
    ])
</x-admin.service-panel>

{{-- J · CTA band --}}
<x-admin.service-panel title="CTA band" :section="'cta_band'" :content="$content">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Headline</label>
            <input type="text" name="content[cta_band][heading]" value="{{ $cat('cta_band.heading') }}" placeholder="Ready to Kickstart Your Journey?" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Button label</label>
            <input type="text" name="content[cta_band][button_label]" value="{{ $cat('cta_band.button_label') }}" placeholder="Get Started" class="{{ $inputClass }}">
        </div>
    </div>
</x-admin.service-panel>

{{-- ───────────────────────── Advanced ───────────────────────── --}}
<x-admin.service-panel title="Advanced & SEO" :open="false">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Full Description (HTML)</label>
        <textarea name="description" rows="5" class="{{ $inputClass }} font-mono">{{ old('description', $s?->description) }}</textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Custom Page HTML (body)</label>
        <p class="mb-1 text-xs text-slate-400">When filled, this raw HTML replaces the default detail-page layout. Leave empty to use the standard template.</p>
        <textarea name="body" rows="8" class="{{ $inputClass }} font-mono">{{ old('body', $s?->body) }}</textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">SEO Title</label>
        <input type="text" name="seo_title" value="{{ old('seo_title', $s?->seo_title) }}" class="{{ $inputClass }}">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">SEO Description</label>
        <textarea name="seo_description" rows="2" class="{{ $inputClass }}">{{ old('seo_description', $s?->seo_description) }}</textarea>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">SEO Keywords</label>
        <input type="text" name="seo_keywords" value="{{ old('seo_keywords', $s?->seo_keywords) }}" class="{{ $inputClass }}">
    </div>
</x-admin.service-panel>
