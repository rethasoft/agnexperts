<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $hash): StreamedResponse
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        try {
            // Hash'lenmiş bilgiyi çöz
            $fileData = json_decode(Crypt::decryptString($hash), true);
            
            if (!isset($fileData['type']) || !isset($fileData['path'])) {
                abort(404);
            }

            $path = $fileData['path'];
            $filename = basename($path);

            if (!Storage::exists($path)) {
                abort(404, 'File not found');
            }

            return $this->streamFile($path, $filename);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    private function streamFile(string $path, string $filename): StreamedResponse
    {
        $mimeType = Storage::mimeType($path);
        
        return Storage::response($path, $filename, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
            'Cache-Control' => 'private, max-age=3600',
            'Pragma' => 'private',
        ]);
    }

    // Yardımcı metod - View'da kullanmak için
    public static function generateFileHash(string $type, string $path): string
    {
        return Crypt::encryptString(json_encode([
            'type' => $type,
            'path' => $path
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        //
    }
}
