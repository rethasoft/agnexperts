<?php

namespace App\Domain\Invoices\Models;

use App\Domain\Inspections\Models\Inspection;
use App\Domain\Invoices\Enums\InvoiceStatus;
use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Domain\Invoices\Events\InvoiceStatusChanged;
use App\Traits\HasMoneyFormat;

class Invoice extends Model
{
    use HasMoneyFormat;

    protected $fillable = [
        'inspection_id',
        'file_id',
        'number',
        'amount',
        'tax_amount',
        'status',
        'issue_date',
        'due_date',
        'source',
        'items',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
        'items' => 'array',
        'metadata' => 'array',
        'status' => InvoiceStatus::class
    ];

    // Relations
    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    // Helper Methods
    public function isPaid(): bool
    {
        return $this->status === InvoiceStatus::PAID;
    }

    public function isOverdue(): bool
    {
        return !$this->isPaid() && $this->due_date->isPast();
    }

    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    public function updateStatus(string $newStatus): void
    {
        $oldStatus = $this->status;
        
        $this->update(['status' => $newStatus]);
        
        event(new InvoiceStatusChanged($this, $oldStatus, $newStatus));
    }

    public function getFormattedAmountAttribute(): string
    {
        return $this->formatMoney($this->amount);
    }
} 