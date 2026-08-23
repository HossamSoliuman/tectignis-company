<?php

namespace App\Http\Controllers\Admin\Portal\Concerns;

use App\Models\Portal\Tender;
use App\Services\Portal\TenderCompletionService;

/**
 * Shared setup for every tab of the tender workspace (§27).
 *
 * The sticky header — deadline countdown, owner, stage, readiness — is the same
 * on all eleven tabs, so each tab controller loads it the same way instead of
 * eleven near-identical view payloads drifting apart.
 */
trait ResolvesTenderWorkspace
{
    /**
     * Header payload every tab view expects, merged with the tab's own data.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function workspace(Tender $tender, string $tab, array $data = []): array
    {
        $tender->loadMissing(['owner:id,name', 'technicalOwner:id,name', 'salesOwner:id,name']);

        return [
            'tender' => $tender,
            'tab' => $tab,
            'summary' => app(TenderCompletionService::class)->summary($tender),
            ...$data,
        ];
    }

    /**
     * Authorize a read of the workspace and return the header payload.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function viewWorkspace(Tender $tender, string $tab, array $data = []): array
    {
        $this->authorize('view', $tender);

        return $this->workspace($tender, $tab, $data);
    }
}
