<?php

namespace App\Domain\Inspections\Enums;

enum InspectionSource: string
{
    case ADMIN_PANEL = 'admin_panel';
    case WEBSITE = 'website';
    case API = 'api';
    
    public function label(): string
    {
        return match($this) {
            self::ADMIN_PANEL => 'Admin Panel',
            self::WEBSITE => 'Website',
            self::API => 'API',
        };
    }
    
    public function icon(): string
    {
        return match($this) {
            self::ADMIN_PANEL => 'ri-admin-line',
            self::WEBSITE => 'ri-global-line',
            self::API => 'ri-api-line',
        };
    }
} 