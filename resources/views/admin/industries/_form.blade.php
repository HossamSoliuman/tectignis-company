@php
    $s = $industry ?? null;
    $content = old('content', $s?->content ?? []);
    $cat = fn (string $key, $default = '') => data_get($content, $key, $default);

    $inputClass = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
@endphp

{{-- ───────────────────────── Basics ───────────────────────── --}}
<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Name *</label>
            <input type="text" name="name" value="{{ old('name', $s?->name) }}" required class="{{ $inputClass }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Slug *</label>
            <input type="text" name="slug" value="{{ old('slug', $s?->slug) }}" required class="{{ $inputClass }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Icon (FontAwesome class)</label>
            <input type="text" name="icon" value="{{ old('icon', $s?->icon) }}" placeholder="fas fa-building" class="{{ $inputClass }} font-mono">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Short Description</label>
        <textarea name="short_description" rows="2" class="{{ $inputClass }}">{{ old('short_description', $s?->short_description) }}</textarea>
        <p class="mt-1 text-xs text-slate-400">Used as the meta description fallback and on the industries index card.</p>
    </div>
</div>

{{-- ───────────────────────── Page sections ───────────────────────── --}}
<p class="px-1 pt-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Landing page sections</p>

{{-- A · Hero --}}
<x-admin.service-panel title="Hero" subtitle="Top banner: eyebrow, headline, intro, feature grid, badges & buttons. The banner image is set in the Details panel.">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Eyebrow</label>
            <input type="text" name="content[hero][eyebrow]" value="{{ $cat('hero.eyebrow') }}" placeholder="CORPORATE OFFICE IT SOLUTIONS" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Highlighted word(s)</label>
            <input type="text" name="content[hero][highlight]" value="{{ $cat('hero.highlight') }}" placeholder="Corporate Offices." class="{{ $inputClass }}">
            <p class="mt-1 text-xs text-slate-400">If this text appears in the heading it is shown in the pink accent colour.</p>
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Heading</label>
        <input type="text" name="content[hero][heading]" value="{{ $cat('hero.heading') }}" placeholder="Smart Technology. Stronger Corporate Offices." class="{{ $inputClass }}">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Intro paragraph</label>
        <textarea name="content[hero][intro]" rows="3" class="{{ $inputClass }}">{{ $cat('hero.intro') }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Primary button label</label>
            <input type="text" name="content[hero][cta_primary_label]" value="{{ $cat('hero.cta_primary_label') }}" placeholder="Talk to an Expert" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Secondary button label</label>
            <input type="text" name="content[hero][cta_secondary_label]" value="{{ $cat('hero.cta_secondary_label') }}" placeholder="Explore Solutions" class="{{ $inputClass }}">
        </div>
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Feature grid (small items beside the heading)</label>
        @include('admin.services.partials._repeater', [
            'prefix' => 'content[hero][features]',
            'rows' => $cat('hero.features', []),
            'rowView' => 'admin.services.partials._feature-row',
            'addLabel' => 'Add feature',
        ])
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Floating badges (over the hero image)</label>
        @include('admin.services.partials._repeater', [
            'prefix' => 'content[hero][badges]',
            'rows' => $cat('hero.badges', []),
            'rowView' => 'admin.services.partials._feature-row',
            'addLabel' => 'Add badge',
        ])
    </div>
</x-admin.service-panel>

{{-- B · Trust logos --}}
<x-admin.service-panel title="Trust logos" :section="'trust'" :content="$content"
    note="Shows the global brand logos managed under Brands. Toggle off to hide the row on this industry." />

{{-- C · Challenges --}}
<x-admin.service-panel title="Challenges (split — left column)" :section="'challenges'" :content="$content">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Eyebrow</label>
            <input type="text" name="content[challenges][eyebrow]" value="{{ $cat('challenges.eyebrow') }}" placeholder="CHALLENGES IN CORPORATE OFFICES" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Heading</label>
            <input type="text" name="content[challenges][heading]" value="{{ $cat('challenges.heading') }}" placeholder="Modern Workplaces. New-Age Challenges." class="{{ $inputClass }}">
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Intro paragraph</label>
        <textarea name="content[challenges][subtitle]" rows="2" class="{{ $inputClass }}">{{ $cat('challenges.subtitle') }}</textarea>
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Challenge points (ticked list)</label>
        @include('admin.services.partials._repeater', [
            'prefix' => 'content[challenges][items]',
            'rows' => $cat('challenges.items', []),
            'rowView' => 'admin.services.partials._text-row',
            'empty' => '',
            'extra' => ['placeholder' => 'A challenge this industry faces'],
            'addLabel' => 'Add challenge',
        ])
    </div>
</x-admin.service-panel>

{{-- D · Solutions --}}
<x-admin.service-panel title="Solutions (split — right column cards)" :section="'solutions'" :content="$content">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Eyebrow</label>
            <input type="text" name="content[solutions][eyebrow]" value="{{ $cat('solutions.eyebrow') }}" placeholder="HOW WE HELP" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Heading</label>
            <input type="text" name="content[solutions][heading]" value="{{ $cat('solutions.heading') }}" placeholder="IT Solutions for High-Performing Corporate Offices" class="{{ $inputClass }}">
        </div>
    </div>
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[solutions][cards]',
        'rows' => $cat('solutions.cards', []),
        'rowView' => 'admin.services.partials._card-row',
        'addLabel' => 'Add solution card',
    ])
