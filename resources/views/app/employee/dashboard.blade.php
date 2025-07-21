@extends('app.layouts.app')
@section('content')
    <div class="container-fluid">
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

        <div class="row mt-4">
            <div class="col-lg-12 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="ri-home-smile-line ri-3x text-primary mb-3"></i>
                        <h2 class="mb-4">Welkom!</h2>
                        <p class="lead mb-4">
                            Welkom bij uw medewerkerportaal. Hier kunt u uw inspecties plannen en beheren.
                        </p>
                        <div class="row g-4 mt-2 justify-content-center">
                            <div class="col-md-5">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <i class="ri-calendar-2-line ri-3x mb-3 text-primary"></i>
                                        <h5 class="card-title">Agenda</h5>
                                        <p class="card-text">Plan hier uw inspecties en afspraken</p>
                                        <a href="{{ route('employee.agenda.index') }}" class="btn btn-primary">
                                            <i class="ri-calendar-check-line me-2"></i>Naar Agenda
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="card h-100 border-0 bg-light">
                                    <div class="card-body">
                                        <i class="ri-file-list-3-line ri-3x mb-3 text-success"></i>
                                        <h5 class="card-title">Keuringen</h5>
                                        <p class="card-text">Beheer hier al uw keuringen</p>
                                        <a href="{{ route('employee.inspections.index') }}" class="btn btn-success">
                                            <i class="ri-clipboard-line me-2"></i>Naar Keuringen
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
