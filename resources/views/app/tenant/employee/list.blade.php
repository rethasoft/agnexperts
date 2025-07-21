@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Medewerker</li>
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
                    @if ($msg = Session::get('msg'))
                        <div class="alert alert-success mt-3">{{ $msg }}</div>
                    @endif
                    <div class="button-bar mb-2">
                        <a href="{{ route('employee.create') }}" class="btn btn-success"><i class="ri-add-line"></i></a>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="15%">Naam / Voornaam</th>
                                <th>Rol</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Datum</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->count() > 0)
                                @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->name . ' ' . $item->surname }}</td>
                                    <td>{{ $item->role ? $item->role->name : '-' }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>{{ $item->address }}</td>
                                    <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('employee.edit', ['employee' => $item->id]) }}" class="d-inline-block" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"><i class="ri-pencil-line"></i></button>
                                        </form>
                                        <form action="{{ route('employee.destroy', ['employee' => $item->id]) }}" class="d-inline-block" method="POST"
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
                                <td colspan="7">
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
