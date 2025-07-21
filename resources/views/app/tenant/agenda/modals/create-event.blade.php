{{-- resources/views/app/tenant/agenda/modals/create-event.blade.php --}}
<div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEventModalLabel">Nieuw Event Toevoegen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createEventForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="eventType" class="form-label">Type Event</label>
                            <select class="form-select" id="eventType" required>
                                <option value="">Selecteer type...</option>
                                <option value="inspection">Inspectie</option>
                                <option value="appointment">Afspraak</option>
                                <option value="meeting">Vergadering</option>
                                <option value="task">Taak</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="eventTitle" class="form-label">Titel</label>
                            <input type="text" class="form-control" id="eventTitle" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="event_start_date" class="form-label">Start Datum/Tijd</label>
                            <input type="datetime-local" class="form-control" id="event_start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="event_end_date" class="form-label">Eind Datum/Tijd</label>
                            <input type="datetime-local" class="form-control" id="event_end_date" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="eventLocation" class="form-label">Locatie</label>
                            <input type="text" class="form-control" id="eventLocation"
                                placeholder="Amsterdam, Hoofdkantoor">
                        </div>
                        <div class="col-md-6">
                            <label for="assignTo" class="form-label">Toewijzen aan</label>
                            <select class="form-select" id="assignTo">
                                <option value="">Selecteer medewerker...</option>
                                <option value="1">Jan Janssen</option>
                                <option value="2">Petra de Vries</option>
                                <option value="3">Bart Smit</option>
                            </select>
                        </div>
                    </div>

                    <!-- Resource selection field - will be shown/hidden based on whether resources are selected -->
                    <div class="mb-3" style="display: none;">
                        <label for="event_resource" class="form-label">Resource/Medewerker</label>
                        <select class="form-select" id="event_resource">
                            <option value="">-- Select Resource --</option>
                            <!-- Options will be populated via JavaScript -->
                        </select>
                        <small class="form-text text-muted">Selecteer de medewerker voor deze afspraak/taak</small>
                    </div>

                    <div class="mb-3">
                        <label for="eventDescription" class="form-label">Beschrijving</label>
                        <textarea class="form-control" id="eventDescription" rows="3" placeholder="Voeg details toe..."></textarea>
                    </div>

                    <div id="inspectionFields" class="d-none">
                        <h6 class="mt-4 mb-3">Inspectie details</h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="inspectionType" class="form-label">Type inspectie</label>
                                <select class="form-select" id="inspectionType">
                                    <option value="annual">Jaarlijkse keuring</option>
                                    <option value="quarterly">Kwartaal inspectie</option>
                                    <option value="special">Speciale keuring</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="clientSelect" class="form-label">Klant</label>
                                <select class="form-select" id="clientSelect">
                                    <option value="">Selecteer klant...</option>
                                    <option value="1">Kantoor Amsterdam</option>
                                    <option value="2">Fabriek Rotterdam</option>
                                    <option value="3">Distributiecentrum Utrecht</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-primary" id="saveEventBtn">Opslaan</button>
            </div>
        </div>
    </div>
</div>
