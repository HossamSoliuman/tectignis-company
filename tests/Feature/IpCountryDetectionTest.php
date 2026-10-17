<?php

use App\Services\IpCountryDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

/*
 * tests/Fixtures/country-test.mmdb maps 1.2.3.0/24 to AE and 81.2.69.0/24 to GB.
 */

beforeEach(function () {
    $this->geoipDirectory = sys_get_temp_dir().'/tectignis-geoip-'.uniqid();
    config(['services.country_detection.database' => $this->geoipDirectory.'/dbip-country-lite.mmdb']);
    Http::preventStrayRequests();
});

afterEach(function () {
    File::deleteDirectory($this->geoipDirectory);
});

function installTestCountryDatabase(string $release = '2026-09'): void
{
    $path = config('services.country_detection.database');
    File::ensureDirectoryExists(dirname($path));
    File::copy(base_path('tests/Fixtures/country-test.mmdb'), $path);
    File::put($path.'.release', $release);
}

function testCountryDatabaseDownload(): string
{
    return gzencode(File::get(base_path('tests/Fixtures/country-test.mmdb')));
}

describe('phone field', function () {
    it('preselects the calling code of the country the IP address belongs to', function () {
        installTestCountryDatabase();

        $this->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->get(route('contact'))
            ->assertOk()
            ->assertSee('name="phone_country" value="AE"', false)
            ->assertSee('data-phone-detected ', false);
    });

    it('prefers the CDN country header over the database', function () {
        installTestCountryDatabase();

        $this->withServerVariables(['REMOTE_ADDR' => '1.2.3.4'])
            ->get(route('contact'), ['CF-IPCountry' => 'GB'])
            ->assertOk()
            ->assertSee('name="phone_country" value="GB"', false);
    });

    it('falls back to the default calling code when the IP address is unknown', function (bool $installed, string $ip) {
        if ($installed) {
            installTestCountryDatabase();
        }

        $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->get(route('contact'))
            ->assertOk()
            ->assertSee('name="phone_country" value="IN"', false)
            ->assertDontSee('data-phone-detected ', false);
    })->with([
        'address not in the database' => [true, '8.8.8.8'],
        'IPv6 address' => [true, '2001:db8::1'],
        'no database installed' => [false, '1.2.3.4'],
    ]);
});

describe('credit', function () {
    it('credits DB-IP in the footers while its database is installed', function (string $url) {
        installTestCountryDatabase();

        $this->get($url)->assertOk()->assertSee('<a href="https://db-ip.com" target="_blank" rel="noopener">IP Geolocation by DB-IP</a>', false);
    })->with(['site footer' => '/', 'landing page footer' => '/lp/website-development']);

    it('shows no credit without the database', function () {
        $this->get('/')->assertOk()->assertDontSee('https://db-ip.com', false);
    });
});

describe('update command', function () {
    it("installs this month's release", function () {
        $this->travelTo('2026-10-17');
        Http::fake(['download.db-ip.com/free/dbip-country-lite-2026-10.mmdb.gz' => Http::response(testCountryDatabaseDownload())]);

        $this->artisan('ip-country:update')
            ->expectsOutput('IP country database updated to 2026-10.')
            ->assertSuccessful();

        $database = app(IpCountryDatabase::class);

        expect($database->installedRelease())->toBe('2026-10')
            ->and($database->lookup('81.2.69.160'))->toBe('GB');
    });

    it("installs last month's release while this month's isn't published", function () {
        $this->travelTo('2026-11-01');
        Http::fake([
            'download.db-ip.com/free/dbip-country-lite-2026-11.mmdb.gz' => Http::response('Not Found', 404),
            'download.db-ip.com/free/dbip-country-lite-2026-10.mmdb.gz' => Http::response(testCountryDatabaseDownload()),
        ]);

        $this->artisan('ip-country:update')->assertSuccessful();

        expect(app(IpCountryDatabase::class)->installedRelease())->toBe('2026-10');
    });

    it('downloads nothing when the latest release is installed', function () {
        $this->travelTo('2026-11-01');
        installTestCountryDatabase('2026-10');
        Http::fake(['download.db-ip.com/free/dbip-country-lite-2026-11.mmdb.gz' => Http::response('Not Found', 404)]);

        $this->artisan('ip-country:update')
            ->expectsOutput('IP country database is current (2026-10).')
            ->assertSuccessful();

        Http::assertSentCount(1);
    });

    it('keeps the working database when a download is broken', function (string $body) {
        $this->travelTo('2026-10-17');
        installTestCountryDatabase('2026-09');
        Http::fake(['download.db-ip.com/free/dbip-country-lite-2026-10.mmdb.gz' => Http::response($body)]);

        $this->artisan('ip-country:update')->assertFailed();

        $database = app(IpCountryDatabase::class);

        expect($database->installedRelease())->toBe('2026-09')
            ->and($database->lookup('1.2.3.4'))->toBe('AE');
    })->with([
        'not gzip' => 'not a database',
        'not a database' => gzencode('not a database'),
        'damaged database' => fn (): string => gzencode(substr(File::get(base_path('tests/Fixtures/country-test.mmdb')), 0, 200)."\xAB\xCD\xEFMaxMind.com"),
    ]);

    it('fails when no release can be downloaded', function () {
        $this->travelTo('2026-10-17');
        Http::fake(['download.db-ip.com/*' => Http::response('Not Found', 404)]);

        $this->artisan('ip-country:update')->assertFailed();

        expect(app(IpCountryDatabase::class)->isInstalled())->toBeFalse();
    });
});
