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
                                <li class="breadcrumb-item active" aria-current="page">Nieuw</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>


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

        <form action="{{ route($guard . '.inspections.store') }}" enctype="multipart/form-data" method="POST"
            id="cart-form">
            @csrf
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
                                    <div class="col-md-12">
                                        <label class="form-label">Klanten</label>
                                        <select id="client-select" name="client_id" class="form-select">
                                            <option value="0" disabled selected>Selecteren</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name . ' ' . $client->surname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endauth
                                @auth('client')
                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <i class="ri-information-line me-2"></i>
                                            Deze inspectie wordt automatisch aan uw account gekoppeld. Vul de gegevens van uw klant in.
                                        </div>
                                    </div>
                                @endauth
                                <div class="col-md-6">
                                    <label class="form-label required">Naam</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">E-mailadres</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Telefoonnummer</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Provincie</label>
                                    <select name="province_id" class="form-select" required>
                                        <option value="">Selecteren</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{ $province->id }}"
                                                {{ old('province_id') == $province->id ? 'selected' : '' }}>
                                                {{ $province->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Bedrijfsnaam (optioneel)</label>
                                    <input type="text" name="company_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">BTW-nummer (optioneel)</label>
                                    <input type="text" name="vat_number" class="form-control">
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
                                    <label class="form-label required">Straat</label>
                                    <input type="text" name="street" class="form-control" value="{{ old('street') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Nummer</label>
                                    <input type="number" name="number" class="form-control" value="{{ old('number') }}"
                                    >
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Bus</label>
                                    <input type="number" name="box" class="form-control" value="{{ old('box') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label required">Postcode</label>
                                    <input type="text" name="postal_code" class="form-control"
                                        value="{{ old('postal_code') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label required">Gemeente</label>
                                    <input type="text" name="city" class="form-control"
                                        value="{{ old('city') }}">
                                </div>

                                <!-- Billing Address Toggle -->
                                <div class="col-12 mt-4">
                                    <div class="form-check">
                                        <input type="hidden" name="has_billing_address" value="0">
                                        <input class="form-check-input" type="checkbox" id="differentBillingAddress"
                                            name="has_billing_address" value="1" data-bs-toggle="collapse"
                                            data-bs-target="#billingAddressSection" checked>
                                        <label class="form-check-label" for="differentBillingAddress">
                                            Factuuradres verschilt van bovenstaand adres
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Address Section -->
                    <div class="collapse" id="billingAddressSection">
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
                                            value="{{ old('billing_street') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Nummer</label>
                                        <input type="number" name="billing_number" class="form-control"
                                            value="{{ old('billing_number') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Bus</label>
                                        <input type="number" name="billing_box" class="form-control"
                                            value="{{ old('billing_box') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Postcode</label>
                                        <input type="text" name="billing_postal_code" class="form-control"
                                            value="{{ old('billing_postal_code') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Gemeente</label>
                                        <input type="text" name="billing_city" class="form-control"
                                            value="{{ old('billing_city') }}">
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
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label required mb-2">Type keuring</label>
                                    <select name="type" id="types" class="form-select add-to-cart-select">
                                        @if (is_object($types))
                                            <option value="0">Selecteren</option>
                                            @foreach ($types as $type)
                                                @if ($type->subTypes->count() > 0)
                                                    <optgroup label="{{ $type->name }}">
                                                        @foreach ($type->subTypes as $subType)
                                                            @php
                                                                $subType->category_name = $type->short_name;
                                                            @endphp
                                                            <option value="{{ $subType->id }}" data-product='@json($subType)'>
                                                               {{ $subType->name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @else
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

                            <div id="cart" class="d-none mt-4">
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
                                        <tbody></tbody>
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
                        </div>
                    </div>
                </div>



                <!-- Right Column -->
                @auth('tenant')
                    <x-tenant::keuringen-sidebar />
                @endauth
                @auth('client')
                    <x-client::keuringen-sidebar />
                @endauth

            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    // Billing address checkbox functionality
    document.addEventListener('DOMContentLoaded', function() {
        const billingCheckbox = document.getElementById('differentBillingAddress');
        const billingInputs = [
            'billing_street',
            'billing_number', 
            'billing_postal_code',
            'billing_city'
        ];

        function checkBillingFieldsFilled() {
            // Check if any billing field has a value
            const hasBillingData = billingInputs.some(inputName => {
                const input = document.querySelector(`input[name="${inputName}"]`);
                return input && input.value.trim() !== '';
            });
            
            return hasBillingData;
        }

        function toggleBillingRequired() {
            const isChecked = billingCheckbox.checked;
            
            billingInputs.forEach(inputName => {
                const input = document.querySelector(`input[name="${inputName}"]`);
                const label = document.querySelector(`label[for="${inputName}"]`) || 
                             input?.closest('.col-md-6, .col-md-2, .col-12')?.querySelector('label');
                
                if (input) {
                    if (isChecked) {
                        input.setAttribute('required', 'required');
                        input.classList.add('required-field');
                        if (label) {
                            label.classList.add('required');
                            if (!label.innerHTML.includes('*')) {
                                label.innerHTML = label.innerHTML + ' <span class="text-danger">*</span>';
                            }
                        }
                    } else {
                        input.removeAttribute('required');
                        input.classList.remove('required-field');
                        if (label) {
                            label.classList.remove('required');
                            label.innerHTML = label.innerHTML.replace(' <span class="text-danger">*</span>', '');
                        }
                    }
                }
            });
        }

        // Initial state - don't change checkbox based on data, just set up required fields
        // toggleBillingRequired();

        // Listen for checkbox changes
        billingCheckbox.addEventListener('change', function() {
            // Update hidden input value
            const hiddenInput = document.querySelector('input[name="has_billing_address"][type="hidden"]');
            if (hiddenInput) {
                hiddenInput.value = billingCheckbox.checked ? '1' : '0';
            }
            
            // Update section visibility based on checkbox
            const billingSection = document.getElementById('billingAddressSection');
            if (billingSection) {
                if (billingCheckbox.checked) {
                    billingSection.classList.add('show');
                } else {
                    billingSection.classList.remove('show');
                }
            }
            
            // Update required fields
            toggleBillingRequired();
        });
        
        // Listen for input changes to auto-update checkbox only when user types
        billingInputs.forEach(inputName => {
            const input = document.querySelector(`input[name="${inputName}"]`);
            if (input) {
                input.addEventListener('input', function() {
                    // Only update checkbox if user is actively typing
                    const hasData = checkBillingFieldsFilled();
                    if (hasData && !billingCheckbox.checked) {
                        billingCheckbox.checked = true;
                        const hiddenInput = document.querySelector('input[name="has_billing_address"][type="hidden"]');
                        if (hiddenInput) {
                            hiddenInput.value = '1';
                        }
                    }
                    toggleBillingRequired();
                });
            }
        });
    });
    </script>
    @endpush
@endsection

