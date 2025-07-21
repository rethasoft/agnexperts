{{-- resources/views/app/tenant/agenda/partials/header.blade.php --}}
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
