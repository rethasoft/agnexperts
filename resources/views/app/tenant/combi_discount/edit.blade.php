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
                            <li class="breadcrumb-item active" aria-current="page">Bewerken</li>
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

    <form action="{{ route('combi_discount.update', $combi->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="card-title mb-0">
                            <i class="ri-price-tag-3-line me-2"></i>Combi-korting bewerken
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="service_ids" class="form-label">Diensten (meerdere selecteren mogelijk)</label>
                                <select name="service_ids[]" id="service_ids" class="form-select" multiple required>
                                    @php
                                        $types = App\Models\Type::all();
                                        $mainTypes = $types->where('category_id', 0);
                                    @endphp
                                    @foreach($mainTypes as $mainType)
                                        @php
                                            $subTypes = $types->where('category_id', $mainType->id);
                                        @endphp
                                        @if($subTypes->count() > 0)
                                            <optgroup label="{{ $mainType->name }}">
                                                @foreach($subTypes as $subType)
                                                    @php
                                                        $category = $mainType;
                                                    @endphp
                                                    <option value="{{ $subType->id }}" {{ in_array($subType->id, $combi->service_ids ?? []) ? 'selected' : '' }}>
                                                        {{ $category->name . ' > ' . $subType->name }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @else
                                            <option value="{{ $mainType->id }}" {{ in_array($mainType->id, $combi->service_ids ?? []) ? 'selected' : '' }}>{{ $mainType->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <small class="text-muted">Gebruik Ctrl (Windows) of Cmd (Mac) voor meerdere selectie.</small>
                                @error('service_ids') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="discount_type" class="form-label">Kortingstype</label>
                                <select name="discount_type" id="discount_type" class="form-select" required>
                                    <option value="percentage" {{ $combi->discount_type == 'percentage' ? 'selected' : '' }}>Procentueel (%)</option>
                                    <option value="fixed" {{ $combi->discount_type == 'fixed' ? 'selected' : '' }}>Vast bedrag (€)</option>
                                </select>
                                @error('discount_type') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="discount_value" class="form-label">Kortingswaarde</label>
                                <input type="number" step="0.01" min="0" name="discount_value" id="discount_value" class="form-control" value="{{ $combi->discount_value }}" required>
                                @error('discount_value') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="active" id="active" {{ $combi->active ? 'checked' : '' }}>
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
@push('scripts')
<script>
$(document).ready(function() {
    $('#service_ids').select2({
        placeholder: 'Selecteer diensten',
        width: '100%',
        language: 'nl',
        closeOnSelect: false
    });
});
</script>
@endpush 