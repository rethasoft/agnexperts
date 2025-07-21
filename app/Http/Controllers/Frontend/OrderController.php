<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('frontend.order.index');
    }

    public function getServices($city)
    {
        // Replace this with your actual database query
        $services = [
            'brussel' => [
                ['id' => 1, 'name' => 'EPC Certificate', 'icon' => 'fas fa-certificate'],
                ['id' => 2, 'name' => 'Asbestos Check', 'icon' => 'fas fa-hard-hat'],
                ['id' => 3, 'name' => 'Property Inspection', 'icon' => 'fas fa-home'],
            ],
            'vlaanderen' => [
                ['id' => 4, 'name' => 'EPC Certificate', 'icon' => 'fas fa-certificate'],
                ['id' => 5, 'name' => 'Property Inspection', 'icon' => 'fas fa-home'],
            ]
        ];

        return response()->json($services[$city] ?? []);
    }

    public function getSubServices($serviceId)
    {
        // Replace this with your actual database query
        $subServices = [
            '1' => [
                ['id' => 1, 'name' => 'Residential EPC', 'icon' => 'fas fa-house-user'],
                ['id' => 2, 'name' => 'Commercial EPC', 'icon' => 'fas fa-building'],
            ],
            '2' => [
                ['id' => 3, 'name' => 'Basic Check', 'icon' => 'fas fa-check'],
                ['id' => 4, 'name' => 'Detailed Analysis', 'icon' => 'fas fa-search'],
            ],
            '4' => [  // EPC - Vlaanderen sub-services
                ['id' => 5, 'name' => 'Residential EPC Vlaanderen', 'icon' => 'fas fa-house-user', 'price' => 150],
                ['id' => 6, 'name' => 'Commercial EPC Vlaanderen', 'icon' => 'fas fa-building', 'price' => 200],
                ['id' => 7, 'name' => 'Industrial EPC Vlaanderen', 'icon' => 'fas fa-industry', 'price' => 250],
            ],
            '5' => [  // Property Inspection - Vlaanderen sub-services
                ['id' => 8, 'name' => 'Basic Property Check', 'icon' => 'fas fa-check', 'price' => 100],
                ['id' => 9, 'name' => 'Detailed Property Analysis', 'icon' => 'fas fa-search', 'price' => 150],
            ]
        ];

        return response()->json($subServices[$serviceId] ?? []);
    }

    public function getSubServicesByServiceId($serviceId)
    {
        return $this->getSubServices($serviceId);
    }
}
