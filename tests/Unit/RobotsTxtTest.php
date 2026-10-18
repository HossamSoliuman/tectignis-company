<?php

use App\Support\RobotsTxt;

it('allows everything without rules or with an empty disallow', function () {
    expect((new RobotsTxt(''))->allows('https://tectignis.in/ar'))->toBeTrue()
        ->and((new RobotsTxt("User-agent: *\nDisallow:"))->allows('https://tectignis.in/ar'))->toBeTrue();
});

it('lets the longest matching rule win, and allow win a tie', function () {
    $robots = new RobotsTxt("User-agent: *\nDisallow: /ar\nAllow: /ar/contact\nDisallow: /es/\nAllow: /es/");

    expect($robots->allows('https://tectignis.in/ar'))->toBeFalse()
        ->and($robots->allows('https://tectignis.in/ar/services/web'))->toBeFalse()
        ->and($robots->allows('https://tectignis.in/ar/contact'))->toBeTrue()
        ->and($robots->allows('https://tectignis.in/es/contact'))->toBeTrue()
        ->and($robots->allows('https://tectignis.in/fr'))->toBeTrue();
});

it('supports wildcards and the end anchor', function () {
    $robots = new RobotsTxt("User-agent: *\nDisallow: /*?page=\nDisallow: /*.pdf$");

    expect($robots->allows('https://tectignis.in/blog?page=2'))->toBeFalse()
        ->and($robots->allows('https://tectignis.in/files/brochure.pdf'))->toBeFalse()
        ->and($robots->allows('https://tectignis.in/files/brochure.pdf?v=2'))->toBeTrue()
        ->and($robots->allows('https://tectignis.in/blog'))->toBeTrue();
});

it('follows the group naming the crawler over the catch-all group', function () {
    $robots = new RobotsTxt("User-agent: *\nDisallow: /\n\nUser-agent: Googlebot\nDisallow: /admin # private");

    expect($robots->allows('https://tectignis.in/de', 'Googlebot'))->toBeTrue()
        ->and($robots->allows('https://tectignis.in/admin', 'Googlebot'))->toBeFalse()
        ->and($robots->allows('https://tectignis.in/de', 'Bingbot'))->toBeFalse();
});

it('spots lines holding more than one directive', function () {
    expect((new RobotsTxt("User-agent: *Allow: /\nSitemap: https://tectignis.in/sitemap.xml"))->joinedLines())->toBe([1])
        ->and((new RobotsTxt("User-agent: *\nDisallow: /allowed-path\nSitemap: https://tectignis.in/sitemap.xml"))->joinedLines())->toBe([]);
});

it('reads the sitemaps it lists', function () {
    expect((new RobotsTxt("User-agent: *\nAllow: /\nSitemap: https://tectignis.in/sitemap.xml"))->sitemaps())
        ->toBe(['https://tectignis.in/sitemap.xml']);
});
