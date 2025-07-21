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
                            <li class="breadcrumb-item active" aria-current="page">Nieuw</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('employee.store') }}" method="POST">
        @csrf

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
                        <h5 class="mb-4">Basis Informatie</h5>
                        <div class="row g-3">
                            <!-- Personal Information Section -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h6 class="mb-3 text-muted">Personal Information</h6>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Naam</label>
                                            <input type="text" id="username" name="data[name]" class="form-control"
                                                autocomplete="off" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="surname" class="form-label">Voornaam</label>
                                            <input type="text" id="surname" name="data[surname]" class="form-control"
                                                autocomplete="off" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="dob" class="form-label">Date of Birth</label>
                                            <input type="date" id="dob" name="data[dob]" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select id="gender" name="data[gender]" class="form-control">
                                                <option value="">Select</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
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
                                            <input type="email" id="email" name="data[email]" class="form-control"
                                                autocomplete="off" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Telefoon</label>
                                            <input type="text" id="phone" name="data[phone]" class="form-control"
                                                autocomplete="off" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="emergency_contact" class="form-label">Emergency
                                                Contact</label>
                                            <input type="text" id="emergency_contact" name="data[emergency_contact]"
                                                class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Adres</label>
                                            <input type="text" id="address" name="data[address]" class="form-control"
                                                autocomplete="off" required>
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
                                                class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Wachtwoord</label>
                                            <input type="text" id="password" name="data[password]"
                                                class="form-control" autocomplete="off" required>
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
                                                        <option value="{{ $role->id }}">{{ $role->name }}
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
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
