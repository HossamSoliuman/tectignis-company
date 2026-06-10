@php
    $s = $solution ?? null;
    $content = old('content', $s?->content ?? []);
    $cat = fn (string $key, $default = '') => data_get($content, $key, $default);

    $inputClass = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
@endphp

{{-- ───────────────────────── Basics ───────────────────────── --}}
<div class="rounded-xl border border-slate-200 bg-white p-6 space-y-5">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title', $s?->title) }}" required class="{{ $inputClass }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Slug *</label>
            <input type="text" name="slug" value="{{ old('slug', $s?->slug) }}" required class="{{ $inputClass }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Icon (FontAwesome class)</label>
            <input type="text" name="icon" value="{{ old('icon', $s?->icon) }}" placeholder="fas fa-cogs" class="{{ $inputClass }} font-mono">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Short Description *</label>
        <textarea name="short_description" rows="2" required class="{{ $inputClass }}">{{ old('short_description', $s?->short_description) }}</textarea>
        <p class="mt-1 text-xs text-slate-400">Used as the meta description fallback and on the solutions index card.</p>
    </div>
</div>

{{-- ───────────────────────── Page sections ───────────────────────── --}}
<p class="px-1 pt-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Landing page sections</p>

{{-- A · Hero --}}
<x-admin.service-panel title="Hero" subtitle="Top banner: badge, headline, intro, benefits list, buttons & floating badges. The banner image is set in the Details panel.">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Badge / eyebrow</label>
            <input type="text" name="content[hero][eyebrow]" value="{{ $cat('hero.eyebrow') }}" placeholder="ERP SOLUTIONS" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Highlighted word(s)</label>
            <input type="text" name="content[hero][highlight]" value="{{ $cat('hero.highlight') }}" placeholder="Smart ERP Solutions" class="{{ $inputClass }}">
            <p class="mt-1 text-xs text-slate-400">If this text appears in the heading it is shown in the pink accent colour.</p>
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Heading</label>
        <input type="text" name="content[hero][heading]" value="{{ $cat('hero.heading') }}" placeholder="Smart ERP Solutions to Power Your Business End-to-End" class="{{ $inputClass }}">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Intro paragraph</label>
        <textarea name="content[hero][intro]" rows="3" class="{{ $inputClass }}">{{ $cat('hero.intro') }}</textarea>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Primary button label</label>
            <input type="text" name="content[hero][cta_primary_label]" value="{{ $cat('hero.cta_primary_label') }}" placeholder="Request Free Consultation" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Secondary button label</label>
            <input type="text" name="content[hero][cta_secondary_label]" value="{{ $cat('hero.cta_secondary_label') }}" placeholder="Talk to Our Expert" class="{{ $inputClass }}">
        </div>
    </div>
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Benefits list (shown beside the heading)</label>
        @include('admin.services.partials._repeater', [
            'prefix' => 'content[hero][benefits]',
            'rows' => $cat('hero.benefits', []),
            'rowView' => 'admin.services.partials._text-row',
            'empty' => '',
            'extra' => ['placeholder' => 'e.g. Unified Business Management'],
            'addLabel' => 'Add benefit',
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

{{-- B · Statistics --}}
<x-admin.service-panel title="Statistics (KPI cards)" :section="'stats'" :content="$content">
    <x-admin.section-heading-fields prefix="stats" :content="$content" :inputClass="$inputClass" />
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[stats][items]',
        'rows' => $cat('stats.items', []),
        'rowView' => 'admin.industries.partials._stat-row',
        'addLabel' => 'Add stat',
    ])
</x-admin.service-panel>

{{-- C · Modules --}}
<x-admin.service-panel title="Modules grid" :section="'modules'" :content="$content"
    subtitle="Feature cards with icon, title & description (e.g. ERP modules). Each card shows a Learn More link.">
    <x-admin.section-heading-fields prefix="modules" :content="$content" :inputClass="$inputClass" />
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[modules][cards]',
        'rows' => $cat('modules.cards', []),
        'rowView' => 'admin.services.partials._card-row',
        'addLabel' => 'Add module',
    ])
</x-admin.service-panel>

{{-- D · Benefits --}}
<x-admin.service-panel title="Benefits grid" :section="'benefits'" :content="$content"
    subtitle="Icon + title + short description cards highlighting the benefits of this solution.">
    <x-admin.section-heading-fields prefix="benefits" :content="$content" :inputClass="$inputClass" />
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[benefits][items]',
        'rows' => $cat('benefits.items', []),
        'rowView' => 'admin.services.partials._card-row',
        'addLabel' => 'Add benefit',
    ])
