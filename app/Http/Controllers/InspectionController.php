<?php

namespace App\Http\Controllers;

use App\Traits\FileUploadTrait;
use App\Domain\Inspections\Actions\CreateInspectionAction;
use App\Domain\Inspections\Actions\UpdateInspectionAction;
use App\Domain\Inspections\DTOs\InspectionData;
use App\Domain\Inspections\Models\Inspection;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Type;
use App\Models\Province;
use App\Models\Status;
use App\Domain\Inspections\Exceptions\InspectionValidationException;
use App\Domain\Inspections\Exceptions\InspectionException;
use App\Domain\Invoices\Services\InvoiceService;
use App\Http\Requests\CreateInspectionRequest;
use App\Http\Requests\UpdateInspectionRequest;
use App\Http\Resources\InspectionResource;
use App\Domain\Inspections\Services\InspectionService;
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Events\Actions\CreateEventAction;
use App\Domain\Events\DTOs\EventData;
use App\Domain\Events\Models\Event;
use App\Domain\Events\Actions\UpdateEventAction;
use App\Domain\Status\Services\StatusService;
use App\Services\TransactionService;
use App\Services\GuardResolver;
use App\Exceptions\InspectionException as CustomInspectionException;
use Illuminate\Support\Facades\Log;


class InspectionController extends Controller
{
    use FileUploadTrait;

    public function __construct(
        private readonly CreateInspectionAction $createInspectionAction,
        private readonly UpdateInspectionAction $updateInspectionAction,
        private readonly InvoiceService $invoiceService,
        private readonly CreateEventAction $createEventAction,
        private readonly UpdateEventAction $updateEventAction,
        private readonly StatusService $statusService,
        private readonly InspectionService $inspectionService,
        private readonly GuardResolver $ctx
    ) {}

    public function index()
    {
        $inspections = Inspection::with(['client', 'employee', 'items', 'invoice'])
            ->forUser()
            ->latest()
            ->limit(50)
            ->get();

        $inspections = InspectionResource::collection($inspections);
        $statuses = $this->statusService->getAllStatuses();

        $guard = $this->ctx->guard();
        return view('app.tenant.inspections.index', compact('inspections', 'statuses', 'guard'));
    }

