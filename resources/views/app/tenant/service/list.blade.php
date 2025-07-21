@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Diensten') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
                    @endif
                    @if ($msg = Session::get('success'))
                        <div class="alert alert-success mt-3">{{ $msg }}</div>
                    @endif
                    <div class="button-bar mb-2">
                        <a href="{{ route('service.create') }}" class="btn btn-success"><i class="ri-add-line"></i></a>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="7%">Image</th>
                                <th>Naam</th>
                                <th>Korte Beschrijving</th>
                                <th>Datum</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($services->count() > 0)
                                @foreach ($services as $service)
                                <tr>
                                    <td><img src="{{ asset($service->image) }}" alt="{{ $service->name }}" height="50" class="border rounded"></td>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ Str::limit($service->short_description, 50) }}</td>
                                    <td>{{ date('Y-m-d', strtotime($service->created_at)) }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('service.edit', ['service' => $service->id]) }}" class="d-inline-block" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"><i class="ri-pencil-line"></i></button>
                                        </form>
                                        <form action="{{ route('service.destroy', ['service' => $service->id]) }}" class="d-inline-block" method="POST"
                                            onsubmit="if(!confirm('Do you want to delete this record')){return false;}"
                                            >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="6">
                                    <div class="alert alert-danger mb-0">{{ __('validation.custom.no_data') }}</div>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
