<?php

namespace App\Http\Controllers;

use App\Mail\StatusChangedMail;
use App\Models\Keuringen;
use App\Models\KeuringenDetail;
use App\Models\Type;
use App\Models\Status;
use App\Models\File;
use App\Models\Client;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AjaxController;
use App\Mail\InvoiceMail;
use App\Models\Provincie;

use App\Http\Requests\KeuringFormRequest;
use App\Mail\SendAllFiles;
use Illuminate\Support\Facades\Log;
use App\Services\InspectionService;

class KeuringenController extends Controller
{

    private $path;
    private $inspectionService;

    public function __construct(InspectionService $inspectionService)
    {
        $this->path =  'app.tenant.keuringen.';
        $this->inspectionService = $inspectionService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Keuringen $keuringen)
    {
        $data = $keuringen->getAll();
        return view($this->path . 'list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = $this->inspectionService->getTypesForUser(auth()->user());
        $formData = $this->inspectionService->getFormData();

        return view($this->path . 'add', array_merge(
            ['types' => $types],
            $formData
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KeuringFormRequest $request, Keuringen $keuringen)
    {
        try {

            $request->validate([
                'files.*' => 'mimes:pdf,jpg,jpeg,png|max:10000',
                'invoice' => 'mimes:pdf,jpg,jpeg,png|max:10000',
                'cart' => 'required'
            ], [
                'files.*' => 'De factuur moet een bestand zijn van het type: pdf, jpg, jpeg, png.',
                'invoice' => 'De factuur moet een bestand zijn van het type: pdf, jpg, jpeg, png.',
                'cart' => 'Dienst(en) verplicht'
            ]);

            $data = $request->data;
            $data['tenant_id'] = getTenantId();

            if (!$request->has('data.client_id')) {
                if (auth()->user()->type === 'client') {
                    $data['client_id'] = auth()->user()->client->id;
                    $data['tenant_id'] = auth()->user()->client->tenant_id;
                }
            }

            if ($request->has('data.type'))
                $data['type'] = json_encode($data['type']);

            $saved_keuring = $keuringen->create($data);
            if (!$saved_keuring)
                return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);

            // Upload invoice
            if ($request->has('invoice')) {
                $saved_image = $saved_keuring->saveFiles('Invoice', $request->file('invoice'));
                if (!$saved_image) {
                    return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);
                }
            }

            // Atama işlemi
            $date = $request->date ?? 0;
            $eventResponse = $saved_keuring->updateOrCreateEvent($request->employee_id, $date);

            if ($eventResponse['status'] == 'error') {
                return back()->withErrors(['msg' => $eventResponse['msg']]);
            }

            if ($request->hasFile('docs')) {
                $newFile = new File();
                $docs = $request->file('docs');
                foreach ($docs as $doc) {
                    $originalName =  pathinfo($doc->getClientOriginalName(), PATHINFO_FILENAME);
                    $docname = Str::slug($originalName, '-');
                    $extension = $doc->getClientOriginalExtension();
                    $docname = $docname . '.' . $extension;

                    $savedDoc = $newFile->create([
                        'object_id' => $saved_keuring->id,
                        'table' => 'KeuringenDocs',
                        'path' => '/img/files/',
                        'name' => $docname,
                        'type' => $extension
                    ]);

                    if (!$savedDoc)
                        return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);

                    $imageStore = $doc->storeas('files/', $docname, ['disk' => 'public_folder']);
                    if (!$imageStore)
                        return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);
                }
            }

            if ($request->has('cart')) {
                $carts = $request->cart;
                foreach ($carts as $cart) {
                    $cart['keuringen_id'] = $saved_keuring->id;
                    $saved_keuring_details = KeuringenDetail::create($cart);
                    if (!$saved_keuring_details) {
                        return back()->withErrors(['msg' => 'Keuring detail kon niet worden opgeslagen']);
                    }
                }
            }

            // Creating note
            if ($request->has('note')) {
                $saved_note = $saved_keuring->notes()->create($request->note);
                if ($saved_note) {
                    return back()->withErrors(['msg' => 'Kan geen communicatie tot stand brengen']);
                }
            }

            if ($request->has('to-detail')) {
                return redirect()
                    ->route('keuringen.edit', ['keuringen' => $saved_keuring->id])
                    ->with('msg', __('validation.custom.record_added_success'));
            }
            return back()->with('msg', __('validation.custom.record_added_success'));
        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
            if ($errors->isNotEmpty()) {
                return redirect()->back()->withErrors($errors);
            }
            return redirect()->back()->withErrors(['msg' => __('validation.custom.default_error_message')]);
        } catch (\PDOException $e) {
            if (str_contains($e->getMessage(), "foreign key constraint fails")) {
                return back()->withErrors(['msg' => 'Selecteer een geldige klant.']);
            }
            return back()->withErrors(['msg' => 'Er is een fout opgetreden bij het opslaan van de gegevens.']);
        } catch (\Throwable $e) {
            return back()->withErrors(['msg' => 'Er is een fout opgetreden bij het opslaan van de gegevens.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Keuringen $keuringen)
    {
        $types = Type::where('tenant_id', getTenantId())->get();
        $statuses = Status::where('tenant_id', getTenantId())->get();
        return view($this->path . 'show', compact('keuringen', 'types', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Keuringen $keuringen)
    {

        $types = Type::where('tenant_id', getTenantId())
            ->where('category_id', 0)
            ->get();
        $statuses = Status::where('tenant_id', getTenantId())->get();
        $clients = Client::where('tenant_id', getTenantId())->get();
        $employes = Employe::where('tenant_id', getTenantId())->get();
        $provincies = Provincie::all();

        $keuringen->load('details.category');

        return view($this->path . 'edit', compact('keuringen', 'types', 'statuses', 'clients', 'employes', 'provincies'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(KeuringFormRequest $request, Keuringen $keuringen)
    {
        try {
            $data = $request->data;
            $previousStatus = $keuringen->status;
            $this->updateKeuring($keuringen, $data);
            $this->sendStatusChangedMail($keuringen, $previousStatus);
            // $this->updateEmploye($keuringen, $data);
            $this->uploadInvoice($request, $keuringen);
            $this->updateOrCreateEvent($keuringen, $data['employee_id'], $data['inspection_date']);
            $this->uploadFiles($request->file('files'), 'Keuringen', $keuringen->id);
            $this->uploadFiles($request->file('docs'), 'KeuringenDocs', $keuringen->id);
            $this->processCart($request->cart, $keuringen);

            return redirect()->back()->with('msg', __('validation.custom.record_added_success'));
        } catch (\Throwable $th) {
            Log::error('KeuringenController@update: ' . $th->getMessage());
            return redirect()->back()->withErrors(['msg' => __('validation.custom.default_error_message')]);
        }
    }

    private function updateKeuring(Keuringen $keuringen, array $data)
    {

        if (!empty($data['province_id'])) {
            $provincie = Provincie::find($data['province_id']);
            if ($provincie) {
                $data['province'] = $provincie->name;
            }
        }
        $keuringen->update($data);
    }

    private function uploadInvoice(Request $request, Keuringen $keuringen)
    {
        if ($request->hasFile('invoice')) {
            $saved_image = $keuringen->saveFiles('Invoice', $request->file('invoice'));
            if (!$saved_image) {
                throw new \Exception(__('validation.custom.record_added_error'));
            }
        }
    }

    private function updateOrCreateEvent(Keuringen $keuringen, $employeeId, $date)
    {
        $eventResponse = $keuringen->updateOrCreateEvent($employeeId, $date);
        if ($eventResponse['status'] == 'error') {
            throw new \Exception($eventResponse['msg']);
        }
    }

    private function sendStatusChangedMail(Keuringen $keuringen, $previousStatus)
    {
        try {
            $client = Client::find($keuringen->client_id);

            if ($keuringen->wasChanged('status')) {
                $previousStatus = Status::find($previousStatus)->name;
                $currentStatus = Status::find($keuringen->status)->name;
                createComment('Keuringen', 'Update', $keuringen->id, 'De bon is nu van de status <b>' . $previousStatus . '</b> naar <b>' . $currentStatus . '</b> gewijzigd');
                if ($client) {
                    if (!Mail::to($client->email)->send(new StatusChangedMail($keuringen, $keuringen->getStatus->name))) {
                        return back()->withErrors(['msg', 'Email couldt not send']);
                    }
                }
            }
        } catch (\Throwable $th) {
            return back()->withErrors(['msg', 'Bir hata oluştu']);
        }
    }

    private function updateEmploye(Keuringen $keuringen, $data)
    {
        $client = Client::find($keuringen->client_id);
        $previousStatus = Status::find($keuringen->status)->name;
        $currentStatus = Status::find($data['status'])->name;

        createComment('Keuringen', 'Update', $keuringen->id, 'De bon is nu van de status <b>' . $previousStatus . '</b> naar <b>' . $currentStatus . '</b> gewijzigd');
        if ($client) {
            Mail::to($client->email)->send(new StatusChangedMail($keuringen, $keuringen->getStatus->name));
        }
    }

    private function uploadFiles($files, $table, $objectId)
    {
        if ($files) {
            $newFile = new File();
            foreach ($files as $file) {
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = Str::slug($originalName, '-');
                $extension = $file->getClientOriginalExtension();
                $filename = $filename . '.' . $extension;

                $savedFile = $newFile->create([
                    'object_id' => $objectId,
                    'table' => $table,
                    'path' => '/img/files/',
                    'name' => $filename,
                    'type' => $extension
                ]);

                if (!$savedFile) {
                    throw new \Exception(__('validation.custom.record_added_error'));
                }

                $imageStore = $file->storeAs('files/', $filename, ['disk' => 'public_folder']);
                if (!$imageStore) {
                    throw new \Exception(__('validation.custom.record_added_error'));
                }
            }
        }
    }

    private function processCart($cart, Keuringen $keuringen)
    {
        if ($cart) {
            foreach ($cart as $item) {
                $item['keuringen_id'] = $keuringen->id;
                $saved_keuring_details = KeuringenDetail::updateOrCreate(['keuringen_id' => $keuringen->id, 'type_id' => $item['type_id']], $item);
                if (!$saved_keuring_details) {
                    throw new \Exception('Keuring detail didn\'t save');
                }
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keuringen $keuringen)
    {
        $keuringen->delete();
        return back()->with('msg', __('validation.custom.record_added_success'));
    }

    public function sendInvoice($id)
    {
        $keuringen = Keuringen::findOrFail($id);
        $receiver = $keuringen->client_id > 0 ? $keuringen->client->email : $keuringen->email;
        if (!Mail::to($receiver)->send(new InvoiceMail($keuringen))) {
            return back()->withErrors(['msg' => __('validation.custom.email_error')]);
        }

        $keuringen->update(['payment_status' => 1]);
        return back()->with('msg', __('validation.custom.email_success'));
    }
    public function updateStatus($id)
    {
        $keuringen = Keuringen::findOrFail($id);
        $files = $keuringen->files->where('table', 'Keuringen')->where('email_send_status', 0);
        if ($files->count() > 0) {
            $receiver = $keuringen->client ? $keuringen->client->email : $keuringen->email;
            if (!Mail::to($receiver)->send(new SendAllFiles($files, $keuringen->file_id))) {
                return back()->withErrors(['msg' => __('validation.custom.email_error')]);
            }
            foreach ($files as $file) {
                $file->update(['email_send_status' => 1]);
            }
        }
        $keuringen->update(['paid' => 1]);

        createComment('Keuringen', 'Update', $keuringen->id, 'Keuring is overgegaan naar de betaalde status');
        createComment('Keuringen', 'Update', $keuringen->id, 'Alle bestanden verzonden');

        return back()->with('msg', __('validation.custom.status_update'));
    }
}
