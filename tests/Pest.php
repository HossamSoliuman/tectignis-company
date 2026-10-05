<?php

use App\Enums\Portal\PortalRole;
use App\Models\Portal\Employee;
use App\Models\User;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * A complete, valid project enquiry form submission (spec §11.2).
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function validEnquiry(array $overrides = []): array
{
    return array_merge([
        'name' => 'Jane Doe',
        'email' => 'jane@acme.example',
        'company' => 'Acme Corp',
        'country' => 'United Arab Emirates',
        'phone' => '+971 50 123 4567',
        'service' => 'Cloud Migration',
        'message' => 'We want to move our ERP workloads to Azure next quarter.',
        'budget' => '15k-50k',
        'timeline' => '1-3-months',
        'consent' => '1',
        'source' => 'contact',
    ], $overrides);
}

/**
 * A signed-in portal user at the given role, together with the employee record
 * behind it — the starting point for almost every portal feature test.
 *
 * @return array{0: User, 1: Employee}
 */
function portalUser(PortalRole $role = PortalRole::Director): array
{
    $user = User::factory()->portal($role)->create();
    $employee = Employee::factory()->create(['user_id' => $user->id]);

    return [$user, $employee];
}