</x-admin.service-panel>

{{-- E · Implementation process --}}
<x-admin.service-panel title="Implementation process" :section="'process'" :content="$content">
    <x-admin.section-heading-fields prefix="process" :content="$content" :inputClass="$inputClass" />
    @include('admin.services.partials._repeater', [
        'prefix' => 'content[process][steps]',
        'rows' => $cat('process.steps', []),
        'rowView' => 'admin.services.partials._card-row',
        'extra' => ['titlePlaceholder' => 'Step title'],
        'addLabel' => 'Add step',
    ])
</x-admin.service-panel>

{{-- F · Industry solutions --}}
<x-admin.service-panel title="Industry solutions" :section="'industries'" :content="$content"
    note="Shows the active industries managed under Industries. Edit the heading and CTA label here.">
    <x-admin.section-heading-fields prefix="industries" :content="$content" :inputClass="$inputClass" />
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">CTA button label</label>
        <input type="text" name="content[industries][cta_label]" value="{{ $cat('industries.cta_label') }}" placeholder="View All Industries" class="{{ $inputClass }}">
    </div>
</x-admin.service-panel>

{{-- G · Why choose --}}
<x-admin.service-panel title="Why choose us (features + testimonial)" :section="'why_choose'" :content="$content">
    <x-admin.section-heading-fields prefix="why_choose" :content="$content" :inputClass="$inputClass" />
    <div>
        <label class="mb-2 block text-sm font-medium text-slate-700">Feature points (ticked list)</label>
        @include('admin.services.partials._repeater', [
            'prefix' => 'content[why_choose][points]',
            'rows' => $cat('why_choose.points', []),
            'rowView' => 'admin.services.partials._text-row',
            'empty' => '',
            'extra' => ['placeholder' => 'Reason to choose us'],
            'addLabel' => 'Add feature',
        ])
    </div>
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <x-admin.image-field name="content[why_choose][image_file]" label="Supporting image" :current="$cat('why_choose.image') ?: null" />
    </div>
    <input type="hidden" name="content[why_choose][image]" value="{{ $cat('why_choose.image') }}">

    <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 space-y-3">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Testimonial card</p>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Quote</label>
            <textarea name="content[why_choose][testimonial_quote]" rows="3" class="{{ $inputClass }}">{{ $cat('why_choose.testimonial_quote') }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Author</label>
                <input type="text" name="content[why_choose][testimonial_author]" value="{{ $cat('why_choose.testimonial_author') }}" placeholder="Operations Head" class="{{ $inputClass }}">
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Author role / company</label>
                <input type="text" name="content[why_choose][testimonial_role]" value="{{ $cat('why_choose.testimonial_role') }}" placeholder="Leading Manufacturing Company" class="{{ $inputClass }}">
            </div>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Link label (optional)</label>
            <input type="text" name="content[why_choose][testimonial_cta_label]" value="{{ $cat('why_choose.testimonial_cta_label') }}" placeholder="View Case Study" class="{{ $inputClass }}">
        </div>
    </div>
</x-admin.service-panel>

{{-- H · Final CTA band --}}
<x-admin.service-panel title="Final CTA band" :section="'cta_band'" :content="$content">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Headline</label>
            <input type="text" name="content[cta_band][heading]" value="{{ $cat('cta_band.heading') }}" placeholder="Ready to Transform Your Business?" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Subtitle</label>
            <input type="text" name="content[cta_band][subtitle]" value="{{ $cat('cta_band.subtitle') }}" placeholder="Let's build an intelligent, integrated business together." class="{{ $inputClass }}">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Primary button label</label>
            <input type="text" name="content[cta_band][button_primary_label]" value="{{ $cat('cta_band.button_primary_label') }}" placeholder="Request Free Consultation" class="{{ $inputClass }}">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Secondary button label</label>
            <input type="text" name="content[cta_band][button_secondary_label]" value="{{ $cat('cta_band.button_secondary_label') }}" placeholder="Talk To Our Expert" class="{{ $inputClass }}">
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
        <p class="mb-1 text-xs text-slate-400">When filled (and no landing-page sections are set), this raw HTML replaces the default detail-page layout. Leave empty to use the standard template.</p>
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
