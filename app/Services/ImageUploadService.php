<?php

namespace App\Services;

use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Handle image upload and return the image path
     *
     * @param \Illuminate\Http\UploadedFile $image
     * @param string $serviceName
     * @return string
     */
    public function upload($image, $serviceName)
    {
        $imageName = time() . '_' . Str::slug($serviceName) . '.' . $image->getClientOriginalExtension();

        // Create directory if it doesn't exist
        $path = public_path('images/services');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // Move the image to public/images/services
        $image->move($path, $imageName);

        return 'images/services/' . $imageName;
    }
} 