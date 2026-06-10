@extends('layouts.public')

@section('title', 'Frequently Asked Questions | Software, AI, Cloud & Security | Tectignis')

@section('seo')
    <meta name="description" content="Find answers to common questions about our services, solutions, and how we can help your business grow — from Tectignis IT Solutions.">
    <link rel="canonical" href="{{ route('faqs') }}">
@endsection

@php
    $sitePhone = \App\Models\Setting::get('site_phone', '+91 9987705688');
    $siteEmail = \App\Models\Setting::get('site_email', 'info@tectignis.in');
    $totalFaqs = $faqCategories->sum(fn ($category) => $category->faqs->count());
@endphp

@section('content')

    <!--=========== FAQs Hero ===========-->
    <section class="res-hero">
        <div class="container">
            <span class="res-hero__eyebrow">FAQs</span>
            <h1 class="res-hero__title">Frequently Asked <span class="res-accent">Questions</span></h1>
            <p class="res-hero__intro">Find answers to common questions about our services, solutions, and how we can help your business grow.</p>
        </div>
    </section>

    <!--=========== FAQs Body ===========-->
    <div class="res-body">
        <div class="container">
            <div class="row">

                {{-- Category sidebar --}}
                <div class="col-lg-3 mb-5 mb-lg-0">
                    <div class="faq-side">
                        <div class="res-search mb-3" style="max-width: none;">
                            <span class="res-search__icon"><i class="fas fa-search" aria-hidden="true"></i></span>
                            <input type="search" placeholder="Search a question..." data-faq-search>
                        </div>
                        <ul class="res-cats">
                            <li>
                                <a href="#" class="is-active" data-faq-cat="all">
                                    <i class="fas fa-layer-group" aria-hidden="true"></i> All Questions
                                    <span class="res-cats__count">{{ $totalFaqs }}</span>
                                </a>
                            </li>
                            @foreach ($faqCategories as $category)
                                <li>
                                    <a href="#" data-faq-cat="cat-{{ $category->id }}">
                                        <i class="{{ $category->icon ?: 'fas fa-question-circle' }}" aria-hidden="true"></i> {{ $category->name }}
                                        <span class="res-cats__count">{{ $category->faqs->count() }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Accordion --}}
                <div class="col-lg-6">
                    @forelse ($faqCategories as $category)
                        <div data-faq-section="cat-{{ $category->id }}">
                            @foreach ($category->faqs as $faq)
                                <details class="faq-acc__item" data-faq-item @if ($loop->parent->first && $loop->first) open @endif>
                                    <summary class="faq-acc__q">
                                        <span data-faq-question>{{ $faq->question }}</span>
                                        <span class="faq-acc__toggle"><i class="fas fa-plus" aria-hidden="true"></i></span>
                                    </summary>
                                    <div class="faq-acc__a">{{ $faq->answer }}</div>
                                </details>
                            @endforeach
                        </div>
                    @empty
                        <div class="res-empty">
                            <i class="far fa-question-circle" aria-hidden="true"></i>
                            <p class="mb-0">No FAQs published yet. Check back soon.</p>
                        </div>
                    @endforelse

                    <div class="res-empty" data-faq-no-results hidden>
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <p class="mb-0">No questions match your search.</p>
                    </div>
                </div>

                {{-- Contact card --}}
                <div class="col-lg-3 mt-5 mt-lg-0">
                    <div class="faq-contact">
                        <div class="faq-contact__icon"><i class="fas fa-headset" aria-hidden="true"></i></div>
                        <h5>Can't find what you're looking for?</h5>
                        <p>Our experts are ready to answer your questions.</p>
                        <ul class="faq-contact__list">
                            <li><i class="fas fa-phone-alt" aria-hidden="true"></i> <a href="tel:{{ preg_replace('/\s+/', '', $sitePhone) }}">{{ $sitePhone }}</a></li>
                            <li><i class="far fa-envelope" aria-hidden="true"></i> <a href="mailto:{{ $siteEmail }}">{{ $siteEmail }}</a></li>
                            <li><i class="far fa-clock" aria-hidden="true"></i> Mon–Sat: 9AM – 8PM</li>
                        </ul>
                    </div>
                </div>

            </div>

            <!--=========== Still have questions ===========-->
            <div class="faq-footer">
                <p class="faq-footer__hint"><i class="far fa-comment-dots" aria-hidden="true"></i> Still have questions? We're here to help! <a href="{{ route('contact') }}">Contact Us <i class="fas fa-arrow-right" aria-hidden="true"></i></a></p>
                <a href="{{ route('contact') }}" class="svc-btn svc-btn--primary">Contact Our Experts <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function () {
            var catLinks = document.querySelectorAll('[data-faq-cat]');
            var sections = document.querySelectorAll('[data-faq-section]');
            var searchInput = document.querySelector('[data-faq-search]');
            var noResults = document.querySelector('[data-faq-no-results]');
            var activeCat = 'all';

            function applyFilters() {
                var term = (searchInput ? searchInput.value : '').trim().toLowerCase();
                var visible = 0;
                sections.forEach(function (section) {
                    var sectionMatchesCat = activeCat === 'all' || section.getAttribute('data-faq-section') === activeCat;
                    var sectionVisible = 0;
                    section.querySelectorAll('[data-faq-item]').forEach(function (item) {
                        var question = item.querySelector('[data-faq-question]').textContent.toLowerCase();
                        var show = sectionMatchesCat && (term === '' || question.indexOf(term) !== -1);
                        item.style.display = show ? '' : 'none';
                        if (show) { sectionVisible++; }
                    });
                    section.style.display = sectionVisible > 0 ? '' : 'none';
                    visible += sectionVisible;
                });
                if (noResults) { noResults.hidden = visible > 0; }
            }

            catLinks.forEach(function (link) {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    catLinks.forEach(function (l) { l.classList.remove('is-active'); });
                    link.classList.add('is-active');
                    activeCat = link.getAttribute('data-faq-cat');
                    applyFilters();
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);
            }
        })();
    </script>
    @endpush

@endsection
