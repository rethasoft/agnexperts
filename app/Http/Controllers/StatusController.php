<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    private $path;

    public function __construct()
    {
        $this->path = 'app.tenant.status.';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Status::where('tenant_id', getTenantId())->get();
        return view($this->path . 'list', compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view($this->path . 'add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->data;
            $data['tenant_id'] = getTenantId();
            $data['is_default'] = isset($data['is_default']) ? (bool)$data['is_default'] : false;

            // Eğer bu kayıt default seçildiyse, diğerlerini kaldır
            if (!empty($data['is_default'])) {
                Status::where('tenant_id', getTenantId())
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }

            $status = new Status();
            if (!$status->create($data))
                return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);

            return back()->with('msg', __('validation.custom.record_added_success'));
        } catch (ValidationException $e) {
            // Handle validation errors
            $errors = $e->validator->errors();

            // Check if there are any errors
            if ($errors->isNotEmpty()) {
                return redirect()->back()->withErrors($errors);
            }

            // If no specific errors, redirect with a default error message from language files
            return redirect()->back()->withErrors(['msg' => __('validation.custom.default_error_message')]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Status $status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Status $status)
    {
        return view($this->path . 'edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Status $status)
    {
        try {
            $data = $request->data;
            $data['tenant_id'] = getTenantId();
            $data['is_default'] = isset($data['is_default']) ? (bool)$data['is_default'] : false;

            // Bu kayıt default yapılmak isteniyorsa diğerlerinden kaldır
            if (!empty($data['is_default'])) {
                Status::where('tenant_id', getTenantId())
                    ->where('id', '!=', $status->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
            if (!$status->update($data))
                return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);

            return back()->with('msg', __('validation.custom.record_added_success'));
        } catch (ValidationException $e) {
            // Handle validation errors
            $errors = $e->validator->errors();

            // Check if there are any errors
            if ($errors->isNotEmpty()) {
                return redirect()->back()->withErrors($errors);
            }

            // If no specific errors, redirect with a default error message from language files
            return redirect()->back()->withErrors(['msg' => __('validation.custom.default_error_message')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Status $status)
    {
        $status->delete();
        return back()->with('msg', __('validation.custom.record_added_success'));
    }
}
