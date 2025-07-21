{{-- resources/views/app/tenant/agenda/components/calendar-toolbar.blade.php --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="btn-group">
        <button class="btn btn-sm btn-outline-primary view-btn active" data-view="dayGridMonth">Maand</button>
        <button class="btn btn-sm btn-outline-primary view-btn" data-view="timeGridWeek">Week</button>
        <button class="btn btn-sm btn-outline-primary view-btn" data-view="timeGridDay">Dag</button>
        <button class="btn btn-sm btn-outline-primary view-btn" data-view="resourceTimelineWeek">Team</button>
    </div>

    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="ri-user-line"></i> Medewerker
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item active" href="#">Alle medewerkers</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                @foreach ($employees ?? [] as $employee)
                    <li><a class="dropdown-item" href="#">{{ $employee['name'] }}</a></li>
                @endforeach
                @if (empty($employees))
                    <li><a class="dropdown-item" href="#">Johan Bakker</a></li>
                    <li><a class="dropdown-item" href="#">Marieke de Vries</a></li>
                    <li><a class="dropdown-item" href="#">Pieter Janssen</a></li>
                    <li><a class="dropdown-item" href="#">Sophie van Dijk</a></li>
                @endif
            </ul>
        </div>

        @if (isset($showAllFilters) && $showAllFilters)
            <span class="filter-badge filter-badge-inspection active" data-filter="inspection">Inspecties</span>
            <span class="filter-badge filter-badge-task" data-filter="task">Taken</span>
            <span class="filter-badge filter-badge-meeting" data-filter="meeting">Vergaderingen</span>
            <span class="filter-badge filter-badge-deadline" data-filter="deadline">Deadlines</span>
        @else
            <span class="filter-badge filter-badge-inspection active" data-filter="inspection">Inspecties</span>
        @endif
    </div>
</div>
