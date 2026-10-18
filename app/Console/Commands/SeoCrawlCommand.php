<?php

namespace App\Console\Commands;

use App\Services\SeoCrawler;
use App\Support\Locales;
use Illuminate\Console\Command;
use RuntimeException;

class SeoCrawlCommand extends Command
{
    protected $signature = 'seo:crawl {url? : The site to crawl, APP_URL by default}';

    protected $description = 'Crawl the sitemap like a search engine and report indexing, canonical, hreflang, title, heading and alt text problems';

    public function handle(SeoCrawler $crawler): int
    {
        $baseUrl = $this->argument('url') ?: config('app.url');

        try {
            $report = $crawler->crawl($baseUrl);
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Crawled '.count($report['pages'])." pages from {$report['sitemap']}.");

        foreach (collect($report['issues'])->groupBy('url') as $url => $issues) {
            $this->newLine();
            $this->line("<options=bold>{$url}</>");

            foreach ($issues as $issue) {
                $this->line($issue['level'] === 'error'
                    ? "  <fg=red>error</>    {$issue['message']}"
                    : "  <fg=yellow>warning</>  {$issue['message']}");
            }
        }

        $this->newLine();
        $this->table(['Language', 'Pages', 'Errors', 'Warnings'], $this->summary($report));

        $errors = collect($report['issues'])->where('level', 'error')->count();
        $warnings = count($report['issues']) - $errors;
        $this->line("{$errors} errors, {$warnings} warnings.");

        return $errors === 0 ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Pages and issues per language, in the order of config/locales.php.
     *
     * @param  array{pages: array<string, array{language: string}>, issues: list<array{url: string, level: string}>}  $report
     * @return list<list<string|int>>
     */
    private function summary(array $report): array
    {
        $rows = [];

        foreach (Locales::codes() as $code) {
            $urls = array_keys(array_filter($report['pages'], fn (array $page): bool => $page['language'] === $code));

            if ($urls === []) {
                continue;
            }

            $issues = collect($report['issues'])->whereIn('url', $urls);
            $rows[] = [Locales::label($code), count($urls), $issues->where('level', 'error')->count(), $issues->where('level', 'warning')->count()];
        }

        return $rows;
    }
}
