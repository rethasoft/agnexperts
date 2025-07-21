<div class="card border-0 shadow-sm rounded-3">
    <div class="card-body p-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
            <h5 class="mb-0 fw-semibold">Documentenbeheer</h5>
            <button type="button" class="btn btn-primary shadow-sm py-2 fw-medium" data-bs-toggle="modal"
                data-bs-target="#uploadDocumentModal">
                <i class="ri-upload-line"></i> Document Uploaden
            </button>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Documents Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Documentnaam</th>
                        <th>Type</th>
                        <th>Upload Datum</th>
                        {{-- <th>Vervaldatum</th>
                        <th>Status</th> --}}
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employee->documents as $document)
                        <tr>
                            <td>{{ $document->name }}</td>
                            <td>{{ ucfirst($document->document_type) }}</td>
                            <td>{{ $document->created_at->format('d-m-Y') }}</td>
                            {{-- <td>{{ $document->expiry_date ? date('d-m-Y', strtotime($document->expiry_date)) : 'N/A' }} --}}
                            </td>
                            {{-- <td>
                                @if (!$document->expiry_date)
                                    <span class="badge bg-success rounded-pill">Actief</span>
                                @elseif(strtotime($document->expiry_date) < time())
                                    <span class="badge bg-danger rounded-pill">Verlopen</span>
                                @elseif(strtotime($document->expiry_date) < strtotime('+30 days'))
                                    <span class="badge bg-warning rounded-pill">Bijna verlopen</span>
                                @else
                                    <span class="badge bg-success rounded-pill">Actief</span>
                                @endif
                            </td> --}}
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- View Document Button -->
                                    <a href="{{ route('documents.download', encrypt($document->id)) }}?view=true"
                                        class="btn btn-sm btn-outline-secondary" target="_blank">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('documents.download', encrypt($document->id)) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="ri-download-line"></i>
                                    </a>
                                    <form action="{{ route('documents.destroy', encrypt($document->id)) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Weet je zeker dat je dit document wilt verwijderen?')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <p class="text-muted mb-0">Geen documenten gevonden</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadDocumentModalLabel">Document Uploaden</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="documentUploadForm"
                    action="{{ route('documents.upload', ['modelType' => 'employee', 'modelId' => $employee->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Documentnaam</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="document_type" class="form-label">Document Type</label>
                        <select class="form-select" id="document_type" name="document_type" required>
                            <option value="">Selecteer Type</option>
                            <option value="contract">Contract</option>
                            <option value="certificate">Certificaat</option>
                            <option value="id">ID Document</option>
                            <option value="other">Overig</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Vervaldatum</label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                    </div>
                    <div class="mb-3">
                        <label for="document_file" class="form-label">Bestand Uploaden</label>
                        <input type="file" class="form-control" id="document_file" name="document_file"
                            accept=".pdf,.doc,.docx,.jpg,.png" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                        <button type="submit" class="btn btn-primary">Uploaden</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
