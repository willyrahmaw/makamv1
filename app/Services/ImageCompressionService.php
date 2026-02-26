<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageCompressionService
{
    public function compressAndStore(
        UploadedFile $file,
        string $path,
        string $disk = 'public',
        int $quality = 10,
        int $maxWidth = 800,
        int $maxHeight = 600
    ): string {

        $originalExtension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        // Jika SVG langsung simpan
        if ($originalExtension === 'svg' || $mimeType === 'image/svg+xml') {
            $filename = uniqid($path . '_', true) . '.svg';
            Storage::disk($disk)->putFileAs($path, $file, $filename);
            return $path . '/' . $filename;
        }

        $filename = uniqid($path . '_', true) . '.webp';
        $fullPath = storage_path('app/' . $disk . '/' . $path);

        // pastikan folder ada
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

       
        $image = Image::read($file->getRealPath())
            ->resize($maxWidth, $maxHeight, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->toWebp(quality: $quality);

        $image->save($fullPath . '/' . $filename);

        return $path . '/' . $filename;
    }

    /**
     * Generate cover image (center crop) dan simpan sebagai WEBP.
     */
    public function generateCover(
        UploadedFile $file,
        string $path,
        string $disk = 'public',
        int $width = 800,
        int $height = 600,
        int $quality = 10
    ): ?string {
        $ext = strtolower($file->getClientOriginalExtension());
        $mime = $file->getMimeType();

        // Lewati SVG
        if ($ext === 'svg' || $mime === 'image/svg+xml') {
            return null;
        }

        $image = Image::read($file->getRealPath());

        $originalWidth  = $image->width();
        $originalHeight = $image->height();

        // Hitung crop center agar proporsi tetap bagus
        $ratio      = max($width / $originalWidth, $height / $originalHeight);
        $cropWidth  = (int) ($width / $ratio);
        $cropHeight = (int) ($height / $ratio);
        $cropX      = (int) (($originalWidth - $cropWidth) / 2);
        $cropY      = (int) (($originalHeight - $cropHeight) / 2);

        // Crop & resize ke ukuran cover
        $image->crop($cropWidth, $cropHeight, $cropX, $cropY)
              ->resize($width, $height);

        $coverFilename = uniqid($path . '_cover_', true) . '.webp';
        $coverDir      = $path . '/covers';
        $coverPath     = $coverDir . '/' . $coverFilename;

        // Pastikan direktori ada
        Storage::disk($disk)->makeDirectory($coverDir);

        $image->toWebp(quality: $quality)
              ->save(Storage::disk($disk)->path($coverPath));

        return $coverPath;
    }
}