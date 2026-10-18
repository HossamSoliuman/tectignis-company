<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Reads a robots.txt the way Google does: a crawler follows the group naming
 * it most specifically, else the `*` group; within it the longest matching
 * rule wins, and Allow wins a tie. Rules may use `*` and a closing `$`.
 */
final class RobotsTxt
{
    /**
     * Rules per lower-case user agent.
     *
     * @var array<string, list<array{allow: bool, pattern: string}>>
     */
    private array $groups = [];

    /**
     * @var list<string>
     */
    private array $sitemaps = [];

    /**
     * Line numbers holding more than one directive, which crawlers misread.
     *
     * @var list<int>
     */
    private array $joinedLines = [];

    public function __construct(string $content)
    {
        $agents = [];
        $readingRules = false;

        foreach (preg_split('/\R/', $content) as $number => $line) {
            $line = trim(Str::before($line, '#'));

            if (! str_contains($line, ':')) {
                continue;
            }

            [$field, $value] = array_map(trim(...), explode(':', $line, 2));
            $field = strtolower($field);

            if (preg_match('/\b(?:user-agent|allow|disallow|sitemap|crawl-delay)\s*:/i', $value)) {
                $this->joinedLines[] = $number + 1;
            }

            if ($field === 'sitemap') {
                $this->sitemaps[] = $value;
            } elseif ($field === 'user-agent') {
                // A user-agent line after rules starts a new group.
                if ($readingRules) {
                    $agents = [];
                    $readingRules = false;
                }

                $agents[] = strtolower($value);
                $this->groups[strtolower($value)] ??= [];
            } elseif ($field === 'allow' || $field === 'disallow') {
                $readingRules = true;

                foreach ($agents as $agent) {
                    $this->groups[$agent][] = ['allow' => $field === 'allow', 'pattern' => $value];
                }
            }
        }
    }

    public function allows(string $url, string $userAgent = 'Googlebot'): bool
    {
        $query = parse_url($url, PHP_URL_QUERY);
        $path = (parse_url($url, PHP_URL_PATH) ?: '/').($query ? '?'.$query : '');
        $winner = null;

        foreach ($this->rulesFor($userAgent) as $rule) {
            // An empty Disallow blocks nothing.
            if ($rule['pattern'] === '' || ! $this->matches($rule['pattern'], $path)) {
                continue;
            }

            $length = strlen($rule['pattern']);
            $winnerLength = $winner === null ? -1 : strlen($winner['pattern']);

            if ($length > $winnerLength || ($length === $winnerLength && $rule['allow'])) {
                $winner = $rule;
            }
        }

        return $winner === null || $winner['allow'];
    }

    /**
     * @return list<string>
     */
    public function sitemaps(): array
    {
        return $this->sitemaps;
    }

    /**
     * @return list<int>
     */
    public function joinedLines(): array
    {
        return $this->joinedLines;
    }

    /**
     * @return list<array{allow: bool, pattern: string}>
     */
    private function rulesFor(string $userAgent): array
    {
        $userAgent = strtolower($userAgent);
        $match = null;

        foreach (array_keys($this->groups) as $agent) {
            if ($agent !== '*' && $agent !== '' && str_contains($userAgent, $agent) && strlen($agent) > strlen($match ?? '')) {
                $match = $agent;
            }
        }

        return $this->groups[$match ?? '*'] ?? [];
    }

    private function matches(string $pattern, string $path): bool
    {
        $anchored = str_ends_with($pattern, '$');
        $regex = str_replace('\*', '.*', preg_quote($anchored ? substr($pattern, 0, -1) : $pattern, '#'));

        return preg_match('#^'.$regex.($anchored ? '$' : '').'#', $path) === 1;
    }
}
