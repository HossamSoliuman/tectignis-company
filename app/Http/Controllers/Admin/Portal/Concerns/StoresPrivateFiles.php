<?php

namespace App\Http\Controllers\Admin\Portal\Concerns;

use App\Models\Portal\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Portal counterpart to UploadsFiles.
 *
 * The CMS trait writes to public/uploads because website images are public by
 * design. Portal documents are confidential — ITR, audited statements, MAF,
 * pricing — so they land on the private `portal` disk and are only ever read
 * back through `admin.portal.files.show`, which authorizes first.
 */
trait StoresPrivateFiles
{
    /**
     * Store an uploaded file privately and attach it to the owning record.
     */
    protected function storePrivateFile(UploadedFile $file, Model $attachable, string $folder = ''): Attachment
    {
        $disk = config('portal.disk', 'portal');
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = $name.'-'.Str::lower(Str::random(8)).'.'.$file->getClientOriginalExtension();

        $path = Storage::disk($disk)->putFileAs(trim($folder, '/'), $file, $filename);

        return $attachable->attachments()->create([
            'disk' => $disk,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'uploaded_by_id' => request()->user()?->id,
            'version' => $attachable->attachments()->max('version') + 1,
        ]);
    }

    /**
     * Store the optional `attachment` field of a form, if one was submitted.
     */
    protected function storeOptionalUpload(Model $attachable, string $folder, string $field = 'attachment'): ?Attachment
    {
        $file = request()->file($field);

        return $file instanceof UploadedFile
            ? $this->storePrivateFile($file, $attachable, $folder)
            : null;
    }

    /**
     * Delete an attachment along with the file it points at.
     */
    protected function deletePrivateFile(Attachment $attachment): void
    {
        Storage::disk($attachment->disk)->delete($attachment->path);

        $attachment->delete();
    }
}
