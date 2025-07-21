@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Instellingen</li>
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
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Bedrijfsnaam</th>
                                <th>Naam voornaam</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->count() > 0)
                                @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->company }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->phone }}</td>
                                    <td>
                                        <form action="{{ route('setting.edit', ['setting' => $item->id]) }}" class="d-inline-block" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"><i class="ri-pencil-line"></i></button>
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
