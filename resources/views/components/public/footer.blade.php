@php
    $siteSettings = \App\Models\Setting::values();
    $footerLogo = \App\Models\Setting::imageUrl($siteSettings['site_logo_dark'] ?? null, 'site_logo_dark')
        ?? asset('assets/images/logo/Tectignis-IT-solution-logo.webp');
    $footerPhone = $siteSettings['site_phone'] ?? '+91 9987705688';
    $footerEmail = $siteSettings['site_email'] ?? 'info@tectignis.in';
    $footerAddress = $siteSettings['site_address'] ?? 'Navi Mumbai, Maharashtra, India';
    $whatsappNumber = preg_replace('/\D/', '', $siteSettings['social_whatsapp'] ?? '');
    $socials = array_filter([
        ['url' => $siteSettings['social_linkedin'] ?? null, 'icon' => 'fab fa-linkedin-in', 'label' => 'Visit LinkedIn'],
        ['url' => $siteSettings['social_facebook'] ?? null, 'icon' => 'fab fa-facebook-f', 'label' => 'Visit Facebook'],
        ['url' => $siteSettings['social_instagram'] ?? null, 'icon' => 'fab fa-instagram', 'label' => 'Visit Instagram'],
        ['url' => $siteSettings['social_twitter'] ?? null, 'icon' => 'fab fa-twitter', 'label' => 'Visit X / Twitter'],
        ['url' => $whatsappNumber ? 'https://wa.me/'.$whatsappNumber : null, 'icon' => 'fab fa-whatsapp', 'label' => 'Chat on WhatsApp'],
    ], fn (array $social): bool => filled($social['url']));
    $capabilityIds = \App\Models\Capability::where('is_active', true)->orderBy('sort_order')->pluck('id');
    $popularServices = \App\Models\Service::where('is_active', true)
        ->whereIn('capability_id', $capabilityIds)
        ->orderBy('capability_id')
        ->orderBy('sort_order')
        ->get(['title', 'slug', 'capability_id'])
        ->groupBy('capability_id')
        ->sortKeysUsing(fn ($a, $b) => $capabilityIds->search($a) <=> $capabilityIds->search($b))
        ->flatMap(fn ($services) => $services->take(3))
        ->take(12)
        ->values();
@endphp

