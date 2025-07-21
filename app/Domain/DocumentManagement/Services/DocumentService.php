<?php

namespace App\Domain\DocumentManagement\Services;

use App\Domain\DocumentManagement\Models\Document;
use App\Domain\DocumentManagement\Repositories\DocumentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\{Storage, Log};
use Illuminate\Support\Str;

class DocumentService
{
    protected DocumentRepository $repository;

    public function __construct(DocumentRepository $repository)
    {
        $this->repository = $repository;
    }


    public function createFolder(array $data)
    {

        return $this->repository->createFolder($data);
    }

    // Laten we de bestaande uploadDocument methode bijwerken

    /**
     * Upload en sla een nieuw document op voor een model.
     *
     * @param Model $model
     * @param array $data
     * @param UploadedFile $file
     * @return Document
     */
    public function uploadDocument(Model $model, array $data, UploadedFile $file): Document
    {
        // Verkrijg modeltype en tenant voor betere mapstructuur
        $modelType = strtolower(class_basename($model));
        $tenantId = $model->tenant_id ?? auth()->user()->tenant_id ?? 'shared';

        // Creëer een meer georganiseerde mapstructuur:
        // tenants/{tenant_id}/documents/{model_type}/{model_id}/{document_type}/{year}/{month}/filename
        $documentType = $data['document_type'] ?? 'general';
        $year = now()->format('Y');
        $month = now()->format('m');

        $storagePath = "tenants/{$tenantId}/documents/{$modelType}/{$model->id}/{$documentType}/{$year}/{$month}";

        // Genereer een unieke bestandsnaam met originele extensie
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid() . '_' . Str::slug($data['name']) . '.' . $extension;

        // Sla het bestand op in privé opslag (niet openbaar toegankelijk)
        $path = $file->storeAs($storagePath, $filename, 'documents');

        // Haal metadata op indien aanwezig
        $meta = $data['meta'] ?? [];
        unset($data['meta']);

        // Bereid documentdata voor
        $documentData = array_merge($data, [
            'documentable_id' => $model->id,
            'documentable_type' => get_class($model),
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'meta' => $meta,
        ]);

        // Maak het documentrecord aan
        return $this->repository->create($documentData);
    }

    /**
     * Verifieer een document.
     *
     * @param Document $document
     * @param string $verifiedBy
     * @return Document
     */
    public function verifyDocument(Document $document, string $verifiedBy): Document
    {
        return $this->repository->update($document, [
            'is_verified' => true,
            'verified_by' => $verifiedBy,
            'verified_at' => now(),
        ]);
    }

    /**
     * Haal documenten op voor een specifiek model.
     *
     * @param Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDocumentsForModel(Model $model)
    {
        return $this->repository->getForModel($model);
    }

    /**
     * Haal documenten op die bijna verlopen zijn.
     *
     * @param int $daysThreshold
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDocumentsAboutToExpire(int $daysThreshold = 30)
    {
        return $this->repository->getDocumentsAboutToExpire($daysThreshold);
    }

    /**
     * Verwijder een document en het bijbehorende bestand.
     *
     * @param Document $document
     * @return bool
     */
    public function deleteDocument(Document $document): bool
    {
        try {
            // Verwijder het bestand uit de opslag
            if (Storage::disk('documents')->exists($document->file_path)) {
                Storage::disk('documents')->delete($document->file_path);
            }

            // Verwijder het documentrecord
            $result = $this->repository->delete($document->id);

            if (!$result) {
                throw new \Exception('Kon het documentrecord niet verwijderen');
            }

            return true;
        } catch (\Exception $e) {
            throw new \Exception('Fout bij het verwijderen van document: ' . $e->getMessage());
        }
    }

    public function deleteDocumentsByFolder($folder): bool
    {
        $documents = $this->repository->getDocumentsByFolder($folder);

        if ($documents->isEmpty()) {
            return 0; // Geen documenten om te verwijderen
        }
        foreach ($documents as $document) {
            try {
                // Verwijder het documentrecord
                $this->deleteDocument($document);
            } catch (\Throwable $th) {
                Log::warning('Kon document niet verwijderen: ' . $th->getMessage());
            }
        }
        return true;
    }

    /**
     * Krijg een document download response.
     *
     * @param Document $document
     * @param bool $forceDownload Of de download geforceerd moet worden of inline getoond
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function downloadDocument($document, $forceDownload = true)
    {
        // Bepaal de juiste schijf en bestandspad
        $disk = 'documents';

        // Controleer het bestandspad
        if (Storage::disk($disk)->exists($document->file_path)) {
            // Bestand bestaat in documents schijf - we gebruiken storage_path in plaats van path()
            $filePath = storage_path('app/documents/' . $document->file_path);
        } else if (Storage::exists($document->file_path)) {
            // Bestand bestaat in standaard schijf
            $filePath = storage_path('app/' . $document->file_path);
        } else {
            // Probeer een alternatief pad (app/documents/tenants in plaats van app/tenants)
            $alternativePath = 'documents/' . $document->file_path;
            if (Storage::exists($alternativePath)) {
                $filePath = storage_path('app/' . $alternativePath);
            } else {
                throw new \Illuminate\Contracts\Filesystem\FileNotFoundException("Bestand niet gevonden: {$document->file_path}");
            }
        }

        // Stel de content disposition in op basis van of we downloaden forceren
        $disposition = $forceDownload ? 'attachment' : 'inline';

        // Verkrijg bestandsextensie voor de juiste bestandsnaam
        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
        $filename = $document->name;

        // Voeg extensie toe aan bestandsnaam als deze er nog geen heeft
        if (!str_contains($filename, '.')) {
            $filename .= '.' . $extension;
        }

        // Geef de file response terug met de juiste headers
        return response()->file($filePath, [
            'Content-Disposition' => $disposition . '; filename="' . $filename . '"',
        ]);
    }

    // Mapinstellingen

    public function createFolderFromPath(string $path)
    {
        $folderNames = array_map('trim', explode('>', $path));

        $parentId = null;
        $lastFolder = null;

        foreach ($folderNames as $name) {
            $existing = $this->repository->findByNameAndParent($name, $parentId);

            if ($existing) {
                $lastFolder = $existing;
            } else {
                $lastFolder = $this->repository->createFolder([
                    'name' => $name,
                    'parent_id' => $parentId
                ]);
            }

            $parentId = $lastFolder->id;
        }

        return $lastFolder; // We geven het ID van de onderste map terug
    }
}
