<?php

use App\Support\ArticleHeadings;

it('renumbers a body that starts below h2 and skips levels, keeping each heading size', function () {
    $html = '<h4 class="intro">Key Trends</h4><p>Text</p><h6>Detail</h6><h4>More</h4>';

    expect(ArticleHeadings::outline($html))->toBe(
        '<h2 data-heading-size="4" class="intro">Key Trends</h2><p>Text</p><h3 data-heading-size="6">Detail</h3><h2 data-heading-size="4">More</h2>'
    );
});

it('leaves a body whose outline is already right untouched', function () {
    $html = '<h2>Overview</h2><h3>Scope</h3><p>Text</p>';

    expect(ArticleHeadings::outline($html))->toBe($html);
});

it('keeps bodies without headings as they are', function () {
    expect(ArticleHeadings::outline('<p>Only text</p>'))->toBe('<p>Only text</p>')
        ->and(ArticleHeadings::outline(null))->toBe('');
});
