<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class TypeController extends Controller
{

    private $path;

    public function __construct()
    {
        $this->path = 'app.tenant.types.';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Type::where('category_id', 0)->get();
        return view($this->path . 'list', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = Type::where('category_id', 0)->get();
        return view($this->path . 'add', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'data.price' => 'numeric'
            ], [ 'data.price' => 'Het veld prijs moet een getal zijn.']);

            if ($request->has('data.extra_price') && $request->data['extra_price'] == 1 ) {
                $request->validate([
                    'data.extra_price' => 'numeric'
                ], 
                [ 'data.extra_price' => 'Het veld extra prijs moet een getal zijn.']);
            }

            $data = $request->data;
            $data['tenant_id'] = getTenantId();
            
            // Handle regions checkbox data
            $regions = [];
            if ($request->has('regions')) {
                $regions = $request->regions;
            }
            $data['regions'] = $regions;

            $type = new Type();
            if (!$type->create($data))
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
    public function show(Type $type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $dienst)
    {
        $types = Type::where('category_id', 0)->get();
        return view($this->path . 'edit', compact('dienst', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Type $dienst)
    {
        try {
            $data = $request->data;
            $data['tenant_id'] = getTenantId();
            
            // Handle regions checkbox data
            $regions = [];
            if ($request->has('regions')) {
                $regions = $request->regions;
            }
            $data['regions'] = $regions;

            if (!$dienst->update($data))
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
    public function destroy(Type $dienst)
    {
        $dienst->delete();
        return back()->with('msg', __('validation.custom.record_added_success'));
    }
}
