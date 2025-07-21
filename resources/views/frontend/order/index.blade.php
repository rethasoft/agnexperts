@extends('frontend.app')
@section('content')

    <style>
        :root {
            --secondary-color: #6c757d;
            --success-color: #198754;
        }

        body {
            background-color: #f8f9fa;
        }

        .main-container {
            max-width: 1140px;
            margin: 10rem auto 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            padding: 2rem;
        }

        .progress {
            height: 0.5rem;
            border-radius: 1rem;
            background-color: rgba(62, 88, 121, 0.1);
        }

        .progress-bar {
            transition: width 0.6s ease;
        }

        .step-header {
            position: relative;
            padding-bottom: 1.5rem;
            margin-bottom: 2rem;
        }

        .step-header:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, var(--it-theme-1) 50%, transparent 50%);
        }

        .service-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            height: 100%;
        }

        .service-card:hover {
            border-color: var(--it-theme-1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .service-card.selected {
            background-color: var(-it-theme-1);
            border-color: var(--it-theme-1);
        }

        .service-card input[type="radio"],
        .service-card input[type="checkbox"] {
            display: none;
        }

        .service-card i {
            font-size: 1.5rem;
            margin-right: 1rem;
            color: var(--it-theme-1);
            transition: transform 0.3s ease;
        }

        .service-card:hover i {
            transform: scale(1.1);
        }

        .service-card .form-check-label {
            display: flex;
            align-items: center;
            margin: 0;
            cursor: pointer;
            font-weight: 500;
        }

        .summary-table {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .summary-header {
            background-color: #f8f9fa;
        }

        .summary-header h6 {
            font-size: 1.1rem;
            color: var(--it-theme-1);
        }

        .table {
            margin-bottom: 0;
        }

        .table> :not(caption)>*>* {
            padding: 0.75rem 1rem;
        }

        .table thead th {
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e9ecef;
            white-space: nowrap;
        }

        .service-row {
            transition: background-color 0.2s ease;
        }

        .service-row:hover {
            background-color: #f8f9fa;
        }

        .service-info {
            max-width: 300px;
        }

        .service-icon {
            width: 32px;
            height: 32px;
            min-width: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(-it-theme-1);
            border-radius: 8px;
        }

        .service-icon i {
            font-size: 1rem;
        }

        .service-name {
            font-weight: 500;
            color: #2c3038;
            font-size: 0.9375rem;
        }

        .quantity-control {
            width: 96px;
            height: 32px;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            display: flex;
            align-items: center;
            background: white;
        }

        .qty-btn {
            width: 28px;
            height: 28px;
            border: none;
            background: none;
            color: #6c757d;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .qty-btn:hover {
            color: var(--it-theme-1);
        }

        .qty-input {
            width: 40px;
            border: none;
            text-align: center;
            font-weight: 500;
            font-size: 0.875rem;
            color: #2c3038;
            padding: 0;
            background: transparent;
        }

        .qty-input::-webkit-inner-spin-button,
        .qty-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .qty-input:focus {
            outline: none;
        }

        .price,
        .total {
            font-weight: 500;
            color: #2c3038;
            font-size: 0.9375rem;
        }

        .remove-btn {
            width: 28px;
            height: 28px;
            min-width: 28px;
            border: none;
            background: none;
            border-radius: 6px;
            color: #dc3545;
            transition: all 0.2s ease;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-btn:hover {
            background-color: #fff5f5;
            transform: scale(1.1);
        }

        .total-row {
            background-color: #f8f9fa;
        }

        .nav-buttons {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-light {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }

        .btn-light:hover {
            background-color: #e9ecef;
        }

        .btn-primary {
            background-color: var(--it-theme-1);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
        }

        .step-title {
            background-color: var(--it-theme-1);
            color: white;
        }

        @media (max-width: 768px) {
            .main-container {
                margin: 0;
                border-radius: 0;
                padding: 1rem;
            }

            .service-card {
                padding: 1rem;
            }

            .service-card i {
                font-size: 1.25rem;
            }

            .btn {
                padding: 0.625rem 1.25rem;
            }

            .table> :not(caption)>*>* {
                padding: 0.625rem 0.5rem;
            }

            .service-icon {
                width: 28px;
                height: 28px;
                min-width: 28px;
            }

            .service-name {
                font-size: 0.875rem;
            }

            .quantity-control {
                width: 88px;
                height: 28px;
            }

            .qty-btn {
                width: 24px;
                height: 24px;
                font-size: 0.875rem;
            }

            .qty-input {
                width: 36px;
                font-size: 0.8125rem;
            }

            .price,
            .total {
                font-size: 0.875rem;
            }
        }

        .step-content {
            transition: opacity 0.3s ease-in-out;
        }

        .step-content.active {
            opacity: 1;
        }

        .step-content:not(.active) {
            opacity: 0;
        }

        .cursor-pointer {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }

        .cursor-pointer:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15);
        }

        .card.border-primary {
            border-width: 2px !important;
        }

        .form-check-input {
            cursor: pointer;
        }

        .form-check-label {
            cursor: pointer;
        }

        #invoiceAddressSection {
            transition: opacity 0.15s ease-in-out;
        }

        #invoiceAddressSection.fade {
            opacity: 0;
        }

        #invoiceAddressSection:not(.fade) {
            opacity: 1;
        }

        /* Block: Invoice Form */
        .invoice-form {
            transition: opacity 0.15s ease-in-out, margin 0.15s ease-in-out;
            opacity: 1;
            margin-top: 1.5rem;
        }

        .invoice-form--fade {
            opacity: 0;
        }

        .invoice-form--hidden {
            display: none;
            margin-top: 0;
        }
    </style>
