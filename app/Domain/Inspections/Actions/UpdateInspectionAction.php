<?php

namespace App\Domain\Inspections\Actions;

use App\Domain\Inspections\DTOs\InspectionData;
use App\Domain\Inspections\Models\Inspection;
use App\Domain\Inspections\Exceptions\InspectionException;
use App\Domain\Inspections\Exceptions\InspectionValidationException;
use App\Domain\Inspections\Events\InspectionScheduleChanged;
use App\Domain\Inspections\Events\InspectionStatusChanged;

class UpdateInspectionAction
{
    public function execute(Inspection $inspection, InspectionData $data): Inspection
    {
        try {
            $oldDate = $inspection->inspection_date;

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
            $inspection->update([
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
                'status_id' => $data->status_id,
                // Additional
                'inspection_date' => $data->inspection_date,
                'text' => $data->text,
                'combi_discount_id' => $data->combi_discount_id,
                'combi_discount_type' => $data->combi_discount_type,
                'combi_discount_value' => $data->combi_discount_value,
                'combi_discount_amount' => $data->combi_discount_amount,
            ]);

            // Mevcut öğeleri sil
            $inspection->items()->delete();

            // Yeni öğeleri oluştur
            foreach ($data->items as $item) {
                $inspection->items()->create([
                    'category_id' => $item->category_id,
                    'type_id' => $item->type_id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total' => $item->price * $item->quantity,
                    'is_offerte' => $item->is_offerte ?? false,
                ]);
            }

            $oldStatus = $inspection->status_id;
            $inspection = $inspection->fresh();

            // Check if inspection date was changed and trigger event
            if ($oldDate?->format('Y-m-d H:i:s') !== $inspection->inspection_date?->format('Y-m-d H:i:s')) {
                event(new InspectionScheduleChanged(
                    oldDate: $oldDate?->format('Y-m-d H:i:s') ?? '',
                    newDate: $inspection->inspection_date?->format('Y-m-d H:i:s') ?? '',
                    inspection: $inspection
                ));
            }

            // Check if status was changed and trigger event
            if ($oldStatus !== $inspection->status_id) {
                event(new InspectionStatusChanged(
                    oldStatus: $oldStatus,
                    newStatus: $inspection->status_id,
                    inspection: $inspection
                ));
            }

            return $inspection;
        } catch (InspectionException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw new InspectionException(
                'Failed to update inspection: ' . $e->getMessage(),
                [],
                500
            );
        }
    }
}
