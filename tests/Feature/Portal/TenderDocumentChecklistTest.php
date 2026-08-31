<?php

use App\Enums\Portal\PortalRole;
use App\Enums\Portal\TenderDocumentStatus;
use App\Models\Portal\Employee;
use App\Models\Portal\Tender;
use App\Models\Portal\TenderDocument;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('stores an uploaded bid document on the private disk', function () {
    Storage::fake(config('portal.disk', 'portal'));

    [$director] = portalUser();
    $tender = Tender::factory()->create();
    $document = TenderDocument::factory()->create(['tender_id' => $tender->id]);

    $this->actingAs($director)->put(route('admin.portal.tenders.documents.update', [$tender, $document]), [
        'status' => TenderDocumentStatus::Uploaded->value,
        'attachment' => UploadedFile::fake()->create('audited-statement.pdf', 120, 'application/pdf'),
    ])->assertRedirect();

    $document->refresh();

    expect($document->attachment_id)->not->toBeNull()
        ->and($document->status)->toBe(TenderDocumentStatus::Uploaded);

    Storage::disk(config('portal.disk', 'portal'))->assertExists($document->attachment->path);
});

it('keeps every earlier version when a document is replaced', function () {
    Storage::fake(config('portal.disk', 'portal'));

    [$director] = portalUser();
    $tender = Tender::factory()->create();
    $document = TenderDocument::factory()->create(['tender_id' => $tender->id]);

    foreach (['v1.pdf', 'v2.pdf'] as $name) {
        $this->actingAs($director)->put(route('admin.portal.tenders.documents.update', [$tender, $document]), [
            'status' => TenderDocumentStatus::Uploaded->value,
            'attachment' => UploadedFile::fake()->create($name, 50, 'application/pdf'),
        ]);
    }

    $document->refresh();

    expect($document->attachments)->toHaveCount(2)
        ->and($document->version)->toBe(2)
        ->and($document->attachment->original_name)->toBe('v2.pdf');
});

it('invalidates an earlier sign-off when a new version arrives', function () {
    Storage::fake(config('portal.disk', 'portal'));

    [$director, $employee] = portalUser();
    $tender = Tender::factory()->create();
    $document = TenderDocument::factory()->ready()->create([
        'tender_id' => $tender->id,
        'verified_by_id' => $employee->id,
        'verified_at' => now(),
    ]);

    $this->actingAs($director)->put(route('admin.portal.tenders.documents.update', [$tender, $document]), [
        'status' => TenderDocumentStatus::Uploaded->value,
        'attachment' => UploadedFile::fake()->create('revised.pdf', 50, 'application/pdf'),
    ]);

    expect($document->refresh()->isVerified())->toBeFalse();
});

it('records who verified a document and when', function () {
    [$director, $employee] = portalUser();
    $tender = Tender::factory()->create();
    $document = TenderDocument::factory()->uploaded()->create(['tender_id' => $tender->id]);

    $this->actingAs($director)->put(route('admin.portal.tenders.documents.verify', [$tender, $document]))->assertRedirect();

    $document->refresh();

    expect($document->verified_by_id)->toBe($employee->id)
        ->and($document->verified_at)->not->toBeNull()
        ->and($document->status)->toBe(TenderDocumentStatus::Ready);
});

it('keeps verification away from an employee who is only working the bid', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();
    $employee = Employee::factory()->create(['user_id' => $user->id]);
    $tender = Tender::factory()->create(['assigned_employee_id' => $employee->id]);
    $document = TenderDocument::factory()->uploaded()->create(['tender_id' => $tender->id]);

    $this->actingAs($user)->put(route('admin.portal.tenders.documents.verify', [$tender, $document]))->assertForbidden();

    expect($document->refresh()->isVerified())->toBeFalse();
});

it('counts only unsettled mandatory documents as gaps', function () {
    $tender = Tender::factory()->create();
    TenderDocument::factory()->count(2)->create(['tender_id' => $tender->id]);
    TenderDocument::factory()->ready()->create(['tender_id' => $tender->id]);
    TenderDocument::factory()->optional()->create(['tender_id' => $tender->id]);

    expect($tender->documents()->missingMandatory()->count())->toBe(2);
});

it('flags a document whose validity date has passed', function () {
    $tender = Tender::factory()->create();
    $expired = TenderDocument::factory()->expired()->create(['tender_id' => $tender->id]);
    $current = TenderDocument::factory()->uploaded()->create(['tender_id' => $tender->id]);

    expect($expired->isExpired())->toBeTrue()
        ->and($current->isExpired())->toBeFalse();
});

it('will not serve a bid document to somebody outside the bid', function () {
    Storage::fake(config('portal.disk', 'portal'));

    [$director] = portalUser();
    $tender = Tender::factory()->create();
    $document = TenderDocument::factory()->create(['tender_id' => $tender->id]);

    $this->actingAs($director)->put(route('admin.portal.tenders.documents.update', [$tender, $document]), [
        'status' => TenderDocumentStatus::Uploaded->value,
        'attachment' => UploadedFile::fake()->create('confidential.pdf', 20, 'application/pdf'),
    ]);

    $outsider = User::factory()->portal(PortalRole::Employee)->create();
    Employee::factory()->create(['user_id' => $outsider->id]);

    $this->actingAs($outsider)
        ->get(route('admin.portal.files.show', $document->refresh()->attachment))
        ->assertForbidden();
});
