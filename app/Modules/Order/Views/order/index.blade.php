@extends('frontend.app')
@section('title', 'Plaats een bestelling | Dienst selectie')
@section('description', 'Beheer hier uw dienstselecties, contactgegevens en bestellingsoverzicht.')
@section('content')

    <link rel="stylesheet" href="{{ asset('modules/order/css/order.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('modules/order/js/Order.js') }}" defer></script>

    <style>
        /* ... existing styles ... */

        .service-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            height: 100%;
            width: 100%;
            display: block;
        }

        .service-card:hover {
            border-color: var(--it-theme-1);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .service-card.active {
            background-color: var(--it-theme-1);
            border-color: var(--it-theme-1);
            color: white;
        }

        .service-card.active i,
        .service-card.active span {
            color: white;
        }

        .service-parent {
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .service-name {
            color: #2c3038;
        }

        .active .service-parent,
        .active .service-name {
            color: white;
        }

        /* ... rest of the existing styles ... */
    </style>

    <form id="orderForm" action="{{ route('order.submit') }}" method="POST">
        @csrf
        <section id="order-section" class="py-5">
            <div class="container">
                <!-- Progress Bar -->
                <div class="step-indicators">
                    <div class="step-indicator">
                        <div class="step-circle active" data-step="1">1</div>
                        <div class="step-label">Dienst</div>
                    </div>
                    <div class="step-indicator">
                        <div class="step-circle" data-step="2">2</div>
                        <div class="step-label">Informatie</div>
                    </div>
                    <div class="step-indicator">
                        <div class="step-circle" data-step="3">3</div>
                        <div class="step-label">Samenvatting</div>
                    </div>
                </div>

                <!-- Steps Container -->
                <div class="row justify-content-center">
                    <div class="col-12 col-lg-10">
                        <!-- Step 1: Service Selection -->
                        <div id="step1" class="step-content active" data-step="1">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white py-3">
                                    <h3 class="card-title h5 mb-0">Selecteer Uw Diensten</h3>
                                </div>
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show ps-4" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show ps-4" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show ps-4" role="alert">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <!-- Location Selection -->
                                    <div class="location-selection mb-4">
                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <label class="service-card" for="brussel">
                                                    <input type="radio" name="location" id="brussel" value="brussel" class="d-none">
                                                    <i class="fas fa-building"></i>
                                                    <span>Brussel</span>
                                                </label>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="service-card" for="vlaanderen">
                                                    <input type="radio" name="location" id="vlaanderen" value="vlaanderen" class="d-none">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <span>Vlaanderen</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Services Container -->
                                    <div id="servicesContainer" class="mb-4">
                                        <!-- Services will be loaded here -->
                                    </div>

                                    <!-- Sub-services Container -->
                                    <div class="sub-services-row row g-4">
                                        <!-- Sub-services will be loaded here -->
                                    </div>

                                    <!-- Summary Table -->
                                    <div class="summary-table mt-4" style="display: none;">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="mb-0 fw-normal">Geselecteerde Diensten</h5>
                                            </div>
                                            <div class="card-body">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Dienst</th>
                                                            <th class="text-center">Aantal</th>
                                                            <th class="text-end">Prijs</th>
                                                            <th class="text-end">Totaal</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="selectedServicesBody">
                                                        <!-- Services will be added here dynamically -->
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td> <input type="text" name="coupon_code"
                                                                    placeholder="Enter coupon code" class="border"
                                                                    maxlength="8" />

                                                                <div id="coupon-message"></div>
                                                            </td>
                                                            <td colspan="2" class="text-end fw-bold">Totaal:</td>
                                                            <td class="text-end fw-bold">
                                                                <span id="grandTotal">0.00</span>
                                                                {{-- <span>(Inc. BTW) </span> --}}
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Contact Details -->
                        <div id="step2" class="step-content" data-step="2">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-white py-3">
                                    <h3 class="card-title h5 mb-0 fw-normal">Contactgegevens</h3>
                                </div>
                                <div class="card-body">
                                    <div class="customer-info-section">
                                        <!-- Customer Information -->
                                        <h5 class="card-title mb-4 fw-normal">Klantgegevens</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Naam</label>
                                                <input type="text" class="form-control" name="name" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">E-mail</label>
                                                <input type="email" class="form-control" name="email" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Telefoon</label>
                                                <input type="tel" class="form-control" name="phone" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Bedrijfsnaam</label>
                                                <input type="text" class="form-control" name="company_name">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">BTW Nummer</label>
                                                <input type="text" class="form-control" name="vat_number">
                                                <small class="text-muted">Voer een geldig BTW nummer in</small>
                                            </div>
                                        </div>

                                        <!-- Delivery Address -->
                                        <h5 class="card-title mb-4 fw-normal mt-4">Keuringsadres</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Straat</label>
                                                <input type="text" class="form-control" name="street" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Nummer</label>
                                                <input type="text" class="form-control" name="number" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Bus</label>
                                                <input type="text" class="form-control" name="box">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Verdieping</label>
                                                <input type="text" class="form-control" name="floor">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Gemeente</label>
                                                <input type="text" class="form-control" name="city" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Postcode</label>
                                                <input type="text" class="form-control" name="postal_code" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Provincie</label>
                                                <select class="form-select" name="province" required>
                                                    <option value="">Selecteer provincie</option>
                                                    @if ($provincies->count() > 0)
                                                        @foreach ($provincies as $provincie)
                                                            <option value="{{ $provincie->id }}"
                                                                {{ old('data.province_id') == $provincie->id ? 'selected' : '' }}>
                                                                {{ $provincie->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Same Address Checkbox -->
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="sameAsDelivery"
                                                name="has_billing_address" checked value="0">
                                            <label class="form-check-label" for="sameAsDelivery">
                                                Factuuradres is hetzelfde als keuringsadres
                                            </label>
                                        </div>

                                        <!-- Invoice Address Section -->
                                        <div id="invoiceAddressSection" class="mt-4" style="display: none;">
                                            <h5 class="card-title mb-4 fw-normal">Factuuradres</h5>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Straat</label>
                                                    <input type="text" class="form-control" name="billing_street">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Huisnummer</label>
                                                    <input type="text" class="form-control"
                                                        name="billing_number">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Toevoeging</label>
                                                    <input type="text" class="form-control"
                                                        name="billing_box">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Postcode</label>
                                                    <input type="text" class="form-control"
                                                        name="billing_postal_code">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Plaats</label>
                                                    <input type="text" class="form-control" name="billing_city">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Summary -->
                        <div id="step3" class="step-content" data-step="3">
                            <div class="row g-4">
                                <!-- Services Summary Card -->
                                <div class="col-12">
                                    <div class="card shadow-sm">
                                        <div class="card-header bg-white py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-shopping-cart text-primary me-2"></i>
                                                <h5 class="mb-0 fw-normal">Geselecteerde Diensten</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="summaryServicesList">
                                                <!-- Services will be populated here -->
                                            </div>
                                            <div class="border-top mt-3 pt-3">
                                                <div class="d-none justify-content-between align-items-center mb-2"
                                                    id="summarySubtotalAmount">
                                                    <span class="h5 mb-0 fw-normal">Subtotaal:</span>
                                                    <span id="summarySubtotalAmountValue" class="h4 mb-0">0.00 €</span>
                                                </div>
                                                <div class="d-none justify-content-between align-items-center mb-2"
                                                    id="summary-discount">
                                                    <span class="h5 mb-0 fw-normal">Korting:</span>
                                                    <span id="summary-discountLabel" class="h4 mb-0 text-success"></span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="h5 mb-0 fw-normal">Totaalbedrag:</span>
                                                    <span id="summary-grandTotal" class="h4 mb-0 text-primary">0.00
                                                        €</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Details Card -->
                                <div class="col-md-6">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-header bg-white py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user text-primary me-2"></i>
                                                <h5 class="mb-0 fw-normal">Contactgegevens</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="summaryContactDetails">
                                                <!-- Contact details will be populated here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delivery Address Card -->
                                <div class="col-md-6">
                                    <div class="card shadow-sm h-100">
                                        <div class="card-header bg-white py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                <h5 class="mb-0 fw-normal">Keuringsadres</h5>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="summaryAddressDetails">
                                                <!-- Address details will be populated here -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4" id="navigation-buttons">
                            <button type="button" class="btn btn-light btn-prev">
                                <i class="fas fa-arrow-left me-2"></i>Vorige
                            </button>
                            <button type="button" class="btn btn-primary btn-next ms-auto">
                                Volgende<i class="fas fa-arrow-right ms-2"></i>
                            </button>
                            <button type="submit" class="btn btn-primary btn-submit ms-auto" style="display: none;">
                                Bestelling Afronden
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </form>
@endsection
