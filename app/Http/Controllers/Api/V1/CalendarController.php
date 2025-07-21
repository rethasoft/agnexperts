<?php

// app/Http/Controllers/Api/V1/CalendarController.php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domain\Calendar\Actions\FetchCalendarEventsAction;
use App\Domain\Calendar\DataTransferObjects\CalendarEventData;
use App\Http\Resources\CalendarEventResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\EmployeEvent;
use Illuminate\Support\Facades\Log;

class CalendarController extends Controller
{
    public function __construct(
        private readonly FetchCalendarEventsAction $fetchCalendarEventsAction
    ) {}

    public function index(Request $request)
    {
        // 1. Yöntem: Aktif guard'ı kontrol etme
        $currentGuard = Auth::getDefaultDriver(); // 'tenant' veya 'employee' döner

        // 2. Yöntem: Spesifik guard'ların durumunu kontrol etme
        $isTenant = Auth::guard('tenant')->check();
        $isEmployee = Auth::guard('employee')->check();

        // 3. Yöntem: Login olmuş kullanıcıyı ve tipini alma
        $user = Auth::user();

        try {
            $query = EmployeEvent::query()
                ->when($request->filled('start'), function ($query) use ($request) {
                    return $query->where('start_date', '>=', $request->start);
                })
                ->when($request->filled('end'), function ($query) use ($request) {
                    return $query->where('end_date', '<=', $request->end);
                });

            // Guard'a göre filtreleme
            if ($currentGuard === 'employee') {
                $query->where('employee_id', auth()->id());
            }

            $events = $query->get();

            return $events;
        } catch (\Exception $e) {
            Log::error('Calendar event fetch failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'guard' => $currentGuard
            ]);

            return response()->json([
                'message' => 'Failed to fetch events',
                'errors' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function events(Request $request): AnonymousResourceCollection
    {
        // Varsayılan tarih aralığı belirleyelim
        $startDate = $request->get('start') ?? now()->startOfMonth()->toDateString();
        $endDate = $request->get('end') ?? now()->endOfMonth()->toDateString();

        $events = $this->fetchCalendarEventsAction->execute(
            startDate: $startDate,
            endDate: $endDate
        );

        return CalendarEventResource::collection($events);
    }
}
