<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Prestatie Overzicht</h5>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-secondary btn-sm">Deze Maand</button>
                <button type="button" class="btn btn-outline-secondary btn-sm">Dit Jaar</button>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="row g-3 mb-4">
            @foreach ($metrics['inspections_by_status']['current'] as $status => $count)
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">{{ ucfirst($status) }}</h6>
                            <div class="d-flex align-items-baseline gap-2">
                                <h3 class="mb-0">{{ $count }}</h3>
                                @php
                                    $previousCount = $metrics['inspections_by_status']['previous'][$status] ?? 0;
                                    $diff = $count - $previousCount;
                                @endphp
                                <small class="{{ $diff >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $diff >= 0 ? '+' : '' }}{{ $diff }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Distribution Chart -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body" style="height: 400px"> <!-- Fixed height -->
                        <h6 class="card-title mb-3">Status Verdeling</h6>
                        <canvas id="statusDistributionChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body" style="height: 300px"> <!-- Same height for consistency -->
                        <h6 class="card-title mb-3">Maandelijkse Trend</h6>
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Status Distribution Chart
        new Chart(document.getElementById('statusDistributionChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($metrics['status_distribution']->toArray())) !!},
                datasets: [{
                    data: {!! json_encode(array_values($metrics['status_distribution']->pluck('percentage')->toArray())) !!},
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Monthly Trend Chart
        new Chart(document.getElementById('monthlyTrendChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($metrics['monthly_trend']->toArray())) !!},
                datasets: [{
                    label: 'Totaal Inspecties',
                    data: {!! json_encode(array_values($metrics['monthly_trend']->map->sum('total')->toArray())) !!},
                    borderColor: '#4e73df',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush

<!-- Key Performance Indicators -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Totaal Gewerkte Uren</h6>
                <div class="d-flex align-items-center gap-3">
                    <div class="display-4 fw-bold text-primary">168</div>
                    <small class="text-muted">uren</small>
                </div>
                <div class="text-success small mt-2">
                    <i class="ri-arrow-up-line"></i> 12% vs vorige maand
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Voltooide Inspecties</h6>
                <div class="d-flex align-items-center gap-3">
                    <div class="display-4 fw-bold text-success">42</div>
                    <small class="text-muted">inspecties</small>
                </div>
                <div class="text-danger small mt-2">
                    <i class="ri-arrow-down-line"></i> 5% vs vorige maand
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Taak Voltooiingspercentage</h6>
                <div class="d-flex align-items-center gap-3">
                    <div class="display-4 fw-bold text-warning">92%</div>
                </div>
                <div class="text-success small mt-2">
                    <i class="ri-arrow-up-line"></i> 8% vs vorige maand
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Klanttevredenheid</h6>
                <div class="d-flex align-items-center gap-3">
                    <div class="display-4 fw-bold text-info">4.5</div>
                    <small class="text-muted">/ 5.0</small>
                </div>
                <div class="text-success small mt-2">
                    <i class="ri-arrow-up-line"></i> 0.3 vs vorige maand
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Charts Section -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Prestatietrend (Laatste 6 Maanden)</h6>
                <div style="height: 450px;">
                    <canvas id="performanceTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted mb-3">Vaardigheidsverdeling</h6>
                <canvas id="skillDistributionChart" style="height: 300px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Performance Records Section -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-muted mb-0">Recente Prestatiebeoordelingen</h6>
            <a href="#" class="btn btn-sm btn-outline-primary">Alles Bekijken</a>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Beoordelaar</th>
                        <th>Beoordeling</th>
                        <th>Opmerkingen</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2023-11-01</td>
                        <td>John Doe</td>
                        <td>
                            <div class="rating-stars">
                                <i class="ri-star-fill text-warning"></i>
                                <i class="ri-star-fill text-warning"></i>
                                <i class="ri-star-fill text-warning"></i>
                                <i class="ri-star-fill text-warning"></i>
                                <i class="ri-star-fill text-muted"></i>
                            </div>
                        </td>
                        <td>Uitstekende prestatie in Q3, overtrof verwachtingen in projectoplevering</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-2">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2023-08-15</td>
                        <td>Jane Smith</td>
                        <td>
                            <div class="rating-stars">
                                <i class="ri-star-fill text-warning"></i>
                                <i class="ri-star-fill text-warning"></i>
                                <i class="ri-star-fill text-warning"></i>
                                <i class="ri-star-fill text-muted"></i>
                                <i class="ri-star-fill text-muted"></i>
                            </div>
                        </td>
                        <td>Goede vooruitgang, verbetering nodig in communicatie met teamleden</td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary me-2">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>

<!-- Add Performance Modal -->
<div class="modal fade" id="addPerformanceModal" tabindex="-1" aria-labelledby="addPerformanceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addPerformanceModalLabel">Prestatie Record Toevoegen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Sluiten"></button>
            </div>
            <div class="modal-body">
                <form id="performanceForm" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="reviewDate" class="form-label">Beoordelingsdatum</label>
                            <input type="date" class="form-control shadow-sm" id="reviewDate" required>
                            <div class="invalid-feedback">Selecteer een datum</div>
                        </div>
                        <div class="col-md-6">
                            <label for="reviewer" class="form-label">Beoordelaar</label>
                            <select class="form-select shadow-sm" id="reviewer" required>
                                <option value="">Selecteer Beoordelaar</option>
                                <option value="1">John Doe</option>
                                <option value="2">Jane Smith</option>
                                <option value="3">Michael Johnson</option>
                            </select>
                            <div class="invalid-feedback">Selecteer een beoordelaar</div>
                        </div>
                        <div class="col-12">
                            <label for="rating" class="form-label">Beoordeling</label>
                            <div class="rating-stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="ri-star-fill text-muted" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <input type="hidden" id="rating" name="rating" required>
                            <div class="invalid-feedback">Geef een beoordeling</div>
                        </div>
                        <div class="col-12">
                            <label for="comments" class="form-label">Opmerkingen</label>
                            <textarea class="form-control shadow-sm" id="comments" rows="4" required></textarea>
                            <div class="invalid-feedback">Voeg opmerkingen toe</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                <button type="submit" form="performanceForm" class="btn btn-primary">Record Opslaan</button>
            </div>
        </div>
    </div>
</div>
