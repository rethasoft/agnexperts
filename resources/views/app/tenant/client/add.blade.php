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
                @include('app.tenant.client._form')
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
