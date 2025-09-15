<?php

namespace App\Domain\Inspections\Actions;

use App\Domain\Inspections\DTOs\InspectionData;
use App\Domain\Inspections\Models\Inspection;
use App\Domain\Inspections\Events\InspectionCreated;
use App\Domain\Inspections\Exceptions\InspectionException;
use App\Domain\Inspections\Exceptions\InspectionValidationException;
use App\Models\Status;
use Illuminate\Support\Facades\Log;

class CreateInspectionAction
{
    public function execute(InspectionData $data): Inspection
    {
        try {
            // Validation kontrolleri
            $errors = [];

            foreach ($data->items as $index => $item) {
                if ($item->price < 0) {
                    $errors["items.{$index}.price"] = 'Price cannot be negative';
                }
                if ($item->quantity < 1) {
                    $errors["items.{$index}.quantity"] = 'Quantity must be at least 1';
                }
            }

            if (!empty($errors)) {
                throw new InspectionValidationException($errors);
            }

            // İşlemleri gerçekleştir
            // Varsayılan status ID'yi belirle (formdan gelmiyorsa default status'u ata)
            $resolvedStatusId = $data->status_id ?: (optional(Status::default()->first())->id);

            $inspection = Inspection::create([
                'tenant_id' => $data->tenant_id,
                'client_id' => $data->client_id,
                'employee_id' => $data->employee_id,
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'company_name' => $data->company_name,
                'vat_number' => $data->vat_number,

                // Address
                'street' => $data->street,
                'number' => $data->number,
                'box' => $data->box,
                'floor' => $data->floor,
                'postal_code' => $data->postal_code,
                'city' => $data->city,
                'province' => $data->province,
                'province_id' => $data->province_id,

                // Billing Address
                'has_billing_address' => $data->has_billing_address,
                'billing_street' => $data->billing_street,
                'billing_number' => $data->billing_number,
                'billing_box' => $data->billing_box,
                'billing_postal_code' => $data->billing_postal_code,
                'billing_city' => $data->billing_city,

                // Financial
                'total' => $data->total,
                'tax' => $data->tax,
                'subtotal' => $data->subtotal,
                'paid' => $data->paid,
                'payment_status' => $data->payment_status,

                // Additional
                'inspection_date' => $data->inspection_date,
                'text' => $data->text,
                'combi_discount_id' => $data->combi_discount_id,
                'combi_discount_type' => $data->combi_discount_type,
                'combi_discount_value' => $data->combi_discount_value,
                'combi_discount_amount' => $data->combi_discount_amount,

                'status_id' => $resolvedStatusId
            ]);

            // Items oluştur
            foreach ($data->items as $item) {
                $inspection->items()->create([
                    'category_id' => $item->category_id,
                    'type_id'     => $item->type_id,
                    'name'        => $item->name,
                    'quantity'    => $item->quantity,
                    'price'       => $item->price,
                    'total'       => $item->total,
                    'is_offerte'  => $item->is_offerte ?? false,
                ]);
            }

            // Event'i tetikle
            event(new InspectionCreated($inspection));

            return $inspection;
        } catch (InspectionException $e) {
            Log::error('Inspection validation error', [
                'message' => $e->getMessage(),
                'errors' => $e->getErrors(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error while creating inspection', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => [
                    'client_id' => $data->client_id,
                    'employee_id' => $data->employee_id,
                    'inspection_date' => $data->inspection_date
                ]
            ]);

            throw new InspectionException(
                'Er is een onverwachte fout opgetreden tijdens de verwerking. Probeer het later opnieuw.',
                ['error' => 'unexpected_error'],
                500
            );
        }
    }
}
