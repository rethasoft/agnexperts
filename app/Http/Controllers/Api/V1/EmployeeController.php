<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Domain\Events\Models\Event;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $employees = Employee::all(['id', 'name']);
            
            return response()->json([
                'success' => true,
                'data' => $employees
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch employees',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $event = Event::with(['employee'])->findOrFail($id);
            
            // Tüm gerekli alanları içeren detaylı veri
            $eventData = [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'is_all_day' => (bool) $event->is_all_day,
                'status' => $event->status,
                'type' => $event->type,
                'employee_id' => $event->employee_id,
                'employee' => $event->employee ? [
                    'id' => $event->employee->id,
                    'name' => $event->employee->name
                ] : null,
                'meta' => $event->meta ? json_decode($event->meta) : null
            ];
            
            return response()->json([
                'success' => true,
                'data' => $eventData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found: ' . $e->getMessage()
            ], 404);
        }
    }
}
