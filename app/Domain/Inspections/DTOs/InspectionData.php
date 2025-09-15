<?php

namespace App\Domain\Inspections\DTOs;

use App\Http\Requests\CreateInspectionRequest;
use App\Http\Requests\UpdateInspectionRequest;
use DomainException;
use App\Domain\Inspections\Enums\InspectionSource;

class InspectionData
{
    public function __construct(
        public int $tenant_id,
        public ?int $client_id,
        public ?int $employee_id,
        public string $name,
        public string $email,
        public ?string $phone,
        public ?string $company_name,
        public ?string $vat_number,
        // Address
        public string $street,
        public string $number,
        public ?string $box,
        public ?string $floor,
        public string $postal_code,
        public string $city,
        public ?string $province,
        public ?int $province_id,
        // Billing Address
        public ?bool $has_billing_address = false,
        public ?string $billing_street = null,
        public ?string $billing_number = null,
        public ?string $billing_box = null,
        public ?string $billing_postal_code = null,
        public ?string $billing_city = null,
        // Financial
        public float $total = 0,
        public float $tax = 0,
        public float $subtotal = 0,
        public bool $paid = false,
        public string $payment_status = 'pending',
        public int $status_id = 0,
        // Additional
        public ?string $inspection_date = null,
        public ?string $text = null,
        /** @var InspectionItemData[] */
        public array $items = [],
        public readonly InspectionSource $source,
        public ?int $combi_discount_id = null,
        public ?string $combi_discount_type = null,
        public ?float $combi_discount_value = null,
        public ?float $combi_discount_amount = null,
    ) {}

    public static function fromRequest(CreateInspectionRequest|UpdateInspectionRequest $request): self
    {
        $items = collect($request->items ?? [])->map(function ($item) {
            return new InspectionItemData(
                category_id: $item['category_id'],
                type_id: $item['type_id'],
                name: $item['name'],
                quantity: $item['quantity'],
                price: $item['price'],
                total: $item['total'] ?? null,
                is_offerte: $item['is_offerte'] ?? false
            );
        })->toArray();

        $tenantId = 1; // Helper fonksiyonunu çağırıyoruz

        if (!$tenantId) {
            throw new DomainException('Tenant ID could not be retrieved');
        }

        $source = $request->is('api/*') 
            ? InspectionSource::API 
            : (auth()->check() 
                ? InspectionSource::ADMIN_PANEL 
                : InspectionSource::WEBSITE);

        return new self(
            tenant_id: $tenantId,
            client_id: $request->client_id,
            employee_id: $request->employee_id,
            name: $request->name,
            email: $request->email,
            phone: $request->phone,
            company_name: $request->company_name,
            vat_number: $request->vat_number,
            street: $request->street,
            number: $request->number,
            box: $request->box,
            floor: $request->floor,
            postal_code: $request->postal_code,
            city: $request->city,
            province: $request->province,
            province_id: $request->province_id,
            has_billing_address: !$request->has_billing_address, // Checkbox logic: checked = same address (false), unchecked = different address (true)
            billing_street: $request->billing_street,
            billing_number: $request->billing_number,
            billing_box: $request->billing_box,
            billing_postal_code: $request->billing_postal_code,
            billing_city: $request->billing_city,
            total: $request->total ?? 0,
            tax: $request->tax ?? 0,
            subtotal: $request->subtotal ?? 0,
            paid: $request->paid ?? false,
            payment_status: $request->payment_status ?? 'pending',
            inspection_date: $request->inspection_date,
            text: $request->text,
            status_id: $request->status_id ?? 0,
            items: $items,
            source: $source,
            combi_discount_id: $request->combi_discount_id,
            combi_discount_type: $request->combi_discount_type,
            combi_discount_value: $request->combi_discount_value,
            combi_discount_amount: $request->combi_discount_amount,
        );
    }
}
