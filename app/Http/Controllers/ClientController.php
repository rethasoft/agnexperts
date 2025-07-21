<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PriceList;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    private $path;

    public function __construct()
    {
        $this->path = 'app.tenant.client.';
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Client::where('tenant_id', getTenantId())->get();
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
                'data.email' => 'email|unique:clients,email'
            ], [
                "data.email.unique" => __('validation.custom.unique_email')
            ]);

            $data = $request->data;
            $data['tenant_id'] = getTenantId();

            $client = new Client();
            if (!$client->create($data))
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
    public function show(Client $client)
    {
        return view($this->path . 'show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $types = Type::where('tenant_id', getTenantId())->where('category_id', 0)->get();
        $price_list = PriceList::where('client_id', $client->id)->get();
        $prices = [];
        if ($price_list->count() > 0) {
            foreach ($price_list as $price) {
                $prices[$price->type_id] = $price;
            }
        }
        return view($this->path . 'edit', compact('client', 'types', 'prices'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        try {

            $data = $request->data;
            $data['tenant_id'] = getTenantId();

            if ($data['password'] == null)
                unset($data['password']);

            if (!$client->update($data))
                return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);


            if ($request->has('pricelist')) {
                $pricelists = $request->pricelist;
                foreach ($pricelists as $pricelist) {
                    if ($pricelist['price'] == 0) {
                        PriceList::where(['client_id' => $client->id, 'type_id' => intval($pricelist['type_id'])])->delete();
                    } else {
                        if ($pricelist['price'] != '') {
                            $pricelist['tenant_id'] = getTenantId();
                            $pricelist['client_id'] = $client->id;
                            $saved_price_list = PriceList::updateOrCreate(['client_id' => $client->id, 'type_id' => $pricelist['type_id']], $pricelist);
                            if (!$saved_price_list) {
                                return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);
                            }
                        }
                    }
                }
            }
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
    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('msg', __('validation.custom.record_added_success'));
    }
}