<footer class="ft-dark__main" aria-label="Site Footer">
    <div class="container">

        {{-- Top Section --}}
        <div class="ft-dark__top">

            {{-- Brand --}}
            <div>
                <a href="{{ route('home') }}" class="ft-dark__brand-logo">
                    <img src="{{ $footerLogo }}" width="140" height="42" class="img-fluid" alt="Tectignis IT Solutions">
                </a>
                <p class="ft-dark__brand-tagline">
                    We deliver innovative software, AI, cloud, cybersecurity and smart infrastructure solutions that help businesses transform, scale and succeed globally.
                </p>
                <ul class="ft-dark__brand-contact">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $footerAddress }}</span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <a href="tel:{{ preg_replace('/[^+\d]/', '', $footerPhone) }}">{{ $footerPhone }}</a>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a>
                    </li>
                </ul>
                <ul class="ft-dark__socials">
                    @foreach ($socials as $social)
                        <li>
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener" aria-label="{{ $social['label'] }}">
                                <i class="{{ $social['icon'] }}"></i>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Company --}}
            <nav aria-label="Company">
                <h6 class="ft-dark__heading">Company</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('about') }}">Why Tectignis</a></li>
                    <li><a href="{{ route('about') }}">Project Delivery Approach</a></li>
                    <li><a href="{{ route('careers') }}">Careers</a></li>
                    <li><a href="{{ route('contact') }}">Global Presence</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </nav>

            {{-- Capabilities --}}
            <nav aria-label="Capabilities">
                <h6 class="ft-dark__heading">Capabilities</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('capabilities.index') }}">Software Development</a></li>
                    <li><a href="{{ route('capabilities.index') }}">AI &amp; Automation</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Cloud &amp; Infrastructure</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Cybersecurity</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Networking &amp; Hardware</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Smart Security Solutions</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Digital Marketing</a></li>
                    <li><a href="{{ route('capabilities.index') }}">AMC &amp; Support</a></li>
                </ul>
            </nav>

            {{-- Solutions --}}
            <nav aria-label="Solutions">
                <h6 class="ft-dark__heading">Solutions</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('capabilities.index') }}">ERP Solutions</a></li>
                    <li><a href="{{ route('capabilities.index') }}">CRM Solutions</a></li>
                    <li><a href="{{ route('capabilities.index') }}">HRMS Solutions</a></li>
                    <li><a href="{{ route('capabilities.index') }}">AI Solutions</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Cloud Solutions</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Automation Solutions</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Security Solutions</a></li>
                    <li><a href="{{ route('capabilities.index') }}">Data &amp; Analytics</a></li>
                </ul>
            </nav>

            {{-- Industries --}}
            <nav aria-label="Industries">
                <h6 class="ft-dark__heading">Industries</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('industries.index') }}">Manufacturing</a></li>
                    <li><a href="{{ route('industries.index') }}">Healthcare</a></li>
                    <li><a href="{{ route('industries.index') }}">Education</a></li>
                    <li><a href="{{ route('industries.index') }}">Real Estate</a></li>
                    <li><a href="{{ route('industries.index') }}">Retail &amp; E-commerce</a></li>
                    <li><a href="{{ route('industries.index') }}">Logistics</a></li>
                    <li><a href="{{ route('industries.index') }}">Hospitality</a></li>
                    <li><a href="{{ route('industries.index') }}">Corporate Offices</a></li>
                </ul>
            </nav>

            {{-- Quick Links --}}
            <nav aria-label="Quick Links">
                <h6 class="ft-dark__heading">Quick Links</h6>
                <ul class="ft-dark__links">
                    <li><a href="{{ route('case-studies.index') }}">Case Studies</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('technology-insights') }}">Technology Insights</a></li>
                    <li><a href="{{ route('downloads') }}">Downloads</a></li>
                    <li><a href="{{ route('legal.show', 'privacy-policy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('legal.show', 'terms-and-conditions') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </nav>

        </div>

        {{-- Middle Section --}}
        <div class="ft-dark__middle">

            {{-- Popular Services --}}
            <div>
                <div class="ft-dark__section-header">
                    <i class="far fa-star" aria-hidden="true"></i>
                    <h3 class="ft-dark__section-title">Popular Services</h3>
                </div>
                <div class="ft-dark__services-grid">
                    @foreach ($popularServices as $service)
                        <a href="{{ route('services.show', $service->slug) }}" class="ft-dark__service-item">{{ $service->title }}</a>
                    @endforeach
                </div>
            </div>

            {{-- Locations --}}
            <div>
                <div class="ft-dark__section-header">
                    <i class="fas fa-globe" aria-hidden="true"></i>
                    <h3 class="ft-dark__section-title">Locations We Serve</h3>
                </div>

                {{-- Indian city tags --}}
                <div class="ft-dark__location-tags">
                    <span class="ft-dark__location-tag"><i class="fas fa-map-marker-alt"></i> Navi Mumbai</span>
                    <span class="ft-dark__location-tag"><i class="fas fa-map-marker-alt"></i> Mumbai</span>
                    <span class="ft-dark__location-tag"><i class="fas fa-map-marker-alt"></i> Thane</span>
                    <span class="ft-dark__location-tag"><i class="fas fa-map-marker-alt"></i> Pune</span>
                    <span class="ft-dark__location-tag"><i class="fas fa-map-marker-alt"></i> Panvel</span>
                    <span class="ft-dark__location-tag"><i class="fas fa-map-marker-alt"></i> Maharashtra</span>
                    <span class="ft-dark__location-tag"><i class="fas fa-map-marker-alt"></i> India</span>
                </div>

                {{-- Lower: map (left) | countries list (right) --}}
                <div class="ft-dark__locations-lower">
                    <div class="ft-dark__map-wrap">
                        <div class="ft-dark__world-map" role="img" aria-label="World map showing Tectignis global service locations">
                            <div class="ft-dark__map-dot ft-dark__map-dot--india"></div>
                            <div class="ft-dark__map-dot ft-dark__map-dot--uae"></div>
                            <div class="ft-dark__map-dot ft-dark__map-dot--uk"></div>
                            <div class="ft-dark__map-dot ft-dark__map-dot--usa"></div>
                            <div class="ft-dark__map-dot ft-dark__map-dot--singapore"></div>
                            <div class="ft-dark__map-dot ft-dark__map-dot--australia"></div>
                        </div>
                    </div>
                    <div class="ft-dark__global-clients">
                        <h4>Serving Clients Across the Globe</h4>
                        <ul class="ft-dark__countries-list">
                            <li>
                                <img src="https://flagcdn.com/w40/ae.png" alt="UAE" width="24" height="16">
                                <span>UAE</span>
                            </li>
                            <li>
                                <img src="https://flagcdn.com/w40/sa.png" alt="Saudi Arabia" width="24" height="16">
                                <span>Saudi Arabia</span>
                            </li>
                            <li>
                                <img src="https://flagcdn.com/w40/gb.png" alt="UK" width="24" height="16">
                                <span>UK</span>
                            </li>
                            <li>
                                <img src="https://flagcdn.com/w40/us.png" alt="USA" width="24" height="16">
                                <span>USA</span>
                            </li>
                            <li>
                                <img src="https://flagcdn.com/w40/ca.png" alt="Canada" width="24" height="16">
                                <span>Canada</span>
                            </li>
                            <li>
                                <img src="https://flagcdn.com/w40/au.png" alt="Australia" width="24" height="16">
                                <span>Australia</span>
                            </li>
                            <li>
                                <img src="https://flagcdn.com/w40/sg.png" alt="Singapore" width="24" height="16">
                                <span>Singapore</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div class="ft-dark__bottom">
            <div class="ft-dark__certifications">
                <div class="ft-dark__cert-label">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    <span>Certifications &amp; Partners</span>
                </div>
                <div class="ft-dark__partner-logos">
                    <img src="{{ asset('images/aws.png') }}" alt="AWS Partner" height="28">
                    <img src="{{ asset('images/microsoft.png') }}" alt="Microsoft Partner" height="24">
                    <img src="{{ asset('images/google.png') }}" alt="Google Cloud Partner" height="28">
                    <img src="{{ asset('images/iso.jpg') }}" alt="ISO 27001" height="32">
                </div>
            </div>
            <div class="ft-dark__copyright">
                <p>&copy; {{ date('Y') }} <a href="{{ route('home') }}">Tectignis IT Solutions Pvt. Ltd.</a> All Rights Reserved.</p>
                <div class="ft-dark__legal-links">
                    <a href="{{ route('legal.show', 'privacy-policy') }}">Privacy Policy</a>
                    <a href="{{ route('legal.show', 'terms-and-conditions') }}">Terms of Service</a>
                    <a href="{{ route('legal.show', 'cookie-policy') }}">Cookie Policy</a>
                </div>
                <div class="ft-dark__reg-info">
                    <span>GSTIN: 27AAHFT31725F1Z3</span>
                    <span>CIN: U72900MH2023PTC401102</span>
                </div>
            </div>
        </div>

    </div>
</footer>
