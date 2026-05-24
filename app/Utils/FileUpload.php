<?php

namespace App\Utils;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class FileUpload
{
    public static function uploadVideoOnLocal(UploadedFile $file, string $folder = 'videos'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');

        return $path;
    }
    public static function uploadImageOnLocal(UploadedFile $file, string $folder): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid() . '.' . $extension;
        $file->move(public_path($folder), $filename);

        // return full URL
        return asset($folder . '/' . $filename);
    }
    public static function deleteFileFromLocal(string $filePath): bool
    {
        // Remove 'storage/' prefix if present
        if (Str::startsWith($filePath, 'storage/')) {
            $filePath = Str::replaceFirst('storage/', '', $filePath);
        }

        return Storage::disk('public')->delete($filePath);
    }
}
