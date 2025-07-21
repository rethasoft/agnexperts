@extends('app.layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Dashboard</li>
                            <li class="breadcrumb-item"><a href="{{ route('employee.index') }}">Medewerker</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Bewerken</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">{{ $errors->first() }}</div>
    @endif
    @if ($msg = Session::get('msg'))
        <div class="alert alert-success mt-3">{{ $msg }}</div>
    @endif

    <div class="row mt-3">
        <div class="col-lg-12 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="basic-info-tab" data-bs-toggle="tab"
                                data-bs-target="#basic-info" type="button" role="tab" aria-controls="basic-info"
                                aria-selected="true">
                                <i class="ri-user-line me-1"></i> Basis Informatie
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents"
                                type="button" role="tab" aria-controls="documents" aria-selected="false">
                                <i class="ri-file-text-line me-1"></i> Documenten
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline"
                                type="button" role="tab" aria-controls="timeline" aria-selected="false">
                                <i class="ri-time-line"></i> Tijdlijn
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="performance-tab" data-bs-toggle="tab" data-bs-target="#performance"
                                type="button" role="tab" aria-controls="performance" aria-selected="false">
                                <i class="ri-bar-chart-line"></i> Prestaties
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="controlled-docs-tab" data-bs-toggle="tab"
                                data-bs-target="#controlled-docs" type="button" role="tab"
                                aria-controls="controlled-docs" aria-selected="false">
                                <i class="ri-file-check-line me-1"></i> Gecontroleerde Documenten
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="employeeTabsContent">
                        <!-- Basic Info Tab -->
                        <div class="tab-pane fade show active" id="basic-info" role="tabpanel"
                            aria-labelledby="basic-info-tab">
                            <form action="{{ route('employee.update', $employee->id) }}" method="POST" id="employee-form">
                                @csrf
                                @method('PUT')
                                <div class="row g-3">
                                    <!-- Personal Information Section -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <h6 class="mb-3 text-muted">Personal Information</h6>
                                                <div class="mb-3">
                                                    <label for="username" class="form-label">Naam</label>
                                                    <input type="text" id="username" name="data[name]"
                                                        class="form-control" autocomplete="off" required
                                                        value="{{ $employee->name }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="surname" class="form-label">Voornaam</label>
                                                    <input type="text" id="surname" name="data[surname]"
                                                        class="form-control" autocomplete="off" required
                                                        value="{{ $employee->surname }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="dob" class="form-label">Date of Birth</label>
                                                    <input type="date" id="dob" name="data[dob]"
                                                        class="form-control" value="{{ $employee->dob }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="gender" class="form-label">Gender</label>
                                                    <select id="gender" name="data[gender]" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="male"
                                                            {{ $employee->gender == 'male' ? 'selected' : '' }}>Male
                                                        </option>
                                                        <option value="female"
                                                            {{ $employee->gender == 'female' ? 'selected' : '' }}>Female
                                                        </option>
                                                        <option value="other"
                                                            {{ $employee->gender == 'other' ? 'selected' : '' }}>Other
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information Section -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <h6 class="mb-3 text-muted">Contact Information</h6>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" id="email" name="data[email]"
                                                        class="form-control" autocomplete="off" required
                                                        value="{{ $employee->email }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Telefoon</label>
                                                    <input type="text" id="phone" name="data[phone]"
                                                        class="form-control" autocomplete="off" required
                                                        value="{{ $employee->phone }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="emergency_contact" class="form-label">Emergency
                                                        Contact</label>
                                                    <input type="text" id="emergency_contact"
                                                        name="data[emergency_contact]" class="form-control"
                                                        value="{{ $employee->emergency_contact }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Adres</label>
                                                    <input type="text" id="address" name="data[address]"
                                                        class="form-control" autocomplete="off" required
                                                        value="{{ $employee->address }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Identification Section -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <h6 class="mb-3 text-muted">Identification</h6>
                                                <div class="mb-3">
                                                    <label for="national_id" class="form-label">National ID</label>
                                                    <input type="text" id="national_id" name="data[national_id]"
                                                        class="form-control" value="{{ $employee->national_id }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="password" class="form-label">Wachtwoord</label>
                                                    <input type="password" id="password" name="data[password]"
                                                        class="form-control" autocomplete="off"
                                                        placeholder="Laat leeg om hetzelfde wachtwoord te behouden">
                                                    <small class="text-muted">Alleen invullen als u het wachtwoord wilt
                                                        wijzigen</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Role Information Section -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <h6 class="mb-3 text-muted">Role Information</h6>
                                                <div class="mb-3">
                                                    <label for="Roles" class="form-label">Rollen</label>
                                                    <select name="data[role_id]" id="Roles" class="form-control"
                                                        autocomplete="off" required>
                                                        <option value="">Selecteren</option>
                                                        @if ($roles->count() > 0)
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}"
                                                                    {{ $employee->role_id == $role->id ? 'selected' : '' }}>
                                                                    {{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-12">
                                        <button class="btn btn-success" type="submit">Opslaan</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Documents Tab -->
                        <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                            <!-- Hidden input with employee ID for document uploads -->
                            <input type="hidden" id="employee-id" value="{{ $employee->id }}">
                            <!-- Alert container for notifications -->
                            <div id="alert-container" class="mb-3"></div>
                            @include('app.tenant.employee.documents', ['employee' => $employee])
                        </div>

                        <div class="tab-pane fade" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                            @include('app.tenant.employee.timeline')
                        </div>

                        <!-- Performance Tab -->
                        <div class="tab-pane fade" id="performance" role="tabpanel" aria-labelledby="performance-tab">
                            @include('app.tenant.employee.performance', ['metrics' => $metrics])
                        </div>

                        <!-- Controlled Documents Tab -->
                        <div class="tab-pane fade" id="controlled-docs" role="tabpanel"
                            aria-labelledby="controlled-docs-tab">
                            @include('app.tenant.employee.controlled_docs')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for document management -->
    <script src="{{ asset('js/employee-documents.js') }}"></script>
@endsection
