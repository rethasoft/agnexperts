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
use App\Domain\Invoices\Models\Invoice;
use App\Domain\Events\Actions\CreateEventAction;
use App\Domain\Events\DTOs\EventData;
use App\Domain\Events\Models\Event;
use App\Domain\Events\Actions\UpdateEventAction;
use App\Domain\Status\Services\StatusService;
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
        private readonly StatusService $statusService
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

        
        return view('app.tenant.inspections.index', compact('inspections', 'statuses'));
    }

    public function create()
    {
        $clients = Client::all();
        $employees = Employee::all();
        $types = Type::all();
        $provinces = Province::all();

        return view('app.tenant.inspections.create', compact('clients', 'employees', 'types', 'provinces'));
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
        try {
            $inspection = $this->createInspectionAction->execute(
                InspectionData::fromRequest($request)
            );

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
                ->route('tenant.inspections.show', $inspection)
                ->with('msg', 'Inspectie succesvol aangemaakt');
            // } catch (ConflictingEventException $e) {
            //     return redirect()
            //         ->route('tenant.inspections.create')
            //         ->withInput($request->all())
            //         ->withErrors($e->getMessage());
            // }
        } catch (\Exception $e) {
            Log::error('InspectionController@store: ' . $e->getMessage());
            return redirect()
                ->route('tenant.inspections.create')
                ->withInput($request->all())
                ->withErrors('An unexpected error occurred.');
        }
    }

    public function show(Inspection $inspection)
    {
        abort_if(!$inspection->userHasAccess(), 403, 'U heeft geen toegang tot deze inspectie.');

        $inspection->load(['client', 'employee', 'items', 'province']);
        $statuses = Status::all();
        return view('app.tenant.inspections.show', compact('inspection', 'statuses'));
    }

    public function edit(Inspection $inspection)
    {
        abort_if(!$inspection->userHasAccess(), 403, 'U heeft geen toegang tot deze inspectie.');
        $inspection->load(['client', 'employee', 'items', 'items.category']);
        $clients = Client::all();
        $provinces = Province::all();
        $types = Type::all();
        return view('app.tenant.inspections.edit', compact('inspection', 'clients', 'provinces', 'types'));
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
                ->route('tenant.inspections.edit', $inspection)
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
        return redirect()->route('tenant.inspections.index')
            ->with('success', 'Inspection deleted successfully');
    }

    public function updateStatus(Invoice $invoice)
    {
        try {

            dd($invoice);
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
}
