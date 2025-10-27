<?php

namespace App\Traits;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

trait FileUploadTrait
{
    protected function uploadFiles($files, string $type, int $modelId, string $morphType): Collection
    {
        if (!$files) return collect();

        $uploadedFiles = collect();

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                // Dosya yolunu oluştur
                $path = File::generatePath(
                    $type,
                    $modelId,
                    $file->hashName()
                );

                // Dosyayı storage'a (private/local) kaydet
                // Not: local disk -> storage/app altında saklanır, web'den direkt erişilemez
                Storage::put($path, file_get_contents($file->getRealPath()));

                // Veritabanına kaydet
                $uploadedFiles->push(File::create([
                    'fileable_id' => $modelId,
                    'fileable_type' => $morphType,
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'disk' => 'local',
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'metadata' => [
                        'type' => $type,
                        'uploaded_by' => auth()->id()
                    ]
                ]));
            }
        }

        return $uploadedFiles;
    }

    protected function deleteFiles(int $id, string $type)
    {
        $files = File::where('fileable_id', $id)
            ->whereJsonContains('metadata->type', $type)
            ->get();

        foreach ($files as $file) {
            // Aynı disk kullanımı: local
            Storage::delete($file->path);
            $file->delete();
        }
    }
}