    public function create()
    {

        $guard = $this->ctx->guard();
        
        $types = collect();
        $clients = Client::all();
        $employees = Employee::all();

        if (auth()->guard('client')->check()) {
            $types = $this->inspectionService->getTypesForClient(auth()->guard('client')->user()->client->id);
        } else {
            $types = Type::where('category_id', 0)
                ->with(['subTypes' => function ($q) {
                    $q->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->get();
        }

        $provinces = Province::all();
        return view('app.tenant.inspections.create', compact('clients', 'employees', 'types', 'provinces', 'guard'));
    }

    private function createInspectionEvent(Inspection $inspection): void
    {
        if ($inspection->employee_id) {
            $inspection->load('items');

            $existingEvent = Event::query()
                ->where('eventable_type', get_class($inspection))
                ->where('eventable_id', $inspection->id)
                ->first();

            $eventData = EventData::fromArray([
                'title'          => "Inspectie: {$inspection->file_id}",
                'start_date'     => $inspection->inspection_date,
                'end_date'       => $inspection->inspection_date->copy()->addHours(2),
                'description'    => "Inspectie voor {$inspection->formatted_address}",
                'employee_id'    => $inspection->employee_id,
                'status'         => 'scheduled',
                'is_all_day'     => false,
                'inspection_id'  => $inspection->id,
                'meta'           => $inspection->items->toArray(),
                'eventable_type' => get_class($inspection),
                'eventable_id'   => $inspection->id,
                'type'           => 'inspection'
            ]);

            if ($existingEvent) {
                $this->updateEventAction->execute($existingEvent, $eventData);
            } else {
                $this->createEventAction->execute($eventData);
            }
        }
    }

    public function store(CreateInspectionRequest $request)
    {
        $requestId = uniqid('inspection_create_');

        try {
            $inspection = TransactionService::execute(function () use ($request, $requestId) {
                // Create inspection
                $inspection = $this->createInspectionAction->execute(
                    InspectionData::fromRequest($request)
                );

                // Create event
                $this->createInspectionEvent($inspection);

                // Handle file uploads
                $this->handleFileUploads($request, $inspection, $requestId);

                return $inspection;
            }, 'inspection_creation');

            // Check which button was clicked
            $guard = $this->ctx->guard();
            
            if ($request->has('to-detail')) {
                // "Opslaan & Details" button - redirect to edit page
                return redirect()
                    ->route($guard . '.inspections.edit', $inspection)
                    ->with('msg', 'Inspectie succesvol aangemaakt');
            } else {
                // "Opslaan" button - redirect back to create page
                return redirect()
                    ->route($guard . '.inspections.create')
                    ->with('msg', 'Inspectie succesvol aangemaakt');
            }
        } catch (InspectionValidationException $e) {
            return back()->withErrors($e->getErrors());
        } catch (InspectionException $e) {
            return back()->withErrors($e->getMessage());
        } catch (\Exception $e) {
            Log::error('InspectionController@store: ' . $e->getMessage());
            return redirect()
                ->route($this->ctx->guard() . '.inspections.create')
                ->withInput($request->all())
                ->withErrors('Hata: ' . $e->getMessage());
        }
    }

    /**
     * Handle file uploads for inspection
     */
    private function handleFileUploads($request, Inspection $inspection, string $requestId): void
    {
        try {
            if ($request->hasFile('invoice')) {
                Log::info('Processing invoice file upload', [
                    'request_id' => $requestId,
                    'inspection_id' => $inspection->id,
                ]);

                $invoice = $this->invoiceService->createFromInspection($inspection);
                $files = $this->uploadFiles(
                    [$request->file('invoice')],
                    'invoice',
                    $inspection->id,
                    'inspection'
                );

                if ($files->isNotEmpty()) {
                    $invoice->update(['file_id' => $files->first()->id]);
                }
            }

            if ($request->hasFile('admin_files')) {
                Log::info('Processing admin files upload', [
                    'request_id' => $requestId,
                    'inspection_id' => $inspection->id,
                    'file_count' => count($request->file('admin_files')),
                ]);

                $this->uploadFiles($request->file('admin_files'), 'admin', $inspection->id, 'inspection');
            }

            if ($request->hasFile('customer_files')) {
                Log::info('Processing customer files upload', [
                    'request_id' => $requestId,
                    'inspection_id' => $inspection->id,
                    'file_count' => count($request->file('customer_files')),
                ]);

                $this->uploadFiles($request->file('customer_files'), 'customer', $inspection->id, 'inspection');
            }
        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'request_id' => $requestId,
                'inspection_id' => $inspection->id,
                'error' => $e->getMessage(),
            ]);

            throw CustomInspectionException::externalServiceError('File Upload Service', [
                'inspection_id' => $inspection->id,
                'original_error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get current authentication guard
     */
    private function getCurrentGuard(): string
    {
        if (auth()->guard('client')->check()) {
            return 'client';
        } elseif (auth()->guard('tenant')->check()) {
            return 'tenant';
        }

        return 'tenant'; // Default
    }

    public function show(Inspection $inspection)
    {
        abort_if(!$inspection->userHasAccess(), 403, 'U heeft geen toegang tot deze inspectie.');

        $inspection->load(['client', 'employee', 'items', 'province']);
        $statuses = Status::all();

        $guard = $this->ctx->guard();
        return view('app.tenant.inspections.show', compact('inspection', 'statuses', 'guard'));
    }

    public function edit(Inspection $inspection)
    {
        abort_if(!$inspection->userHasAccess(), 403, 'U heeft geen toegang tot deze inspectie.');

        $inspection->load(['client', 'employee', 'items', 'items.category', 'combiDiscount']);
        $clients = Client::all();
        $provinces = Province::all();
        $types = Type::where('category_id', 0)
            ->with(['subTypes' => function ($q) {
                $q->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
        
        $guard = $this->ctx->guard();
        return view('app.tenant.inspections.edit', compact('inspection', 'clients', 'provinces', 'types', 'guard'));
    }

    public function update(UpdateInspectionRequest $request, Inspection $inspection)
    {
        try {

            $inspection = $this->updateInspectionAction->execute(
                $inspection,
                InspectionData::fromRequest($request)
            );

            // Event'i güncelle veya oluştur
            $this->createInspectionEvent($inspection);

            if ($request->hasFile('invoice')) {
                // Önce invoice service ile invoice kaydı oluştur
                $invoice = $this->invoiceService->createFromInspection($inspection);

                // Sonra dosyayı yükle ve invoice ile ilişkilendir
                $files = $this->uploadFiles(
                    [$request->file('invoice')],
                    'invoice',  // type olarak 'invoice' belirtiyoruz
                    $inspection->id,
                    'inspection'
                );

                // İlk dosyayı invoice ile ilişkilendir
                if ($files->isNotEmpty()) {
                    $invoice->update(['file_id' => $files->first()->id]);
                }
            }

            if ($request->hasFile('admin_files')) {
                $this->uploadFiles($request->file('admin_files'), 'admin', $inspection->id, 'inspection');
            }

            if ($request->hasFile('customer_files')) {
                $this->uploadFiles($request->file('customer_files'), 'customer', $inspection->id, 'inspection');
            }

            return redirect()
                ->route($this->ctx->guard() . '.inspections.edit', $inspection)
                ->with('msg', 'Inspectie succesvol bijgewerkt');
        } catch (InspectionValidationException $e) {
            return back()->withErrors($e->getErrors());
        } catch (InspectionException $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function destroy(Inspection $inspection)
    {
        // Önce dosyaları silelim
        $this->deleteFiles($inspection->id, 'Inspection');
        $this->deleteFiles($inspection->id, 'InspectionDocs');

        $inspection->delete();

        return redirect()->route($this->ctx->guard() . '.inspections.index')
            ->with('success', 'Inspection deleted successfully');
    }

    public function updateStatus(Invoice $invoice)
    {
        try {
            if ($invoice) {
                $this->invoiceService->updateStatus($invoice, 'Paid');
            }

            return response()->json([
                'message' => 'Status succesvol bijgewerkt'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Er is een fout opgetreden',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeService($id)
    {
        try {
            // Find the specific InspectionItem by ID
            $inspectionItem = \App\Domain\Inspections\Models\InspectionItem::findOrFail($id);

            // Security check - ensure user has access to this inspection
            if (!$inspectionItem->inspection->userHasAccess()) {
                return response()->json(['message' => 'Unauthorized access'], 403);
            }

            // Log the deletion for audit purposes
            Log::info('InspectionItem deleted', [
                'inspection_item_id' => $inspectionItem->id,
                'inspection_id' => $inspectionItem->inspection_id,
                'type_id' => $inspectionItem->type_id,
                'user_id' => auth()->id(),
            ]);

            // Delete the specific InspectionItem
            $inspectionItem->delete();

            return response()->json(['message' => 'Service removed successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('InspectionItem not found', [
                'inspection_item_id' => $id,
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Service not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error removing service', [
                'inspection_item_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);
            return response()->json(['message' => 'Error removing service'], 500);
        }
    }
}
