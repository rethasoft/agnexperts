<?php

namespace App\Traits;

trait HasMoneyFormat
{
    /**
     * Format any amount to currency format
     *
     * @param float|null $amount
     * @param string $currency
     * @return string
     */
    public function formatMoney(?float $amount, string $currency = 'EUR'): string
    {
        if (is_null($amount)) {
            return '-';
        }

        return match ($currency) {
            'EUR' => '€ ' . number_format($amount, 2, ',', '.'),
            'USD' => '$ ' . number_format($amount, 2, '.', ','),
            default => number_format($amount, 2, ',', '.')
        };
    }

    /**
     * Format any field as money
     *
     * @param string $field
     * @return string
     */
    public function getMoneyFormat(string $field): string
    {
        return $this->formatMoney($this->$field);
    }

    /**
     * Get raw amount from any field
     *
     * @param string $field
     * @return float|null
     */
    public function getRawAmount(string $field): ?float
    {
        return $this->$field;
    }
}