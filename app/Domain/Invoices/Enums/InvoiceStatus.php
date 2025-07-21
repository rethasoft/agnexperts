<?php

namespace App\Domain\Invoices\Enums;

enum InvoiceStatus: string
{
    case DRAFT = 'DRAFT';
    case SENT = 'SENT';
    case PAID = 'PAID';
    case OVERDUE = 'OVERDUE';

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'warning',
            self::SENT => 'primary',
            self::PAID => 'success',
            self::OVERDUE => 'danger'
        };
    }
} 