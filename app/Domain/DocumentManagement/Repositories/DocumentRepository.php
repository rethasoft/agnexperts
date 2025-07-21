<?php

namespace App\Domain\DocumentManagement\Repositories;

use App\Domain\DocumentManagement\Models\Document;
use App\Domain\DocumentManagement\Repositories\Interfaces\DocumentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Folder;

class DocumentRepository implements DocumentRepositoryInterface
{
    /**
     * @var Document
     */
    protected $model;

    /**
     * DocumentRepository constructor.
     *
     * @param Document $document
     */
    public function __construct(Document $document)
    {
        $this->model = $document;
    }

    /**
     * Get all documents for a model.
     *
     * @param Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllForModel(Model $model)
    {
        return $model->documents()->latest()->get();
    }

    /**
     * Store a new document.
     *
     * @param Model $model
     * @param array $data
     * @param UploadedFile $file
     * @return Document
     */
    public function store(Model $model, array $data, UploadedFile $file)
    {
        $modelType = strtolower(class_basename($model));
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs($modelType . '_documents/' . $model->id, $fileName, 'public');

        $document = new Document([
            'name' => $data['name'],
            'document_type' => $data['document_type'],
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $file->getSize(),
            'file_extension' => $file->getClientOriginalExtension(),
            'expiry_date' => $data['expiry_date'] ?? null,
        ]);

        $model->documents()->save($document);

        return $document;
    }

    /**
     * Find a document by ID.
     *
     * @param int $id
     * @return Document
     */
    public function findById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Delete a document.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $document = $this->findById($id);

        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        return $document->delete();
    }

    /**
     * Download a document.
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|null
     */
    public function download($id)
    {
        $document = $this->findById($id);

        if (!Storage::disk('documents')->exists($document->file_path)) {
            return null;
        }

        $filename = $document->name . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION);
        $contents = Storage::disk('documents')->get($document->file_path);

        return response($contents)
            ->header('Content-Type', $document->file_type ?? 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Create a new document.
     *
     * @param array $data
     * @return Document
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update a document.
     *
     * @param Document $document
     * @param array $data
     * @return Document
     */
    public function update(Document $document, array $data)
    {
        $document->update($data);
        return $document;
    }

    /**
     * Get documents for a specific model.
     *
     * @param Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getForModel(Model $model)
    {
        return $this->getAllForModel($model);
    }

    /**
     * Get documents that are about to expire.
     *
     * @param int $daysThreshold
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDocumentsAboutToExpire(int $daysThreshold = 30)
    {
        $expiryDate = now()->addDays($daysThreshold);

        return $this->model
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', $expiryDate)
            ->where('expiry_date', '>=', now())
            ->latest()
            ->get();
    }

    public function createFolder(array $data)
    {
        return Folder::create($data);
    }
    public function findByNameAndParent(string $name, $parentId)
    {
        return Folder::where('name', $name)
            ->where('parent_id', $parentId)
            ->first();
    }
    public function getDocumentsByFolder($folder)
    {
        return $folder->documents;
    }
}
