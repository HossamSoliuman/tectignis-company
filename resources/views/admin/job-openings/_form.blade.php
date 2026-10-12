@php
    $j = $jobOpening ?? null;
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
    $label = 'mb-1 block text-sm font-medium text-slate-700';
    $hint = 'mt-1 text-xs text-slate-400';
@endphp

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <h3 class="text-sm font-semibold text-slate-800">Job details</h3>
    <div>
        <label for="title" class="{{ $label }}">Job Title *</label>
        <input type="text" id="title" name="title" value="{{ old('title', $j?->title) }}" required maxlength="255" class="{{ $input }}">
    </div>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="department" class="{{ $label }}">Department *</label>
            <input type="text" id="department" name="department" value="{{ old('department', $j?->department) }}" required maxlength="255" placeholder="e.g. Development" class="{{ $input }}">
        </div>
        <div>
            <label for="location" class="{{ $label }}">Location *</label>
            <input type="text" id="location" name="location" value="{{ old('location', $j?->location ?? 'Navi Mumbai, India') }}" required maxlength="255" class="{{ $input }}">
        </div>
        <div>
            <label for="employment_type" class="{{ $label }}">Employment Type *</label>
            <select id="employment_type" name="employment_type" required class="{{ $input }}">
                @foreach (\App\Models\JobOpening::EMPLOYMENT_TYPES as $type)
                    <option value="{{ $type }}" @selected(old('employment_type', $j?->employment_type ?? 'Full-time') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="experience" class="{{ $label }}">Experience *</label>
            <input type="text" id="experience" name="experience" value="{{ old('experience', $j?->experience) }}" required maxlength="255" placeholder="e.g. 3–5 years" class="{{ $input }}">
        </div>
    </div>
    <div>
        <label for="skills" class="{{ $label }}">Required Skills *</label>
        <textarea id="skills" name="skills" rows="3" required maxlength="2000" placeholder="One per line, or comma-separated" class="{{ $input }}">{{ old('skills', $j?->skills) }}</textarea>
    </div>
</div>

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <h3 class="text-sm font-semibold text-slate-800">Posting content</h3>
    <div>
        <label for="description" class="{{ $label }}">Job Description</label>
        <textarea id="description" name="description" rows="8" class="{{ $input }} font-mono">{{ old('description', $j?->description) }}</textarea>
        <p class="{{ $hint }}">HTML is supported, e.g. &lt;p&gt;, &lt;strong&gt;, &lt;ul&gt;.</p>
    </div>
    @foreach (['responsibilities' => 'Responsibilities', 'requirements' => 'Requirements', 'benefits' => 'Benefits'] as $field => $fieldLabel)
        <div>
            <label for="{{ $field }}" class="{{ $label }}">{{ $fieldLabel }}</label>
            <textarea id="{{ $field }}" name="{{ $field }}" rows="5" class="{{ $input }}">{{ old($field, $j?->{$field}) }}</textarea>
            <p class="{{ $hint }}">One item per line is shown as a bullet list; HTML is also accepted.</p>
        </div>
    @endforeach
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="salary" class="{{ $label }}">Salary</label>
            <input type="text" id="salary" name="salary" value="{{ old('salary', $j?->salary) }}" maxlength="255" placeholder="e.g. ₹6–9 LPA" class="{{ $input }}">
            <label class="mt-2 flex items-center gap-2 text-sm text-slate-700">
                <input type="hidden" name="show_salary" value="0">
                <input type="checkbox" name="show_salary" value="1" class="rounded border-slate-300" @checked(old('show_salary', $j?->show_salary))>
                Salary approved for the public page
            </label>
        </div>
        <div>
            <label for="application_deadline" class="{{ $label }}">Application Deadline</label>
            <input type="date" id="application_deadline" name="application_deadline" value="{{ old('application_deadline', $j?->application_deadline?->format('Y-m-d')) }}" class="{{ $input }}">
            <p class="{{ $hint }}">The posting stops taking applications after this date.</p>
        </div>
    </div>
</div>

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <h3 class="text-sm font-semibold text-slate-800">Publishing &amp; SEO</h3>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="status" class="{{ $label }}">Status *</label>
            <select id="status" name="status" required class="{{ $input }}">
                @foreach (\App\Enums\JobOpeningStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $j?->status?->value ?? 'draft') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
            <p class="{{ $hint }}">Draft is hidden; Closed keeps the page but stops applications.</p>
        </div>
        <div>
            <label for="slug" class="{{ $label }}">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $j?->slug) }}" maxlength="255" placeholder="Generated from the title" class="{{ $input }}">
            @if ($j?->slug)
                <p class="{{ $hint }}">{{ route('careers.show', $j) }}</p>
            @endif
        </div>
        <div>
            <label for="icon" class="{{ $label }}">Icon (Font Awesome class)</label>
            <input type="text" id="icon" name="icon" value="{{ old('icon', $j?->icon) }}" maxlength="255" placeholder="fas fa-code" class="{{ $input }}">
        </div>
        <div>
            <label for="sort_order" class="{{ $label }}">Sort Order</label>
            <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $j?->sort_order ?? 0) }}" min="0" class="{{ $input }}">
        </div>
    </div>
    <div>
        <label for="seo_title" class="{{ $label }}">SEO Title</label>
        <input type="text" id="seo_title" name="seo_title" value="{{ old('seo_title', $j?->seo_title) }}" maxlength="255" placeholder="Defaults to the job title and location" class="{{ $input }}">
    </div>
    <div>
        <label for="seo_description" class="{{ $label }}">SEO Description</label>
        <textarea id="seo_description" name="seo_description" rows="2" maxlength="500" placeholder="Defaults to the start of the job description" class="{{ $input }}">{{ old('seo_description', $j?->seo_description) }}</textarea>
    </div>
</div>
