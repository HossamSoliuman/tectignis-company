<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait UploadsFiles
{
    /**
     * Move an uploaded file into a subfolder of public/uploads using a unique,
     * slugged filename and return the stored path relative to uploads/
     * (e.g. "services/custom-software-ab12cd34.webp"), referenced via
     * asset('uploads/...').
     */
    protected function storeUpload(UploadedFile $file, string $folder = ''): string
    {
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = $name.'-'.Str::lower(Str::random(8)).'.'.$file->getClientOriginalExtension();

        $folder = trim($folder, '/');
        $destination = $folder === '' ? public_path('uploads') : public_path('uploads/'.$folder);

        $file->move($destination, $filename);

        return $folder === '' ? $filename : $folder.'/'.$filename;
    }

    /**
     * Delete a previously stored upload from public/uploads if it exists.
     */
    protected function deleteUpload(?string $filename): void
    {
        if ($filename && is_file(public_path('uploads/'.$filename))) {
            @unlink(public_path('uploads/'.$filename));
        }
    }

    /**
     * Resolve an image form field into the data array, handling three cases:
     * a new upload (replaces and deletes the old file), an explicit removal
     * (deletes the old file and stores null), or no change (leaves it intact).
     *
     * @param  array<string, mixed>  $data
     */
    protected function syncUpload(Request $request, array &$data, string $field, ?string $current = null, string $folder = ''): void
    {
        if ($request->hasFile($field)) {
            $this->deleteUpload($current);
            $data[$field] = $this->storeUpload($request->file($field), $folder);

            return;
        }

        if ($request->boolean('remove_'.$field)) {
            $this->deleteUpload($current);
            $data[$field] = null;

            return;
        }

        unset($data[$field]);
    }
}
