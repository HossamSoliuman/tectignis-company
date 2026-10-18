<?php

namespace App\Services;

use App\Support\Locales;
use App\Support\RobotsTxt;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Crawls the public site from its robots.txt and sitemap the way a search
 * engine does, and reports what would keep a page out of the index or show
 * it under the wrong language: blocked, redirected or noindexed URLs,
 * canonicals pointing elsewhere, a wrong `lang`, broken `hreflang` and
 * untranslated titles. Headings and image alt text are checked as well.
 *
 * @phpstan-type Page array{
 *     url: string,
 *     language: string,
 *     status: int,
 *     location: string|null,
 *     robots: string,
 *     lang: string|null,
 *     titles: list<string>,
 *     description: string|null,
 *     canonicals: list<string>,
 *     hreflang: array<string, string>,
 *     h1: int,
 *     headingJumps: list<string>,
 *     imagesWithoutAlt: list<string>,
 * }
 * @phpstan-type Issue array{url: string, level: 'error'|'warning', message: string}
 */
class SeoCrawler
{
    /**
     * Contains "bot", so the site neither redirects the crawler to a detected
     * language nor counts its requests as visits.
     */
    public const USER_AGENT = 'TectignisSeoBot/1.0';

    private const CONCURRENCY = 5;

    private const TIMEOUT_SECONDS = 30;

    /**
     * @return array{sitemap: string, pages: array<string, Page>, issues: list<Issue>}
     *
     * @throws RuntimeException when the sitemap can't be read
     */
    public function crawl(string $baseUrl): array
    {
        $baseUrl = rtrim($baseUrl, '/');
        $issues = [];

        $robotsUrl = $baseUrl.'/robots.txt';
        $robotsResponse = $this->fetch([$robotsUrl])[$robotsUrl];
        $robots = new RobotsTxt($robotsResponse?->status() === 200 ? $robotsResponse->body() : '');

        // Without a robots.txt everything may be crawled; a failing one stops Google crawling the site.
        if ($robotsResponse?->status() === 404) {
            $issues[] = $this->issue($robotsUrl, 'warning', 'No robots.txt');
        } elseif ($robotsResponse?->status() !== 200) {
            $issues[] = $this->issue($robotsUrl, 'error', 'robots.txt returns HTTP '.($robotsResponse?->status() ?? 'nothing'));
        } elseif ($robots->sitemaps() === []) {
            $issues[] = $this->issue($robotsUrl, 'warning', 'robots.txt does not list the sitemap');
        }

        foreach ($robots->joinedLines() as $line) {
            $issues[] = $this->issue($robotsUrl, 'error', "robots.txt line {$line} holds more than one directive");
        }

        $sitemapUrl = $baseUrl.'/sitemap.xml';
        $sitemap = $this->readSitemap($sitemapUrl);

        $pages = [];

        foreach ($this->fetch(array_keys($sitemap)) as $url => $response) {
            $pages[$url] = $this->readPage($url, $response);
        }

        foreach ($pages as $page) {
            array_push($issues, ...$this->pageIssues($page, $pages, $sitemap[$page['url']], $robots));
        }

        array_push($issues, ...$this->duplicateTitleIssues($pages));

        return ['sitemap' => $sitemapUrl, 'pages' => $pages, 'issues' => $issues];
    }

    /**
     * Each URL in the sitemap with the `hreflang` alternates listed for it.
     *
     * @return array<string, array<string, string>>
     */
    private function readSitemap(string $url): array
    {
        $response = $this->fetch([$url])[$url];

        if ($response?->status() !== 200) {
            throw new RuntimeException("The sitemap at {$url} returns HTTP ".($response?->status() ?? 'nothing').'.');
        }

        $document = new DOMDocument;

        if (! @$document->loadXML($response->body())) {
            throw new RuntimeException("The sitemap at {$url} is not valid XML.");
        }

        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $xpath->registerNamespace('xhtml', 'http://www.w3.org/1999/xhtml');

        $entries = [];

        foreach ($xpath->query('//s:url') as $node) {
            $alternates = [];

            foreach ($xpath->query('xhtml:link[@rel="alternate"][@hreflang]', $node) as $link) {
                /** @var DOMElement $link */
                $alternates[$link->getAttribute('hreflang')] = $link->getAttribute('href');
            }

            $entries[trim($xpath->evaluate('string(s:loc)', $node))] = $alternates;
        }

        return $entries;
    }

    /**
     * Requests a few URLs at a time, without following redirects. A request
     * that fails is tried once more on its own, so a dropped connection isn't
     * reported as a broken page.
     *
     * @param  list<string>  $urls
     * @return array<string, Response|null> null when the request failed twice
     */
    private function fetch(array $urls): array
    {
        $responses = [];

        foreach (array_chunk($urls, self::CONCURRENCY) as $chunk) {
            $results = Http::pool(fn (Pool $pool): array => array_map(
                fn (string $url) => $this->request($pool->as($url))->get($url),
                $chunk,
            ));

            foreach ($chunk as $url) {
                $responses[$url] = ($results[$url] ?? null) instanceof Response ? $results[$url] : $this->retry($url);
            }
        }

        return $responses;
    }

