<?php

namespace App\Filament\Resources\TImageResource\Pages;

use App\Filament\Resources\TImageResource;
use App\Models\TImage;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateTImage extends CreateRecord
{
    protected static string $resource = TImageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Get the uploaded file
        if (isset($data['image_file'])) {
            $uploadedFile = $data['image_file'];
            
            // Get file info
            $originalFilename = $uploadedFile->getClientOriginalName();
            $mimeType = $uploadedFile->getMimeType();
            $fileSize = $uploadedFile->getSize();
            
            // Generate hash
            $fileHash = hash_file('sha256', $uploadedFile->getRealPath());
            
            // Get file extension
            $extension = $uploadedFile->getClientOriginalExtension();
            
            // Generate converted filename
            $convertedFilename = $fileHash . '.' . $extension;
            
            // Store the file
            $path = $uploadedFile->storeAs('images', $convertedFilename, 'public');
            
            // Get image dimensions if it's an image
            $width = null;
            $height = null;
            
            if (str_starts_with($mimeType, 'image/')) {
                try {
                    // Try to get image dimensions
                    $imageInfo = getimagesize($uploadedFile->getRealPath());
                    if ($imageInfo) {
                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                    }
                } catch (\Exception $e) {
                    // Ignore errors, dimensions will remain null
                }
            }
            
            // Generate URLs
            $uploadUrl = Storage::disk('public')->url($path);
            $uploadPath = $path;
            
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