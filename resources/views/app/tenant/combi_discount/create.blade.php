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
                            <li class="breadcrumb-item"><a href="{{ route('combi_discount.index') }}" class="text-decoration-none">Combi-kortingen</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Nieuw</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

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

    <form action="{{ route('combi_discount.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-price-tag-3-line me-2"></i>Nieuwe Combi-korting
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="service_ids" class="form-label">Diensten (meerdere selecteren mogelijk)</label>
                                <select name="service_ids[]" id="service_ids" class="form-select" multiple required>
                                    @foreach(App\Models\Service::all() as $service)
                                        <option value="{{ $service->id }}">{{ $service->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Gebruik Ctrl (Windows) of Cmd (Mac) voor meerdere selectie.</small>
                                @error('service_ids') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="discount_type" class="form-label">Kortingstype</label>
                                <select name="discount_type" id="discount_type" class="form-select" required>
                                    <option value="percentage">Procentueel (%)</option>
                                    <option value="fixed">Vast bedrag (€)</option>
                                </select>
                                @error('discount_type') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="discount_value" class="form-label">Kortingswaarde</label>
                                <input type="number" step="0.01" min="0" name="discount_value" id="discount_value" class="form-control" required>
                                @error('discount_value') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="active" id="active" checked>
                                    <label class="form-check-label" for="active">Actief</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Opslaan</button>
                <a href="{{ route('combi_discount.index') }}" class="btn btn-secondary">Annuleren</a>
            </div>
        </div>
    </form>
</div>
@endsection 