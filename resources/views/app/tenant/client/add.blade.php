@extends('app.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 bg-white shadow-sm">
                        <li class="breadcrumb-item">Dashboard</li>
                        <li class="breadcrumb-item"><a href="{{ route('client.index') }}">Client</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nieuw</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Form Section -->
        <form action="{{ route('client.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <!-- Alerts -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($msg = Session::get('msg'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $msg }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Main Content -->
            <div class="row g-4">
                <!-- Personal Information Card -->
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-primary">
                        <div class="card-header bg-light d-flex align-items-center">
                            <h5 class="card-title mb-0"><i class="ri-user-line me-2"></i>Persoonlijke Informatie</h5>
                            <button class="btn btn-link ms-auto" type="button" data-bs-toggle="collapse"
                                data-bs-target="#personalInfoCollapse">
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                        </div>
                        <div class="card-body collapse show" id="personalInfoCollapse">
                            <div class="row g-3">
                                <!-- Company Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label">Bedrijfsnaam</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-building-line"></i></span>
                                        <input type="text" id="company_name" name="data[company_name]"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="btw_number" class="form-label">BTW Nummer</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-file-text-line"></i></span>
                                        <input type="text" id="btw_number" name="data[btw_number]" class="form-control">
                                    </div>
                                </div>
                               
                                <!-- Personal Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">Naam <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-user-line"></i></span>
                                        <input type="text" id="username" name="data[name]" class="form-control"
                                            required>
                                    </div>
                                    <div class="invalid-feedback">Voer een naam in</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="surname" class="form-label">Voornaam <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-user-line"></i></span>
                                        <input type="text" id="surname" name="data[surname]" class="form-control"
                                            required>
                                    </div>
                                    <div class="invalid-feedback">Voer een voornaam in</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                        <input type="email" id="email" name="data[email]" class="form-control"
                                            required>
                                    </div>
                                    <div class="invalid-feedback">Voer een geldig e-mailadres in</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Telefoon <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                        <input type="text" id="phone" name="data[phone]" class="form-control"
                                            required>
                                    </div>
                                    <div class="invalid-feedback">Voer een telefoonnummer in</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact_person" class="form-label">Contactpersoon</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-contacts-line"></i></span>
                                        <input type="text" id="contact_person" name="data[contact_person]"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="industry" class="form-label">Branche</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-briefcase-line"></i></span>
                                        <select id="industry" name="data[industry]" class="form-control">
                                            <option value="">Selecteer branche</option>
                                            <option value="makelaar">Makelaar</option>
                                            <option value="architect">Architect</option>
                                            <option value="bouw">Bouw</option>
                                            <option value="notaris">Notaris</option>
                                            <option value="andere">Andere</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="password" class="form-label">Wachtwoord <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-lock-line"></i></span>
                                        <input type="password" id="password" name="data[password]" class="form-control"
                                            required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="ri-eye-line"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">Voer een wachtwoord in</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information Card -->
                <div class="col-lg-6">
                    <div class="card h-100 shadow-sm border-success">
                        <div class="card-header bg-light d-flex align-items-center">
                            <h5 class="card-title mb-0"><i class="ri-map-pin-line me-2"></i>Adres Informatie</h5>
                            <button class="btn btn-link ms-auto" type="button" data-bs-toggle="collapse"
                                data-bs-target="#addressInfoCollapse">
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                        </div>
                        <div class="card-body collapse show" id="addressInfoCollapse">
                            <div class="row g-3">
                                <!-- Normal Address Fields -->
                                <div class="col-md-12 mb-3">
                                    <label for="street" class="form-label">Straat <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-road-map-line"></i></span>
                                        <input type="text" id="street" name="data[address][street]"
                                            class="form-control" required>
                                    </div>
                                    <div class="invalid-feedback">Voer een straatnaam in</div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="house_number" class="form-label">Huisnummer <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-numbers-line"></i></span>
                                        <input type="text" id="house_number" name="data[address][house_number]"
                                            class="form-control" required>
                                    </div>
                                    <div class="invalid-feedback">Voer een huisnummer in</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="house_number_addition" class="form-label">Toevoeging</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-add-line"></i></span>
                                        <input type="text" id="house_number_addition"
                                            name="data[address][house_number_addition]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label for="city" class="form-label">Plaats <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-building-2-line"></i></span>
                                        <input type="text" id="city" name="data[address][city]"
                                            class="form-control" required>
                                    </div>
                                    <div class="invalid-feedback">Voer een plaatsnaam in</div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="postal_code" class="form-label">Postcode <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                        <input type="text" id="postal_code" name="data[address][postal_code]"
                                            class="form-control" required>
                                    </div>
                                    <div class="invalid-feedback">Voer een postcode in</div>
                                </div>
                                <!-- Billing Address Section -->
                                <div class="col-12 mt-4 pt-3 border-top">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="same_billing_address"
                                            checked>
                                        <label class="form-check-label" for="same_billing_address">
                                            Factuuradres is hetzelfde als bovenstaand adres
                                        </label>
                                    </div>
                                    <div id="billing_address_section" class="row g-3 d-none">
                                        <div class="col-md-12 mb-3">
                                            <label for="billing_street" class="form-label">Straat</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ri-road-map-line"></i></span>
                                                <input type="text" id="billing_street"
                                                    name="data[billing_address][street]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label for="billing_house_number" class="form-label">Huisnummer</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ri-numbers-line"></i></span>
                                                <input type="text" id="billing_house_number"
                                                    name="data[billing_address][house_number]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="billing_house_number_addition"
                                                class="form-label">Toevoeging</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ri-add-line"></i></span>
                                                <input type="text" id="billing_house_number_addition"
                                                    name="data[billing_address][house_number_addition]"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <label for="billing_city" class="form-label">Plaats</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ri-building-2-line"></i></span>
                                                <input type="text" id="billing_city"
                                                    name="data[billing_address][city]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="billing_postal_code" class="form-label">Postcode</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                                <input type="text" id="billing_postal_code"
                                                    name="data[billing_address][postal_code]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div class="col-12">
                    <div class="card shadow-sm border-secondary">
                        <div class="card-header bg-light d-flex align-items-center">
                            <h5 class="card-title mb-0"><i class="ri-edit-line me-2"></i>Notities</h5>
                            <button class="btn btn-link ms-auto" type="button" data-bs-toggle="collapse"
                                data-bs-target="#notesCollapse">
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                        </div>
                        <div class="card-body collapse show" id="notesCollapse">
                            <div class="mb-3">
                                <label for="internal_notes" class="form-label">Interne Notities</label>
                                <textarea id="internal_notes" name="data[internal_notes]" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="client_notes" class="form-label">Klant Notities</label>
                                <textarea id="client_notes" name="data[client_notes]" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="col-12 text-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="bi bi-save me-2"></i>Opslaan
                    </button>
                    <button type="reset" class="btn btn-outline-secondary btn-lg ms-2 px-5">
                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Billing Address Toggle
        document.getElementById('same_billing_address').addEventListener('change', function() {
            const billingSection = document.getElementById('billing_address_section');
            const billingInputs = billingSection.querySelectorAll('input[type="text"]');

            if (this.checked) {
                billingSection.classList.add('d-none');
                billingInputs.forEach(input => input.removeAttribute('required'));
            } else {
                billingSection.classList.remove('d-none');
                billingInputs.forEach(input => input.setAttribute('required', ''));
            }
        });

        // Password Visibility Toggle
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });

        // Form Validation
        (function() {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
@endpush
