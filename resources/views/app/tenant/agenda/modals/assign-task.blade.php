{{-- resources/views/app/tenant/agenda/modals/assign-task.blade.php --}}
<div class="modal fade" id="assignTaskModal" tabindex="-1" aria-labelledby="assignTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignTaskModalLabel">Taak Toewijzen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assignTaskForm">
                    <div class="mb-3">
                        <label for="taskTitle" class="form-label">Taak titel</label>
                        <input type="text" class="form-control" id="taskTitle" required>
                    </div>

                    <div class="mb-3">
                        <label for="taskDescription" class="form-label">Omschrijving</label>
                        <textarea class="form-control" id="taskDescription" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="taskDueDate" class="form-label">Deadline datum</label>
                            <input type="date" class="form-control" id="taskDueDate" value="2025-05-04">
                        </div>
                        <div class="col-md-6">
                            <label for="taskDueTime" class="form-label">Deadline tijd</label>
                            <input type="time" class="form-control" id="taskDueTime" value="17:00">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="assignTaskTo" class="form-label">Toewijzen aan</label>
                        <select class="form-select" id="assignTaskTo" required>
                            <option value="">Selecteer medewerker...</option>
                            <option value="1">Jan Janssen</option>
                            <option value="2">Petra de Vries</option>
                            <option value="3">Bart Smit</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="taskPriority" class="form-label">Prioriteit</label>
                        <select class="form-select" id="taskPriority">
                            <option value="low">Laag</option>
                            <option value="medium" selected>Normaal</option>
                            <option value="high">Hoog</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-primary" id="saveTaskBtn">Taak toewijzen</button>
            </div>
        </div>
    </div>
</div>
