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

        <form action="{{ route($guard . '.keuringen.store') }}" enctype="multipart/form-data" method="POST" id="cart-form">
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
                                        <select name="data[client_id]" class="form-select">
                                            <option value="0">Selecteren</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name . ' ' . $client->surname }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endauth
                                <div class="col-md-6">
                                    <label class="form-label">Naam</label>
                                    <input type="text" name="data[name]" class="form-control" required
                                        value="{{ old('data.name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">E-mailadres</label>
                                    <input type="email" name="data[email]" class="form-control" required
                                        value="{{ old('data.email') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Telefoonnummer</label>
                                    <input type="text" name="data[phone]" class="form-control" required
                                        value="{{ old('data.phone') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Provincie</label>
                                    <select name="data[province_id]" class="form-select">
                                        <option value="0">Selecteren</option>
                                        @foreach ($provincies as $provincie)
                                            <option value="{{ $provincie->id }}"
                                                {{ old('data.province_id') == $provincie->id ? 'selected' : '' }}>
                                                {{ $provincie->name }}
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
                                    <input type="text" name="data[street]" class="form-control" required
                                        value="{{ old('data.street') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Nummer</label>
                                    <input type="number" name="data[number]" class="form-control" required
                                        value="{{ old('data.number') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Bus</label>
                                    <input type="number" name="data[box]" class="form-control"
                                        value="{{ old('data.box') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Postcode</label>
                                    <input type="text" name="data[postal_code]" class="form-control" required
                                        value="{{ old('data.postal_code') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Gemeente</label>
                                    <input type="text" name="data[city]" class="form-control" required
                                        value="{{ old('data.city') }}">
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
                                <div class="col-md-8">
                                    <label class="form-label">Type keuring</label>
                                    <select name="data[type][]" id="types" class="form-select add-to-cart-select">
                                        @if (is_object($types))
                                            <option value="0">Selecteren</option>
                                            @foreach ($types as $type)
                                                @if ($type->subTypes->count() > 0)
                                                    <optgroup label="{{ $type->name }}">
                                                        @foreach ($type->subTypes as $subType)
                                                            @php
                                                                $subType->category_name = $type->short_name;
                                                            @endphp
                                                            <option value="{{ $subType->id }}"
                                                                data-product="{{ $subType }}">
                                                                {{ $subType->name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @else
                                                    <option value="{{ $type->id }}"
                                                        data-product="{{ $type }}">
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
                                            <tr>
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
@endsection
