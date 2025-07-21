@extends('app.layouts.app')
@section('title', 'Klantgegevens')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">Dashboard</li>
                                <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Klanten</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Details</li>
                            </ol>
                        </nav>
                        <div class="d-flex gap-2">
                            <a href="{{ route('client.edit', $client->id) }}" class="btn btn-primary">
                                <i class="ri-pencil-line"></i> Klant Bewerken
                            </a>
                            <a href="{{ route('client.index') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line"></i> Terug naar Lijst
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <!-- Main Tab Navigation -->
            <ul class="nav nav-tabs mb-4" id="mainTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                        type="button" role="tab" aria-controls="overview" aria-selected="true">
                        <i class="ri-dashboard-line me-2"></i>Overzicht
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="activities-tab" data-bs-toggle="tab" data-bs-target="#activities"
                        type="button" role="tab" aria-controls="activities" aria-selected="false">
                        <i class="ri-calendar-event-line me-2"></i>Activiteiten
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents"
                        type="button" role="tab" aria-controls="documents" aria-selected="false">
                        <i class="ri-file-list-line me-2"></i>Documenten
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="inspections-tab" data-bs-toggle="tab" data-bs-target="#inspections"
                        type="button" role="tab" aria-controls="inspections" aria-selected="false">
                        <i class="ri-search-line me-2"></i>Keuringen
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="invoices-tab" data-bs-toggle="tab" data-bs-target="#invoices"
                        type="button" role="tab" aria-controls="invoices" aria-selected="false">
                        <i class="ri-file-text-line me-2"></i>Facturen
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="mainTabsContent">
                <!-- Overview Tab -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    <div class="row">
                        <!-- Main Client Information -->
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                                    <i class="ri-user-3-line me-2 fs-5 text-primary"></i>
                                    <h5 class="card-title mb-0 fw-bold">Klantinformatie</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                                <label class="form-label fw-bold text-muted small mb-1">Naam:</label>
                                                <p class="mb-0 fw-medium text-dark">{{ $client->name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                                <label class="form-label fw-bold text-muted small mb-1">Achternaam:</label>
                                                <p class="mb-0 fw-medium text-dark">{{ $client->surname }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                                <label class="form-label fw-bold text-muted small mb-1">E-mail:</label>
                                                <p class="mb-0 fw-medium">
                                                    <a href="mailto:{{ $client->email }}"
                                                        class="text-decoration-none text-primary">
                                                        {{ $client->email }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                                <label class="form-label fw-bold text-muted small mb-1">Telefoon:</label>
                                                <p class="mb-0 fw-medium">
                                                    <a href="tel:{{ $client->phone }}"
                                                        class="text-decoration-none text-primary">
                                                        {{ $client->phone }}
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                                <label class="form-label fw-bold text-muted small mb-1">Adres:</label>
                                                <p class="mb-0 fw-medium text-dark">{{ $client->address }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Client Statistics -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                                    <i class="ri-bar-chart-line me-2 fs-5 text-primary"></i>
                                    <h5 class="card-title mb-0 fw-bold">Statistieken</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                        <label class="form-label fw-bold text-muted small mb-1">Totaal
                                            projecten:</label>
                                        <p class="mb-0 fw-medium text-dark">5</p>
                                    </div>
                                    <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                        <label class="form-label fw-bold text-muted small mb-1">Actieve
                                            projecten:</label>
                                        <p class="mb-0 fw-medium text-dark">2</p>
                                    </div>
                                    <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                        <label class="form-label fw-bold text-muted small mb-1">Openstaand
                                            saldo:</label>
                                        <p class="mb-0 fw-medium text-dark">€ 1.250,00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activities Tab -->
                <div class="tab-pane fade" id="activities" role="tabpanel" aria-labelledby="activities-tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                                    <i class="ri-calendar-line me-2 fs-5 text-primary"></i>
                                    <h5 class="card-title mb-0 fw-bold">Activiteiten</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                        <label class="form-label fw-bold text-muted small mb-1">Laatste
                                            activiteit:</label>
                                        <p class="mb-0 fw-medium text-dark">2 dagen geleden</p>
                                    </div>
                                    <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                        <label class="form-label fw-bold text-muted small mb-1">Aangemaakt op:</label>
                                        <p class="mb-0 fw-medium text-dark">
                                            {{ $client->created_at->format('d-m-Y H:i') }}</p>
                                    </div>
                                    <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                        <label class="form-label fw-bold text-muted small mb-1">Laatst
                                            bijgewerkt:</label>
                                        <p class="mb-0 fw-medium text-dark">
                                            {{ $client->updated_at->format('d-m-Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                                    <i class="ri-chat-history-line me-2 fs-5 text-primary"></i>
                                    <h5 class="card-title mb-0 fw-bold">Interactiegeschiedenis</h5>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-point"></div>
                                            <div class="timeline-content">
                                                <small class="text-muted">2 uur geleden</small>
                                                <p>E-mail verzonden over factuur #12345</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-point"></div>
                                            <div class="timeline-content">
                                                <small class="text-muted">1 dag geleden</small>
                                                <p>Telefoongesprek over nieuwe keuring</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Tab -->
                <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                                    <i class="ri-sticky-note-line me-2 fs-5 text-primary"></i>
                                    <h5 class="card-title mb-0 fw-bold">Notities</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <textarea class="form-control" rows="3" placeholder="Nieuwe notitie toevoegen..."></textarea>
                                        <button class="btn btn-primary mt-2">Opslaan</button>
                                    </div>
                                    <div class="notes-list">
                                        <div class="note-item mb-3">
                                            <p class="mb-1">Klant heeft voorkeur voor ochtendafspraken</p>
                                            <small class="text-muted">Toegevoegd op 01-01-2024 door Jan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                                    <i class="ri-file-list-line me-2 fs-5 text-primary"></i>
                                    <h5 class="card-title mb-0 fw-bold">Documenten</h5>
                                </div>
                                <div class="card-body">
                                    <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                        <label class="form-label fw-bold text-muted small mb-1">Contracten:</label>
                                        <p class="mb-0 fw-medium text-dark">3 actieve contracten</p>
                                    </div>
                                    <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                        <label class="form-label fw-bold text-muted small mb-1">Facturen:</label>
                                        <p class="mb-0 fw-medium text-dark">5 facturen (1 openstaand)</p>
                                    </div>
                                    <div class="info-card bg-white p-3 rounded-3 mb-3 border">
                                        <label class="form-label fw-bold text-muted small mb-1">Documenten:</label>
                                        <p class="mb-0 fw-medium text-dark">12 geüploade documenten</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inspections Tab -->
                <div class="tab-pane fade" id="inspections" role="tabpanel" aria-labelledby="inspections-tab">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                            <i class="ri-search-line me-2 fs-5 text-primary"></i>
                            <h5 class="card-title mb-0 fw-bold">Keuringen</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Datum</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Acties</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>01-01-2024</td>
                                            <td>Jaarlijkse Keuring</td>
                                            <td><span class="badge bg-success">Voltooid</span></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-light">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>15-02-2024</td>
                                            <td>Periodieke Keuring</td>
                                            <td><span class="badge bg-warning">In Behandeling</span></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-light">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Invoices Tab -->
                <div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices-tab">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                            <i class="ri-file-text-line me-2 fs-5 text-primary"></i>
                            <h5 class="card-title mb-0 fw-bold">Facturen</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Factuurnummer</th>
                                            <th>Datum</th>
                                            <th>Bedrag</th>
                                            <th>Status</th>
                                            <th>Acties</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#12345</td>
                                            <td>01-01-2024</td>
                                            <td>€ 250,00</td>
                                            <td><span class="badge bg-success">Betaald</span></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-light">
                                                    <i class="ri-download-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#12346</td>
                                            <td>15-02-2024</td>
                                            <td>€ 500,00</td>
                                            <td><span class="badge bg-danger">Openstaand</span></td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-light">
                                                    <i class="ri-download-line"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
