<?php

namespace App\Domain\DocumentManagement\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

interface DocumentRepositoryInterface
{
    /**
     * Get all documents for a model.
     *
     * @param Model $model
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllForModel(Model $model);

    /**
     * Store a new document.
     *
     * @param Model $model
     * @param array $data
     * @param UploadedFile $file
     * @return \App\Domain\DocumentManagement\Models\Document
     */
    public function store(Model $model, array $data, UploadedFile $file);

    /**
     * Find a document by ID.
     *
     * @param int $id
     * @return \App\Domain\DocumentManagement\Models\Document
     */
    public function findById($id);

    /**
     * Delete a document.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);

    /**
     * Download a document.
     *
     * @param int $id
     * @return mixed
     */
    public function download($id);
}
