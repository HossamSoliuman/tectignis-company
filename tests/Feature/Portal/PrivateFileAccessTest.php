<?php

use App\Enums\Portal\PortalRole;
use App\Models\Portal\Attachment;
use App\Models\Portal\Employee;
use App\Models\Portal\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('portal');
});

it('stores a task attachment on the private disk, never in public', function () {
    [$director] = portalUser(PortalRole::Director);

    $this->actingAs($director)->post(route('admin.portal.tasks.store'), [
        'title' => 'Tender pricing sheet',
        'priority' => 'high',
        'status' => 'not_started',
        'attachment' => UploadedFile::fake()->create('pricing.pdf', 64, 'application/pdf'),
    ])->assertRedirect();

    $attachment = Attachment::first();

    expect($attachment)->not->toBeNull()
        ->and($attachment->disk)->toBe('portal')
        ->and($attachment->original_name)->toBe('pricing.pdf');

    Storage::disk('portal')->assertExists($attachment->path);
    expect(file_exists(public_path('uploads/'.$attachment->path)))->toBeFalse();
});

it('streams a document to somebody allowed to see the owning task', function () {
    [$director] = portalUser(PortalRole::Director);
    $task = Task::factory()->create();
    $attachment = attachFileTo($task);

    $this->actingAs($director)->get(route('admin.portal.files.show', $attachment))
        ->assertOk()
        ->assertDownload('confidential.pdf');
});

it('refuses a document belonging to a task the user cannot see', function () {
    $user = User::factory()->portal(PortalRole::Employee)->create();
    Employee::factory()->create(['user_id' => $user->id]);

    $attachment = attachFileTo(Task::factory()->create());

    $this->actingAs($user)->get(route('admin.portal.files.show', $attachment))->assertForbidden();
});

it('refuses a document to a user with no portal access at all', function () {
    $outsider = User::factory()->create(['role' => 'admin', 'portal_role' => null]);
    $attachment = attachFileTo(Task::factory()->create());

    $this->actingAs($outsider)->get(route('admin.portal.files.show', $attachment))->assertForbidden();
});

it('requires authentication to read a document', function () {
    $attachment = attachFileTo(Task::factory()->create());

    $this->get(route('admin.portal.files.show', $attachment))->assertRedirect(route('login'));
});

it('404s instead of resolving a path that escapes the disk', function () {
    [$director] = portalUser(PortalRole::Director);
    $task = Task::factory()->create();

    $attachment = $task->attachments()->create([
        'disk' => 'portal',
        'path' => '../../../.env',
        'original_name' => 'env-steal.txt',
        'mime_type' => 'text/plain',
        'size' => 10,
        'version' => 1,
    ]);

    $this->actingAs($director)->get(route('admin.portal.files.show', $attachment))->assertNotFound();
});

it('records every download in the audit trail', function () {
    [$director] = portalUser(PortalRole::Director);
    $task = Task::factory()->create();
    $attachment = attachFileTo($task);

    $this->actingAs($director)->get(route('admin.portal.files.show', $attachment));

    $this->assertDatabaseHas('portal_activity_logs', [
        'event' => 'file_downloaded',
        'subject_type' => $task->getMorphClass(),
        'subject_id' => $task->id,
        'user_id' => $director->id,
    ]);
});

/**
 * Put a real file on the faked private disk and attach it to a record.
 */
function attachFileTo(Task $task): Attachment
{
    Storage::disk('portal')->put('tasks/'.$task->id.'/confidential.pdf', 'sensitive');

    return $task->attachments()->create([
        'disk' => 'portal',
        'path' => 'tasks/'.$task->id.'/confidential.pdf',
        'original_name' => 'confidential.pdf',
        'mime_type' => 'application/pdf',
        'size' => 9,
        'version' => 1,
    ]);
}
