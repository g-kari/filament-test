<?php

namespace App\Filament\Resources\TImageResource\Pages;

use App\Filament\Resources\TImageResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateTImage extends CreateRecord
{
    protected static string $resource = TImageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Get the uploaded file path (Filament FileUpload returns the stored path)
        if (isset($data['image_file']) && $data['image_file']) {
            $storedPath = $data['image_file'];
            
            // Get full path to the stored file
            $fullPath = Storage::disk('public')->path($storedPath);
            
            // Extract original filename from the path
            $pathInfo = pathinfo($storedPath);
            $convertedFilename = $pathInfo['basename'];
            $extension = $pathInfo['extension'] ?? '';
            
            // Generate original filename (we'll use the converted filename as base)
            $originalFilename = $convertedFilename;
            
            // Get file info from stored file
            $mimeType = Storage::disk('public')->mimeType($storedPath);
            $fileSize = Storage::disk('public')->size($storedPath);
            
            // Generate hash of the stored file
            $fileHash = hash_file('sha256', $fullPath);
            
            // Get image dimensions
            $width = null;
            $height = null;
            
            if (str_starts_with($mimeType, 'image/') && file_exists($fullPath)) {
                try {
                    $imageInfo = getimagesize($fullPath);
                    if ($imageInfo) {
                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                    }
                } catch (\Exception) {
                    // Ignore errors, dimensions will remain null
                }
            }
            
            // Generate URLs
            $uploadUrl = Storage::disk('public')->url($storedPath);
            $uploadPath = $storedPath;
            
            // Remove the file upload data and replace with processed data
            unset($data['image_file']);
            
            $data = array_merge($data, [
                'original_filename' => $originalFilename,
                'converted_filename' => $convertedFilename,
                'upload_url' => $uploadUrl,
                'upload_path' => $uploadPath,
                'mimetype' => $mimeType,
                'image_type' => $extension,
                'width' => $width,
                'height' => $height,
                'file_size' => $fileSize,
                'file_hash' => $fileHash,
                'created_by' => auth()->user()->name ?? 'system',
            ]);
        }
        
        return $data;
    }
}