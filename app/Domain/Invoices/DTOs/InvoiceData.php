<?php

namespace App\Domain\Invoices\DTOs;

use App\Domain\Invoices\Enums\InvoiceStatus;

class InvoiceData
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $inspection_id,
        public readonly ?int $file_id,
        public readonly string $number,
        public readonly float $amount,
        public readonly float $tax_amount,
        public readonly InvoiceStatus $status,
        public readonly string $issue_date,
        public readonly string $due_date,
        public readonly string $source,
        public readonly array $items,
        public readonly ?array $metadata = []
    ) {}

    public static function fromInspection($inspection): self
    {
        return new self(
            id: null,
            inspection_id: $inspection->id,
            file_id: null,
            number: 'INV-' . date('Y') . '-' . str_pad($inspection->id, 4, '0', STR_PAD_LEFT),
            amount: $inspection->items->sum('price'),
            tax_amount: $inspection->items->sum('tax_amount'),
            status: InvoiceStatus::DRAFT,
            issue_date: now()->format('Y-m-d'),
            due_date: now()->addDays(30)->format('Y-m-d'),
            source: 'manual',
            items: $inspection->items->map(fn($item) => [
                'description' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'tax_rate' => $item->tax_rate
            ])->toArray()
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'inspection_id' => $this->inspection_id,
            'file_id' => $this->file_id,
            'number' => $this->number,
            'amount' => $this->amount,
            'tax_amount' => $this->tax_amount,
            'status' => $this->status->value,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'source' => $this->source,
            'items' => $this->items,
            'metadata' => $this->metadata
        ];
    }
} 