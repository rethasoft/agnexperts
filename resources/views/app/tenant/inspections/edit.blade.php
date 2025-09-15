@extends('app.layouts.app')
@section('content')
    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">Dashboard</li>
                                <li class="breadcrumb-item"><a href="{{ route($guard . '.inspections.index') }}"
                                        class="text-decoration-none">Keuringen</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Bewerken #{{ $inspection->file_id }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breadcrumb -->
        {{-- <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('keuringen.index') }}" class="text-decoration-none">Keuringen</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Bewerken #{{ $inspection->file_id }}</li>
            </ol>
        </nav> --}}

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($msg = Session::get('msg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $msg }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route($guard . '.inspections.update', $inspection->id) }}" enctype="multipart/form-data"
            method="POST" id="cart-form">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Client Info Section -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="ri-user-line me-2"></i>Klantgegevens
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @auth('tenant')
                                    @if ($inspection->client_id > 0)
                                        <div class="col-md-12">
                                            <label class="form-label">Klanten</label>
                                            <select name="client_id" class="form-select" required>
                                                <option value="0" disabled selected>Selecteren</option>
                                                @foreach ($clients as $client)
                                                    <option value="{{ $client->id }}"
                                                        {{ $inspection->client_id == $client->id ? 'selected' : '' }}>
                                                        {{ $client->name . ' ' . $client->surname }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                @endauth
                                <div class="col-md-6">
                                    <label class="form-label">Naam</label>
                                    <input type="text" name="name" class="form-control" required
                                        value="{{ $inspection->name }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">E-mailadres</label>
                                    <input type="email" name="email" class="form-control" required
                                        value="{{ $inspection->email }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Telefoonnummer</label>
                                    <input type="text" name="phone" class="form-control" required
                                        value="{{ $inspection->phone }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Provincie</label>
                                    <select name="province_id" class="form-select" required>
                                        <option value="">Selecteren</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}"
                                                {{ $inspection->province_id == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Info Section -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="ri-map-pin-line me-2"></i>Adresgegevens
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Straat</label>
                                    <input type="text" name="street" class="form-control" required
                                        value="{{ $inspection->street }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Nummer</label>
                                    <input type="number" name="number" class="form-control" required
                                        value="{{ $inspection->number }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Bus</label>
                                    <input type="number" name="box" class="form-control"
                                        value="{{ $inspection->box }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Postcode</label>
                                    <input type="text" name="postal_code" class="form-control" required
                                        value="{{ $inspection->postal_code }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label required">Gemeente</label>
                                    <input type="text" name="city" class="form-control" required
                                        value="{{ $inspection->city }}">
                                </div>

                                <!-- Billing Address Toggle -->
                                <div class="col-12 mt-4">
                                    <div class="form-check">
                                        <input type="hidden" name="has_billing_address" value="0">
                                        <input class="form-check-input" type="checkbox" id="differentBillingAddress"
                                            name="has_billing_address" value="1" data-bs-toggle="collapse"
                                            data-bs-target="#billingAddressSection"
                                            {{ $inspection->has_billing_address ? 'checked' : '' }}>
                                        <label class="form-check-label" for="differentBillingAddress">
                                            Factuuradres verschilt van bovenstaand adres
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address Section -->
                    <div class="collapse {{ isset($inspection->billing_street) ? 'show' : '' }}"
                        id="billingAddressSection">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="card-title mb-0">
                                    <i class="ri-bill-line me-2"></i>Factuuradres
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Straat</label>
                                        <input type="text" name="billing_street" class="form-control"
                                            value="{{ $inspection->billing_street ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Nummer</label>
                                        <input type="number" name="billing_number" class="form-control"
                                            value="{{ $inspection->billing_number ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Bus</label>
                                        <input type="number" name="billing_box" class="form-control"
                                            value="{{ $inspection->billing_box ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Postcode</label>
                                        <input type="text" name="billing_postal_code" class="form-control"
                                            value="{{ $inspection->billing_postal_code ?? '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Gemeente</label>
                                        <input type="text" name="billing_city" class="form-control"
                                            value="{{ $inspection->billing_city ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services Section -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="ri-list-unordered me-2"></i>Diensten
                            </h5>
                        </div>
                        <div class="card-body">
                            @auth('tenant')
                                <div class="row g-3">
                                    <div class="col-auto">
                                        <label class="form-label">Type keuring</label>
                                        <select name="type[]" id="types" class="form-select add-to-cart-select" multiple>
                                            @if (is_object($types))
                                                <option value="0" disabled>Selecteren</option>
                                                @foreach ($types as $type)
                                                    @if ($type->subTypes->count() > 0)
                                                        <optgroup label="{{ $type->name }}">
                                                            @foreach ($type->subTypes as $subType)
                                                                @php
                                                                    $subType->category_name = $type->short_name;
                                                                    $isSelected = in_array(
                                                                        $subType->id,
                                                                        json_decode($inspection->type ?? '[]'),
                                                                    );
                                                                @endphp
                                                                <option value="{{ $subType->id }}" data-product='@json($subType)'>
                                                                    {{ $type->name . ' > ' . $subType->name }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @else
                                                        @php
                                                            $isSelected = in_array(
                                                                $type->id,
                                                                json_decode($inspection->type ?? '[]'),
                                                            );
                                                        @endphp
                                                        <option value="{{ $type->id }}" data-product='@json($type)'>
                                                            {{ $type->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @else
                                                {!! $types !!}
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div id="cart" class="mt-4">
                                    <div class="table-responsive">
                                        <table id="cart-table" class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Id</th>
                                                    <th>Dienst</th>
                                                    <th>Aantal</th>
                                                    <th>Prijs</th>
                                                    <th class="text-end">Totaal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- JavaScript ile doldurulacak -->
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr id="cart-total-row">
                                                    <td colspan="6" class="text-end fw-bold" id="cart-total">
                                                        Totaal: €0.00
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                @endif


                                @auth('client')
                                    <div id="cart" class="mt-4">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Dienst</th>
                                                        <th>Aantal</th>
                                                        <th>Prijs</th>
                                                        <th class="text-end">Totaal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($inspection->details as $detail)
                                                        <tr>
                                                            <td>{{ $detail->name }}</td>
                                                            <td>{{ $detail->quantity }}</td>
                                                            <td>{{ $detail->price }}</td>
                                                            <td class="text-end">{{ $detail->total }}</td>
                                                        </tr>
                                                    @endforeach
                                                    <!-- JavaScript ile doldurulacak -->
                                                </tbody>
                                                <tfoot class="table-light">
                                                    <tr>
                                                        <td colspan="6" class="text-end fw-bold" id="cart-total">
                                                            Totaal: €{{ $inspection->details->sum('total') }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                @endauth

                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    @auth('tenant')
                        <x-tenant::keuringen-sidebar :inspection="$inspection" />
                    @endauth
                    @auth('client')
                        <x-client::keuringen-sidebar :inspection="$inspection" />
                    @endauth
                    {{-- <div class="col-lg-4">
                    <!-- File Info Section -->
                    <div class="card shadow-sm mb-4 sticky-top" style="top: 1rem;">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title mb-0">
                                <i class="ri-folder-line me-2"></i>Dossiergegevens
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Dossiernummer</label>
                                    <input type="text" name="data[file_id]" class="form-control"
                                        value="{{ $inspection->file_id }}" readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Medewerker</label>
                                    <select name="data[employee_id]" class="form-select">
                                        <option value="">Selecteren</option>
                                        @foreach ($employes as $employe)
                                            <option value="{{ $employe->id }}"
                                                {{ $inspection->employee_id == $employe->id ? 'selected' : '' }}>
                                                {{ $employe->name . ' ' . $employe->surname }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Datum plaatsbezoek</label>
                                    <input type="date" name="data[inspection_date]" class="form-control"
                                        value="{{ $inspection->inspection_date }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Status</label>
                                    @if (auth()->user()->type === 'tenant')
                                        <select name="data[status]" class="form-select" required>
                                            <option value="">Selecteren</option>
                                            @foreach ($statuses as $status)
                                                <option value="{{ $status->id }}"
                                                    {{ $inspection->status == $status->id ? 'selected' : '' }}>
                                                    {{ $status->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Factuur uploaden</label>
                                    <input type="file" name="invoice" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Certificaat</label>
                                    @if (auth()->user()->type === 'tenant')
                                        <input type="file" name="files[]" class="form-control" multiple>
                                    @endif
                                </div>
                                @if (auth()->user()->type === 'client')
                                    <div class="col-12">
                                        <label class="form-label">Files</label>
                                        <input type="file" name="docs[]" class="form-control" multiple>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-success btn-sm px-3 py-2 d-flex align-items-center" type="submit">
                                    <i class="ri-save-line me-2"></i>
                                    <span>Opslaan</span>
                                </button>
                                <div class="vr"></div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                </div>
            </form>

            <!-- Files Section -->
            <div class="row">
                <div class="col-lg-8">
                    <x-shared.file-list :inspection="$inspection" :show-upload="true" />
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                // PHP'den alınan veriyi JSON formatında JavaScript'e aktar
                const existingCartData = @json($inspection->items);
                
                // Mevcut combi discount verilerini JavaScript'e aktar
                @php
                    $combiDiscountData = null;
                    if ($inspection->combiDiscount) {
                        $combiDiscountData = [
                            'has_combi' => true,
                            'combi_discount_id' => $inspection->combiDiscount->id,
                            'combi_discount_type' => $inspection->combiDiscount->discount_type,
                            'combi_discount_value' => $inspection->combiDiscount->discount_value,
                            'combi_discount_amount' => $inspection->combiDiscount->discount_amount
                        ];
                    }
                @endphp
                const existingCombiDiscount = @json($combiDiscountData);

                console.log(existingCombiDiscount);
            </script>
            <script>
$(document).ready(function() {
    $('#types').select2({
        placeholder: 'Selecteer diensten',
        allowClear: true,
        width: '100%',
        language: 'nl',
        closeOnSelect: false
    });
    
    // Mevcut cart verilerini yükle
    if (typeof existingCartData !== 'undefined' && existingCartData.length > 0) {
        cart.initializeFromExistingData(existingCartData);
    }
});
            </script>
        @endpush
    @endsection
