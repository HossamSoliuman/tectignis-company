@php
    $p = $product ?? null;
    $field = 'w-full rounded-lg border border-slate-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
    $input = $field.' h-10 px-3';
    $textarea = $field.' px-3 py-2 leading-relaxed';
    $label = 'block text-sm font-medium text-slate-700 mb-1';
    $fileInput = 'block w-full rounded-lg border-2 border-dashed border-slate-200 p-3 text-sm text-slate-600 transition file:mr-3 file:rounded-lg file:border-0 file:bg-fuchsia-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-fuchsia-700 hover:border-fuchsia-300 hover:file:bg-fuchsia-100';
    $features = old('features') !== null
        ? (is_array(old('features')) ? implode("\n", old('features')) : (string) old('features'))
        : implode("\n", $p?->features ?? []);
@endphp

<x-admin.form-section title="Product" icon="cube">
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="product-name" class="{{ $label }}">Name *</label>
            <input id="product-name" type="text" name="name" value="{{ old('name', $p?->name) }}" required placeholder="TenderSet" class="{{ $input }}">
        </div>
        <div>
            <label for="product-icon" class="{{ $label }}">Icon (Font Awesome class)</label>
            <input id="product-icon" type="text" name="icon" value="{{ old('icon', $p?->icon) }}" placeholder="fas fa-file-contract" class="{{ $input }} font-mono">
            <p class="mt-1 text-xs text-slate-400">e.g. <code>fas fa-qrcode</code>. See fontawesome.com/icons.</p>
        </div>
    </div>

    <div>
        <label for="product-description" class="{{ $label }}">Description *</label>
        <textarea id="product-description" name="description" rows="3" required class="{{ $textarea }}">{{ old('description', $p?->description) }}</textarea>
    </div>

    <div>
        <label for="product-features" class="{{ $label }}">Key features (one per line, up to 6)</label>
        <textarea id="product-features" name="features" rows="4" placeholder="Tenders from GeM, CPPP and state portals&#10;Email and WhatsApp alerts" class="{{ $textarea }}">{{ $features }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="product-url" class="{{ $label }}">Website</label>
            <input id="product-url" type="url" name="url" value="{{ old('url', $p?->url) }}" placeholder="https://tenderset.in" class="{{ $input }}">
        </div>
        <div>
            <label for="product-case-study" class="{{ $label }}">Case study</label>
            <select id="product-case-study" name="case_study_id" class="{{ $field }} h-10 px-2">
                <option value="">None</option>
                @foreach ($caseStudies as $id => $title)
                    <option value="{{ $id }}" @selected((int) old('case_study_id', $p?->case_study_id) === $id)>{{ $title }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="flex flex-wrap items-end gap-6">
        <div>
            <label for="product-sort-order" class="{{ $label }}">Sort Order</label>
            <input id="product-sort-order" type="number" name="sort_order" value="{{ old('sort_order', $p?->sort_order ?? 0) }}" min="0" class="{{ $input }} w-40">
        </div>
        <div class="flex h-10 items-center gap-2">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" id="is_active" name="is_active" value="1"
                {{ old('is_active', $p?->is_active ?? true) ? 'checked' : '' }}
                class="rounded border-slate-300">
            <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
        </div>
    </div>
</x-admin.form-section>

<x-admin.form-section title="Screenshots" icon="photo-up">
    @if ($p && $p->galleryImages() !== [])
        <div class="grid gap-3 sm:grid-cols-2">
            @foreach ($p->gallery as $index => $item)
                @continue(blank($item['image'] ?? null))
                <div class="overflow-hidden rounded-lg border border-slate-200 transition has-checked:border-rose-300 has-checked:bg-rose-50">
                    <img src="{{ asset('uploads/'.$item['image']) }}" alt="" class="aspect-[16/10] w-full bg-slate-50 object-cover object-top">
                    <div class="space-y-2 p-3">
                        <input type="hidden" name="gallery[{{ $index }}][image]" value="{{ $item['image'] }}">
                        <input type="text" name="gallery[{{ $index }}][alt]" value="{{ old("gallery.$index.alt", $item['alt'] ?? '') }}" placeholder="Caption" aria-label="Caption for screenshot {{ $loop->iteration }}" class="{{ $input }}">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs text-slate-400">{{ $loop->first ? 'Main preview' : 'Screenshot '.$loop->iteration }}</span>
                            <label class="inline-flex items-center gap-1.5 text-xs font-medium text-rose-600">
                                <input type="hidden" name="gallery[{{ $index }}][remove]" value="0">
                                <input type="checkbox" name="gallery[{{ $index }}][remove]" value="1" class="rounded border-slate-300"> Remove
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div>
        <label for="product-gallery" class="{{ $label }}">Add screenshots</label>
        <input id="product-gallery" type="file" name="gallery_uploads[]" accept="image/*" multiple class="{{ $fileInput }}">
        <p class="mt-1 text-xs text-slate-400">The first screenshot is the main preview. Wide 16:10 images (e.g. 1600 × 1000) look best. Up to 4 MB each.</p>
    </div>
</x-admin.form-section>

<x-admin.form-section title="Videos" icon="video-camera">
    @if ($p && ! empty($p->videos))
        <ul class="divide-y divide-slate-100 rounded-lg border border-slate-200">
            @foreach ($p->videos as $index => $video)
                @continue(blank($video['source'] ?? null))
                <li class="grid gap-2 p-3 transition has-checked:bg-rose-50 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-center">
                    <div class="min-w-0 space-y-1.5">
                        <input type="hidden" name="videos[{{ $index }}][source]" value="{{ $video['source'] }}">
                        <input type="text" name="videos[{{ $index }}][title]" value="{{ old("videos.$index.title", $video['title'] ?? '') }}" placeholder="Title" aria-label="Title for video {{ $loop->iteration }}" class="{{ $input }}">
                        <p class="truncate text-xs text-slate-400">
                            @if (str_starts_with($video['source'], 'http'))
                                <a href="{{ $video['source'] }}" target="_blank" rel="noopener" class="hover:text-fuchsia-600 hover:underline">{{ $video['source'] }}</a>
                            @else
                                <a href="{{ asset('uploads/'.$video['source']) }}" target="_blank" rel="noopener" class="hover:text-fuchsia-600 hover:underline">Uploaded file: {{ basename($video['source']) }}</a>
                            @endif
                        </p>
                    </div>
                    <label class="inline-flex items-center gap-1.5 text-xs font-medium text-rose-600">
                        <input type="hidden" name="videos[{{ $index }}][remove]" value="0">
                        <input type="checkbox" name="videos[{{ $index }}][remove]" value="1" class="rounded border-slate-300"> Remove
                    </label>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="space-y-4 rounded-lg bg-slate-50 p-4">
        <p class="text-sm font-medium text-slate-700">Add a video</p>
        <div>
            <label for="product-video-title" class="{{ $label }}">Title</label>
            <input id="product-video-title" type="text" name="new_video[title]" value="{{ old('new_video.title') }}" maxlength="255" placeholder="TenderSet product tour" class="{{ $input }}">
        </div>
        <div>
            <label for="product-video-link" class="{{ $label }}">YouTube or Vimeo link</label>
            <input id="product-video-link" type="url" name="new_video[link]" value="{{ old('new_video.link') }}" placeholder="https://www.youtube.com/watch?v=..." class="{{ $input }}">
        </div>
        <div>
            <label for="product-video-file" class="{{ $label }}">Or upload a video file</label>
            <input id="product-video-file" type="file" name="new_video_file" accept="video/mp4,video/webm" class="{{ $fileInput }}">
            <p class="mt-1 text-xs text-slate-400">MP4 or WebM, up to 100 MB. A YouTube link loads faster for visitors.</p>
        </div>
    </div>
</x-admin.form-section>
