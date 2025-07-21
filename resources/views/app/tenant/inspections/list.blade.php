@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item active" aria-current="page">Keuringen</li>
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
                    {{-- @can('create_keuringen') --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Keuringen Overzicht</h4>
                        <a href="{{ route($guard . '.keuringen.create') }}" class="btn btn-primary d-flex align-items-center">
                            <i class="ri-add-circle-line me-1"></i>
                            <span>Nieuwe Keuring</span>
                        </a>
                    </div>
                    {{-- @endcan --}}
                    <table class="table table-striped data-table">
                        <thead>
                            <tr>
                                <th>Project nr</th>
                                <th>Naam</th>
                                <th>Adres</th>
                                <th>Email</th>
                                <th>Telefoon</th>
                                @auth('tenant')
                                    <th>Prijs</th>
                                @endauth
                                <th>Status</th>
                                @if(auth('tenant')->check() || auth('client')->check())
                                <th>Factuur</th>
                                @endif
                                <th>Datum</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->count() > 0)
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $item->file_id }}</td>
                                        <td><a
                                                href="{{ route($guard . '.inspections.show', $item->id) }}">{{ $item->name }}</a>
                                        </td>
                                        <td>{{ $item->street . ' ' . $item->postal_code }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ $item->phone }}</td>
                                        @auth('tenant')
                                            <td>{{ $item->details ? '€' . $item->details->sum('total') : 0 }}</td>
                                        @endauth
                                        <td>
                                            @if ($item->getStatus)
                                                <span class="badge fw-normal"
                                                    style="background: {{ $item->getStatus->color }}">{{ $item->getStatus->name }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if(auth('tenant')->check() || auth('client')->check())
                                        <td>
                                            @if ($item->paid == 1)
                                                <span class="badge bg-success">Betaald</span>
                                            @elseif ($item->payment_status == 1)
                                                <span class="badge bg-info">Verstuurd</span>
                                            @else
                                                <span class="badge bg-warning">Draft</span>
                                            @endif
                                        </td>
                                        @endif
                                        <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                                        <td>
                                            <div class="d-flex gap-2 align-items-center">
                                                @auth('tenant')
                                                    <a href="{{ route($guard . '.invoice.update-status', ['id' => $item->id]) }}"
                                                        onclick="return confirm('Weet u zeker dat u de status van deze keuring wilt bijwerken?')"
                                                        class="btn btn-sm {{ $item->paid == 1 ? 'btn-success disabled' : 'btn-warning' }}
                                                        {{ !$item->invoice ? 'disabled' : '' }}"><i
                                                            class="ri-check-line"></i></a>
                                                    <a href="{{ $item->invoice ? $item->invoice->path . $item->invoice->name : '' }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-danger {{ !$item->invoice ? 'disabled' : '' }}"><i
                                                            class="ri-file-pdf-line"></i></a>
                                                @endauth
                                                @auth('client')

                                                <form action="{{ route($guard . '.inspections.show', ['inspection' => $item->id]) }}" method="GET">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary"><i class="ri-eye-line"></i></button>
                                                </form>
                                                @endauth
                                                <form action="{{ route($guard . '.inspections.edit', ['inspection' => $item->id]) }}"
                                                    method="GET">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-info"><i
                                                            class="ri-pencil-line"></i></button>
                                                </form>
                                                <form action="{{ route($guard . '.inspections.destroy', ['inspection' => $item->id]) }}"
                                                    method="POST"
                                                    onsubmit="if(!confirm('Do you want to delete this record')){return false;}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"><i
                                                            class="ri-delete-bin-line"></i></button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
