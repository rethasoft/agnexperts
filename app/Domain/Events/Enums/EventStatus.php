<?php

namespace App\Domain\Events\Enums;

enum EventStatus: string
{
    case ANNUAL_LEAVE = 'annual_leave';         // Yıllık İzin
    case SICK_LEAVE = 'sick_leave';            // Hastalık İzni
    case MATERNITY_LEAVE = 'maternity_leave';  // Doğum İzni
    case UNPAID_LEAVE = 'unpaid_leave';        // Ücretsiz İzin
    case BUSINESS_TRIP = 'business_trip';      // İş Seyahati
    case REMOTE_WORK = 'remote_work';          // Uzaktan Çalışma
    case OVERTIME = 'overtime';                // Mesai
    case TRAINING = 'training';                // Eğitim
    case OTHER = 'other';                      // Diğer

    public function label(): string
    {
        return match($this) {
            self::ANNUAL_LEAVE => 'Annual Leave',
            self::SICK_LEAVE => 'Sick Leave',
            self::MATERNITY_LEAVE => 'Maternity Leave',
            self::UNPAID_LEAVE => 'Unpaid Leave',
            self::BUSINESS_TRIP => 'Business Trip',
            self::REMOTE_WORK => 'Remote Work',
            self::OVERTIME => 'Overtime',
            self::TRAINING => 'Training',
            self::OTHER => 'Other',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ANNUAL_LEAVE => 'success',    // Yeşil
            self::SICK_LEAVE => 'danger',       // Kırmızı
            self::MATERNITY_LEAVE => 'info',    // Mavi
            self::UNPAID_LEAVE => 'warning',    // Sarı
            self::BUSINESS_TRIP => 'primary',   // Koyu Mavi
            self::REMOTE_WORK => 'secondary',   // Gri
            self::OVERTIME => 'purple',         // Mor
            self::TRAINING => 'info',          // Mavi
            self::OTHER => 'secondary',        // Gri
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::ANNUAL_LEAVE => 'ri-sun-line',
            self::SICK_LEAVE => 'ri-heart-pulse-line',
            self::MATERNITY_LEAVE => 'ri-parent-line',
            self::UNPAID_LEAVE => 'ri-calendar-event-line',
            self::BUSINESS_TRIP => 'ri-flight-takeoff-line',
            self::REMOTE_WORK => 'ri-computer-line',
            self::OVERTIME => 'ri-time-line',
            self::TRAINING => 'ri-graduation-cap-line',
            self::OTHER => 'ri-calendar-todo-line',
        };
    }
} 