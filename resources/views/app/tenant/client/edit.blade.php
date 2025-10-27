@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Client</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bewerk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
    @endif
    @if ($msg = Session::get('msg'))
        <div class="alert alert-success mt-3">{{ $msg }}</div>
    @endif
    <div class="row mt-3">
        <div class="col-lg-12">
            <!-- Tab Navigation -->
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">
                        <i class="ri-user-line me-2"></i>Algemeen
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pricelist-tab" data-bs-toggle="tab" data-bs-target="#pricelist" type="button" role="tab" aria-controls="pricelist" aria-selected="false">
                        <i class="ri-price-tag-3-line me-2"></i>Prijslijst
                    </button>
                </li>
            </ul>
            
            <!-- Tab Content -->
            <div class="tab-content" id="myTabContent">


                <!-- Price List Tab -->
                <div class="tab-pane fade" id="pricelist" role="tabpanel" aria-labelledby="pricelist-tab">
                    <div class="card mt-3">
                        <div class="card-body">
                            <form action="{{ route('client.pricelist.update', $client) }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    @if ($types->count() > 0)
                                        @foreach ($types as $type)
                                            <div class="col-12 col-lg-6">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong>{{ $type->name }}</strong>
                                                            @if (!empty($type->short_name))
                                                                <span class="text-muted">({{ $type->short_name }})</span>
                                                            @endif
                                                        </div>
                                                        @if ($type->subTypes->count() > 0)
                                                            <span class="badge bg-secondary">{{ $type->subTypes->count() }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="card-body">
                                                        @if ($type->subTypes->count() > 0)
                                                            <div class="table-responsive">
                                                                <table class="table align-middle mb-0">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="width:50%">Dienst</th>
                                                                            <th style="width:25%">Prijs</th>
                                                                            <th style="width:25%">Nieuwe Prijs</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($type->subTypes as $subType)
                                                                            <tr>
                                                                                <td scope="row">{{ $subType->name }}</td>
                                                                                <td>{{ $subType->price }}</td>
                                                                                <td>
                                                                                    <input type="hidden" name="pricelist[{{ $type->id * $subType->id }}][type_id]" value="{{ $subType->id }}">
                                                                                    <div class="input-group" style="max-width:180px;">
                                                                                        <span class="input-group-text">€</span>
                                                                                        <input type="number" step="0.01" min="0" class="form-control text-center" name="pricelist[{{ $type->id * $subType->id }}][price]" value="{{ isset($prices[$subType->id]) ? $prices[$subType->id]['price'] : '' }}" placeholder="0.00">
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @else
                                                            <div class="row g-2 align-items-center">
                                                                <div class="col">
                                                                    <div class="text-muted small">Dienst</div>
                                                                    <div>{{ $type->name }}</div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <div class="text-muted small">Prijs</div>
                                                                    <div class="text-nowrap">{{ $type->price }}</div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <div class="text-muted small">Nieuwe Prijs</div>
                                                                    <input type="hidden" name="pricelist[{{ $type->id }}][type_id]" value="{{ $type->id }}">
                                                                    <div class="input-group" style="max-width:180px;">
                                                                        <span class="input-group-text">€</span>
                                                                        <input type="number" step="0.01" min="0" class="form-control text-center" name="pricelist[{{ $type->id }}][price]" value="{{ isset($prices[$type->id]) ? $prices[$type->id]['price'] : '' }}" placeholder="0.00">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="text-end mt-3">
                                    <button class="btn btn-success" type="submit">Opslaan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- General Tab -->
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div class="card mt-3">
                        <div class="card-body">
                            <form action="{{ route('client.update', ['client' => $client->id]) }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')
                                <div class="row g-4">
                                    @include('app.tenant.client._form', ['client' => $client])
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection

