<?php

use App\Models\Setting;
use App\Models\Comment;

if (!function_exists('getTenantId')) {
    function getTenantId()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->type == 'tenant') {
                return $user->id;
            }
            if ($user->type == 'client') {
                return $user->client->tenant_id;
            }
            if ($user->type == 'employee') {
                return $user->employee->tenant_id;
            }
        }
        // Default value if no authentication is found
        return null;
    }
}

if (!function_exists('getClientId')) {
    function getClientId()
    {
        return auth()->user()->client->id;
    }
}

if (!function_exists('settings')) {
    function settings()
    {
        return Setting::first();
    }
}

if (!function_exists('toEuro')) {
    function toEuro($amount, $currency = '€')
    {
        // Remove any thousands separator (comma) from the amount
        //  $amount = str_replace(',', '', $amount);

        // Round the amount to two decimal places
        $amount = round($amount, 2);
        return $amount;
        // Format the rounded amount with two decimal places and currency symbol
        //  return number_format($amount, 2, '.', ',');
    }
}

if (!function_exists('createComment')) {
    function createComment($table, $action, $id, $message)
    {
        $saved = Comment::create([
            'object_id' => $id,
            'table' => $table,
            'action' => $action,
            'message' => $message
        ]);

        if (!$saved) {
            return ['status' => 'error', 'message' => 'Kan log niet maken.'];
        }

        return ['status' => 'success', 'message' => 'Log toegevoegd.'];
    }
}



if (! function_exists('guarded_route')) {
    function guarded_route($name, $parameters = [], $absolute = true)
    {
        // Kullanıcının hangi guard ile giriş yaptığını kontrol et
        if (auth()->guard('tenant')->check()) {
            $prefix = 'tenant';
        } elseif (auth()->guard('client')->check()) {
            $prefix = 'client';
        } else {
            $prefix = 'web'; // Eğer hiçbir guard bulunamazsa default olarak web kullan
        }

        return route("{$prefix}.{$name}", $parameters, $absolute);
    }
}
