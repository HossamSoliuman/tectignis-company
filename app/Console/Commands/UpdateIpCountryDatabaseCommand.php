<?php

namespace App\Console\Commands;

use App\Services\IpCountryDatabase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class UpdateIpCountryDatabaseCommand extends Command
{
    protected $signature = 'ip-country:update {--force : Download the latest release even when it is already installed}';

    protected $description = 'Download the monthly DB-IP country database used to preselect the enquiry form calling code';

    public function handle(IpCountryDatabase $database): int
    {
        try {
            $release = $database->update((bool) $this->option('force'));
        } catch (RuntimeException $exception) {
            Log::warning('IP country database update failed', ['error' => $exception->getMessage()]);
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info($release === null
            ? "IP country database is current ({$database->installedRelease()})."
            : "IP country database updated to {$release}.");

        return self::SUCCESS;
    }
}
