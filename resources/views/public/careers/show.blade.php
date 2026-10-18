@extends('layouts.public')

@section('title', $jobOpening->seoTitle().' | Careers at Tectignis IT Solutions')

@php
    $isOpen = $jobOpening->isOpen();
    $timezone = config('app.timezone');
    $skills = \App\Models\JobOpening::listItems(str_replace(',', "\n", (string) $jobOpening->skills));
    $sections = array_filter([
        'Responsibilities' => $jobOpening->responsibilities,
        'Requirements' => $jobOpening->requirements,
        'Benefits' => $jobOpening->benefits,
    ], fn ($value) => filled($value));
    $overview = array_filter([
        ['fas fa-building', 'Department', $jobOpening->department],
        ['fas fa-map-marker-alt', 'Location', $jobOpening->location],
        ['far fa-clock', 'Employment type', $jobOpening->employment_type],
        ['fas fa-user-graduate', 'Experience', $jobOpening->experience],
        ['fas fa-wallet', 'Salary', $jobOpening->salaryIsPublic() ? $jobOpening->salary : null],
        ['far fa-calendar-alt', 'Apply by', $jobOpening->application_deadline?->format('M d, Y')],
    ], fn (array $row) => filled($row[2]));

    $employmentTypes = [
        'Full-time' => 'FULL_TIME',
        'Part-time' => 'PART_TIME',
        'Contract' => 'CONTRACTOR',
        'Internship' => 'INTERN',
        'Remote' => 'FULL_TIME',
        'Other' => 'OTHER',
    ];
    $postingDescription = collect([$jobOpening->description])
        ->merge(collect($sections)->map(fn ($value, $heading) => '<h3>'.e($heading).'</h3>'.(\App\Models\JobOpening::isHtml($value)
            ? $value
            : '<ul>'.collect(\App\Models\JobOpening::listItems($value))->map(fn ($item) => '<li>'.e($item).'</li>')->implode('').'</ul>')))
        ->filter()
        ->implode('');
    $jobPostingSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'JobPosting',
        'title' => $jobOpening->title,
        'description' => $postingDescription ?: e($jobOpening->title),
        'identifier' => ['@type' => 'PropertyValue', 'name' => $company->name(), 'value' => 'JOB-'.$jobOpening->id],
        'datePosted' => ($jobOpening->published_at ?? $jobOpening->created_at)?->toDateString(),
        'validThrough' => $jobOpening->application_deadline?->copy()->endOfDay()->toIso8601String(),
        'employmentType' => $employmentTypes[$jobOpening->employment_type] ?? 'OTHER',
        'hiringOrganization' => ['@type' => 'Organization', 'name' => $company->name(), 'sameAs' => url('/'), 'logo' => $company->logoUrl()],
        'jobLocationType' => $jobOpening->employment_type === 'Remote' ? 'TELECOMMUTE' : null,
        'jobLocation' => [
            '@type' => 'Place',
            'address' => array_filter([
                '@type' => 'PostalAddress',
                'addressLocality' => $jobOpening->location,
                'addressCountry' => \Illuminate\Support\Str::contains((string) $jobOpening->location, 'India', true) ? 'IN' : null,
            ]),
        ],
        'skills' => $skills ? implode(', ', $skills) : null,
        'experienceRequirements' => $jobOpening->experience,
        'directApply' => true,
    ]);
@endphp

@section('seo')
    <meta name="description" content="{{ $jobOpening->seoDescription() }}">
    <meta property="og:title" content="{{ $jobOpening->seoTitle() }}">
    <meta property="og:description" content="{{ $jobOpening->seoDescription() }}">
    <meta property="og:url" content="{{ route('careers.show', $jobOpening) }}">
    <meta property="og:type" content="website">
@endsection

@unless ($isOpen)
    @section('robots', 'noindex, follow')
@endunless