</head>

<body>

    <div class="main-container">
        <!-- Progress bar -->
        <div class="progress mb-4">
            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <!-- Steps Container -->
        <div class="steps-container">
            <!-- Step 1: Service Selection -->
            <div id="step1" class="step-content active">
                <div class="step-header">
                    <h4 class="mb-0 d-flex align-items-center">
                        <span class="badge step-title rounded-pill me-3">1</span>
                        Service Selection
                    </h4>
                </div>

                <!-- City Selection -->
                <div class="mb-5">
                    <h6 class="text-secondary mb-3">Select Location</h6>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="service-card">
                                <input type="radio" name="city" id="brussel" value="brussel">
                                <label class="form-check-label w-100" for="brussel">
                                    <i class="fas fa-building"></i>
                                    <span>Brussel</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="service-card">
                                <input type="radio" name="city" id="vlaanderen" value="vlaanderen">
                                <label class="form-check-label w-100" for="vlaanderen">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>Vlaanderen</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Services -->
                <div class="mb-5">
                    <h6 class="text-secondary mb-3">Select Service</h6>
                    <div class="row g-4" id="servicesContainer">
                        <!-- Services will be loaded here dynamically -->
                    </div>
                </div>

                <!-- Sub Services -->
                <div class="mb-5">
                    <h6 class="text-secondary mb-3">Select Sub Services</h6>
                    <div class="row g-4" id="subServicesContainer">
                        <!-- Sub services will be loaded here dynamically -->
                    </div>
                </div>

                <!-- Selected Services Summary -->
                <div class="summary-table mt-5">
                    <div class="summary-header p-4 border-bottom">
                        <h6 class="mb-0 d-flex align-items-center">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Selected Services
                        </h6>
                    </div>
                    <div class="summary-content p-4">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 50%">Service</th>
                                        <th scope="col" class="text-center" style="width: 15%">Qty</th>
                                        <th scope="col" class="text-end" style="width: 15%">Price</th>
                                        <th scope="col" class="text-end" style="width: 15%">Total</th>
                                        <th scope="col" style="width: 5%"></th>
                                    </tr>
                                </thead>
                                <tbody id="selectedServices">
                                    <tr class="service-row">
                                        <td>
                                            <div class="service-info d-flex align-items-center gap-3">
                                                <div class="service-icon">
                                                    <i class="fas fa-house-user"></i>
                                                </div>
                                                <div class="service-name">Residential EPC</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="quantity-control d-flex align-items-center justify-content-center">
                                                <button class="qty-btn minus">-</button>
                                                <input type="number" class="qty-input" value="1" min="1" max="99">
                                                <button class="qty-btn plus">+</button>
                                            </div>
                                        </td>
                                        <td class="text-end price">€150</td>
                                        <td class="text-end total">€150</td>
                                        <td>
                                            <button class="remove-btn" aria-label="Remove item">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="service-row">
                                        <td>
                                            <div class="service-info d-flex align-items-center gap-3">
                                                <div class="service-icon">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                                <div class="service-name">Commercial EPC</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="quantity-control d-flex align-items-center justify-content-center">
                                                <button class="qty-btn minus">-</button>
                                                <input type="number" class="qty-input" value="2" min="1" max="99">
                                                <button class="qty-btn plus">+</button>
                                            </div>
                                        </td>
                                        <td class="text-end price">€250</td>
                                        <td class="text-end total">€500</td>
                                        <td>
                                            <button class="remove-btn" aria-label="Remove item">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="total-row">
                                        <td colspan="3" class="text-end">
                                            <strong class="text-secondary">Total Amount:</strong>
                                        </td>
                                        <td class="text-end">
                                            <strong class=fs-5">€650</strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="nav-buttons">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-light" id="prevBtn">
                            <i class="fas fa-arrow-left me-2"></i>Previous
                        </button>
                        <button class="btn btn-primary" id="nextBtn">
                            Next<i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 2: Contact Details -->
            <div id="step2" class="step-content d-none">
                <div class="step-header">
                    <h4 class="mb-0 d-flex align-items-center">
                        <span class="badge step-title rounded-pill me-3">2</span>
                        Contact details
                    </h4>
                </div>

                <div class="row g-4 mt-3">
                    <!-- Personal Information -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Full name</label>
                            <input type="text" class="form-control" id="fullName" autocapitalize="off" style="text-transform: none;" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" autocapitalize="off" style="text-transform: none;" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Phone number</label>
                            <input type="tel" class="form-control" id="phone" autocapitalize="off" style="text-transform: none;" required>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="col-12">
                        <h6 class="mb-3">Delivery address</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Street</label>
                            <input type="text" class="form-control" id="street" autocapitalize="off" style="text-transform: none;" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label class="form-label">Number</label>
                            <input type="text" class="form-control" id="houseNumber" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Box (optional)</label>
                            <input type="text" class="form-control" id="box">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Postal code</label>
                            <input type="text" class="form-control" id="postalCode" required>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" id="city" autocapitalize="off" style="text-transform: none;" required>
                        </div>
                    </div>

                    <!-- Invoice Address -->
                    <div class="col-12 mt-4">
                        <div class="form-check">
                            <input class="form-check-input p-0"
                                type="checkbox"
                                id="sameAsDelivery">
                            <label class="form-check-label" for="sameAsDelivery">
                                Invoice address is the same as delivery address
                            </label>
                        </div>
                    </div>

                    <div id="invoiceAddressSection" class="col-12">
                        <div class="row g-4">
                            <div class="col-12">
                                <h6 class="mb-3">Invoice address</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Company name (optional)</label>
                                    <input type="text" class="form-control" id="companyName" autocapitalize="off" style="text-transform: none;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">VAT number (optional)</label>
                                    <input type="text" class="form-control" id="vatNumber">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Street</label>
                                    <input type="text" class="form-control invoice-input" id="invoiceStreet" autocapitalize="off" style="text-transform: none;">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="form-label">Number</label>
                                    <input type="text" class="form-control invoice-input" id="invoiceHouseNumber">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Box (optional)</label>
                                    <input type="text" class="form-control invoice-input" id="invoiceBox">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">Postal code</label>
                                    <input type="text" class="form-control invoice-input" id="invoicePostalCode">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control invoice-input" id="invoiceCity" autocapitalize="off" style="text-transform: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="nav-buttons">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-light" id="prevBtn">
                            <i class="fas fa-arrow-left me-2"></i>Previous
                        </button>
                        <button class="btn btn-primary" id="nextBtn">
                            Next<i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Summary -->
            <div id="step3" class="step-content d-none">
                <div class="step-header">
                    <h4 class="mb-0 d-flex align-items-center">
                        <span class="badge step-title rounded-pill me-3">3</span>
                        Order Summary
                    </h4>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="mb-0 text-primary">Selected Services</h6>
                            <span class="badge bg-primary rounded-pill">1 item</span>
                        </div>
                        
                        <div class="selected-service mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="service-icon rounded-circle bg-light p-3 me-3">
                                        <i class="fas fa-broom text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1" id="summaryServiceName">Service Name</h6>
                                        <p class="text-muted small mb-0" id="summaryLocationName">Location</p>
                                    </div>
                                </div>
                                <h6 class="mb-0">€150</h6>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-2">Total Amount</p>
                                <h4 class="mb-0 text-primary">€150</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="mb-0 text-primary">Contact Information</h6>
                            <button class="btn btn-outline-primary btn-sm" id="editDetailsBtn">
                                <i class="fas fa-edit me-2"></i>Edit Details
                            </button>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <p class="text-muted small mb-1">Full Name</p>
                                    <p class="mb-0" id="summaryFullName"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <p class="text-muted small mb-1">Email Address</p>
                                    <p class="mb-0" id="summaryEmail"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <p class="text-muted small mb-1">Phone Number</p>
                                    <p class="mb-0" id="summaryPhone"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <p class="text-muted small mb-1">Address</p>
                                    <p class="mb-0" id="summaryAddress"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <p class="text-muted small mb-1">City</p>
                                    <p class="mb-0" id="summaryCity"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="contact-info">
                                    <p class="text-muted small mb-1">Postal Code</p>
                                    <p class="mb-0" id="summaryPostalCode"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS and its dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables
            const stepContents = document.querySelectorAll('.step-content');
            const totalSteps = stepContents.length;
            let currentStep = 1;
            const sameAsDeliveryCheckbox = document.getElementById('sameAsDelivery');
            const invoiceAddressSection = document.getElementById('invoiceAddressSection');
            const serviceCards = document.querySelectorAll('.service-card');

            // Functions
            function updateStepUI() {
                // Progress bar update
                const progress = ((currentStep - 1) / (totalSteps - 1)) * 100;
                document.querySelector('.progress-bar').style.width = `${progress}%`;

                // Show/hide steps
                stepContents.forEach((step, index) => {
                    if (index + 1 === currentStep) {
                        // Make step visible
                        step.classList.remove('d-none');
                        step.classList.add('active');
                        step.style.opacity = '1';
                        step.style.display = 'block';
                        console.log('Making step visible:', index + 1);
                    } else {
                        // Hide step
                        step.classList.add('d-none');
                        step.classList.remove('active');
                        step.style.opacity = '0';
                        step.style.display = 'none';
                        console.log('Hiding step:', index + 1);
                    }
                });

                // Debug info
                console.log('Current step:', currentStep);
                console.log('Step content:', document.querySelector(`#step${currentStep}`).innerHTML);

                // Update button states
                updateButtonStates();
            }

            function updateButtonStates() {
                const currentStepElement = document.querySelector(`#step${currentStep}`);
                const prevBtn = currentStepElement.querySelector('.btn-light');
                const nextBtn = currentStepElement.querySelector('.btn-primary, .btn-success');

                if (prevBtn) {
                    prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
                }

                if (nextBtn) {
                    if (currentStep === totalSteps) {
                        nextBtn.textContent = 'Confirm Order';
                        nextBtn.classList.remove('btn-primary');
                        nextBtn.classList.add('btn-success');
                    } else {
                        nextBtn.textContent = 'Next';
                        nextBtn.classList.add('btn-primary');
                        nextBtn.classList.remove('btn-success');
                    }
                }
            }

            // Handle next button click
            function handleNextClick(e) {
                e.preventDefault();
                
                if (!validateStep()) {
                    return;
                }

                if (currentStep < totalSteps) {
                    currentStep++;
                    if (currentStep === 3) {
                        updateConfirmationStep();
                    }
                    updateStepUI();
                }
            }

            // Handle previous button click
            function handlePrevClick(e) {
                e.preventDefault();
                if (currentStep > 1) {
                    currentStep--;
                    updateStepUI();
                }
            }

            // Add click handlers for all next and previous buttons
            stepContents.forEach(stepContent => {
                const nextBtn = stepContent.querySelector('.btn-primary, .btn-success');
                const prevBtn = stepContent.querySelector('.btn-light');

                if (nextBtn) {
                    nextBtn.addEventListener('click', handleNextClick);
                }

                if (prevBtn) {
                    prevBtn.addEventListener('click', handlePrevClick);
                }
            });

            // Validate current step
            function validateStep() {
                switch(currentStep) {
                    case 1:
                        const locationSelected = document.querySelector('input[name="city"]:checked');
                        const serviceSelected = document.querySelector('input[name="service"]:checked');
                        if (!locationSelected || !serviceSelected) {
                            alert('Please select both location and service');
                            return false;
                        }
                        return true;
                    
                    case 2:
                        const requiredFields = ['fullName', 'email', 'phone', 'street', 'houseNumber', 'postalCode', 'city'];
                        const isValid = requiredFields.every(field => {
                            const input = document.getElementById(field);
                            if (!input || !input.value.trim()) {
                                if (input) input.classList.add('is-invalid');
                                return false;
                            }
                            input.classList.remove('is-invalid');
                            return true;
                        });
                        
                        if (!isValid) {
                            alert('Please fill in all required fields');
                            return false;
                        }
                        return true;
                    
                    case 3:
                        return true;

                    default:
                        return false;
                }
            }

            // Update confirmation step content
            function updateConfirmationStep() {
                try {
                    console.log('Starting updateConfirmationStep');

                    // 1. Get and update service selection
                    const selectedService = document.querySelector('input[name="service"]:checked');
                    console.log('Selected Service:', selectedService);

                    if (selectedService) {
                        const serviceCard = selectedService.closest('.service-card');
                        console.log('Service Card:', serviceCard);
                        
                        const serviceName = serviceCard?.querySelector('.service-name')?.textContent;
                        console.log('Service Name:', serviceName);
                        
                        if (document.getElementById('summaryServiceName')) {
                            document.getElementById('summaryServiceName').textContent = serviceName || 'No service selected';
                        }
                    }

                    // 2. Get and update location selection
                    const selectedLocation = document.querySelector('input[name="city"]:checked');
                    console.log('Selected Location:', selectedLocation);

                    if (selectedLocation) {
                        const locationName = selectedLocation.value || 
                                           selectedLocation.nextElementSibling?.querySelector('h6')?.textContent || 
                                           'No location selected';
                        console.log('Location Name:', locationName);
                        
                        if (document.getElementById('summaryLocationName')) {
                            document.getElementById('summaryLocationName').textContent = locationName;
                        }
                    }

                    // 3. Update contact details
                    document.getElementById('summaryFullName').textContent = document.getElementById('fullName')?.value || '-';
                    document.getElementById('summaryEmail').textContent = document.getElementById('email')?.value || '-';
                    document.getElementById('summaryPhone').textContent = document.getElementById('phone')?.value || '-';
                    document.getElementById('summaryAddress').textContent = 
                        `${document.getElementById('street')?.value || ''} ${document.getElementById('houseNumber')?.value || ''}`;
                    document.getElementById('summaryCity').textContent = document.getElementById('city')?.value || '-';
                    document.getElementById('summaryPostalCode').textContent = document.getElementById('postalCode')?.value || '-';

                    // 4. Show contact summary
                    const contactSummary = document.getElementById('contactSummary');
                    if (contactSummary) {
                        contactSummary.classList.remove('d-none');
                    }

                    // 5. Add event listeners for buttons
                    const editDetailsBtn = document.getElementById('editDetailsBtn');
                    if (editDetailsBtn) {
                        editDetailsBtn.onclick = function() {

                            console.log('Editing details');
                            goToStep(2);
                        };
                    }

                    const submitOrderBtn = document.getElementById('submitOrderBtn');
                    if (submitOrderBtn) {
                        submitOrderBtn.onclick = function() {
                            this.disabled = true;
                            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
                            
                            setTimeout(() => {
                                alert('Order submitted successfully!');
                                this.innerHTML = '<i class="fas fa-check-circle me-2"></i>Order Created';
                            }, 1000);
                        };
                    }

                } catch (error) {
                    console.error('Error in updateConfirmationStep:', error);
                }
            }

            // Add this function if it doesn't exist
            function goToStep(step) {
                currentStep = step;
                updateStepUI();
            }

            // Event Listeners
            if (sameAsDeliveryCheckbox && invoiceAddressSection) {
                sameAsDeliveryCheckbox.addEventListener('click', function() {
                    if (this.checked) {
                        invoiceAddressSection.style.display = 'none';
                        copyDeliveryAddress();
                    } else {
                        invoiceAddressSection.style.display = 'block';
                        clearInvoiceFields();
                    }
                });
            }

            // Service card selection
            if (serviceCards.length > 0) {
                serviceCards.forEach(card => {
                    const radio = card.querySelector('input[type="radio"]');
                    if (radio) {
                        card.addEventListener('click', function(e) {
                            if (!e.target.closest('.btn')) {
                                radio.checked = true;
                                updateCardSelection(radio);
                            }
                        });

                        radio.addEventListener('change', function() {
                            updateCardSelection(this);
                        });
                    }
                });
            }

            // Helper Functions
            function copyDeliveryAddress() {
                document.getElementById('invoiceStreet').value = document.getElementById('street').value;
                document.getElementById('invoiceHouseNumber').value = document.getElementById('houseNumber').value;
                document.getElementById('invoiceBox').value = document.getElementById('box').value;
                document.getElementById('invoicePostalCode').value = document.getElementById('postalCode').value;
                document.getElementById('invoiceCity').value = document.getElementById('city').value;
            }

            function clearInvoiceFields() {
                const invoiceInputs = document.querySelectorAll('.invoice-input');
                invoiceInputs.forEach(input => {
                    input.value = '';
                });
            }

            function updateCardSelection(input) {
                if (input.type === 'radio') {
                    const name = input.getAttribute('name');
                    document.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
                        radio.closest('.service-card').classList.remove('selected');
                    });
                }
                input.closest('.service-card').classList.toggle('selected', input.checked);
            }

            // Service template function
            function createServiceCard(service) {
                return `
                    <div class="col-md-4">
                        <div class="service-card">
                            <input type="radio" name="service" id="service${service.id}" value="${service.id}">
                            <label class="form-check-label w-100" for="service${service.id}">
                                <i class="${service.icon}"></i>
                                <span>${service.name}</span>
                            </label>
                        </div>
                    </div>
                `;
            }

            // Sub-service template function
            function createSubServiceCard(subService) {
                return `
                    <div class="col-md-4">
                        <div class="service-card">
                            <input type="checkbox" id="subService${subService.id}" value="${subService.id}">
                            <label class="form-check-label w-100" for="subService${subService.id}">
                                <i class="${subService.icon}"></i>
                                <span>${subService.name}</span>
                            </label>
                        </div>
                    </div>
                `;
            }

            // Load services when location is selected
            document.querySelectorAll('input[name="city"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const city = this.value;
                    
                    // Clear previous services and sub-services
                    document.getElementById('servicesContainer').innerHTML = '';
                    document.getElementById('subServicesContainer').innerHTML = '';
                    
                    // Show loading state
                    document.getElementById('servicesContainer').innerHTML = 
                        '<div class="col-12 text-center"><div class="spinner-border text-primary"></div></div>';

                    // Fetch services for selected city
                    fetch(`/get-services/${city}`)
                        .then(response => response.json())
                        .then(services => {
                            document.getElementById('servicesContainer').innerHTML = 
                                services.map(service => createServiceCard(service)).join('');
                            
                            // Add event listeners to new service cards
                            attachServiceListeners();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            document.getElementById('servicesContainer').innerHTML = 
                                '<div class="col-12 text-center text-danger">Error loading services</div>';
                        });
                });
            });

            // Function to attach listeners to service cards
            function attachServiceListeners() {
                document.querySelectorAll('input[name="service"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const serviceId = this.value;
                        
                        // Clear previous sub-services
                        document.getElementById('subServicesContainer').innerHTML = '';
                        
                        // Show loading state
                        document.getElementById('subServicesContainer').innerHTML = 
                            '<div class="col-12 text-center"><div class="spinner-border text-primary"></div></div>';

                        // Fetch sub-services for selected service
                        fetch(`/get-sub-services/${serviceId}`)
                            .then(response => response.json())
                            .then(subServices => {
                                document.getElementById('subServicesContainer').innerHTML = 
                                    subServices.map(subService => createSubServiceCard(subService)).join('');
                                
                                // Reattach card selection handlers
                                initializeServiceCards();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                document.getElementById('subServicesContainer').innerHTML = 
                                    '<div class="col-12 text-center text-danger">Error loading sub-services</div>';
                            });
                    });
                });
            }

            // Function to initialize service cards (from your existing code)
            function initializeServiceCards() {
                const serviceCards = document.querySelectorAll('.service-card');
                serviceCards.forEach(card => {
                    const input = card.querySelector('input');
                    if (input) {
                        card.addEventListener('click', function(e) {
                            if (!e.target.closest('.btn')) {
                                input.checked = !input.checked;
                                updateCardSelection(input);
                            }
                        });

                        input.addEventListener('change', function() {
                            updateCardSelection(this);
                        });
                    }
                });
            }

            // Initialize
            updateStepUI();
        });
    </script>
@endsection