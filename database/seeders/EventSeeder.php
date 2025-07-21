<?php

namespace Database\Seeders;

use App\Domain\Events\Models\Event;
use App\Domain\Events\Enums\EventType;
use App\Domain\Events\Enums\EventStatus;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Çalışanları al
        $employees = \App\Models\Employee::all();
        
        if ($employees->isEmpty()) {
            $this->command->info('Önce çalışan oluşturmanız gerekiyor!');
            return;
        }

        // Etkinlik türleri - enum değerlerini kullan
        $eventTypes = [
            EventType::STANDARD->value,
            EventType::VACATION->value,
            EventType::SICK_LEAVE->value,
            EventType::MEETING->value,
            EventType::PERSONAL->value,
        ];

        // Etkinlik durumları
        $eventStatuses = [
            'scheduled',
            'confirmed',
            'completed',
            'cancelled',
        ];

        // Renkler
        $colors = [
            '#3788d8', // Mavi
            '#28a745', // Yeşil
            '#dc3545', // Kırmızı
            '#fd7e14', // Turuncu
            '#6f42c1', // Mor
            '#20c997', // Turkuaz
            '#6c757d', // Gri
        ];

        // Bugünden başlayarak 30 gün için etkinlikler oluştur
        $startDate = Carbon::now()->startOfDay();
        
        // Her çalışan için 10 etkinlik oluştur
        foreach ($employees as $employee) {
            for ($i = 0; $i < 10; $i++) {
                // Rastgele bir gün seç (bugünden itibaren 30 gün içinde)
                $eventDate = $startDate->copy()->addDays(rand(1, 30));
                
                // Rastgele başlangıç saati (8:00 - 17:00 arası)
                $startHour = rand(8, 16);
                $startMinute = [0, 15, 30, 45][rand(0, 3)];
                
                $eventStartDate = $eventDate->copy()->setHour($startHour)->setMinute($startMinute);
                
                // Etkinlik süresi (30 dk - 3 saat arası)
                $durationHours = rand(0, 2);
                $durationMinutes = [30, 45, 60][rand(0, 2)];
                
                $eventEndDate = $eventStartDate->copy()->addHours($durationHours)->addMinutes($durationMinutes);
                
                // Rastgele tür ve durum seç
                $type = $eventTypes[rand(0, count($eventTypes) - 1)];
                $status = $eventStatuses[rand(0, count($eventStatuses) - 1)];
                
                // Tüm gün etkinliği mi?
                $isAllDay = rand(0, 10) < 2; // %20 ihtimalle tüm gün
                
                // Tüm gün etkinliği ise, başlangıç ve bitiş saatlerini ayarla
                if ($isAllDay) {
                    $eventStartDate = $eventDate->copy()->startOfDay();
                    $eventEndDate = $eventDate->copy()->endOfDay();
                }
                
                // Etkinlik başlığı
                $titles = [
                    'standard' => ['Toplantı', 'Görüşme', 'Randevu', 'Sunum', 'Eğitim'],
                    'vacation' => ['Yıllık İzin', 'Tatil', 'Dinlenme'],
                    'sick_leave' => ['Hastalık İzni', 'Doktor Randevusu', 'Tedavi'],
                    'meeting' => ['Ekip Toplantısı', 'Müşteri Görüşmesi', 'Proje Toplantısı'],
                    'personal' => ['Kişisel İzin', 'Özel Gün', 'Aile Etkinliği'],
                ];
                
                $title = $titles[$type][rand(0, count($titles[$type]) - 1)];
                
                // Açıklama
                $descriptions = [
                    'standard' => ['Standart bir randevu', 'Genel bir görüşme', 'Rutin toplantı'],
                    'vacation' => ['Yıllık izin kullanımı', 'Tatil planı', 'Dinlenme zamanı'],
                    'sick_leave' => ['Hastalık nedeniyle izin', 'Sağlık kontrolü', 'Tedavi süreci'],
                    'meeting' => ['Haftalık ekip toplantısı', 'Müşteri ile görüşme', 'Proje değerlendirmesi'],
                    'personal' => ['Kişisel nedenlerle izin', 'Özel bir gün', 'Aile ile ilgili etkinlik'],
                ];
                
                $description = $descriptions[$type][rand(0, count($descriptions[$type]) - 1)];
                
                // Meta verisi
                $meta = [
                    'color' => $colors[rand(0, count($colors) - 1)],
                    'is_private' => rand(0, 10) < 3, // %30 ihtimalle özel
                    'notes' => 'Test etkinliği için notlar',
                ];
                
                // Etkinliği oluştur
                Event::create([
                    'employee_id' => $employee->id,
                    'title' => $title,
                    'description' => $description,
                    'start_date' => $eventStartDate,
                    'end_date' => $eventEndDate,
                    'type' => $type,
                    'status' => $status,
                    'is_all_day' => $isAllDay,
                    'is_available' => rand(0, 10) < 8, // %80 ihtimalle müsait
                    'meta' => $meta,
                ]);
            }
        }
        
        $this->command->info('Toplam ' . ($employees->count() * 10) . ' test etkinliği oluşturuldu!');
    }
} 