@if ($isOpen)
    @push('json-ld')
        <script type="application/ld+json">{!! json_encode($jobPostingSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endpush
@endif

@section('content')

    <!--=========== Job Hero ===========-->
    <section class="res-hero res-hero--article car-detail-hero">
        <div class="container">
            <span class="res-hero__eyebrow">{{ $jobOpening->department ?: 'Careers' }}</span>
            <h1 class="res-hero__title">{{ $jobOpening->title }}</h1>
            <div class="res-hero__meta">
                @if ($jobOpening->location)
                    <span><i class="fas fa-map-marker-alt" aria-hidden="true"></i> {{ $jobOpening->location }}</span>
                @endif
                <span><i class="far fa-clock" aria-hidden="true"></i> {{ $jobOpening->employment_type }}</span>
                @if ($jobOpening->experience)
                    <span><i class="fas fa-user-graduate" aria-hidden="true"></i> {{ $jobOpening->experience }}</span>
                @endif
                @if ($jobOpening->salaryIsPublic())
                    <span><i class="fas fa-wallet" aria-hidden="true"></i> {{ $jobOpening->salary }}</span>
                @endif
            </div>
            <div class="car-hero__buttons mt-4">
                @if ($isOpen)
                    <a href="#apply" class="svc-btn svc-btn--primary">Apply Now <i class="fas fa-arrow-down" aria-hidden="true"></i></a>
                @endif
                <a href="{{ route('careers') }}#open-positions" class="svc-btn svc-btn--ghost">All Open Positions</a>
            </div>
        </div>
    </section>

    <!--=========== Job Body ===========-->
    <div class="res-body">
        <div class="container">
            @if (session('career_status'))
                <div class="alert alert-success mb-4" role="status">{{ session('career_status') }}</div>
            @endif

            @unless ($isOpen)
                <div class="car-closed mb-4" role="status">
                    <i class="fas fa-lock" aria-hidden="true"></i>
                    <span>This position is no longer accepting applications. Take a look at our <a href="{{ route('careers') }}#open-positions">other open positions</a>.</span>
                </div>
            @endunless

            <div class="row">
                <div class="col-lg-8">
                    <div class="res-article car-detail">
                        @if (filled($jobOpening->description))
                            <h2>About the Role</h2>
                            {!! $jobOpening->description !!}
                        @endif

                        @foreach ($sections as $heading => $content)
                            <h2>{{ $heading }}</h2>
                            @if (\App\Models\JobOpening::isHtml($content))
                                {!! $content !!}
                            @else
                                <ul>
                                    @foreach (\App\Models\JobOpening::listItems($content) as $item)
                                        <li>{{ $item }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach

                        @if ($skills)
                            <h2>Skills</h2>
                            <div class="car-skills">
                                @foreach ($skills as $skill)
                                    <span class="car-skill">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4 mt-5 mt-lg-0">
                    <div class="res-widget">
                        <h2 class="res-widget__title">Job Overview</h2>
                        <ul class="car-overview">
                            @foreach ($overview as [$icon, $label, $value])
                                <li>
                                    <i class="{{ $icon }}" aria-hidden="true"></i>
                                    <div>
                                        <span class="car-overview__label">{{ $label }}</span>
                                        <span class="car-overview__value">{{ $value }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        @if ($isOpen)
                            <a href="#apply" class="svc-btn svc-btn--primary svc-btn--block mt-3">Apply Now</a>
                        @endif
                    </div>

                    @if ($otherOpenings->isNotEmpty())
                        <div class="res-widget">
                            <h2 class="res-widget__title">Other Openings</h2>
                            <ul class="car-other">
                                @foreach ($otherOpenings as $opening)
                                    <li>
                                        <a href="{{ route('careers.show', $opening) }}">{{ $opening->title }}</a>
                                        <span>{{ collect([$opening->location, $opening->employment_type])->filter()->implode(' · ') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            @if ($isOpen)
                <!--=========== Application Form ===========-->
                <div class="car-apply" id="apply">
                    <h2 class="res-section-title">Apply for {{ $jobOpening->title }}</h2>
                    <x-public.application-form :designations="$designations" :job-opening="$jobOpening" id-prefix="job_apply" />
                </div>
            @endif
        </div>
    </div>

@endsection
