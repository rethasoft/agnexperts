@extends('app.layouts.app')

@section('title', 'Agenda & Planning')

@section('styles')
    @include('app.tenant.agenda.partials.styles')
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div>
                    <h4 class="mb-0">{{ __('Agenda & Planning') }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Agenda</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2 mt-2 mt-md-0">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#helpModal">
                        <i class="ri-question-line"></i> Help
                    </button>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createEventModal">
                        <i class="ri-add-line"></i> Nieuw
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left sidebar with event lists -->
        <div class="col-lg-3">
            <!-- Today's Events -->
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fs-6">Inspections</h5>
                    <span class="badge bg-primary">5</span>
                </div>
                <div class="card-body p-0">
                    @include('app.tenant.agenda.components.inspection-card')
                    <div class="card-footer p-2 text-center">
                        <a href="#" class="btn btn-sm btn-outline-primary w-100" data-bs-toggle="modal"
                            data-bs-target="#createEventModal">
                            <i class="ri-add-line"></i> Inspectie Toevoegen
                        </a>
                    </div>
                </div>

                <!-- Team Members -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0 fs-6">Team</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush employee-draggable-target">
                            @include('app.tenant.agenda.components.employee-card')
                        </div>
                    </div>
                </div>
            </div><!-- Close the col-lg-3 div here -->
        </div>

        <!-- Main calendar and controls -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    @include('app.tenant.agenda.components.calendar-container')
                </div>
            </div>
        </div>

        <!-- Toast container for notifications -->
        <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1070;"></div>

        <!-- Modals -->
        @include('app.tenant.agenda.modals.create-event')
        @include('app.tenant.agenda.modals.assign-task')
        @include('app.tenant.agenda.modals.help')
        @include('app.tenant.agenda.modals.event-detail')
    </div>
@endsection

@push('scripts')
    @include('app.tenant.agenda.partials.scripts')
@endpush
