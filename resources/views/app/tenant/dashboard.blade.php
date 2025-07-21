@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <div class="card">
        <div class="card-body">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 gy-3">
                @if (is_array($statuses) && count($statuses) > 0 )
                    @foreach ($statuses as $status_key => $status)
                    <div class="col mb-3 mb-md-0">
                        <div class="card" style="background:{{ $status['color'] }}">
                            <div class="card-header">{{ ucwords($status_key) }}</div>
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <h1>{{ $status['count'] }}</h1>
                                <i class="fs-1 ri-file-text-line"></i>
                            </div>
                        </div>
                    </div>            
                    @endforeach
                @endif
            
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
            <div class="card">
                <div class="card-header">Totaal aantal klanten</div>
                <div class="card-body d-flex align-items-center justify-content-between">
                    <h1>{{ $totalClients }}</h1>
                    <i class="fs-1 ri-group-line"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
            <div class="card">
                <div class="card-header">Totaal aantal keuringen</div>
                <div class="card-body d-flex align-items-center justify-content-between">
                    <h1>{{ $totalInspections }}</h1>
                    <i class="fs-1 ri-file-text-line"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
            <div class="card">
                <div class="card-header">Totaal inkomsten</div>
                <div class="card-body d-flex align-items-center justify-content-between">
                    <h1>{{ $totalIncome }}</h1>
                    <i class="fs-1 ri-money-euro-circle-line"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Totaal keuringen</div>
                <div class="card-body">
                    <!-- Chart Buttons -->
                    <div class="chart-buttons"></div>
                    <!-- Chart Buttons End -->
                    <canvas class="my-charts" id="mychart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Totaal klanten keuringen</div>
                <div class="card-body">
                    <canvas class="my-charts" id="mychart-2"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Laatste toegevoegde klanten</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Naam / Voornaam</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Datum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($clients->count() > 0)
                                @foreach ($clients as $client)
                                    <tr>
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->phone }}</td>
                                        <td>{{ $client->address }}</td>
                                        <td>{{ date('Y-m-d', strtotime($client->created_at)) }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">Laatste keuringsaanvraagen</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Naam</th>
                                <th>Adres</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($inspections->count() > 0)
                                @foreach ($inspections as $inspection)
                                    <tr>
                                        <td>
                                            <a href="{{ route($guard . '.inspections.show', ['inspection' => $inspection->id]) }}">
                                                {{ $inspection->name }}
                                            </a>
                                        </td>
                                        <td>{{ $inspection->street . ' ' . $inspection->postal_code }}</td>
                                        <td>{{ $inspection->email }}</td>
                                        <td>
                                            @if ($inspection->getStatus)
                                                <span class="badge fw-normal"
                                                    style="background: {{ $keuringen->getStatus->color }}">{{ $keuringen->getStatus->name }}</span>
                                            @else
                                                -
                                            @endif
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
