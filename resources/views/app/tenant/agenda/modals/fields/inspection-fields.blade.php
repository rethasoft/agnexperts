{{-- resources/views/app/tenant/agenda/modals/fields/inspection-fields.blade.php --}}
<h6 class="mb-3 border-bottom pb-2">Inspectie Details</h6>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="client_id" class="form-label">Klant</label>
        <select class="form-select" id="client_id" name="client_id">
            <option value="">Selecteer Klant</option>
            @foreach ($clients ?? [] as $client)
                <option value="{{ $client['id'] }}">{{ $client['name'] }}</option>
            @endforeach
            @if (empty($clients))
                <option value="1">Restaurant Holding BV</option>
                <option value="2">Hotel De Witte Raaf</option>
                <option value="3">Kantoorgebouw Zuidpark</option>
                <option value="4">Bouwbedrijf Jansen</option>
            @endif
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label for="inspection_type" class="form-label">Type Inspectie</label>
        <select class="form-select" id="inspection_type" name="inspection_type">
            <option value="">Selecteer Type</option>
            @foreach ($inspectionTypes ?? [] as $type)
                <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
            @endforeach
            @if (empty($inspectionTypes))
                <option value="1">Brandveiligheid</option>
                <option value="2">HACCP</option>
                <option value="3">Gebouwveiligheid</option>
                <option value="4">Elektra</option>
            @endif
        </select>
    </div>
</div>

<div class="mb-3">
    <label for="inspector_id" class="form-label">Inspecteur</label>
    <select class="form-select" id="inspector_id" name="inspector_id">
        <option value="">Selecteer Inspecteur</option>
        @foreach ($inspectors ?? [] as $inspector)
            <option value="{{ $inspector['id'] }}">{{ $inspector['name'] }}</option>
        @endforeach
        @if (empty($inspectors))
            <option value="1">Johan Bakker</option>
            <option value="2">Marieke de Vries</option>
            <option value="3">Pieter Janssen</option>
        @endif
    </select>
</div>
