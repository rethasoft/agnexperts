@props(['inspection', 'showUpload' => false])

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="ri-folder-line me-2"></i>Documenten
            </h5>
            <div class="nav nav-pills nav-pills-custom" role="tablist">
                <button class="nav-link active px-3 py-2 me-2" data-bs-toggle="pill" data-bs-target="#admin-docs"
                    type="button" role="tab">
                    <i class="ri-shield-user-line me-1"></i>
                    Admin
                </button>
                <button class="nav-link px-3 py-2" data-bs-toggle="pill" data-bs-target="#customer-docs" type="button"
                    role="tab">
                    <i class="ri-user-3-line me-1"></i>
                    Klant
                </button>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="tab-content">
            <!-- Admin Documents Tab -->
            <div class="tab-pane fade show active" id="admin-docs" role="tabpanel">
                @auth('tenant')
                    @if ($showUpload)
                        {{-- <div class="p-3 border-bottom">
                            <button class="btn btn-light w-100 text-start position-relative" type="button"
                                data-bs-toggle="collapse" data-bs-target="#adminUploadForm">
                                <div class="d-flex align-items-center">
                                    <i class="ri-upload-cloud-2-line ri-lg me-2 text-primary"></i>
                                    <span>Sleep bestanden hierheen of klik om te uploaden</span>
                                </div>
                            </button>
                            <div class="collapse mt-3" id="adminUploadForm">
                                <div class="upload-area bg-light rounded-3 p-3">
                                    <input type="file" name="admin_files[]" class="form-control form-control-sm" multiple
                                        accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted d-block mt-2">
                                        <i class="ri-information-line"></i>
                                        Toegestane formaten: PDF, JPG, PNG
                                    </small>
                                </div>
                            </div>
                        </div> --}}
                    @endif
                @endauth

                @if ($inspection->files->where('metadata.type', 'admin')->count() > 0)
                    <div class="file-list">
                        @foreach ($inspection->files->where('metadata.type', 'admin') as $file)
                            <div class="file-item p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="ri-file-text-line ri-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $file->name }}</h6>
                                            <small class="text-muted">
                                                {{ $file->formatted_size }} • {{ $file->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $file->secure_url }}" target="_blank" class="btn btn-sm btn-light">
                                            <i class="ri-eye-line text-dark"></i>
                                        </a>
                                        <a href="{{ $file->secure_url }}" download="{{ $file->name }}"
                                            class="btn btn-sm btn-light">
                                            <i class="ri-download-2-line text-dark"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-4">
                        <i class="ri-folder-open-line fs-2"></i>
                        <p class="text-muted mb-0">Geen admin documenten beschikbaar</p>
                    </div>
                @endif
            </div>

            <!-- Customer Documents Tab -->
            <div class="tab-pane fade" id="customer-docs" role="tabpanel">
                @auth('tenant')
                    @if ($showUpload)
                        {{-- <div class="p-3 border-bottom">
                            <button class="btn btn-light w-100 text-start position-relative" type="button"
                                data-bs-toggle="collapse" data-bs-target="#customerUploadForm">
                                <div class="d-flex align-items-center">
                                    <i class="ri-upload-cloud-2-line ri-lg me-2 text-success"></i>
                                    <span>Sleep bestanden hierheen of klik om te uploaden</span>
                                </div>
                            </button>
                            <div class="collapse mt-3" id="customerUploadForm">
                                <div class="upload-area bg-light rounded-3 p-3">
                                    <input type="file" name="customer_files[]" class="form-control form-control-sm"
                                        multiple accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted d-block mt-2">
                                        <i class="ri-information-line"></i>
                                        Toegestane formaten: PDF, JPG, PNG
                                    </small>
                                </div>
                            </div>
                        </div> --}}
                    @endif
                @endauth

                @if ($inspection->files->where('metadata.type', 'customer')->count() > 0)
                    <div class="file-list">
                        @foreach ($inspection->files->where('metadata.type', 'customer') as $file)
                            <div class="file-item p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="ri-file-text-line ri-lg"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $file->name }}</h6>
                                            <small class="text-muted">
                                                {{ $file->formatted_size }} • {{ $file->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <a href="{{ $file->secure_url }}" target="_blank" class="btn btn-sm btn-light">
                                            <i class="ri-eye-line text-dark"></i>
                                        </a>
                                        <a href="{{ $file->secure_url }}" download="{{ $file->name }}"
                                            class="btn btn-sm btn-light">
                                            <i class="ri-download-2-line text-dark"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-4">
                        <i class="ri-folder-open-line fs-2"></i>
                        <p class="text-muted mb-0">Geen klant documenten beschikbaar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .nav-pills-custom .nav-link {
        color: #6c757d;
        background: #f8f9fa;
        font-size: 0.875rem;
    }

    .nav-pills-custom .nav-link.active {
        color: #fff;
        background: var(--bs-primary);
    }

    .file-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .file-item:hover {
        background-color: #f8f9fa;
    }

    .btn-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }

    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .upload-area {
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: var(--bs-primary);
        background-color: #f8f9fa;
    }
</style>

@push('scripts')
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
@endpush

<!-- Invoice Section -->
<div class="card shadow-sm mt-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">
            <i class="ri-bill-line me-2"></i>Factuur
        </h5>
    </div>

    <div class="card-body p-0">
        @if ($inspection->invoice && $inspection->invoice->file)
            <div class="file-item p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="ri-file-text-line ri-lg"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">
                                {{ $inspection->invoice->file->name }}
                                <span class="badge bg-{{ $inspection->invoice->status->color() }} ms-2">
                                    {{ $inspection->invoice->status }}
                                </span>
                            </h6>
                            <small class="text-muted">
                                {{ $inspection->invoice->file->formatted_size }} •
                                {{ $inspection->invoice->file->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    <div class="btn-group">
                        <!-- View Invoice -->
                        <a href="{{ $inspection->invoice->file->secure_url }}" target="_blank"
                            class="btn btn-sm btn-light" title="View Invoice">
                            <i class="ri-eye-line text-dark"></i>
                        </a>

                        <!-- Download Invoice -->
                        <a href="{{ $inspection->invoice->file->secure_url }}"
                            download="{{ $inspection->invoice->file->name }}" class="btn btn-sm btn-light"
                            title="Download Invoice">
                            <i class="ri-download-2-line text-dark"></i>
                        </a>

                        @auth('tenant')
                            <!-- Send Invoice (if not sent) -->
                            @if ($inspection->invoice->status->value == 'DRAFT')
                                <a href="#" class="btn btn-sm btn-light" title="Send to Customer"
                                    onclick="return confirm('Are you sure you want to send this invoice?')">
                                    <i class="ri-mail-send-line text-primary"></i>
                                </a>
                            @endif

                            <!-- Mark as Paid (if not paid) -->
                            @if ($inspection->invoice->status->value !== 'PAID')
                                <a href="#" class="btn btn-sm btn-light" title="Mark as Paid"
                                    onclick="return confirm('Mark this invoice as paid?')">
                                    <i class="ri-checkbox-circle-line text-success"></i>
                                </a>
                            @endif

                            <!-- Delete Invoice -->
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light" title="Delete Invoice"
                                    onclick="return confirm('Are you sure you want to delete this invoice?')">
                                    <i class="ri-delete-bin-line text-danger"></i>
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
        @else
            <div class="text-center p-4">
                <i class="ri-folder-open-line fs-2"></i>
                <p class="text-muted mb-0">Geen factuur beschikbaar</p>
            </div>
        @endif
    </div>
</div>
