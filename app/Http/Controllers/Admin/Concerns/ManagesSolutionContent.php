<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Solution;
use Illuminate\Http\Request;

/**
 * Normalizes the structured `content` payload coming from the solution admin
 * form: forces section toggles to booleans, stores per-item icon uploads and
 * the single why-choose image, and strips/reindexes empty repeater rows so the
 * persisted JSON stays clean.
 *
 * Relies on {@see UploadsFiles} for the actual file storage helpers.
 */
trait ManagesSolutionContent
{
    /** Sections that carry an on/off visibility toggle. */
    private const CONTENT_TOGGLES = [
        'stats', 'modules', 'benefits', 'process',
        'industries', 'why_choose', 'cta_band',
    ];

    /**
     * Build the final `content` array into $data, ready to persist.
     *
     * @param  array<string, mixed>  $data
     */
    protected function prepareContent(Request $request, array &$data, ?Solution $solution = null): void
    {
        /** @var array<string, mixed> $content */
        $content = $data['content'] ?? [];

        foreach (self::CONTENT_TOGGLES as $section) {
            $content[$section]['enabled'] = $request->boolean("content.{$section}.enabled");
        }

        // Per-item icon uploads (keep the existing filename when none is chosen).
        $this->syncItemIcons($request, $content, 'hero', 'badges');
        $this->syncItemIcons($request, $content, 'stats', 'items');
        $this->syncItemIcons($request, $content, 'modules', 'cards');
        $this->syncItemIcons($request, $content, 'benefits', 'items');
        $this->syncItemIcons($request, $content, 'process', 'steps');

        // Single why-choose image (replace-on-upload, delete the old file).
        $current = $solution?->content['why_choose']['image'] ?? null;
        if ($request->hasFile('content.why_choose.image_file')) {
            $this->deleteUpload($current);
            $content['why_choose']['image'] = $this->storeUpload($request->file('content.why_choose.image_file'), 'solutions');
        }
        unset($content['why_choose']['image_file']);

        // Drop empty repeater rows and reindex every list.
        $content['hero']['benefits'] = $this->cleanStrings($content['hero']['benefits'] ?? []);
        $content['hero']['badges'] = $this->cleanRows($content['hero']['badges'] ?? [], ['label', 'icon']);
        $content['stats']['items'] = $this->cleanRows($content['stats']['items'] ?? [], ['value', 'label', 'icon']);
        $content['modules']['cards'] = $this->cleanRows($content['modules']['cards'] ?? [], ['title', 'description', 'icon']);
        $content['benefits']['items'] = $this->cleanRows($content['benefits']['items'] ?? [], ['title', 'description', 'icon']);
        $content['process']['steps'] = $this->cleanRows($content['process']['steps'] ?? [], ['title', 'description', 'icon']);
        $content['why_choose']['points'] = $this->cleanStrings($content['why_choose']['points'] ?? []);

        $data['content'] = $content;
    }

    /**
     * Store any uploaded icon for each row in a repeater list and drop the
     * transient `icon_file` key, leaving `icon` holding the stored filename.
     *
     * @param  array<string, mixed>  $content
     */
    private function syncItemIcons(Request $request, array &$content, string $section, string $listKey): void
    {
        if (empty($content[$section][$listKey]) || ! is_array($content[$section][$listKey])) {
            return;
        }

        foreach ($content[$section][$listKey] as $index => &$item) {
            if (! is_array($item)) {
                continue;
            }

            $fileKey = "content.{$section}.{$listKey}.{$index}.icon_file";
            if ($request->hasFile($fileKey)) {
                $item['icon'] = $this->storeUpload($request->file($fileKey), 'solutions');
            }

            unset($item['icon_file']);
        }
    }

    /**
     * Trim a flat list of strings, dropping blanks and reindexing.
     *
     * @param  array<int, mixed>  $values
     * @return array<int, string>
     */
    private function cleanStrings(array $values): array
    {
        $clean = [];

        foreach ($values as $value) {
            $value = is_string($value) ? trim($value) : $value;
            if (filled($value)) {
                $clean[] = $value;
            }
        }

        return $clean;
    }

    /**
     * Keep only repeater rows that have at least one of the given keys filled,
     * then reindex.
     *
     * @param  array<int, mixed>  $rows
     * @param  array<int, string>  $keys
     * @return array<int, array<string, mixed>>
     */
    private function cleanRows(array $rows, array $keys): array
    {
        $clean = [];

        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }

            foreach ($keys as $key) {
                if (filled($row[$key] ?? null)) {
                    $clean[] = $row;
                    break;
                }
            }
        }

        return $clean;
    }
}
