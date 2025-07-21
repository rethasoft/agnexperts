{{-- resources/views/app/tenant/agenda/partials/sidebar.blade.php --}}
<!-- Today's Events -->
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0 fs-6">Inspections</h5>
        <span class="badge bg-primary">{{ count($inspections ?? []) }}</span>
    </div>
    <div class="card-body p-0">
        <div class="list-group list-group-flush sidebar-draggable">
            @forelse($inspections ?? [] as $inspection)
                @include('app.tenant.agenda.components.inspection-card', $inspection)
            @empty
                @include('app.tenant.agenda.components.inspection-card', [
                    'id' => 'IN0124',
                    'title' => 'Kantoor Zuidpark',
                    'status' => 'planned',
                    'location' => 'Utrecht',
                    'time' => '11:00',
                    'dateTime' => '2025-05-04T11:00:00',
                    'assignedTo' => 'Marieke',
                    'assignedBadgeClass' => 'bg-success',
                    'description' => 'Jaarlijkse brandveiligheidsinspectie',
                ])

                @include('app.tenant.agenda.components.inspection-card', [
                    'id' => 'IN0125',
                    'title' => 'Restaurant De Molen',
                    'status' => 'in-progress',
                    'location' => 'Amsterdam',
                    'time' => '14:00',
                    'dateTime' => '2025-05-04T14:00:00',
                    'assignedTo' => 'Johan',
                    'assignedBadgeClass' => 'bg-primary',
                    'description' => 'Periodieke veiligheidscontrole',
                    'entityType' => 'Woning',
                ])
            @endforelse
        </div>
    </div>
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
            @forelse($employees ?? [] as $employee)
                @include('app.tenant.agenda.components.employee-card', $employee)
            @empty
                @php
                    $defaultEmployees = [
                        ['id' => 1, 'name' => 'Johan Bakker', 'tasks' => 3, 'gender' => 'men', 'photoId' => 41],
                        ['id' => 2, 'name' => 'Marieke de Vries', 'tasks' => 2, 'gender' => 'women', 'photoId' => 41],
                        ['id' => 3, 'name' => 'Pieter Janssen', 'tasks' => 4, 'gender' => 'men', 'photoId' => 42],
                        ['id' => 4, 'name' => 'Sophie van Dijk', 'tasks' => 1, 'gender' => 'women', 'photoId' => 42],
                    ];
                @endphp

                @foreach ($defaultEmployees as $employee)
                    @include('app.tenant.agenda.components.employee-card', $employee)
                @endforeach
            @endforelse
        </div>
    </div>
</div>
