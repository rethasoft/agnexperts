@extends('app.layouts.app')

@section('content')
    <div class="row mb-4">
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

    <div class="row g-4">
        <!-- Statistics Cards -->
        <div class="col-12">
            <!-- Statistics Cards -->
            <div class="row g-4">
                <!-- Totaal Keuringen -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary-subtle p-2 me-3">
                                    <i class="ri-file-list-3-line fs-4 text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Totaal Keuringen</h6>
                                    <h3 class="mb-0">{{ $statistics['total_keuringen'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deze Maand -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-success-subtle p-2 me-3">
                                    <i class="ri-calendar-check-line fs-4 text-success"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Deze Maand</h6>
                                    <h3 class="mb-0">{{ $statistics['this_month_count'] }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vorige Maand -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-info-subtle p-2 me-3">
                                    <i class="ri-calendar-line fs-4 text-info"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Vorige Maand</h6>
                                    <h3 class="mb-0">{{ $statistics['last_month_count'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dit Jaar -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-warning-subtle p-2 me-3">
                                    <i class="ri-calendar-2-line fs-4 text-warning"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1">Dit Jaar</h6>
                                    <h3 class="mb-0">{{ $statistics['this_year_count'] ?? 0 }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <!-- Status Verdeling -->
        <div class="col-12">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary-subtle me-3">
                            <i class="ri-pie-chart-line fs-4 text-primary"></i>
                        </div>
                        <h3 class="card-title h5 mb-0">Status Verdeling</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach ($statistics['status_counts'] as $status => $count)
                            @php
                                $statusColor = App\Models\Status::where('name', $status)->first()->color ?? '#f8f9fa';
                            @endphp
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card h-100 border-0 shadow-sm status-filter hover-lift"
                                    data-status="{{ $status }}"
                                    style="cursor: pointer; border-left: 4px solid {{ $statusColor }} !important">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-2 text-muted">{{ ucfirst($status) }}</h6>
                                                <h3 class="mb-0 display-6">{{ $count }}</h3>
                                            </div>
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 50px; height: 50px; background-color: {{ $statusColor }}15">
                                                <i class="ri-file-list-3-line fs-4" style="color: {{ $statusColor }}"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recente Keuringen -->
        <div class="col-12">
            <div class="card border-0 shadow-sm hover-lift">
                <div class="card-header bg-transparent border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-opacity-10 me-3">
                                <i class="ri-time-line fs-4 text-warning"></i>
                            </div>
                            <h4 class="card-title h5 mb-0">Recente Inspecties</h4>
                        </div>
                        <a href="{{ route('client.inspections.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-line me-1"></i>
                            Nieuwe Keuring
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="keuringen-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Dossier nr</th>
                                    <th>Naam</th>
                                    <th>Email</th>
                                    <th>Telefoon</th>
                                    <th>Adres</th>
                                    <th>Status</th>
                                    <th>Datum</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statistics['recent_keuringen'] as $inspection)
                                    <tr>
                                        <td>{{ $inspection->file_id ?? '-' }}</td>
                                        <td>{{ $inspection->name ?? '-' }}</td>
                                        <td>{{ $inspection->email ?? '-' }}</td>
                                        <td>{{ $inspection->phone ?? '-' }}</td>
                                        <td>{{ $inspection->street ?? '-' }}</td>
                                        <td>
                                            @if ($inspection->status)
                                                <span class="badge fw-normal"
                                                    style="background: {{ $inspection->status->color }}">
                                                    <i class="ri-checkbox-circle-line me-1"></i>
                                                    {{ $inspection->status->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary fw-normal">
                                                    <i class="ri-error-warning-line me-1"></i>
                                                    Geen status
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $inspection->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('client.inspections.show', $inspection->id) }}"
                                                    class="btn btn-outline-primary" title="Details bekijken">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('client.inspections.edit', $inspection->id) }}"
                                                    class="btn btn-outline-info" title="Bewerken">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            let table = $('#keuringen-table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/nl-NL.json'
                },
                order: [
                    [0, 'desc']
                ]
            });

            // Status kartlarına tıklama olayı
            $('.status-filter').on('click', function() {
                let status = $(this).data('status');

                // Eğer zaten aktif olan karta tıklandıysa
                if ($(this).hasClass('active-filter')) {
                    // Aktif filtreyi kaldır
                    $(this).removeClass('active-filter');
                    table.column(5).search('').draw();
                } else {
                    // Tüm status kartlarından active class'ını kaldır
                    $('.status-filter').removeClass('active-filter');
                    // Seçilen karta active class'ı ekle
                    $(this).addClass('active-filter');
                    // DataTable'da arama yap
                    table.column(5).search(status).draw();
                }
            });

            // Monthly Trend Chart
            const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($statistics['monthly_labels'] ?? []) !!},
                    datasets: [{
                        label: 'Aantal Keuringen',
                        data: {!! json_encode($statistics['monthly_counts'] ?? []) !!},
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
