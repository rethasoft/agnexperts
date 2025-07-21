<?php

namespace App\Services;

use App\Domain\Inspections\Models\Inspection;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;
use App\Models\Type;
use App\Models\Status;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Province;
use App\Http\Controllers\AjaxController;

class InspectionService
{
    public function createInspection($request)
    {
        try {
            $selectedServicesValidation = $request->validate([
                'selectedServices' => 'required|array',
                'selectedServices.*.id' => 'required|exists:types,id',
                'selectedServices.*.category_id' => 'required|integer',
                'selectedServices.*.name' => 'required',
                'selectedServices.*.quantity' => 'required|integer|min:1',
                'selectedServices.*.price' => 'required|numeric|min:0',
                'selectedServices.*.total' => 'required|numeric|min:0',
            ]);

            $validated = $request->validate([
                'street' => 'required',
                'number' => 'required',
                'bus' => 'nullable',
                'postal_code' => 'required',
                'city' => 'required',
                'email' => 'required|email',
                'phone' => 'required|string',
                'company_name' => 'nullable',
                'vat_number' => 'nullable',
                // 'delivery_street' => 'required|string',
                // 'delivery_house_number' => 'required|string',
                // 'delivery_postal_code' => 'required|string',
                // 'delivery_city' => 'required|string',
            ]);
            $validated['name'] = $request->first_name . ' ' . $request->last_name;
            $validated['tenant_id'] = 1;
            $validated['client_id'] = 1;
            $validated['province_id'] = 1;
            $validated['paid'] = 0;
            $validated['payment_status'] = 0;

            $selectedServices = $selectedServicesValidation['selectedServices'];
            $inspection = Inspection::create($validated);

            foreach ($selectedServices as $service) {
                $service['type_id'] = $service['id'];
                $inspection->details()->create($service);
            }

            return ['success' => true, 'inspection' => $inspection];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getTypesForUser($user)
    {
        if ($user->type === 'client') {
            return $this->getTypesForClient($user->client);
        }

        return Type::where('tenant_id', getTenantId())
            ->where('category_id', 0)
            ->get();
    }

    private function getTypesForClient($client)
    {
        $ajaxController = new AjaxController();
        $jsonResponse = $ajaxController->getTypesByClient($client);
        $dataArray = json_decode($jsonResponse->getContent(), true);

        return isset($dataArray['data'])
            ? $dataArray['data']
            : collect([]);
    }

    public function getFormData()
    {
        return [
            'statuses' => Status::where('tenant_id', getTenantId())->get(),
            'clients' => Client::where('tenant_id', getTenantId())->get(),
            'employes' => Employee::where('tenant_id', getTenantId())->get(),
            'provincies' => Province::all(),
        ];
    }
}
