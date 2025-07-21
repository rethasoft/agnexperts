@extends('app.layouts.app')
@section('title', 'Map aanmaken')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documenten</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Map aanmaken</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Nieuwe map aanmaken</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Mapnaam <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ $folder_name ?? old('name') }}" required>
                            <small class="text-muted">
                                <i class="ri-information-line me-1"></i>Je kunt submappen aanmaken door het '>' teken te
                                gebruiken.
                                Bijvoorbeeld: <strong>EPC > 2024 > certificates</strong> creëert een mapstructuur met
                                submappen.
                            </small>


                        </div>

                        <div class="mb-3">
                            <label for="files" class="form-label">Documenten uploaden</label>
                            <input type="file" class="form-control" id="files" name="document_files[]" multiple
                                accept=".pdf,.png,.jpg,.jpeg">
                            <small class="form-text text-muted">Optioneel: Upload meerdere documenten naar deze map.</small>
                            <div class="mt-1">
                                <small class="text-muted"><i class="ri-information-line me-1"></i>Toegestane
                                    bestandsformaten: PDF, PNG, JPEG (max. 10MB per bestand)</small>
                            </div>
                            <div id="selected-files-counter" class="mt-2"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('documents.index') }}" class="btn btn-light">Annuleren</a>
                            <button type="submit" class="btn btn-primary">Map aanmaken</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (isset($currentFolder))
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Huidige locatie</h5>
                        <p class="mb-0">
                            U maakt een map aan in:
                            <strong>
                                <i class="ri-folder-line text-warning me-1"></i>
                                {{ $currentFolder->name ?? 'Hoofdmap' }}
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        // Add validation and file counter
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            nameInput.focus();

            // Show selected file count
            const fileInput = document.getElementById('files');
            const fileCounter = document.getElementById('selected-files-counter');

            fileInput.addEventListener('change', function() {
                const fileCount = this.files.length;
                if (fileCount > 0) {
                    fileCounter.innerHTML =
                        `<span class="badge bg-info">${fileCount} bestand${fileCount !== 1 ? 'en' : ''} geselecteerd</span>`;
                } else {
                    fileCounter.innerHTML = '';
                }
            });
        });
    </script>
@endsection
