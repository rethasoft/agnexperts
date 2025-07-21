<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Domain\DocumentManagement\Services\DocumentService;
use App\Domain\DocumentManagement\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Folder;

class DocumentController extends Controller
{
    /**
     * @var DocumentService
     */
    protected $documentService;

    /**
     * DocumentController constructor.
     *
     * @param DocumentService $documentService
     */
    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Display a listing of the documents for a specific model.
     *
     * @param  Request  $request
     * @param  string  $modelType
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $modelType = null)
    {
        // Tüm klasörleri al ve her birinin alt klasörlerini içeren yapı ile getir
        $allFolders = Folder::with('children')->whereNull('parent_id')->get();

        // Belirli bir klasör seçildiğinde, onun altındaki tüm çocuk klasörlerin ID'lerini almak:
        if ($request->has('folder_id')) {
            $folder = Folder::with('children')->findOrFail($request->folder_id); // Klasörü al
            $allChildIds = $folder->getAllChildFolderIds(); // Tüm çocukların ID'lerini al

            // Bu ID'lerle eşleşen tüm belgeleri al
            // Bu ID'lerle eşleşen tüm belgeleri al
            $documents = Document::whereIn('documentable_id', $allChildIds)
                ->where('documentable_type', Folder::class)
                ->get();
        }

        // Verileri view'a gönder
        return view('app.tenant.documents.index', [
            'allFolders' => $allFolders,
            'allChildIds' => $allChildIds ?? [],
            'documents' => $documents ?? [],
        ]);
    }

    /**
     * Show the form for creating a new document.
     *
     * @param  Request  $request
     * @param  string  $modelType
     * @param  int  $modelId
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Get folders for organizing documents
        $folders = Folder::all();
        $folder_name = null;
        if ($request->has('parent_id')) {
            $parent_id = $request->parent_id;
            $parentFolder = Folder::find($parent_id);
            $folder_name = $parentFolder->buildBreadcrumb();
        }

        return view('app.tenant.documents.create', ['folders' => $folders, 'folder_name' => $folder_name]);
    }
    /**
     * Store a newly created document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $modelType
     * @param  int  $modelId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $modelType = [], $modelId = 0)
    {
        try {
            $folder = $this->documentService->createFolderFromPath($request->name);
            if ($folder) {
                $filesUploaded = 0;

                if ($request->hasFile('document_files')) {
                    // Validate the uploaded files
                    $request->validate([
                        'document_files.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                    ]);

                    // Upload each file
                    foreach ($request->file('document_files') as $file) {
                        $this->documentService->uploadDocument($folder, ['document_type' => 'general', 'name' => $file->getClientOriginalName()], $file);
                        $filesUploaded++;
                    }
                }

                // Success message
                $successMsg = "Folder '{$folder->name}' created successfully";
                if ($filesUploaded > 0) {
                    $successMsg .= " with {$filesUploaded} document" . ($filesUploaded > 1 ? 's' : '');
                }

                return redirect()->route('documents.index')->with('msg', $successMsg);
            }

            // If folder creation failed
            return back()->withErrors(['error' => 'Failed to create folder'])->withInput();
        } catch (\Throwable $th) {
            // Return any exception message
            return back()->withErrors(['error' => 'An error occurred: ' . $th->getMessage()])->withInput();
        }
    }

    public function show(Request $request) {}

    /**
     * Show the form for editing the specified document.
     *
     * @param  string  $encryptedDocumentId
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $folder = Folder::findOrFail($id);

            // breadcrumb oluşturmak için geri doğru yolu bul
            $breadcrumb = $folder->buildBreadcrumb();

            return view('app.tenant.documents.edit', ['folder' => $folder, 'folder_name' => $breadcrumb]);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(403, 'Invalid document access token');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Document not found');
        }
    }

    /**
     * Update the specified document in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the folder
            $folder = Folder::findOrFail($id);
            $folder->update(['name' => $request->name]);

            if ($request->hasFile('document_files')) {
                $filesUploaded = 0;
                // Validate the uploaded files
                $request->validate([
                    'document_files.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                ]);

                // Upload each file
                foreach ($request->file('document_files') as $file) {
                    $this->documentService->uploadDocument($folder, ['document_type' => 'general', 'name' => $file->getClientOriginalName()], $file);
                    $filesUploaded++;
                }
            }

            $successMsg = "Folder '{$folder->name}' updated successfully";
            return redirect()->route('documents.index', ['folder_id' => $folder->id])->with('msg', $successMsg);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Map niet gevonden']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Wijzigen van map mislukt: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Check if setting a new parent would create a circular reference
     *
     * @param int $folderId
     * @param int $newParentId
     * @return bool
     */
    private function wouldCreateCircularReference($folderId, $newParentId)
    {
        // If the new parent is the folder itself, it's a circular reference
        if ($folderId == $newParentId) {
            return true;
        }

        // Get the potential new parent
        $parent = Folder::find($newParentId);
        if (!$parent) {
            return false;
        }

        // Check if any of the parent's ancestors is this folder
        $ancestorId = $parent->parent_id;
        while ($ancestorId) {
            if ($ancestorId == $folderId) {
                return true;
            }
            $ancestor = Folder::find($ancestorId);
            if (!$ancestor) {
                break;
            }
            $ancestorId = $ancestor->parent_id;
        }

        return false;
    }

    /**
     * Download or view the specified document.
     *
     * @param  string  $encryptedDocumentId
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function download($encryptedDocumentId, Request $request)
    {
        try {
            // Decrypt the document ID
            $documentId = decrypt($encryptedDocumentId);

            // Find the document
            $document = \App\Domain\DocumentManagement\Models\Document::findOrFail($documentId);

            // Check if we should view or download
            $isViewRequest = $request->has('view') && $request->view === 'true';

            // Get the document from the service
            return $this->documentService->downloadDocument($document, !$isViewRequest);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(403, 'Invalid document access token');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Document not found');
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            abort(404, 'Document file not found');
        } catch (\Exception $e) {
            abort(500, 'Failed to download document: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified document from storage.
     *
     * @param  string  $encryptedDocumentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {

            $folder = Folder::findOrFail($id);

            $this->documentService->deleteDocumentsByFolder($folder);
            $folder->delete();

            return redirect()->route('documents.index')->with('msg', 'Document succesvol verwijderd');
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            return back()->withErrors(['error' => 'Ongeldige documenttoegangstoken']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->withErrors(['error' => 'Document niet gevonden']);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Verwijderen van document mislukt: ' . $e->getMessage()]);
        }
    }

    /**
     * Get the model instance based on type and ID.
     *
     * @param  string  $modelType
     * @param  int  $modelId
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function getModel($modelType, $modelId)
    {
        switch ($modelType) {
            case 'employee':
                return Employee::findOrFail($modelId);
                // Add more model types as needed
            default:
                abort(404, 'Invalid model type');
        }
    }

    /**
     * Generate an encrypted document URL
     *
     * @param  int  $documentId
     * @param  bool  $forViewing
     * @return string
     */
    public function getEncryptedDocumentUrl($documentId, $forViewing = false)
    {
        $encryptedId = encrypt($documentId);
        $baseUrl = route('documents.download', $encryptedId);

        return $forViewing ? $baseUrl . '?view=true' : $baseUrl;
    }
}
