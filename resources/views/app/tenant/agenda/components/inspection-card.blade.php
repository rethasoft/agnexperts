{{-- resources/views/app/tenant/agenda/components/inspection-card.blade.php --}}
<div class="list-group-item list-group-item-action event-card inspection p-2" data-id="1001"
    data-title="Inspectie #1001 - Jaarlijkse keuring" data-date="2025-05-04" data-status="pending" data-employee-id="1">
    <div class="d-flex w-100 justify-content-between align-items-center">
        <div>
            <div class="d-flex align-items-center">
                <span class="status-dot status-pending me-2"></span>
                <span class="text-primary"><i class="ri-file-list-line me-1"></i></span>
                <span class="fw-medium">Inspectie #1001</span>
            </div>
            <small class="text-muted d-block">Kantoor - Jaarlijkse keuring</small>
        </div>
        <small class="text-nowrap ms-2">10:30</small>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-1">
        <small class="text-muted"><i class="ri-map-pin-line"></i> Amsterdam, Hoofdkantoor</small>
        <span class="badge bg-primary">Jan Janssen</span>
    </div>

    <div class="drag-handle position-absolute top-0 end-0 p-2 text-muted" style="cursor: grab;">
        <i class="ri-drag-move-fill"></i>
    </div>
</div>
