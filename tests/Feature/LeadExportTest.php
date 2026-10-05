<?php

use App\Enums\LeadStatus;
use App\Enums\UserRole;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('super admins export the filtered leads as CSV', function () {
    Lead::factory()->enquiry()->create(['name' => 'Exported Lead', 'company' => 'Hooli', 'country' => 'India', 'status' => LeadStatus::Proposal]);
    Lead::factory()->enquiry()->create(['name' => 'Filtered Out', 'country' => 'Canada']);

    $response = $this->actingAs(User::factory()->role(UserRole::SuperAdmin)->create())
        ->get(route('admin.leads.export', ['country' => 'India']))
        ->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8');

    $csv = $response->streamedContent();

    expect($response->headers->get('content-disposition'))->toContain('attachment; filename=leads-')
        ->and($csv)->toContain('"Lead ID",Submitted,Status,Name,Company')
        ->toContain('Exported Lead')
        ->toContain('Hooli')
        ->toContain('Proposal')
        ->not->toContain('Filtered Out');
});

it('export needs its own permission on top of viewing', function () {
    Lead::factory()->create();

    $this->actingAs(User::factory()->role(UserRole::Sales)->create())
        ->get(route('admin.leads.export'))
        ->assertForbidden();

    $this->actingAs(User::factory()->role(UserRole::Sales)->canExportLeads()->create())
        ->get(route('admin.leads.export'))
        ->assertOk();

    $this->actingAs(User::factory()->role(UserRole::ReadOnly)->canExportLeads()->create())
        ->get(route('admin.leads.export'))
        ->assertOk();
});

it('hides the export button from users without the permission', function () {
    $this->actingAs(User::factory()->role(UserRole::Sales)->create())
        ->get(route('admin.leads.index'))
        ->assertDontSee('Export CSV');

    $this->actingAs(User::factory()->role(UserRole::Sales)->canExportLeads()->create())
        ->get(route('admin.leads.index'))
        ->assertSee('Export CSV');
});

it('neutralises spreadsheet formulas in visitor-supplied values', function () {
    Lead::factory()->create(['name' => '=HYPERLINK("http://evil.example","click")']);

    $csv = $this->actingAs(User::factory()->role(UserRole::SuperAdmin)->create())
        ->get(route('admin.leads.export'))
        ->streamedContent();

    expect($csv)->toContain("'=HYPERLINK");
});
