<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateInspectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Gerekirse auth kontrolü eklenebilir
    }

    public function rules(): array
    {
        return [
            'client_id' => ['nullable'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string'],
            'company_name' => ['nullable', 'string'],
            'vat_number' => ['nullable', 'string'],
            
            // Address
            'street' => ['required', 'string'],
            'number' => ['required', 'string'],
            'box' => ['nullable', 'string'],
            'floor' => ['nullable', 'string'],
            'postal_code' => ['required', 'string'],
            'city' => ['required', 'string'],
            'province' => ['nullable', 'string'],
            'province_id' => ['required', 'integer', 'min:1', 'exists:provinces,id'],
            
            // Billing Address
            'has_billing_address' => ['nullable'],
            'billing_street' => ['required_with:billing_number,billing_postal_code,billing_city'],
            'billing_number' => ['required_with:billing_street,billing_postal_code,billing_city'],
            'billing_box' => ['nullable', 'string'],
            'billing_postal_code' => ['required_with:billing_street,billing_number,billing_city'],
            'billing_city' => ['required_with:billing_street,billing_number,billing_postal_code'],
            
            // Financial
            'total' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'subtotal' => ['nullable', 'numeric', 'min:0'],
            'paid' => ['boolean'],
            'payment_status' => ['string', 'in:pending,paid,cancelled'],
            'status_id' => ['nullable', 'exists:statuses,id'],
            // Additional
            'inspection_date' => ['nullable', 'date'],
            'text' => ['nullable', 'string'],
            'items' => ['nullable', 'array'],
            'items.*.category_id' => ['nullable', 'integer', 'min:0'],
            'items.*.type_id' => ['nullable', 'integer', 'exists:types,id'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.is_offerte' => ['nullable', 'boolean'],
            
            // Combi Discount
            'combi_discount_id' => ['nullable', 'integer'],
            'combi_discount_type' => ['nullable', 'string', 'in:percentage,fixed'],
            'combi_discount_value' => ['nullable', 'numeric', 'min:0'],
            'combi_discount_amount' => ['nullable', 'numeric', 'min:0'],
        ];
    }
} 