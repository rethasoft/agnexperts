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
                            <li class="breadcrumb-item active" aria-current="page">Combi-kortingen</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ri-price-tag-3-line me-2"></i>Combi-kortingen
                    </h5>
                    <a href="{{ route('combi_discount.create') }}" class="btn btn-primary">Nieuwe Combi</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Diensten</th>
                                    <th>Kortingstype</th>
                                    <th>Kortingswaarde</th>
                                    <th>Actief</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($combis as $combi)
                                    <tr>
                                        <td>{{ $combi->id }}</td>
                                        <td>
                                            @foreach(App\Models\Service::whereIn('id', $combi->service_ids)->get() as $service)
                                                <span class="badge bg-info">{{ $service->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $combi->discount_type == 'percentage' ? 'Procentueel' : 'Vast bedrag' }}</td>
                                        <td>{{ $combi->discount_value }}</td>
                                        <td>{{ $combi->active ? 'Ja' : 'Nee' }}</td>
                                        <td>
                                            <a href="{{ route('combi_discount.edit', $combi->id) }}" class="btn btn-sm btn-warning">Bewerken</a>
                                            <form action="{{ route('combi_discount.destroy', $combi->id) }}" method="POST" style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je wilt verwijderen?')">Verwijderen</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Geen combi-kortingen gevonden.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 