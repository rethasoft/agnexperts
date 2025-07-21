<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class OrderController extends Controller
{
    public function submit(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'location' => 'required',
                'service' => 'required',
                'selectedServices' => 'required|array',
                'selectedServices.*.id' => 'required|exists:services,id',
                'selectedServices.*.quantity' => 'required|integer|min:1',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email',
                'phone' => 'required|string',
                'delivery_street' => 'required|string',
                'delivery_house_number' => 'required|string',
                'delivery_postal_code' => 'required|string',
                'delivery_city' => 'required|string',
            ]);

            // Get prices from database and calculate totals
            foreach ($validated['selectedServices'] as &$service) {
                $serviceModel = Service::findOrFail($service['id']);
                $service['price'] = $serviceModel->price;
                $service['total'] = $serviceModel->price * $service['quantity'];
            }

            // Process the order (save to database, send emails, etc.)
            // ... your order processing logic here ...

            // Redirect with success message
            return redirect()
                ->route('order.confirmation')
                ->with('success', 'Uw bestelling is succesvol ontvangen. U ontvangt spoedig een bevestigingsmail.');

        } catch (\Exception $e) {
            // Redirect back with error message
            return back()
                ->withInput()
                ->withErrors(['error' => 'Er is een fout opgetreden bij het verwerken van uw bestelling. Probeer het opnieuw.']);
        }
    }
} 