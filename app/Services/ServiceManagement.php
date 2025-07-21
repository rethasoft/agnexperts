<?php

// app/Services/FileService.php

namespace App\Services;

use App\Models\File;
use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\DocumentUploadedMail;
use Intervention\Image\Facades\Image;

class ServiceManagement
{
    public function getServicesByCity($cityId)
    {
        $services = Service::where('city_id', $cityId)->get();
        return response()->json($services ?? []);
    }

    public function getServicesByCategory($categoryId)
    {
        $services = Service::where('category_id', $categoryId)->get();
        return response()->json($services ?? []);
    }
}
