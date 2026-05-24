<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileUploadService
{
    public function upload(UploadedFile $file, string $path): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid() . '.' . $extension;
        $file->move(public_path($path), $filename);
        return $filename;
    }
}
