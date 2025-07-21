@extends('app.layouts.admin')
@section('title', 'Yeni Combi İndirimi Ekle')
@section('content')
<div class="container py-4">
    <h2>Yeni Combi İndirimi Ekle</h2>
    <form action="{{ route('admin.combi_discounts.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="service_ids" class="form-label">Hizmetler (Birden fazla seçebilirsiniz)</label>
            <select name="service_ids[]" id="service_ids" class="form-select" multiple required>
                @foreach(App\Models\Service::all() as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
            <small class="text-muted">Ctrl (Windows) veya Cmd (Mac) ile çoklu seçim yapabilirsiniz.</small>
            @error('service_ids') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="discount_type" class="form-label">İndirim Tipi</label>
            <select name="discount_type" id="discount_type" class="form-select" required>
                <option value="percentage">Yüzde (%)</option>
                <option value="fixed">Sabit Tutar (€)</option>
            </select>
            @error('discount_type') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="discount_value" class="form-label">İndirim Değeri</label>
            <input type="number" step="0.01" min="0" name="discount_value" id="discount_value" class="form-control" required>
            @error('discount_value') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="active" id="active" checked>
            <label class="form-check-label" for="active">Aktif</label>
        </div>
        <button type="submit" class="btn btn-success">Kaydet</button>
        <a href="{{ route('admin.combi_discounts.index') }}" class="btn btn-secondary">Vazgeç</a>
    </form>
</div>
@endsection 