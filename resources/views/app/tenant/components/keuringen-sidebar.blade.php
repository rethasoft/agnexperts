<div class="col-lg-4">
    <!-- File Info Section -->
    <div class="card shadow-sm mb-4 sticky-top" style="top: 1rem;">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="ri-folder-line me-2"></i>Dossiergegevens
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @if ($isEdit)
                    <div class="col-12">
                        <label class="form-label">Dossiernummer</label>
                        <input type="text" name="file_id" class="form-control" readonly
                            value="{{ $inspection->file_id }}">
                    </div>
                @endif
                <div class="col-12">
                    <label class="form-label">Medewerker</label>
                    <select name="employee_id" class="form-select" required>
                        <option value="">{{ __('Selecteren') }}</option>
                        @foreach ($employes as $employe)
                            <option value="{{ $employe->id }}"
                                {{ $isEdit && $inspection->employee_id == $employe->id ? 'selected' : '' }}>
                                {{ $employe->name . ' ' . $employe->surname }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Datum plaatsbezoek</label>
                    <input type="date" name="inspection_date" class="form-control" required
                        min="{{ now()->toDateString() }}"
                        value="{{ $isEdit && $inspection->inspection_date ? \Carbon\Carbon::parse($inspection->inspection_date)->format('Y-m-d') : '' }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Status</label>
                    <select name="status_id" class="form-select" required>
                        <option value="">Selecteren</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->id }}"
                                @if($isEdit)
                                    {{ $inspection->status_id == $status->id ? 'selected' : '' }}
                                @else
                                    {{ old('status_id') == $status->id ? 'selected' : ($status->is_default ? 'selected' : '') }}
                                @endif
                            >
                                {{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label d-flex justify-content-between">
                        <span>Factuur</span>
                        <small class="text-muted">PDF</small>
                    </label>
                    <input type="file" name="invoice" class="form-control" accept=".pdf">
                </div>
                <div class="col-12">
                    <label class="form-label d-flex justify-content-between">
                        <span>Admin Bestanden</span>
                        <small class="text-muted">PDF, JPG, PNG</small>
                    </label>
                    <input type="file" name="admin_files[]" class="form-control" multiple
                        accept=".pdf,.jpg,.jpeg,.png">
                </div>
            </div>
        </div>
        <div class="card-footer bg-white">
            <div class="d-flex gap-2 justify-content-end">
                <button class="btn btn-success btn-sm px-3 py-2 d-flex align-items-center" type="submit">
                    <i class="ri-save-line me-2"></i>
                    <span>Opslaan</span>
                </button>
                <div class="vr"></div>
                @if (!$isEdit)
                    <button class="btn btn-primary btn-sm px-3 py-2 d-flex align-items-center" type="submit"
                        name="to-detail" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Opslaan en detailleren">
                        <i class="ri-file-list-3-line me-2"></i>
                        <span>Opslaan & Details</span>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
