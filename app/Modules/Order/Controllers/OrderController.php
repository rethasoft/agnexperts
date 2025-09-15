<?php

namespace App\Modules\Order\Controllers;

use App\Http\Controllers\Controller;
use App\Domain\Inspections\Actions\CreateInspectionAction;
use App\Domain\Inspections\DTOs\InspectionData;
use App\Http\Requests\CreateInspectionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Province;
use App\Models\Type;
use App\Models\Coupon;

class OrderController extends Controller
{
    public function __construct(
        private readonly CreateInspectionAction $createInspectionAction
    ) {}

    public function index()
    {
        $provincies = Province::all();
        return view('order::order.index', compact('provincies'));
    }

    public function store(CreateInspectionRequest $request)
    {
        try {
            // selectedServices'ı items'a dönüştür
            if ($request->has('selectedServices')) {
                $items = [];
                foreach ($request->selectedServices as $service) {
                    $items[] = [
                        'type_id' => $service['id'],
                        'category_id' => $service['category_id'], 
                        'name' => $service['name'],
                        'quantity' => $service['quantity'],
                        'price' => $service['price'],
                        'total' => $service['total']
                    ];
                }
                $request->merge(['items' => $items]);
            }

            // Request verilerini InspectionData'ya dönüştür
            $inspectionData = InspectionData::fromRequest($request);

            // CreateInspectionAction ile inspection oluştur
            $inspection = $this->createInspectionAction->execute($inspectionData);

            // Başarılı sonuç dön
            return back()
                ->with('success', 'Uw bestelling is succesvol ontvangen. U ontvangt spoedig een bevestigingsmail.');
        } catch (\Exception $e) {
            // Hata logla
            Log::error('Module Order Controller:store-> ' . $e->getMessage());

            // Hata mesajı ile geri dön
            return back()
                ->withInput()
                ->withErrors(['error' => 'Er is een fout opgetreden bij het verwerken van uw bestelling. Probeer het opnieuw.']);
        }
    }

    public function getServices($location)
    {
        // Return services based on location and regions
        $services = Type::where('category_id', 0)
            ->whereJsonContains('regions', $location)
            ->get();
        return response()->json($services);
    }

    public function getSubServices($service_id)
    {
        // Return sub-services based on service and selected location
        $selectedLocation = request()->query('location');
        
        $subServices = Type::where('category_id', $service_id);
        
        if ($selectedLocation) {
            $subServices = $subServices->whereJsonContains('regions', $selectedLocation);
        }
        
        return response()->json($subServices->get());
    }

    public function validateCoupon(Request $request)
    {
        $couponCode = $request->input('coupon_code');

        // Validate input
        if (empty($couponCode)) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon code is required.',
                'errorCode' => 'MISSING_CODE'
            ], 400); // Bad Request
        }

        $coupon = Coupon::where('code', $couponCode)->first();

        if ($coupon) {
            return response()->json([
                'valid' => true,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value,
                'message' => 'Coupon is valid.'
            ]);
        } else {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code.',
                'errorCode' => 'INVALID_CODE'
            ], 404); // Not Found
        }
    }

    public function checkCombiDiscount(Request $request)
    {
        // Mevcut CombiDiscountController'ın method'unu kullan
        $combiController = new \App\Http\Controllers\CombiDiscountController(
            new \App\Domain\Inspections\Repositories\CombiDiscountRepository()
        );
        
        return $combiController->checkCombiDiscount($request);
    }
}
