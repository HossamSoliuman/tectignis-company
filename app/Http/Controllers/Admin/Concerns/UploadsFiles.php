<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait UploadsFiles
{
    /**
     * Move an uploaded file into public/uploads using a unique, slugged filename
     * and return the stored filename (referenced via asset('uploads/...')).
     */
    protected function storeUpload(UploadedFile $file): string
    {
        $name = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $filename = $name.'-'.Str::lower(Str::random(8)).'.'.$file->getClientOriginalExtension();

        $file->move(public_path('uploads'), $filename);

        return $filename;
    }
}
