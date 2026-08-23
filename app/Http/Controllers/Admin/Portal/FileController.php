<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Http\Controllers\Controller;
use App\Models\Portal\ActivityLog;
use App\Models\Portal\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;
use League\Flysystem\PathTraversalDetected;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The only way a confidential portal document is ever read.
 *
 * Files live on a private disk with no public URL, so access always runs
 * through here: authorize against the record that owns the file, then stream.
 */
class FileController extends Controller
{
    public function show(Request $request, Attachment $attachment): StreamedResponse
    {
        $owner = $attachment->attachable;

        // An orphaned attachment has no record to authorize against, so it is
        // never served — failing closed is the only safe default here.
        abort_if($owner === null, 404);

        // Attachments inherit the visibility of what they are attached to:
        // whoever may view the task may read its documents, nobody else. Child
        // records (a task update, say) defer to their parent for that decision.
        $this->authorize('view', method_exists($owner, 'attachmentAuthority')
            ? $owner->attachmentAuthority()
            : $owner);

        $disk = Storage::disk($attachment->disk);

        // A stored path that escapes the disk root makes Flysystem throw rather
        // than return false. Treat it exactly like a missing file: the caller
        // learns nothing about what does or does not exist outside the disk.
        try {
            abort_unless($disk->exists($attachment->path), 404);
        } catch (FilesystemException|PathTraversalDetected) {
            abort(404);
        }

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'subject_type' => $owner->getMorphClass(),
            'subject_id' => $owner->getKey(),
            'event' => 'file_downloaded',
            'description' => 'Downloaded '.$attachment->original_name,
            'file_reference' => $attachment->path,
            'ip_address' => $request->ip(),
        ]);

        return $disk->download($attachment->path, $attachment->original_name);
    }
}
