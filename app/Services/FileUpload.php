<?php

// app/Services/FileService.php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentUploadedMail;
use Intervention\Image\Facades\Image;

class FileService
{
    public function fileUpload($request, $attribute, $object, $resizeOptions = [])
    {
        if ($request->hasFile($attribute)) {
            $newFile = new File();
            $files = $request->file($attribute);

            foreach ($files as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = Str::slug($originalName, '-') . '.' . $file->getClientOriginalExtension();

                $savedFile = $newFile->create([
                    'object_id' => $object->id,
                    'table' => get_class($object),
                    'path' => '/img/files/',
                    'name' => $filename,
                    'type' => $file->getClientOriginalExtension(),
                ]);

                if (!$savedFile) {
                    return false; // Indicate failure
                }

                // Resize image if options are provided
                if (!empty($resizeOptions) && in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'gif'])) {
                    $filename = 'resized_' . $filename; // Add "resized_" prefix to filename
                    $this->resizeImage($file, $resizeOptions, $filename);
                } else {
                    // Store original file if no resize options are provided or file type is not supported
                    $imageStore = $file->storeAs('files/', $filename, ['disk' => 'public_folder']);
                }

                if (!$imageStore) {
                    return false; // Indicate failure
                }
            }

            Mail::to($object->email)->send(new DocumentUploadedMail($object));

            return true; // Indicate success
        }

        return false; // No files to process
    }

    private function resizeImage($file, $resizeOptions, $filename)
    {
        $image = Image::make($file);

        if (isset($resizeOptions['width'])) {
            $image->resize($resizeOptions['width'], null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        if (isset($resizeOptions['height'])) {
            $image->resize(null, $resizeOptions['height'], function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        // Save the resized image
        $image->save(public_path('img/files/' . $filename));
    }
}
