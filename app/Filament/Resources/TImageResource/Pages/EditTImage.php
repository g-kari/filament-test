<?php

namespace App\Filament\Resources\TImageResource\Pages;

use App\Filament\Resources\TImageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditTImage extends EditRecord
{
    protected static string $resource = TImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Handle image replacement
        if (isset($data['replacement_image']) && $data['replacement_image']) {
            $uploadedFile = $data['replacement_image'];
            
            // Delete old file if it exists
            $oldPath = $this->record->upload_path;
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            
            // Get file info
            $originalFilename = $uploadedFile->getClientOriginalName();
            $mimeType = $uploadedFile->getMimeType();
            $fileSize = $uploadedFile->getSize();
            
            // Generate hash of the processed/edited file
            $fileHash = hash_file('sha256', $uploadedFile->getRealPath());
            
            // Get file extension
            $extension = $uploadedFile->getClientOriginalExtension();
            
            // Generate converted filename with timestamp for uniqueness
            $convertedFilename = $fileHash . '_' . time() . '.' . $extension;
            
            // Store the new file (this will be the edited version)
            $path = $uploadedFile->storeAs('images', $convertedFilename, 'public');
            
            // Get image dimensions of the processed/edited image
            $width = null;
            $height = null;
            
            if (str_starts_with($mimeType, 'image/')) {
                try {
                    // Get dimensions of the edited image
                    $imageInfo = getimagesize($uploadedFile->getRealPath());
                    if ($imageInfo) {
                        $width = $imageInfo[0];
                        $height = $imageInfo[1];
                    }
                } catch (\Exception $e) {
                    // Fallback: try to get dimensions from storage
                    try {
                        $storedPath = Storage::disk('public')->path($path);
                        $imageInfo = getimagesize($storedPath);
                        if ($imageInfo) {
                            $width = $imageInfo[0];
                            $height = $imageInfo[1];
                        }
                    } catch (\Exception $e2) {
                        // Ignore errors, dimensions will remain null
                    }
                }
            }
            
            // Generate URLs
            $uploadUrl = Storage::disk('public')->url($path);
            $uploadPath = $path;
            
            // Remove the replacement image data and update with new data
            unset($data['replacement_image']);
            
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
                'updated_by' => auth()->user()->name ?? 'system',
            ]);
        } else {
            // Remove replacement_image field if no new file uploaded
            unset($data['replacement_image']);
            $data['updated_by'] = auth()->user()->name ?? 'system';
        }
        
        return $data;
    }
}