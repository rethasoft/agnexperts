{{-- resources/views/app/tenant/agenda/modals/event-detail.blade.php --}}
<div class="modal fade" id="eventDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Event Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventDetailContent">
                <!-- Content will be dynamically populated -->
                <div class="row">
                    <div class="col-md-8">
                        <h5 id="event-title">Inspectie #IN0123 - Restaurant De Molen</h5>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="status-dot status-in-progress"></span>
                            <span class="badge bg-primary">In uitvoering</span>
                            <small class="text-muted" id="event-date">4 mei 2025, 09:30 - 11:30</small>
                        </div>

                        <p id="event-description">
                            Inspectie van Restaurant De Molen voor periodieke veiligheidscontrole.
                            Er is speciale aandacht nodig voor de brandveiligheid na de recente renovatie van de keuken.
                            Het restaurant is tijdens de inspectie gesloten voor publiek.
                        </p>

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="ri-map-pin-line"></i>
                            <span id="event-location">Amsterdam, Molenstraat 45</span>
                        </div>

                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="ri-user-line"></i>
                            <span id="event-assigned">Toegewezen aan: Johan Bakker</span>
                        </div>

                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 60%"
                                aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">Voortgang: <span id="event-progress">60%</span></small>
                            <small class="text-muted">Deadline: <span id="event-deadline">6 mei 2025</span></small>
                        </div>

                        <!-- Related Tasks Section -->
                        <h6 class="fw-bold mt-4 mb-2">Gerelateerde taken</h6>
                        <ul class="list-group" id="event-tasks">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="status-dot status-completed me-2"></span>
                                    <span>Voorbereiden documenten</span>
                                </div>
                                <span class="badge bg-success rounded-pill">Afgerond</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="status-dot status-in-progress me-2"></span>
                                    <span>Uitvoeren inspectie</span>
                                </div>
                                <span class="badge bg-primary rounded-pill">In uitvoering</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="status-dot status-planned me-2"></span>
                                    <span>Rapport opstellen</span>
                                </div>
                                <span class="badge bg-secondary rounded-pill">Gepland</span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <!-- Additional info -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#assignTaskModal">
                    <i class="ri-task-line"></i> Taak toewijzen
                </button>
                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal"
                    data-bs-target="#editEventModal">
                    <i class="ri-edit-line"></i> Bewerken
                </button>
                <a href="#" class="btn btn-primary">
                    <i class="ri-file-list-line"></i> Inspectie details
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
            </div>
        </div>
    </div>
</div>
