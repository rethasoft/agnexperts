@extends('app.layouts.app')
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb & Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">Dashboard</li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route($guard . '.inspections.index') }}" class="text-decoration-none">
                                            Keuringen
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">#{{ $inspection->file_id }}</li>
                                </ol>
                            </nav>
                            <div class="d-flex gap-2">
                                <a href="{{ route($guard . '.inspections.edit', $inspection->id) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="ri-pencil-line me-1"></i>
                                    Bewerken
                                </a>
                                <a href="{{ route($guard . '.inspections.index') }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="ri-arrow-left-line me-1"></i>
                                    Terug
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Overview -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm bg-white">
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Status -->
                            <div class="col-md-3">
                                <div class="d-flex align-items-center p-3 rounded-3"
                                    style="background: {{ $inspection->status?->color ?? '#e3e6f0' }}15">
                                    <div class="me-3">
                                        <span class="badge p-2" style="background: {{ $inspection->status?->color ?? '#e3e6f0' }}">
                                            <i class="ri-checkbox-circle-line ri-lg"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Status</small>
                                        <strong>{{ $inspection->status?->name ?? 'Bilinmiyor' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Inspection Date -->
                            <div class="col-md-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="me-3">
                                        <span class="badge bg-primary p-2">
                                            <i class="ri-calendar-2-line ri-lg"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Datum plaatsbezoek</small>
                                        <strong>{{ $inspection->inspection_date ?? 'Niet gepland' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Employee -->
                            <div class="col-md-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="me-3">
                                        <span class="badge bg-info p-2">
                                            <i class="ri-user-line ri-lg"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Medewerker</small>
                                        <strong>{{ $inspection->employee?->name ?? 'Niet toegewezen' }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Amount -->
                            <div class="col-md-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                    <div class="me-3">
                                        <span class="badge bg-success p-2">
                                            <i class="ri-money-euro-circle-line ri-lg"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Totaal bedrag</small>
                                        <strong>€{{ number_format($inspection->total, 2, ',', '.') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Left Column - Primary Information -->
            <div class="col-lg-8">
                <!-- Client Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-user-line me-2"></i>Klantgegevens
                        </h5>
                        <button class="btn btn-link btn-sm p-0 text-muted" type="button" data-bs-toggle="collapse"
                            data-bs-target="#clientInfo">
                            <i class="ri-arrow-down-s-line ri-lg"></i>
                        </button>
                    </div>
                    <div class="collapse show" id="clientInfo">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Naam</label>
                                    <input type="text" class="form-control" value="{{ $inspection->name }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">E-mailadres</label>
                                    <input type="email" class="form-control" value="{{ $inspection->email }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Telefoonnummer</label>
                                    <input type="text" class="form-control" value="{{ $inspection->phone }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Provincie</label>
                                    <input type="text" class="form-control" value="{{ $inspection->province }}"
                                        readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Bedrijfsnaam</label>
                                    <input type="text" class="form-control" value="{{ $inspection->company_name }}"
                                        readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">BTW nummer</label>
                                    <input type="text" class="form-control" value="{{ $inspection->vat_number }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Info -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ri-map-pin-line me-2"></i>Adresgegevens
                        </h5>
                        <button class="btn btn-link btn-sm p-0 text-muted" type="button" data-bs-toggle="collapse"
                            data-bs-target="#addressInfo">
                            <i class="ri-arrow-down-s-line ri-lg"></i>
                        </button>
                    </div>
                    <div class="collapse show" id="addressInfo">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Straat</label>
                                    <input type="text" class="form-control" value="{{ $inspection->street }}"
                                        readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Nummer</label>
                                    <input type="number" class="form-control" value="{{ $inspection->number }}"
                                        readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Bus</label>
                                    <input type="number" class="form-control" value="{{ $inspection->box }}" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Postcode</label>
                                    <input type="text" class="form-control" value="{{ $inspection->postal_code }}"
                                        readonly>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Gemeente</label>
                                    <input type="text" class="form-control" value="{{ $inspection->city }}" readonly>
                                </div>

                                <!-- Billing Address Toggle -->
                                @if ($inspection->has_billing_address)
                                    <div class="col-12 mt-4">
                                        <div class="form-check">
                                            <input type="hidden" name="has_billing_address" value="0">
                                            <input class="form-check-input" type="checkbox" id="differentBillingAddress"
                                                name="has_billing_address" value="1" data-bs-toggle="collapse"
                                                data-bs-target="#billingAddressSection"
                                                {{ $inspection->has_billing_address ? 'checked' : '' }}>
                                            <label class="form-check-label" for="differentBillingAddress">
                                                Factuuradres verschilt van bovenstaand adres
                                            </label>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing Address Section -->
                @if ($inspection->has_billing_address)
                    <div class="collapse show" id="billingAddressSection">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="card-title mb-0">
                                    <i class="ri-bill-line me-2"></i>Factuuradres
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Straat</label>
                                        <input type="text" name="billing_street" class="form-control"
                                            value="{{ $inspection->billing_street }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Nummer</label>
                                        <input type="number" name="billing_number" class="form-control"
                                            value="{{ $inspection->billing_number }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Bus</label>
                                        <input type="number" name="billing_box" class="form-control"
                                            value="{{ $inspection->billing_box }}" readonly>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Postcode</label>
                                        <input type="text" name="billing_postal_code" class="form-control"
                                            value="{{ $inspection->billing_postal_code }}" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Gemeente</label>
                                        <input type="text" name="billing_city" class="form-control"
                                            value="{{ $inspection->billing_city }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Services -->
                @if ($inspection->items->count() > 0)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-list-unordered me-2"></i>Diensten
                            </h5>
                            <button class="btn btn-link btn-sm p-0 text-muted" type="button" data-bs-toggle="collapse"
                                data-bs-target="#servicesInfo">
                                <i class="ri-arrow-down-s-line ri-lg"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="servicesInfo">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Dienst</th>
                                                <th>Aantal</th>
                                                <th>Prijs</th>
                                                <th class="text-end">Totaal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($inspection->items as $item)
                                                <tr data-item-id="{{ $item->id }}" data-is-offerte="{{ $item->is_offerte ? 'true' : 'false' }}">
                                                    <td>
                                                        {{ $item->name }}
                                                        @if($item->is_offerte)
                                                            <br><small class="text-info">Offerte - Prijs wordt opgegeven</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td class="item-price">
                                                        @if($item->is_offerte)
                                                            Offerte
                                                        @else
                                                            €{{ number_format($item->price, 2, ',', '.') }}
                                                        @endif
                                                    </td>
                                                    <td class="text-end item-total">
                                                        @if($item->is_offerte)
                                                            Offerte
                                                        @else
                                                            €{{ number_format($item->total, 2, ',', '.') }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            @if($inspection->combi_discount_id)
                                                <tr>
                                                    <td colspan="4" class="text-end">
                                                        <div class="mb-1"><span class="fw-normal">Normale prijs:</span> <span class="text-decoration-line-through text-danger" id="normal-price">€{{ number_format($inspection->normal_total + $inspection->combi_discount_amount, 2, ',', '.') }}</span></div>
                                                        <div class="mb-1"><span class="fw-normal">Combi-korting:</span> <span class="text-success">{{ $inspection->combi_discount_type == 'percentage' ? $inspection->combi_discount_value . '%' : '€' . number_format($inspection->combi_discount_value, 2, ',', '.') }} korting</span></div>
                                                        <div><span class="fw-normal">Totaal:</span> <span id="final-total">€{{ number_format($inspection->display_total, 2, ',', '.') }}</span></div>
                                                        <div class="small text-muted mt-1">Combi: <strong>{{ $inspection->combiDiscount?->service_names }}</strong></div>
                                                    </td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold">Totaal:
                                                        <span id="final-total">€{{ number_format($inspection->display_total, 2, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                            @endif
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Documents & Invoice -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 1rem;">
                    <x-shared.file-list :inspection="$inspection" :showUpload="true" />

                    <!-- Invoice Section -->
                    {{-- @if ($inspection->getInvoiceFile)
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="card-title mb-0">
                                    <i class="ri-bill-line me-2"></i>Factuur
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <i class="ri-file-pdf-line text-danger me-2"></i>
                                            {{ $inspection->getInvoiceFile->name }}
                                        </div>
                                        <div class="btn-group">
                                            <a href="{{ $inspection->getInvoiceFile->path . $inspection->getInvoiceFile->name }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ $inspection->getInvoiceFile->path . $inspection->getInvoiceFile->name }}"
                                                download class="btn btn-sm btn-outline-success">
                                                <i class="ri-download-2-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
$(document).ready(function() {
    // Show sayfasında cart.js mantığını uygula
    function calculateShowPageTotals() {
        let normalTotal = 0;
        let hasOfferte = false;
        
        // Her item'ı kontrol et
        $('tr[data-item-id]').each(function() {
            const isOfferte = $(this).data('is-offerte');
            const priceCell = $(this).find('.item-price');
            const totalCell = $(this).find('.item-total');
            
            if (isOfferte) {
                hasOfferte = true;
                // Offerte item'lar için "Offerte" göster
                priceCell.text('Offerte');
                totalCell.text('Offerte');
            } else {
                // Normal item'lar için sayısal değerleri al
                const priceText = priceCell.text().replace('€', '').replace(',', '.');
                const totalText = totalCell.text().replace('€', '').replace(',', '.');
                
                const price = parseFloat(priceText) || 0;
                const total = parseFloat(totalText) || 0;
                
                normalTotal += total;
            }
        });
        
        // Toplamları güncelle
        if (hasOfferte) {
            console.log('✅ Show sayfası: Offerte item\'lar bulundu, toplam hesaplandı');
        }
        
        // Normal price ve final total'ı güncelle
        const normalPriceElement = $('#normal-price');
        const finalTotalElement = $('#final-total');
        
        if (normalPriceElement.length > 0) {
            // Combi discount varsa
            const combiAmount = {{ $inspection->combi_discount_amount ?? 0 }};
            const normalPriceWithCombi = normalTotal + combiAmount;
            normalPriceElement.text('€' + normalPriceWithCombi.toFixed(2).replace('.', ','));
        }
        
        if (finalTotalElement.length > 0) {
            finalTotalElement.text('€' + normalTotal.toFixed(2).replace('.', ','));
        }
        
        console.log('🧮 Show sayfası toplam hesaplandı:', {
            normalTotal: normalTotal,
            hasOfferte: hasOfferte
        });
    }
    
    // Sayfa yüklendiğinde toplamları hesapla
    calculateShowPageTotals();
});
</script>
