@extends('app.layouts.admin')
@section('title', 'Combi İndirimleri')
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Combi İndirimleri</h2>
        <a href="{{ route('admin.combi_discounts.create') }}" class="btn btn-primary">Yeni Combi Ekle</a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Hizmetler</th>
                <th>İndirim Tipi</th>
                <th>İndirim Değeri</th>
                <th>Aktif</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($combis as $combi)
                <tr>
                    <td>{{ $combi->id }}</td>
                    <td>
                        @foreach($combi->service_ids as $sid)
                            <span class="badge bg-info">{{ $sid }}</span>
                        @endforeach
                    </td>
                    <td>{{ $combi->discount_type }}</td>
                    <td>{{ $combi->discount_value }}</td>
                    <td>{{ $combi->active ? 'Evet' : 'Hayır' }}</td>
                    <td>
                        <a href="{{ route('admin.combi_discounts.edit', $combi->id) }}" class="btn btn-sm btn-warning">Düzenle</a>
                        <form action="{{ route('admin.combi_discounts.destroy', $combi->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 