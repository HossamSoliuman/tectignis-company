@extends('layouts.public')

@section('title', 'Free Downloads | Brochures, Case Studies & Guides | Tectignis')

@section('seo')
    <meta name="description" content="Download free resources from Tectignis — company brochures, whitepapers, case studies, datasheets, presentations and guides to support your digital transformation journey.">
    <link rel="canonical" href="{{ route('downloads') }}">
@endsection

@php
    $downloadCategories = \App\Models\Download::CATEGORIES;
    $usedCategories = $downloads->pluck('category')->unique();
    $fileTypeIcons = [
        'pdf' => 'far fa-file-pdf',
        'doc' => 'far fa-file-word',
        'ppt' => 'far fa-file-powerpoint',
        'xls' => 'far fa-file-excel',
        'zip' => 'far fa-file-archive',
    ];
    $modalOpen = $errors->any() && old('download_id');
@endphp

@section('content')

    <!--=========== Downloads Hero ===========-->
    <section class="res-hero">
        <div class="container">
            <span class="res-hero__eyebrow">Downloads</span>
            <h1 class="res-hero__title">Resources to <span class="res-accent">Empower Your Business</span></h1>
            <p class="res-hero__intro">Explore our brochures, whitepapers, case studies, and helpful guides.</p>
        </div>
    </section>

    <!--=========== Browse & Download ===========-->
    <div class="res-body">
        <div class="container">
            <h2 class="res-section-title">Browse &amp; Download Resources</h2>
            <p class="res-section-sub">Access valuable insights, product brochures, solution guides, and more to help you make informed decisions.</p>

            @if (session('download_status'))
                <div class="alert alert-success mb-4">{{ session('download_status') }}</div>
            @endif

            <div class="res-pills" data-dl-filters>
                <button type="button" class="res-pill is-active" data-dl-filter="all">All Resources</button>
                @foreach ($downloadCategories as $value => $label)
                    @if ($usedCategories->contains($value))
                        <button type="button" class="res-pill" data-dl-filter="{{ $value }}">{{ $label }}</button>
                    @endif
                @endforeach
            </div>

            @if ($downloads->isEmpty())
                <div class="res-empty">
                    <i class="fas fa-download" aria-hidden="true"></i>
                    <p class="mb-0">No resources available yet. Check back soon.</p>
                </div>
            @else
                <div class="row" data-dl-grid>
                    @foreach ($downloads as $download)
                        <div class="col-xl-4 col-md-6 mb-30" data-dl-category="{{ $download->category }}">
                            <div class="dl-card">
                                <div class="dl-card__icon dl-card__icon--{{ $download->file_type }}">
                                    <i class="{{ $fileTypeIcons[$download->file_type] ?? 'far fa-file' }}" aria-hidden="true"></i>
                                </div>
                                <div class="dl-card__category">{{ $download->categoryLabel() }}</div>
                                <h3 class="dl-card__title">{{ $download->title }}</h3>
                                @if ($download->description)
                                    <p class="dl-card__desc">{{ $download->description }}</p>
                                @endif
                                <button type="button" class="dl-card__action js-dl-open"
                                    data-download-id="{{ $download->id }}" data-download-title="{{ $download->title }}">
                                    Download {{ strtoupper($download->file_type) }} <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!--=========== Help CTA ===========-->
            <div class="res-cta-band">
                <div class="res-cta-band__icon"><i class="fas fa-headset" aria-hidden="true"></i></div>
                <div class="res-cta-band__content">
                    <h4>Need Help Finding a Resource?</h4>
                    <p>Our team is here to help. Get in touch and we'll send you the right information.</p>
                </div>
                <div class="res-cta-band__actions">
                    <a href="{{ route('contact') }}" class="svc-btn svc-btn--primary">Contact Us <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                    <a href="#" class="svc-btn svc-btn--outline-light js-consult-open">Request Consultation <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>

    @push('modals')
    <!--=========== Download Lead Capture Modal ===========-->
    <div class="consult-modal" id="download-modal" aria-hidden="{{ $modalOpen ? 'false' : 'true' }}" @if ($modalOpen) data-open="true" @endif>
        <div class="consult-modal__overlay" data-dl-close aria-hidden="true"></div>

        <div class="consult-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="download-modal-title">
            <div class="consult-modal__header">
                <span class="consult-modal__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                </span>
                <div class="consult-modal__heading">
                    <h3 class="consult-modal__title" id="download-modal-title">Download Resource</h3>
                    <p class="consult-modal__desc" data-dl-resource-name>Please fill in your details to download the resource.</p>
                </div>
                <button type="button" class="consult-modal__close" data-dl-close aria-label="Close">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="consult-modal__body">
                @if ($modalOpen)
                    <div class="consult-modal__alert consult-modal__alert--error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="consult-form" action="{{ route('downloads.request') }}" method="post">
                    @csrf
                    <input type="hidden" name="download_id" value="{{ old('download_id') }}" data-dl-id-field>

                    <div class="consult-field">
                        <label for="dl_name">Name <span aria-hidden="true">*</span></label>
                        <input id="dl_name" name="name" type="text" placeholder="Enter your full name" value="{{ old('name') }}" required>
                    </div>

                    <div class="consult-field">
                        <label for="dl_phone">Mobile No. <span aria-hidden="true">*</span></label>
                        <input id="dl_phone" name="phone" type="text" placeholder="Enter your mobile number" value="{{ old('phone') }}" maxlength="20" required>
                    </div>

                    <div class="consult-field">
                        <label for="dl_email">Email <span class="consult-field__optional">(Optional)</span></label>
                        <input id="dl_email" name="email" type="email" placeholder="Enter your email address" value="{{ old('email') }}">
                    </div>

                    <button type="submit" class="consult-form__submit">Submit &amp; Download</button>
                    <p class="res-news__note" style="color: #9b9bad;">We respect your privacy. Your information is safe with us.</p>
                </form>
            </div>
        </div>
    </div>
    @endpush

    @push('scripts')
    <script>
        (function () {
            // Category filter pills
            var pills = document.querySelectorAll('[data-dl-filter]');
            var cards = document.querySelectorAll('[data-dl-category]');
            pills.forEach(function (pill) {
                pill.addEventListener('click', function () {
                    pills.forEach(function (p) { p.classList.remove('is-active'); });
                    pill.classList.add('is-active');
                    var filter = pill.getAttribute('data-dl-filter');
                    cards.forEach(function (card) {
                        card.style.display = (filter === 'all' || card.getAttribute('data-dl-category') === filter) ? '' : 'none';
                    });
                });
            });

            // Lead capture modal
            var modal = document.getElementById('download-modal');
            if (!modal) { return; }
            var idField = modal.querySelector('[data-dl-id-field]');
            var resourceName = modal.querySelector('[data-dl-resource-name]');

            function openModal() {
                modal.setAttribute('data-open', 'true');
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
                var firstField = modal.querySelector('input[type="text"]');
                if (firstField) { firstField.focus(); }
            }

            function closeModal() {
                modal.removeAttribute('data-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            document.querySelectorAll('.js-dl-open').forEach(function (trigger) {
                trigger.addEventListener('click', function () {
                    idField.value = trigger.getAttribute('data-download-id');
                    resourceName.textContent = 'Please fill in your details to download "' + trigger.getAttribute('data-download-title') + '".';
                    openModal();
                });
            });

            modal.querySelectorAll('[data-dl-close]').forEach(function (el) {
                el.addEventListener('click', closeModal);
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.hasAttribute('data-open')) { closeModal(); }
            });

            if (modal.hasAttribute('data-open')) {
                document.body.style.overflow = 'hidden';
            }

            // Auto-start the file download after a successful lead submit.
            @if (session('download_url'))
                var link = document.createElement('a');
                link.href = @json(session('download_url'));
                link.download = '';
                document.body.appendChild(link);
                link.click();
                link.remove();
            @endif
        })();
    </script>
    @endpush

@endsection