    private function retry(string $url): ?Response
    {
        try {
            return $this->request(Http::createPendingRequest())->get($url);
        } catch (ConnectionException) {
            return null;
        }
    }

    private function request(PendingRequest $request): PendingRequest
    {
        return $request->withUserAgent(self::USER_AGENT)->withoutRedirecting()->timeout(self::TIMEOUT_SECONDS);
    }

    /**
     * @return Page
     */
    private function readPage(string $url, ?Response $response): array
    {
        $page = [
            'url' => $url,
            'language' => $this->languageOf($url),
            'status' => $response?->status() ?? 0,
            'location' => $response?->header('Location') ?: null,
            'robots' => strtolower($response?->header('X-Robots-Tag') ?? ''),
            'lang' => null,
            'titles' => [],
            'description' => null,
            'canonicals' => [],
            'hreflang' => [],
            'h1' => 0,
            'headingJumps' => [],
            'imagesWithoutAlt' => [],
        ];

        if ($page['status'] !== 200) {
            return $page;
        }

        $document = new DOMDocument;
        $previous = libxml_use_internal_errors(true);
        // The XML declaration makes libxml read the page as UTF-8.
        $document->loadHTML('<?xml encoding="UTF-8">'.$response->body(), LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        $xpath = new DOMXPath($document);

        $page['lang'] = $xpath->evaluate('string(//html/@lang)') ?: null;
        $page['description'] = trim($xpath->evaluate('string(//meta[@name="description"]/@content)')) ?: null;

        foreach ($xpath->query('//title') as $title) {
            $page['titles'][] = trim(preg_replace('/\s+/u', ' ', $title->textContent));
        }

        foreach ($xpath->query('//meta[@name="robots" or @name="googlebot"]/@content') as $content) {
            $page['robots'] .= ' '.strtolower($content->value);
        }

        foreach ($xpath->query('//link[@rel="canonical"]/@href') as $href) {
            $page['canonicals'][] = $href->value;
        }

        foreach ($xpath->query('//link[@rel="alternate"][@hreflang]') as $link) {
            /** @var DOMElement $link */
            $page['hreflang'][$link->getAttribute('hreflang')] = $link->getAttribute('href');
        }

        $page['h1'] = $xpath->query('//h1')->length;

        // Only the page's own content: the header and footer are the same everywhere.
        $main = $xpath->query('//main')->item(0) ?? $document->documentElement;
        $previousLevel = 1;

        foreach ($xpath->query('.//h1|.//h2|.//h3|.//h4|.//h5|.//h6', $main) as $heading) {
            $level = (int) substr($heading->nodeName, 1);

            if ($level > $previousLevel + 1) {
                $page['headingJumps'][] = "h{$previousLevel} → h{$level}";
            }

            $previousLevel = $level;
        }

        foreach ($xpath->query('//img[not(@alt)]/@src') as $src) {
            $page['imagesWithoutAlt'][] = $src->value;
        }

        $page['headingJumps'] = array_values(array_unique($page['headingJumps']));
        $page['imagesWithoutAlt'] = array_values(array_unique($page['imagesWithoutAlt']));

        return $page;
    }

    /**
     * @param  Page  $page
     * @param  array<string, Page>  $pages
     * @param  array<string, string>  $listedAlternates  the page's alternates in the sitemap
     * @return list<Issue>
     */
    private function pageIssues(array $page, array $pages, array $listedAlternates, RobotsTxt $robots): array
    {
        $url = $page['url'];
        $issues = [];
        $error = function (string $message) use (&$issues, $url): void {
            $issues[] = $this->issue($url, 'error', $message);
        };
        $warning = function (string $message) use (&$issues, $url): void {
            $issues[] = $this->issue($url, 'warning', $message);
        };

        if (! $robots->allows($url)) {
            $error('Blocked by robots.txt');
        }

        if ($page['status'] !== 200) {
            $error(match (true) {
                $page['status'] === 0 => 'No response',
                $page['location'] !== null => "Redirects (HTTP {$page['status']}) to {$page['location']}",
                default => "Returns HTTP {$page['status']}",
            });

            return $issues;
        }

        if (str_contains($page['robots'], 'noindex')) {
            $error('Marked noindex');
        }

        if ($page['canonicals'] === []) {
            $error('No canonical URL');
        } elseif (count($page['canonicals']) > 1) {
            $error(count($page['canonicals']).' canonical tags');
        } elseif (! $this->sameUrl($page['canonicals'][0], $url)) {
            $error("Canonical points to {$page['canonicals'][0]}");
        }

        $lang = $page['lang'] === null ? null : strtolower(explode('-', $page['lang'])[0]);

        if ($lang !== $page['language']) {
            $error($page['lang'] === null ? 'No lang on <html>' : "<html lang=\"{$page['lang']}\">, expected \"{$page['language']}\"");
        }

        if ($page['titles'] === [] || $page['titles'][0] === '') {
            $error('No <title>');
        } elseif (count($page['titles']) > 1) {
            $warning(count($page['titles']).' <title> tags');
        }

        if ($page['description'] === null) {
            $warning('No meta description');
        }

        if ($page['h1'] !== 1) {
            $warning($page['h1'] === 0 ? 'No <h1>' : "{$page['h1']} <h1> headings");
        }

        if ($page['headingJumps'] !== []) {
            $warning('Heading levels skip: '.implode(', ', $page['headingJumps']));
        }

        if ($page['imagesWithoutAlt'] !== []) {
            $warning('Images without alt text: '.implode(', ', $page['imagesWithoutAlt']));
        }

        $alternates = $page['hreflang'];

        if ($alternates === []) {
            if (! Locales::isDefault($page['language'])) {
                $error('No hreflang alternates');
            }

            return $issues;
        }

        if (! $this->sameUrl($alternates[$page['language']] ?? '', $url)) {
            $error("hreflang \"{$page['language']}\" does not point to the page itself");
        }

        if (! isset($alternates['x-default'])) {
            $warning('No x-default hreflang');
        }

        if ($listedAlternates === []) {
            $warning('The sitemap lists no hreflang alternates for this page');
        } elseif ($this->normalized($listedAlternates) !== $this->normalized($alternates)) {
            $warning('The page and the sitemap list different hreflang alternates');
        }

        foreach ($alternates as $code => $href) {
            if ($code === 'x-default' || $this->sameUrl($href, $url)) {
                continue;
            }

            $alternate = $this->find($pages, $href);

            if ($alternate === null) {
                $warning("hreflang \"{$code}\" points to {$href}, which is not in the sitemap");
            } elseif ($alternate['status'] !== 200) {
                $error("hreflang \"{$code}\" points to {$href}, which returns HTTP {$alternate['status']}");
            } elseif ($alternate['language'] !== strtolower($code)) {
                $error("hreflang \"{$code}\" points to a page in \"{$alternate['language']}\"");
            } elseif (! $this->sameUrl($alternate['hreflang'][$page['language']] ?? '', $url)) {
                $error("{$href} does not link back to this page as hreflang \"{$page['language']}\"");
            }
        }

        $english = $this->find($pages, $alternates[Locales::default()] ?? '');

        if (! Locales::isDefault($page['language']) && $english !== null && $english['titles'] !== [] && $english['titles'][0] === ($page['titles'][0] ?? null)) {
            $warning('Same title as the English page');
        }

        return $issues;
    }

    /**
     * Pages of the same language sharing a title compete for the same searches.
     *
     * @param  array<string, Page>  $pages
     * @return list<Issue>
     */
    private function duplicateTitleIssues(array $pages): array
    {
        $groups = [];

        foreach ($pages as $page) {
            if ($page['status'] === 200 && ($page['titles'][0] ?? '') !== '') {
                $groups[$page['language']."\0".$page['titles'][0]][] = $page['url'];
            }
        }

        $issues = [];

        foreach (array_filter($groups, fn (array $urls): bool => count($urls) > 1) as $urls) {
            foreach ($urls as $url) {
                $issues[] = $this->issue($url, 'warning', 'Same title as '.implode(', ', array_diff($urls, [$url])));
            }
        }

        return $issues;
    }

    /**
     * The language of a URL from its prefix; unprefixed URLs are English.
     */
    private function languageOf(string $url): string
    {
        $segment = explode('/', trim((string) parse_url($url, PHP_URL_PATH), '/'))[0];

        return in_array($segment, Locales::prefixed(), true) ? $segment : Locales::default();
    }

    /**
     * @param  array<string, Page>  $pages
     * @return Page|null
     */
    private function find(array $pages, string $url): ?array
    {
        foreach ($pages as $pageUrl => $page) {
            if ($this->sameUrl($pageUrl, $url)) {
                return $page;
            }
        }

        return null;
    }

    private function sameUrl(string $first, string $second): bool
    {
        return $first !== '' && $this->normalizeUrl($first) === $this->normalizeUrl($second);
    }

    /**
     * The home page is the same with or without its trailing slash.
     */
    private function normalizeUrl(string $url): string
    {
        return rtrim($url, '/');
    }

    /**
     * @param  array<string, string>  $alternates
     * @return array<string, string>
     */
    private function normalized(array $alternates): array
    {
        $alternates = array_map($this->normalizeUrl(...), $alternates);
        ksort($alternates);

        return $alternates;
    }

    /**
     * @param  'error'|'warning'  $level
     * @return Issue
     */
    private function issue(string $url, string $level, string $message): array
    {
        return ['url' => $url, 'level' => $level, 'message' => $message];
    }
}
