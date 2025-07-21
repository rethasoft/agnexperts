<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
            <h5 class="mb-0 fw-semibold text-primary">Medewerker Tijdlijn</h5>
            <button type="button" class="btn btn-primary shadow-sm py-2 fw-medium d-flex align-items-center gap-2"
                data-bs-toggle="modal" data-bs-target="#addEventModal">
                <i class="ri-add-line"></i> Toevoegen
            </button>
        </div>

        <!-- Timeline Content -->
        <div class="timeline-wrapper ps-3 position-relative">
            <!-- Timeline Line -->
            <div class="timeline-line position-absolute start-0 h-100 bg-primary"
                style="width: 2px; margin-left: 19px;"></div>

            <!-- Timeline Item -->
            <div class="timeline-item position-relative mb-4">
                <div class="timeline-icon position-absolute start-0 translate-middle bg-primary text-white d-flex align-items-center justify-content-center rounded-circle shadow-lg"
                    style="width: 40px; height: 40px;">
                    <i class="ri-user-add-line"></i>
                </div>
                <div class="timeline-content ms-5 p-4 bg-white rounded-3 shadow-sm position-relative">
                    <!-- Ribbon Effect -->
                    <div class="position-absolute top-0 start-0 h-100 bg-primary"
                        style="width: 4px; border-radius: 3px 0 0 3px;"></div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold text-primary">Medewerker Aangemaakt</h6>
                        <small class="text-muted"><i class="ri-time-line me-1"></i>Zojuist</small>
                    </div>
                    <div class="timeline-details">
                        <div class="d-flex align-items-center mb-2">
                            <i class="ri-user-line me-2 text-muted"></i>
                            <span><strong>Aangemaakt Door:</strong> John Doe</span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="ri-briefcase-line me-2 text-muted"></i>
                            <span><strong>Initiële Rol:</strong> Manager</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ri-file-text-line me-2 text-muted"></i>
                            <span><strong>Notities:</strong> Nieuwe medewerker onboarding proces gestart</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Timeline Item -->
            <div class="timeline-item position-relative mb-4">
                <div class="timeline-icon position-absolute start-0 translate-middle bg-warning text-white d-flex align-items-center justify-content-center rounded-circle shadow-lg"
                    style="width: 40px; height: 40px;">
                    <i class="ri-play-circle-line"></i>
                </div>
                <div class="timeline-content ms-5 p-4 bg-white rounded-3 shadow-sm position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-semibold text-warning">Inspectie Gestart</h6>
                        <small class="text-muted"><i class="ri-time-line me-1"></i>2023-11-20 09:00</small>
                    </div>
                    <div class="timeline-details">
                        <div class="d-flex align-items-center mb-2">
                            <i class="ri-search-line me-2 text-muted"></i>
                            <span><strong>Type:</strong> Veiligheidsinspectie</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ri-user-line me-2 text-muted"></i>
                            <span><strong>Inspecteur:</strong> John Doe</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addEventModalLabel">Tijdlijn Gebeurtenis Toevoegen</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Sluiten"></button>
            </div>
            <div class="modal-body">
                <form id="eventForm" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="eventTitle" class="form-label">Gebeurtenis Titel</label>
                            <input type="text" class="form-control shadow-sm" id="eventTitle" required>
                            <div class="invalid-feedback">Voer een titel in</div>
                        </div>
                        <div class="col-md-6">
                            <label for="eventDate" class="form-label">Datum & Tijd</label>
                            <input type="datetime-local" class="form-control shadow-sm" id="eventDate" required>
                            <div class="invalid-feedback">Selecteer een datum</div>
                        </div>
                        <div class="col-12">
                            <label for="eventType" class="form-label">Type Gebeurtenis</label>
                            <select class="form-select shadow-sm" id="eventType" required>
                                <option value="">Selecteer Type</option>
                                <option value="performance_review">Functioneringsgesprek</option>
                                <option value="training">Training</option>
                                <option value="meeting">Vergadering</option>
                                <option value="milestone">Mijlpaal</option>
                                <option value="other">Overig</option>
                                <option value="inspection_scheduled">Inspectie Gepland</option>
                                <option value="inspection_started">Inspectie Gestart</option>
                                <option value="inspection_completed">Inspectie Voltooid</option>
                                <option value="inspection_followup">Inspectie Opvolging</option>
                                <option value="inspection_closed">Inspectie Afgesloten</option>
                            </select>
                            <div class="invalid-feedback">Selecteer een type gebeurtenis</div>
                        </div>
                        <div class="col-12">
                            <label for="eventParticipants" class="form-label">Deelnemers</label>
                            <input type="text" class="form-control shadow-sm" id="eventParticipants">
                        </div>
                        <div class="col-12">
                            <label for="eventDescription" class="form-label">Gedetailleerde Beschrijving</label>
                            <textarea class="form-control shadow-sm" id="eventDescription" rows="4"></textarea>
                        </div>
                        <div class="col-12">
                            <label for="eventAttachments" class="form-label">Bijlagen</label>
                            <input type="file" class="form-control shadow-sm" id="eventAttachments" multiple>
                        </div>

                        <!-- Inspection Specific Fields -->
                        <div class="col-12 inspection-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="inspectionType" class="form-label">Inspectie Type</label>
                                <select class="form-select shadow-sm" id="inspectionType">
                                    <option value="">Selecteer Inspectie Type</option>
                                    <option value="safety">Veiligheidsinspectie</option>
                                    <option value="quality">Kwaliteitscontrole</option>
                                    <option value="compliance">Regelgeving Naleving</option>
                                    <option value="equipment">Apparatuur Controle</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="inspectionStandards" class="form-label">Toepasselijke Normen</label>
                                <input type="text" class="form-control shadow-sm" id="inspectionStandards">
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="criticalFindings" class="form-label">Kritieke Bevindingen</label>
                                    <input type="number" class="form-control shadow-sm" id="criticalFindings"
                                        min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="majorFindings" class="form-label">Grote Bevindingen</label>
                                    <input type="number" class="form-control shadow-sm" id="majorFindings"
                                        min="0">
                                </div>
                                <div class="col-md-4">
                                    <label for="minorFindings" class="form-label">Kleine Bevindingen</label>
                                    <input type="number" class="form-control shadow-sm" id="minorFindings"
                                        min="0">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="correctiveActions" class="form-label">Corrigerende Maatregelen</label>
                                <textarea class="form-control shadow-sm" id="correctiveActions" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="followUpDate" class="form-label">Vervolg Datum</label>
                                <input type="date" class="form-control shadow-sm" id="followUpDate">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                <button type="submit" form="eventForm" class="btn btn-primary">Opslaan</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('eventType').addEventListener('change', function() {
        const inspectionFields = document.querySelector('.inspection-fields');
        if (this.value === 'inspection') {
            inspectionFields.style.display = 'block';
        } else {
            inspectionFields.style.display = 'none';
        }
    });
</script>
