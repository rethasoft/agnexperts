<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    private $path;

    public function __construct()
    {
        $this->path = 'app.tenant.setting.';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Setting::where('tenant_id', getTenantId())->get();
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

            $request->validate([
                'logo' => 'mimes:png,jpg, jpeg'
            ]);

            $data = $request->data;
            $data['tenant_id'] = getTenantId();

            dd($data);
            $setting = new Setting();

            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $originalName =  pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = Str::slug($originalName, '-');
                $imageStore = $image->storeas('files/', $filename, ['disk' => 'public_folder']);
                if (!$imageStore)
                    return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);
                $data['logo'] = $filename;
            }

            if (!$setting->create($data))
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
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        return view($this->path . 'edit', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        try {

            $request->validate([
                'logo' => 'mimes:png,jpg, jpeg'
            ]);

            $data = $request->data;
            $data['tenant_id'] = getTenantId();

            $tenant = $data;
            if ($request->password != null){
                    $tenant['password'] = $request->password;
            }

            $newTenant = Tenant::find(getTenantId());
            $newTenant->update($tenant);

            if ($request->hasFile('logo')) {
                $image = $request->file('logo');
                $originalName =  pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $filename = Str::slug($originalName, '-');
                $imageStore = $image->storeas('files/', $filename, ['disk' => 'public_folder']);
                if (!$imageStore)
                    return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);
                $data['logo'] = $filename;
            }

            if (!$setting->update($data))
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
    public function destroy(Setting $setting)
    {
        $setting->delete();
        return back()->with('msg', __('validation.custom.record_added_success'));
    }
    public function destroyLogo($id)
    {
        $setting = Setting::find($id);
        if($setting){
            $setting->update(['logo' => null]);
            return back()->with('msg', __('validation.custom.record_added_success'));
        } else{
            return back()->with('msg', __('validation.custom.record_added_success'));
        }
    }
}
