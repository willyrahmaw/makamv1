<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageCompressionService
{
    /**
     * Cek apakah GD extension tersedia
     */
    public function __construct()
    {
        if (!extension_loaded('gd')) {
            throw new \Exception('GD extension tidak tersedia. Silakan install php-gd extension.');
        }
    }

    /**
     * Compress dan simpan image
     * 
     * @param UploadedFile $file
     * @param string $path Path untuk menyimpan (contoh: 'makam', 'logos')
     * @param string $disk Disk storage ('public')
     * @param int $quality Quality untuk JPEG (1-100, default: 85)
     * @param int $maxWidth Maximum width (default: 1920)
     * @param int $maxHeight Maximum height (default: 1920)
     * @return string Path file yang disimpan
     */
    public function compressAndStore(
        UploadedFile $file,
        string $path,
        string $disk = 'public',
        int $quality = 85,
        int $maxWidth = 1920,
        int $maxHeight = 1920
    ): string {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = uniqid($path . '_', true) . '.' . $extension;
        $fullPath = $path . '/' . $filename;

        // Jika SVG, simpan langsung tanpa compression
        if ($extension === 'svg' || $file->getMimeType() === 'image/svg+xml') {
            Storage::disk($disk)->putFileAs($path, $file, $filename);
            return $fullPath;
        }

        // Gunakan GD native untuk compression
        return $this->compressWithGD($file, $path, $filename, $disk, $quality, $maxWidth, $maxHeight);
    }

    /**
     * Compress menggunakan GD native PHP
     */
    protected function compressWithGD(
        UploadedFile $file,
        string $path,
        string $filename,
        string $disk,
        int $quality,
        int $maxWidth,
        int $maxHeight
    ): string {
        $filePath = $file->getRealPath();
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeType = $file->getMimeType();
        
        // Baca image berdasarkan tipe
        $image = null;
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($filePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($filePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($filePath);
                break;
            default:
                // Jika tidak didukung, simpan langsung
                Storage::disk($disk)->putFileAs($path, $file, $filename);
                return $path . '/' . $filename;
        }
        
        if (!$image) {
            // Fallback: simpan langsung jika gagal membaca
            Storage::disk($disk)->putFileAs($path, $file, $filename);
            return $path . '/' . $filename;
        }
        
        // Get original dimensions
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);
        
        // Cek apakah perlu resize
        $needsResize = ($originalWidth > $maxWidth || $originalHeight > $maxHeight);
        
        if ($needsResize) {
            // Calculate new dimensions dengan maintain aspect ratio
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            $newWidth = (int)($originalWidth * $ratio);
            $newHeight = (int)($originalHeight * $ratio);
            
            // Create new image dengan ukuran baru
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency untuk PNG dan GIF
            if ($extension === 'png' || $extension === 'gif') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }
            
            // Resize image
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            
            // Clean up original image
            imagedestroy($image);
            $imageToSave = $newImage;
        } else {
            // Tidak perlu resize, gunakan image asli tapi tetap akan di-compress saat save
            $imageToSave = $image;
        }
        
        // Simpan image
        $fullPath = $path . '/' . $filename;
        $storagePath = Storage::disk($disk)->path($fullPath);
        
        // Pastikan directory exists
        Storage::disk($disk)->makeDirectory($path);
        
        // Simpan berdasarkan extension dengan compression
        switch ($extension) {
            case 'png':
                imagepng($imageToSave, $storagePath, 9); // 9 = maximum compression (0-9)
                break;
            case 'webp':
                imagewebp($imageToSave, $storagePath, $quality);
                break;
            case 'gif':
                imagegif($imageToSave, $storagePath);
                break;
            default: // jpeg/jpg
                imagejpeg($imageToSave, $storagePath, $quality);
                break;
        }
        
        // Clean up memory
        imagedestroy($imageToSave);
        
        return $fullPath;
    }

    /**
     * Generate cover image dengan crop center
     * 
     * @param UploadedFile $file
     * @param string $path Path untuk menyimpan
     * @param string $disk Disk storage
     * @param int $width Width cover (default: 800)
     * @param int $height Height cover (default: 600)
     * @param int $quality Quality untuk JPEG (default: 85)
     * @return string|null Path file cover yang disimpan, atau null jika gagal
     */
    public function generateCover(
        UploadedFile $file,
        string $path,
        string $disk = 'public',
        int $width = 800,
        int $height = 600,
        int $quality = 85
    ): ?string {
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        
        // Skip SVG
        if ($extension === 'svg' || $mimeType === 'image/svg+xml') {
            return null;
        }
        
        $filePath = $file->getRealPath();
        
        // Baca image berdasarkan tipe
        $image = null;
        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($filePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($filePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($filePath);
                break;
            default:
                return null;
        }
        
        if (!$image) {
            return null;
        }
        
        // Get original dimensions
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);
        
        // Calculate crop dimensions (center crop)
        $ratio = max($width / $originalWidth, $height / $originalHeight);
        $cropWidth = (int)($width / $ratio);
        $cropHeight = (int)($height / $ratio);
        $cropX = (int)(($originalWidth - $cropWidth) / 2);
        $cropY = (int)(($originalHeight - $cropHeight) / 2);
        
        // Create cover image
        $coverImage = imagecreatetruecolor($width, $height);
        
        // Preserve transparency untuk PNG
        if ($extension === 'png' || $extension === 'gif') {
            imagealphablending($coverImage, false);
            imagesavealpha($coverImage, true);
            $transparent = imagecolorallocatealpha($coverImage, 255, 255, 255, 127);
            imagefilledrectangle($coverImage, 0, 0, $width, $height, $transparent);
        }
        
        // Crop dan resize ke cover size
        imagecopyresampled(
            $coverImage, $image,
            0, 0, $cropX, $cropY,
            $width, $height, $cropWidth, $cropHeight
        );
        
        // Generate filename untuk cover
        $coverFilename = uniqid($path . '_cover_', true) . '.' . $extension;
        $coverPath = $path . '/covers/' . $coverFilename;
        $storagePath = Storage::disk($disk)->path($coverPath);
        
        // Pastikan directory exists
        Storage::disk($disk)->makeDirectory($path . '/covers');
        
        // Simpan cover image
        switch ($extension) {
            case 'png':
                imagepng($coverImage, $storagePath, 9);
                break;
            case 'webp':
                imagewebp($coverImage, $storagePath, $quality);
                break;
            case 'gif':
                imagegif($coverImage, $storagePath);
                break;
            default: // jpeg/jpg
                imagejpeg($coverImage, $storagePath, $quality);
                break;
        }
        
        // Clean up memory
        imagedestroy($image);
        imagedestroy($coverImage);
        
        return $coverPath;
    }
}