</x-admin.service-panel>

{{-- E · Performance stats --}}
<x-admin.service-panel title="Performance stats" :section="'stats'" :content="$content">
    <x-admin.section-heading-fields prefix="stats" :content="$content" :inputClass="$inputClass" />
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[stats][items]',
        'rows' => $cat('stats.items', []),
        'rowView' => 'admin.industries.partials._stat-row',
        'addLabel' => 'Add stat',
    ])
</x-admin.service-panel>

{{-- F · Success stories --}}
<x-admin.service-panel title="Success stories" :section="'case_studies'" :content="$content"
    note="Shows the 3 most recent active case studies, managed under Case Studies.">
    <x-admin.section-heading-fields prefix="case_studies" :content="$content" :inputClass="$inputClass" />
</x-admin.service-panel>

{{-- G · Solutions grid --}}
<x-admin.service-panel title="Solutions grid (compact icon + title list)" :section="'solutions_grid'" :content="$content">
    <x-admin.section-heading-fields prefix="solutions_grid" :content="$content" :inputClass="$inputClass" />
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[solutions_grid][items]',
        'rows' => $cat('solutions_grid.items', []),
        'rowView' => 'admin.services.partials._feature-row',
        'addLabel' => 'Add item',
    ])
</x-admin.service-panel>

{{-- H · FAQ + mid CTA --}}
<x-admin.service-panel title="FAQ + mid-page CTA" :section="'faq'" :content="$content">
    <x-admin.section-heading-fields prefix="faq" :content="$content" :inputClass="$inputClass" />
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[faq][items]',
        'rows' => $cat('faq.items', []),
        'rowView' => 'admin.services.partials._faq-row',
        'addLabel' => 'Add question',
    ])
    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 space-y-3">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Side CTA box</p>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">CTA heading</label>
            <input type="text" name="content[faq][cta_heading]" value="{{ $cat('faq.cta_heading') }}" placeholder="Ready to Transform Your Corporate Office?" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">CTA text</label>
            <textarea name="content[faq][cta_text]" rows="2" class="{{ $inputClass }}">{{ $cat('faq.cta_text') }}</textarea>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">CTA button label</label>
            <input type="text" name="content[faq][cta_label]" value="{{ $cat('faq.cta_label') }}" placeholder="Get Started Today" class="{{ $inputClass }}">
        </div>
    </div>
</x-admin.service-panel>

{{-- I · Bottom CTA band --}}
<x-admin.service-panel title="Bottom CTA band" :section="'cta_band'" :content="$content">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Headline</label>
            <input type="text" name="content[cta_band][heading]" value="{{ $cat('cta_band.heading') }}" placeholder="Let's Build Smarter Workplaces, Together" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Subtitle</label>
            <input type="text" name="content[cta_band][subtitle]" value="{{ $cat('cta_band.subtitle') }}" placeholder="Partner with our experts..." class="{{ $inputClass }}">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Primary button label</label>
            <input type="text" name="content[cta_band][button_primary_label]" value="{{ $cat('cta_band.button_primary_label') }}" placeholder="Talk to an Expert" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Secondary button label</label>
            <input type="text" name="content[cta_band][button_secondary_label]" value="{{ $cat('cta_band.button_secondary_label') }}" placeholder="Request a Consultation" class="{{ $inputClass }}">
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
        <p class="mb-1 text-xs text-slate-400">When filled, this raw HTML replaces the default landing-page layout. Leave empty to use the standard template.</p>
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
