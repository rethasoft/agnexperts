<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Spatie\Permission\Models\Role;

class EmployeController extends Controller
{
    private $path;

    public function __construct()
    {
        $this->path = 'app.tenant.employee.';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Employee::where('tenant_id', getTenantId())->get();
        return view($this->path . 'list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $roles = Role::all();
        return view($this->path . 'add', compact('roles'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'data.email' => 'email|unique:employes,email',
                'places' => 'array',
            ], [
                "data.email.unique" => __('validation.custom.unique_email')
            ]);

            $data = $request->data;
            $data['tenant_id'] = getTenantId();

            if ($request->has('places')) {
                $data['places'] = json_encode($request->places);
            }


            $employe = new Employee();
            $saved = $employe->create($data);
            if (!$saved)
                return back()->withErrors(['msg' => __('validatProvincieion.custom.record_added_error')]);

            $role = Role::find($request->data['role_id']);
            $saved->assignRole($role);
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
        } catch (QueryException $e) {
            return back()->withErrors(['msg' => $e->getMessage()]);
        } catch (\Throwable $th) {
            return back()->withErrors(['msg' => 'Er is een fout opgetreden. Neem contact op met de bevoegde persoon.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employe)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $places = $employee->places != '' ? json_decode($employee->places) : '';
        $roles = Role::all();
        $metrics = $employee->getPerformanceMetrics();

        return view($this->path . 'edit', compact('employee', 'places', 'roles', 'metrics'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        try {

            $data = $request->data;
            $data['tenant_id'] = getTenantId();

            if ($data['password'] == null)
                unset($data['password']);

            // if ($request->has('places')) {
            //     $data['places'] = json_encode($request->places);
            // }

            if (!$employee->update($data))
                return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);

            return back()->with('msg', __('validation.custom.record_added_success'));
        } catch (ValidationException $e) {
            dd($e);

            // Handle validation errors
            $errors = $e->validator->errors();

            // Check if there are any errors
            if ($errors->isNotEmpty()) {
                return redirect()->back()->withErrors($errors);
            }

            // If no specific errors, redirect with a default error message from language files
            return redirect()->back()->withErrors(['msg' => __('validation.custom.default_error_message')]);
        } catch (\Throwable $e) {
            dd($e);
            return redirect()->back()->withErrors(['msg' => __('validation.custom.default_error_message')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        if (!$employee)
            return back()->withErrors(['msg' => __('validation.custom.record_not_found')]);

        // Delete the employee
        $employee->delete();
        return back()->with('msg', __('validation.custom.record_added_success'));
    }
}
