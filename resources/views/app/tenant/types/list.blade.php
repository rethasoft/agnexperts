@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Dienst</li>
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
                        <a href="{{ route('dienst.create') }}" class="btn btn-success"><i class="ri-add-line"></i></a>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th>Korte Naam</th>
                                <th>Prijs</th>
                                <th>Extra Prijs</th>
                                <th>Regio's</th>
                                <th>Datum</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->count() > 0)
                                @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->short_name }}</td>
                                    <td>{{ $item->formatted_price }}</td>
                                    <td>{{ $item->formatted_extra_price }}</td>
                                    <td>
                                        @if($item->regions)
                                            @foreach($item->regions as $region)
                                                <span class="badge bg-primary me-1">{{ ucfirst($region) }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Geen regio's</span>
                                        @endif
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <form action="{{ route('dienst.edit', ['dienst' => $item->id]) }}" class="d-inline-block" method="GET">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success"><i class="ri-pencil-line"></i></button>
                                        </form>
                                        <form action="{{ route('dienst.destroy', ['dienst' => $item->id]) }}" class="d-inline-block" method="POST"
                                            onsubmit="if(!confirm('Do you want to delete this record')){return false;}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- Sub Types --}}
                                @if ($item->subTypes->count() > 0)
                                    @foreach ($item->subTypes as $type)
                                    <tr>
                                        <td> &nbsp;&nbsp;&nbsp;&nbsp;-- {{ $type->name }}</td>
                                        <td>{{ $type->short_name }}</td>
                                        <td>{{ $type->formatted_price }}</td>
                                        <td>{{ $type->formatted_extra_price }}</td>
                                        <td>
                                            @if($type->regions)
                                                @foreach($type->regions as $region)
                                                    <span class="badge bg-primary me-1">{{ ucfirst($region) }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Geen regio's</span>
                                            @endif
                                        </td>
                                        <td>{{ date('Y-m-d', strtotime($type->created_at)) }}</td>
                                        <td>
                                            <form action="{{ route('dienst.edit', ['dienst' => $type->id]) }}" class="d-inline-block" method="GET">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success"><i class="ri-pencil-line"></i></button>
                                            </form>
                                            <form action="{{ route('dienst.destroy', ['dienst' => $type->id]) }}" class="d-inline-block" method="POST"
                                                onsubmit="if(!confirm('Do you want to delete this record')){return false;}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                                {{-- Sub Types End --}}

                                @endforeach
                            @else
                            <tr>
                                <td colspan="8">
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
