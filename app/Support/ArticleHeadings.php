<?php

namespace App\Support;

/**
 * Admin-written article bodies sit under the page's h1, but editors pick a
 * heading level for its size. The body's headings are renumbered so its top
 * level is h2 and no level is skipped; a renumbered heading keeps the size of
 * the level it was written at through `data-heading-size`.
 */
final class ArticleHeadings
{
    public static function outline(?string $html): string
    {
        if ($html === null || ! preg_match_all('/<h([1-6])\b/i', $html, $matches)) {
            return (string) $html;
        }

        $used = array_values(array_unique(array_map(intval(...), $matches[1])));
        sort($used);

        // The levels used, in order, become h2, h3, ...
        $levels = [];

        foreach ($used as $index => $level) {
            $levels[$level] = min(6, $index + 2);
        }

        return preg_replace_callback('/<(\/?)h([1-6])\b([^>]*)>/i', function (array $tag) use ($levels): string {
            $written = (int) $tag[2];
            $level = $levels[$written];

            if ($level === $written) {
                return $tag[0];
            }

            return $tag[1] === '/' ? "</h{$level}>" : "<h{$level} data-heading-size=\"{$written}\"{$tag[3]}>";
        }, $html);
    }
}
