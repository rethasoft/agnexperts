{{-- resources/views/app/tenant/agenda/modals/fields/task-fields.blade.php --}}
<h6 class="mb-3 border-bottom pb-2">Taak Details</h6>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="priority" class="form-label">Prioriteit</label>
        <select class="form-select" id="priority" name="priority">
            <option value="low">Laag</option>
            <option value="medium" selected>Middel</option>
            <option value="high">Hoog</option>
            <option value="urgent">Urgent</option>
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label for="assigned_to" class="form-label">Toegewezen aan</label>
        <select class="form-select" id="assigned_to" name="assigned_to">
            <option value="">Selecteer Medewerker</option>
            @foreach ($employees ?? [] as $employee)
                <option value="{{ $employee['id'] }}">{{ $employee['name'] }}</option>
            @endforeach
            @if (empty($employees))
                <option value="1">Johan Bakker</option>
                <option value="2">Marieke de Vries</option>
                <option value="3">Pieter Janssen</option>
                <option value="4">Sophie van Dijk</option>
            @endif
        </select>
    </div>
</div>

<div class="mb-3">
    <label for="related_inspection" class="form-label">Gerelateerde Inspectie</label>
    <select class="form-select" id="related_inspection" name="related_inspection">
        <option value="">Geen</option>
        @foreach ($inspections ?? [] as $inspection)
            <option value="{{ $inspection['id'] }}">{{ $inspection['id'] }} - {{ $inspection['title'] }}</option>
        @endforeach
        @if (empty($inspections))
            <option value="1">IN0123 - Restaurant De Molen</option>
            <option value="2">IN0118 - Kantoor Zuidpark</option>
            <option value="3">IN0124 - Hotel De Witte Raaf</option>
        @endif
    </select>
</div>
