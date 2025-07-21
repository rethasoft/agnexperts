<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Events\Actions\CreateEventAction;
use App\Domain\Events\DTOs\EventData;
use App\Domain\Events\Repositories\EventRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DateTime;
use App\Domain\Events\Resources\EventResource;
use App\Domain\Events\Models\Event;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function __construct(
        private readonly EventRepositoryInterface $repository,
        private readonly CreateEventAction $createEventAction
    ) {}

    public function index(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            $query = Event::with(['employee']);

            // Tarih filtreleri
            if ($request->has('start') && $request->has('end')) {
                $query->whereBetween('start_date', [$request->start, $request->end]);
            }

            // Kullanıcı rolüne göre filtreleme
            // if ($user->type == 'employee') {
            //     // Çalışan sadece kendisine atanmış etkinlikleri görebilir
            //     $query->where('employee_id', $user->employee_id);
            // }
            // Admin tüm etkinlikleri görebilir (ek filtreleme gerekmez)

            $events = EventResource::collection($query->get());

            return response()->json([
                'success' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching events: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {

            // Sadece admin yeni etkinlik ekleyebilir
            // if ($user->type !== 'admin') {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Unauthorized. Only admins can create events.'
            //     ], 403);
            // }

            // Validate the request
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date_format:Y-m-d H:i:s',
                'end_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:start_date',
                'status' => 'required|string|in:annual_leave,sick_leave,maternity_leave,unpaid_leave,business_trip,remote_work,overtime,training,other',
                'type' => 'required|string|in:standard,vacation,sick_leave,meeting,personal',
                'is_all_day' => 'boolean',
                'employee_id' => 'required|exists:employees,id',
                'meta' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            // Check for conflicting events
            $conflictingEvents = $this->checkForConflictingEvents(
                $validatedData['employee_id'],
                $validatedData['start_date'],
                $validatedData['end_date'],
                null // No event ID for new events
            );

            if ($conflictingEvents->isNotEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Çakışan etkinlik',
                    'error' => 'Bu çalışanın bu zaman diliminde zaten bir etkinliği var.',
                    'conflicting_events' => EventResource::collection($conflictingEvents)
                ], 422);
            }

            $event = Event::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Etkinlik başarıyla oluşturuldu',
                'data' => new EventResource($event)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            $errorTrace = config('app.debug') ? $e->getTraceAsString() : null;

            return response()->json([
                'success' => false,
                'message' => 'Etkinlik oluşturulurken hata oluştu',
                'error' => $errorMessage,
                'trace' => $errorTrace
            ], 500);
        }
    }

    public function checkAvailability(Request $request): JsonResponse
    {
        try {
            $startDate = new DateTime($request->get('start_date'));
            $endDate = new DateTime($request->get('end_date'));
            $employeeId = $request->get('employee_id');

            $hasConflict = $this->repository->hasConflict(
                employeeId: $employeeId,
                startDate: $startDate,
                endDate: $endDate
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'is_available' => !$hasConflict,
                    'conflicts' => $hasConflict ?
                        $this->repository->getEmployeeAvailability($employeeId, $startDate, $endDate) :
                        []
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check availability',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            // $user = auth()->user();

            // // Sadece admin etkinlikleri düzenleyebilir
            // if ($user->role !== 'admin') {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Unauthorized. Only admins can update events.'
            //     ], 403);
            // }

            if ($request->process && ($request->process == 'dragAndDrop' || $request->process && $request->process == 'resize')) {
                $validator = Validator::make($request->all(), [
                    'start_date' => 'required|date_format:Y-m-d H:i:s',
                    'end_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:start_date',
                ]);
            } else {

                // Validate the request
                $validator = Validator::make($request->all(), [
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'start_date' => 'required|date_format:Y-m-d H:i:s',
                    'end_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:start_date',
                    'status' => 'required|string|in:annual_leave,sick_leave,maternity_leave,unpaid_leave,business_trip,remote_work,overtime,training,other',
                    'is_all_day' => 'boolean',
                    'employee_id' => 'required|exists:employees,id',
                    'meta' => 'nullable|array',
                ]);
            }


            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validatedData = $validator->validated();

            $event = Event::findOrFail($id);

            // Check for conflicting events (excluding the current event)
            $conflictingEvents = $this->checkForConflictingEvents(
                isset($validatedData['employee_id']) || $event->employee_id,
                $validatedData['start_date'],
                $validatedData['end_date'],
                $id
            );

            if ($conflictingEvents->isNotEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Çakışan etkinlik',
                    'error' => 'Bu çalışanın bu zaman diliminde zaten bir etkinliği var.',
                    'conflicting_events' => EventResource::collection($conflictingEvents)
                ], 422);
            }

            $event->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Etkinlik başarıyla güncellendi',
                'data' => new EventResource($event)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Etkinlik bulunamadı'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Doğrulama hatası',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Etkinlik güncellenirken hata oluştu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified event.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $user = auth()->user();

            // Sadece admin etkinlikleri silebilir
            if ($user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only admins can delete events.'
                ], 403);
            }

            $event = Event::findOrFail($id);

            // Önce event'i cancelled olarak işaretle
            $event->update([
                'status' => Event::STATUS_CANCELLED,
                'cancellation_reason' => $request->reason,
                // 'cancelled_by' => auth()->user()->id
            ]);

            // Soft delete uygula
            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Event cancelled successfully',
                'data' => new EventResource($event)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while cancelling the event',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $user = auth()->user();
            $query = Event::with(['employee']);

            // Kullanıcı rolüne göre filtreleme
            // if ($user->role === 'employee') {
            //     // Çalışan sadece kendisine atanmış etkinlikleri görebilir
            //     $query->where('employee_id', $user->employee_id);
            // }

            $event = $query->findOrFail($id);

            // Tüm gerekli alanları içeren detaylı veri
            $eventData = new EventResource($event);

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

    /**
     * Check for conflicting events for an employee in a given time period.
     *
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     * @param string|null $excludeEventId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function checkForConflictingEvents($employeeId, $startDate, $endDate, $excludeEventId = null)
    {
        $query = Event::where('employee_id', $employeeId)
            ->where(function ($query) use ($startDate, $endDate) {
                // Event starts during the new event
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '>=', $startDate)
                        ->where('start_date', '<', $endDate);
                })
                    // Event ends during the new event
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('end_date', '>', $startDate)
                            ->where('end_date', '<=', $endDate);
                    })
                    // Event encompasses the new event
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            });

        // Exclude the current event when updating
        if ($excludeEventId) {
            $query->where('id', '!=', $excludeEventId);
        }

        return $query->get();
    }
}
