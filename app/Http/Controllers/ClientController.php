<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\PriceList;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                'data.email' => 'required|email|unique:clients,email|unique:users,email'
            ], [
                'data.email.required' => __('validation.required', ['attribute' => 'email']),
                'data.email.email' => __('validation.email', ['attribute' => 'email']),
                'data.email.unique' => __('validation.custom.unique_email')
            ]);

            $data = $request->data;
            $data['tenant_id'] = getTenantId();

            // Address array'ini string'e çevir
            if (isset($data['address']) && is_array($data['address'])) {
                $addressParts = [];
                if (!empty($data['address']['street'])) $addressParts[] = $data['address']['street'];
                if (!empty($data['address']['house_number'])) $addressParts[] = $data['address']['house_number'];
                if (!empty($data['address']['house_number_addition'])) $addressParts[] = $data['address']['house_number_addition'];
                if (!empty($data['address']['postal_code'])) $addressParts[] = $data['address']['postal_code'];
                if (!empty($data['address']['city'])) $addressParts[] = $data['address']['city'];
                
                $data['address'] = implode(' ', $addressParts);
            }

            DB::transaction(function () use (&$data) {
                $password = $data['password'] ?? Str::random(10);

                $user = User::create([
                    'name' => trim(($data['name'] ?? '') . ' ' . ($data['surname'] ?? '')),
                    'email' => $data['email'],
                    'password' => $password,
                    'type' => 'client',
                ]);

                $data['user_id'] = $user->id;
                $data['password'] = $password; // Client modelinde cast mevcut

                Client::create($data);
            });

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

            // Address bilgilerini ayrı ayrı sakla ve full address oluştur
            if (isset($data['address']) && is_array($data['address'])) {
                // Ayrı alanları çıkar
                $data['street'] = $data['address']['street'] ?? null;
                $data['house_number'] = $data['address']['house_number'] ?? null;
                $data['house_number_addition'] = $data['address']['house_number_addition'] ?? null;
                $data['postal_code'] = $data['address']['postal_code'] ?? null;
                $data['city'] = $data['address']['city'] ?? null;
                
                // Full address oluştur (geriye dönük uyumluluk için)
                $addressParts = [];
                if (!empty($data['street'])) $addressParts[] = $data['street'];
                if (!empty($data['house_number'])) $addressParts[] = $data['house_number'];
                if (!empty($data['house_number_addition'])) $addressParts[] = $data['house_number_addition'];
                if (!empty($data['postal_code'])) $addressParts[] = $data['postal_code'];
                if (!empty($data['city'])) $addressParts[] = $data['city'];
                
                $data['address'] = implode(' ', $addressParts);
                
                // Address array'ini kaldır
                unset($data['address']);
            }

            // Billing address'i JSON olarak sakla
            if (isset($data['billing_address']) && is_array($data['billing_address'])) {
                // Boş değerleri temizle
                $billingAddress = array_filter($data['billing_address'], function($value) {
                    return !empty($value);
                });
                $data['billing_address'] = !empty($billingAddress) ? $billingAddress : null;
            }

            if (($data['password'] ?? null) == null)
                unset($data['password']);

            DB::transaction(function () use (&$data, $client) {
                $user = $client->user ?: User::where('email', $client->email)->first();

                // Email değişiyorsa benzersizlik kontrolünü yap
                if (!empty($data['email'])) {
                    request()->validate([
                        'data.email' => 'required|email|unique:clients,email,' . $client->id . '|unique:users,email,' . ($user->id ?? 'NULL'),
                    ]);
                }

                if (!$user) {
                    $user = User::create([
                        'name' => trim(($data['name'] ?? '') . ' ' . ($data['surname'] ?? '')),
                        'email' => $data['email'],
                        'password' => $data['password'] ?? Str::random(10),
                        'type' => 'client',
                    ]);
                    $data['user_id'] = $user->id;
                } else {
                    $user->name = trim(($data['name'] ?? '') . ' ' . ($data['surname'] ?? ''));
                    if (!empty($data['email'])) $user->email = $data['email'];
                    if (!empty($data['password'])) $user->password = $data['password'];
                    $user->type = 'client';
                    $user->save();
                }

                if (!$client->update($data)) {
                    throw new \RuntimeException('Client update failed');
                }
            });

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

    public function updatePriceList(Request $request, Client $client)
    {
        $request->validate([
            'pricelist' => 'required|array'
        ]);

        if ($request->has('pricelist')) {
            $pricelists = $request->pricelist;
            foreach ($pricelists as $pricelist) {
                if (($pricelist['price'] ?? '') === '' || floatval($pricelist['price']) == 0) {
                    PriceList::where(['client_id' => $client->id, 'type_id' => intval($pricelist['type_id'])])->delete();
                } else {
                    $payload = $pricelist;
                    $payload['tenant_id'] = getTenantId();
                    $payload['client_id'] = $client->id;
                    $saved = PriceList::updateOrCreate(
                        ['client_id' => $client->id, 'type_id' => intval($payload['type_id'])],
                        $payload
                    );
                    if (!$saved) {
                        return back()->withErrors(['msg' => __('validation.custom.record_added_error')]);
                    }
                }
            }
        }

        return back()->with('msg', __('validation.custom.record_added_success'));
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
