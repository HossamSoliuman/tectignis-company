<?php

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('rewrites existing lead phone numbers in E.164 form and keeps the ones it cannot read', function () {
    $spaced = Lead::factory()->create(['phone' => '+44 7400 123456']);
    $deleted = Lead::factory()->create(['phone' => '+971 50 123 4567']);
    $deleted->delete();
    $unreadable = Lead::factory()->create(['phone' => '9876543210']);
    $empty = Lead::factory()->create(['phone' => null]);

    (require database_path('migrations/2026_10_17_161513_normalize_lead_phone_numbers.php'))->up();

    expect($spaced->fresh()->phone)->toBe('+447400123456')
        ->and($deleted->fresh()->phone)->toBe('+971501234567')
        ->and($unreadable->fresh()->phone)->toBe('9876543210')
        ->and($empty->fresh()->phone)->toBeNull();
});

it('shows stored phone numbers grouped for reading', function () {
    expect(Lead::factory()->make(['phone' => '+447400123456'])->phoneLabel())->toBe('+44 7400 123456')
        ->and(Lead::factory()->make(['phone' => '9876543210'])->phoneLabel())->toBe('9876543210')
        ->and(Lead::factory()->make(['phone' => null])->phoneLabel())->toBeNull();
});
