<?php

namespace App\Console\Commands\Portal;

use App\Services\Portal\OverdueService;
use Illuminate\Console\Command;

class RefreshOverdueCommand extends Command
{
    protected $signature = 'portal:refresh-overdue';

    protected $description = 'Reconcile cached overdue flags on portal tasks with their deadlines';

    public function handle(OverdueService $overdue): int
    {
        ['flagged' => $flagged, 'cleared' => $cleared] = $overdue->refresh();

        $this->info("Portal overdue refreshed: {$flagged} flagged, {$cleared} cleared.");

        return self::SUCCESS;
    }
}
