<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use MaxMind\Db\Reader;
use RuntimeException;
use Throwable;

/**
 * The free DB-IP "IP to Country Lite" database, kept on the server so the
 * enquiry form can preselect a visitor's calling code from their IP address
 * without sending it to a lookup service.
 *
 * The data is licensed CC BY 4.0, which asks for an "IP Geolocation by
 * DB-IP" link on the pages that use it: the site footers show it while the
 * database is installed.
 */
class IpCountryDatabase
{
    public const DOWNLOAD_URL = 'https://download.db-ip.com/free/dbip-country-lite-%s.mmdb.gz';

    private ?Reader $reader = null;

    public function path(): string
    {
        return config('services.country_detection.database');
    }

    public function isInstalled(): bool
    {
        return is_file($this->path());
    }

    /**
     * The monthly release installed, e.g. "2026-10".
     */
    public function installedRelease(): ?string
    {
        $marker = $this->path().'.release';

        return $this->isInstalled() && is_file($marker) ? trim((string) file_get_contents($marker)) : null;
    }

    /**
     * The ISO 3166-1 alpha-2 code of the country an IP address belongs to.
     * Null when it isn't found or the database can't be read.
     */
    public function lookup(string $ip): ?string
    {
        if (! $this->isInstalled()) {
            return null;
        }

        try {
            $this->reader ??= new Reader($this->path());
            $isoCode = $this->reader->get($ip)['country']['iso_code'] ?? null;
        } catch (Throwable) {
            return null;
        }

        return is_string($isoCode) ? $isoCode : null;
    }

    /**
     * Install this month's release, or last month's while this month's isn't
     * published yet. Each download is checked before it replaces the current
     * file, so a failed update leaves the working database in place.
     *
     * @return string|null The release installed, or null when it was already current.
     *
     * @throws RuntimeException When no release could be downloaded and checked.
     */
    public function update(bool $force = false): ?string
    {
        foreach ([now()->format('Y-m'), now()->subMonthNoOverflow()->format('Y-m')] as $release) {
            if (! $force && $this->installedRelease() === $release) {
                return null;
            }

            $database = $this->download($release);

            if ($database !== null) {
                $this->install($database, $release);

                return $release;
            }
        }

        throw new RuntimeException('No DB-IP country release could be downloaded from download.db-ip.com.');
    }

    /**
     * The release's database, unpacked, or null when it isn't available.
     *
     * @throws RuntimeException When the download isn't a readable database.
     */
    private function download(string $release): ?string
    {
        $isTemporary = fn (Throwable $exception): bool => $exception instanceof ConnectionException
            || ($exception instanceof RequestException && $exception->response->serverError());

        try {
            $response = Http::timeout(120)->connectTimeout(10)
                ->retry(2, 2000, $isTemporary, throw: false)
                ->get(sprintf(self::DOWNLOAD_URL, $release));
        } catch (ConnectionException) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $database = @gzdecode($response->body());

        if (! is_string($database) || ! str_contains($database, "\xAB\xCD\xEFMaxMind.com")) {
            throw new RuntimeException("The DB-IP {$release} download isn't a country database.");
        }

        return $database;
    }

    /**
     * Write the database next to the current one, check that it opens, then
     * swap it in.
     *
     * @throws RuntimeException When the new file can't be read.
     */
    private function install(string $database, string $release): void
    {
        File::ensureDirectoryExists(dirname($this->path()));

        $staging = $this->path().'.new';
        File::put($staging, $database);

        try {
            (new Reader($staging))->close();
        } catch (Throwable $exception) {
            File::delete($staging);

            throw new RuntimeException("The DB-IP {$release} download can't be read: {$exception->getMessage()}");
        }

        $this->reader?->close();
        $this->reader = null;

        File::move($staging, $this->path());
        File::put($this->path().'.release', $release);
    }
}
