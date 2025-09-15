class OrderHandler {
    constructor() {
        this.baseUrl = '/keuring-aanvragen';
        this.selectedServices = new Map(); // Store all selected services
        this.services = new Map(); // Store services and their quantities
        this.currentMainService = null; // Store current main service
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Try with event delegation
        $(document).on('click', 'input[type="radio"][name="location"]', (e) => {
            this.handleLocationChange(e);
        });

        $(document).on('change', 'input[type="radio"][name="service"]', (e) => this.handleServiceChange(e));

        // Add sub-services click handler
        $(document).on('change', '.sub-services', (e) => this.handleSubServiceClick(e));

        $('.step-indicator').on('click', (e) => {
            const targetStep = $(e.currentTarget).data('step');

            // Only allow clicking on completed steps or the next available step
            if (targetStep <= this.currentStep + 1) {
                this.goToStep(targetStep);
            }
        });
    }

    async handleLocationChange(event) {
        try {
            const $radio = $(event.target);
            const locationId = $radio.val();

            // Remove active class from all location cards and add to selected one
            $radio.removeClass('active');
            $radio.addClass('active');

            if (!locationId) {
                $('#servicesContainer').html('<div class="text-center text-muted">Selecteer eerst een locatie</div>');
                return;
            }

            // Show loading state
            $('#servicesContainer').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Services laden...</div>');

            // Use makeRequest function to fetch services
            const services = await this.getServicesByLocation(locationId);

            console.log(services);

            // Generate services HTML with title
            let servicesHtml = `
                <h5 class="card-title mb-4 fw-normal">Selecteer Service</h5>
                <div class="row g-4">
            `;

            services.forEach(service => {
                servicesHtml += `
                    <div class="col-md-4">
                        <div class="service-card">
                            <input type="radio" name="service" id="service${service.id}" value="${service.id}">
                            <label class="form-check-label w-100" for="service${service.id}">
                                <i class="fas fa-${service.icon}"></i>
                                <span>${service.name}</span>
                            </label>
                        </div>
                    </div>
                `;
            });

            servicesHtml += '</div>';

            // Insert services HTML
            $('#servicesContainer').html(servicesHtml);

        } catch (error) {
            console.error('Error fetching services:', error);
            $('#servicesContainer').html('<div class="alert alert-danger">Fout bij het laden van services. Probeer het opnieuw.</div>');
            this.showError('Services laden mislukt');
        }
    }

    async handleServiceChange(event) {
        console.log('handleServiceChange called with value:', event.target.value);
        try {
            const serviceId = event.target.value;
            const serviceName = $(event.target).closest('.service-card').find('span').text();
            console.log('Selected service:', serviceId);

            // Store previously selected services
            const previouslySelectedServices = new Map(this.selectedServices);

            // Update active class for main service
            $('.service-card').removeClass('active');
            $(event.target).closest('.service-card').addClass('active');

            // Store current main service
            this.currentMainService = {
                id: serviceId,
                name: serviceName
            };

            if (!serviceId) {
                $('.sub-services-row').html('<div class="text-center text-muted">Selecteer eerst een service</div>');
                $('.summary-table').hide();
                return;
            }

            // Show loading state
            $('.sub-services-row').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Extra services laden...</div>');

            // Use makeRequest function to fetch sub-services
            const subServices = await this.getSubServicesByServiceId(serviceId);

            // Generate sub-services HTML with title
            let subServicesHtml = `
                <div class="col-12">
                    <h5 class="card-title mb-4 fw-normal">Selecteer Extra Services voor ${serviceName}</h5>
                </div>
            `;

            subServices.forEach(subService => {
                const isSelected = previouslySelectedServices.has(String(subService.id));
                subServicesHtml += `
                    <div class="col-md-4">
                        <div class="service-card ${isSelected ? 'active' : ''}">
                            <input type="checkbox" 
                                   id="subService${subService.id}" 
                                   name="subServices[]"
                                   class="sub-services"
                                   data-price="${subService.price || 0}"
                                   data-category_id="${subService.category_id}"
                                   data-name="${subService.name}"
                                   data-parent-service="${serviceName}"
                                   data-parent-id="${serviceId}"
                                   value="${subService.id}"
                                   ${isSelected ? 'checked' : ''}>
                            <label class="service-card-label" for="subService${subService.id}">
                                <div class="service-card-content d-flex justify-content-between align-items-center">
                                    <div class="service-info d-flex align-items-center gap-2">
                                        <i class="fas fa-house-user"></i>
                                        <span>${subService.name}</span>
                                    </div>
                                </div>
                            </label>                            
                        </div>
                    </div>
                `;
            });

            // Insert sub-services HTML
            $('.sub-services-row').html(subServicesHtml);

            // Restore previously selected services
            this.selectedServices = previouslySelectedServices;

            // Update selected services display
            this.updateSelectedServicesTable();

        } catch (error) {
            console.error('Error fetching sub-services:', error);
            $('.sub-services-row').html('<div class="alert alert-danger">Fout bij het laden van extra services. Probeer het opnieuw.</div>');
            this.showError('Extra services laden mislukt');
        }
    }

    async makeRequest(url, options = {}) {
        try {
            const response = await fetch(url, options);
            if (!response.ok) throw new Error('Network response was not ok');
            return await response.json();
        } catch (error) {
            console.error('Error:', error);
            throw error;
        }
    }

    async getServicesByLocation(locationId) {
        return this.makeRequest(`${this.baseUrl}/location/${locationId}/services`);
    }

    async getSubServices(serviceId) {
        return this.makeRequest(`${this.baseUrl}/service/${serviceId}/subservices`);
    }

    async getSubServicesByServiceId(serviceId) {
        return this.makeRequest(`${this.baseUrl}/service/${serviceId}/subservices`);
    }

    updateServicesDropdown(services) {
        this.updateDropdown('#serviceSelect', services);
        this.resetSubServiceSelect();
    }

    updateSubServicesDropdown(subServices) {
        this.updateDropdown('#subServiceSelect', subServices);
    }

    updateDropdown(selectId, items) {
        const $select = $(selectId);
        $select.prop('disabled', false);
        $select.empty().append(`<option value="">Selecteer ${selectId.replace('#', '')}</option>`);

        items.forEach(item => {
            $select.append(`<option value="${item.id}">${item.name}</option>`);
        });
    }

    resetServiceSelect() {
        this.resetDropdown('#serviceSelect');
        this.resetSubServiceSelect();
    }

    resetSubServiceSelect() {
        this.resetDropdown('#subServiceSelect');
    }

    resetDropdown(selectId) {
        const $select = $(selectId);
        $select.prop('disabled', true);
        $select.empty().append(`<option value="">Selecteer ${selectId.replace('#', '')}</option>`);
    }

    showError(message) {
        // You can replace this with a better UI notification
        // For example, using a toast or custom alert
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: message,
            confirmButtonColor: '#3085d6'
        });
    }

    updateSelectedServicesTable() {
        const $selectedServicesBody = $('#selectedServicesBody');
        let grandTotal = 0;

        // Clear existing rows
        $selectedServicesBody.empty();

        // Add row for each selected service
        this.selectedServices.forEach((service) => {
            const total = service.price * service.quantity;
            grandTotal += total;

            const html = `
                <tr class="service-row" data-id="${service.id}" data-price="${service.price}">
                    <td>
                        <div class="service-info d-flex align-items-center gap-3">
                            <div class="service-icon">
                                <i class="fas fa-${service.icon}"></i>
                            </div>
                            <div>
                                <div class="service-parent text-muted">${service.name}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="quantity-control d-flex align-items-center justify-content-center">
                            <button type="button" class="qty-btn minus" data-id="${service.id}">-</button>
                            <input type="number" class="qty-input" value="${service.quantity}" min="1" max="99" style="height:57px">
                            <button type="button" class="qty-btn plus" data-id="${service.id}">+</button>
                        </div>
                    </td>
                    <td class="text-end price">€${service.price.toFixed(2)}</td>
                    <td class="text-end total">€${total.toFixed(2)}</td>
                    <td>
                        <button class="remove-btn" aria-label="Remove item" data-id="${service.id}">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
            $selectedServicesBody.append(html);
        });

        // Add discount row if there's a valid coupon
        const discountLabel = $('#coupon-message').text();
        if (discountLabel && discountLabel.includes('Korting toegepast')) {
            const originalTotal = grandTotal;
            const discountedTotal = parseFloat($('#grandTotal').text().replace('€', '').trim());
            const discountAmount = originalTotal - discountedTotal;

            $selectedServicesBody.append(`
                <tr class="discount-row text-success">
                    <td colspan="3">
                        <div class="d-flex align-items-center">
                            <div class="discount-icon me-2">
                                <i class="fas fa-tag"></i>
                            </div>
                            <div>${discountLabel}</div>
                        </div>
                    </td>
                    <td class="text-end">-€${discountAmount.toFixed(2)}</td>
                    <td></td>
                </tr>
            `);

            // Update grand total with discounted price
            $('#grandTotal').text('€' + discountedTotal.toFixed(2));
        } else {
            $('#grandTotal').text('€' + grandTotal.toFixed(2));
        }

        // Store original total for coupon calculations
        $('#grandTotal').data('originalTotal', grandTotal.toFixed(2));
        $('#grandTotal').attr('data-original-total', grandTotal.toFixed(2));

        if (this.selectedServices.size > 0) {
            $('.summary-table').show();
        } else {
            $('.summary-table').hide();
        }

        // Add quantity button handlers
        this.initializeQuantityControls();

        // Add remove button handlers
        this.initializeRemoveButtons();
    }

    initializeQuantityControls() {
        // Remove existing handlers to prevent duplicates
        $(document).off('click', '.qty-btn.plus');
        $(document).off('click', '.qty-btn.minus');
        $(document).off('change', '.qty-input');

        // Handle plus button
        $(document).on('click', '.qty-btn.plus', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const $row = $(e.currentTarget).closest('tr');
            const serviceId = String($row.data('id'));
            const $input = $row.find('.qty-input');
            const currentVal = parseInt($input.val()) || 1;

            if (currentVal < 99) {
                $input.val(currentVal + 1);

                // Update quantity in the selectedServices Map
                const service = this.selectedServices.get(serviceId);
                service.quantity = currentVal + 1;
                this.selectedServices.set(serviceId, service);

                this.updateRowTotal($row);
                this.updateGrandTotal();
            }
        });

        // Handle minus button
        $(document).on('click', '.qty-btn.minus', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const $row = $(e.currentTarget).closest('tr');
            const serviceId = String($row.data('id'));
            const $input = $row.find('.qty-input');
            const currentVal = parseInt($input.val()) || 1;

            if (currentVal > 1) {
                $input.val(currentVal - 1);
                const service = this.selectedServices.get(serviceId);
                service.quantity = currentVal - 1;
                this.selectedServices.set(serviceId, service);
                this.updateRowTotal($row);
                this.updateGrandTotal();
            }
        });

        // Handle direct input changes
        $(document).on('change', '.qty-input', (e) => {
            const $input = $(e.currentTarget);
            const $row = $input.closest('tr');
            let value = parseInt($input.val()) || 1;

            // Ensure value is between 1 and 99
            value = Math.max(1, Math.min(99, value));
            $input.val(value);

            this.updateRowTotal($row);
            this.updateGrandTotal();
        });
    }

    updateRowTotal($row) {
        const quantity = parseInt($row.find('.qty-input').val()) || 1;
        const priceText = $row.find('.price').text().replace(/[^0-9.-]+/g, '');
        const price = parseFloat(priceText) || 0;
        const total = quantity * price;

        // Update the total with € at the start
        $row.find('.total').text('€ ' + total.toFixed(2));
        this.updateGrandTotal();
    }

    updateGrandTotal() {
        let total = 0;
        $('.total').each(function() {
            const value = $(this).text().replace(/[^0-9.-]+/g, '');
            total += parseFloat(value) || 0;
        });

        // Update with € at the start
        $('#grandTotal').text('€ ' + total.toFixed(2));
        $('#summary-grandTotal').text('€ ' + total.toFixed(2));
        
        // Store original total for coupon calculations
        $('#grandTotal').data('originalTotal', total.toFixed(2));
        $('#grandTotal').attr('data-original-total', total.toFixed(2));
    }

    validateCurrentStep() {
        const $currentStep = $(`.step-content[data-step="${this.currentStep}"]`);
        let isValid = true;

        // Basic required field validation
        $currentStep.find('[required]').each(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Step-specific validations
        switch (this.currentStep) {
            case 1:
                // Validate location selection
                if (!$('input[name="location"]:checked').length) {
                    this.showError('Selecteer een locatie');
                    isValid = false;
                }

                // Validate service selection
                if (!$('input[name="service"]:checked').length) {
                    this.showError('Selecteer een service');
                    isValid = false;
                }

                // Validate sub-services selection
                const selectedSubServices = $('.sub-services:checked').length;
                if (selectedSubServices === 0) {
                    this.showError('Selecteer ten minste één extra service');
                    isValid = false;
                }

                break;

            case 2:
                // Validate name fields
                const name = $('input[name="name"]').val();
                if (!name) {
                    $('input[name="name"]').addClass('is-invalid');
                    this.showError('Naam is verplicht');
                    isValid = false;
                }

                // Validate email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const email = $('input[name="email"]').val();
                if (!email) {
                    $('input[name="email"]').addClass('is-invalid');
                    this.showError('E-mailadres is verplicht');
                    isValid = false;
                } else if (!emailRegex.test(email)) {
                    $('input[name="email"]').addClass('is-invalid');
                    this.showError('Voer een geldig e-mailadres in');
                    isValid = false;
                }

                // Validate phone number
                const phone = $('input[name="phone"]').val();
                if (!phone) {
                    $('input[name="phone"]').addClass('is-invalid');
                    this.showError('Telefoonnummer is verplicht');
                    isValid = false;
                } else if (!/^\+?[\d\s-]{8,}$/.test(phone)) {
                    $('input[name="phone"]').addClass('is-invalid');
                    this.showError('Voer een geldig telefoonnummer in');
                    isValid = false;
                }

                // Validate required address fields
                const requiredAddressFields = {
                    'street': 'Straat',
                    'number': 'Nummer',
                    'city': 'Gemeente',
                    'postal_code': 'Postcode',
                    'province': 'Provincie'
                };

                Object.entries(requiredAddressFields).forEach(([field, label]) => {
                    const $field = field === 'province' ?
                        $(`select[name="${field}"]`) :
                        $(`input[name="${field}"]`);

                    if (!$field.val()) {
                        $field.addClass('is-invalid');
                        this.showError(`${label} is verplicht`);
                        isValid = false;
                    }
                });

                // Validate invoice address if different from delivery
                if (!$('#sameAsDelivery').is(':checked')) {
                    const requiredInvoiceFields = {
                        'invoice_street': 'Factuur straat',
                        'invoice_house_number': 'Factuur huisnummer',
                        'invoice_city': 'Factuur plaats',
                        'invoice_postal_code': 'Factuur postcode'
                    };

                    Object.entries(requiredInvoiceFields).forEach(([field, label]) => {
                        const $field = $(`input[name="${field}"]`);
                        if (!$field.val()) {
                            $field.addClass('is-invalid');
                            this.showError(`${label} is verplicht`);
                            isValid = false;
                        }
                    });
                }
                break;
        }

        return isValid;
    }

    initializeRemoveButtons() {
        // Remove existing handlers first
        $(document).off('click', '.remove-btn');

        $(document).on('click', '.remove-btn', (e) => {
            const $btn = $(e.target).closest('.remove-btn');
            const serviceId = $btn.data('id');

            // Uncheck the corresponding checkbox
            $(`input[value="${serviceId}"].sub-services`).prop('checked', false);

            // Remove from selected services and table
            this.selectedServices.delete(serviceId);
            $btn.closest('tr').remove();

            this.updateGrandTotal();
            $('.summary-table').toggle(this.selectedServices.size > 0);
        });
    }

    updateSummary() {
        const $servicesList = $('#summaryServicesList');
        let servicesHtml = '';
        let subtotal = 0;

        $('.service-row').each(function () {
            const serviceName = $(this).find('.service-name').text();
            const quantity = $(this).find('.qty-input').val();
            const price = parseFloat($(this).find('.price').text().replace('€', '').trim());
            const total = parseFloat($(this).find('.total').text().replace('€', '').trim());

            servicesHtml += `
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">${serviceName}</h6>
                            <small class="text-muted">Aantal: ${quantity}</small>
                        </div>
                        <div class="text-end">
                            <strong>€${total.toFixed(2)}</strong>
                        </div>
                    </div>
                </div>
            `;

            subtotal += total;
        });

        // Add discount information if there's a valid coupon
        const discountLabel = $('#coupon-message').text();
        if (discountLabel && discountLabel.includes('Korting toegepast')) {
            const discountedTotal = parseFloat($('#grandTotal').text().replace('€', '').trim());
            const discountAmount = subtotal - discountedTotal;

            servicesHtml += `
                <div class="mb-3 pb-3 border-bottom text-success">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1"><i class="fas fa-tag me-2"></i>${discountLabel}</h6>
                        </div>
                        <div class="text-end">
                            <strong>-€${discountAmount.toFixed(2)}</strong>
                        </div>
                    </div>
                </div>
            `;

            // Update totals with discount
            $('#grandTotal').text('€' + discountedTotal.toFixed(2));
            $('#summary-grandTotal').text('€' + discountedTotal.toFixed(2));
        } else {
            $('#grandTotal').text('€' + subtotal.toFixed(2));
            $('#summary-grandTotal').text('€' + subtotal.toFixed(2));
        }

        $servicesList.html(servicesHtml);

        // Update Contact Details Summary
        const contactHtml = `
            <div class="mb-3">
                <p class="mb-1"><strong>Naam:</strong> ${$('input[name="first_name"]').val()} ${$('input[name="last_name"]').val()}</p>
                <p class="mb-1"><strong>E-mail:</strong> ${$('input[name="email"]').val()}</p>
                <p class="mb-1"><strong>Telefoon:</strong> ${$('input[name="phone"]').val()}</p>
                ${$('input[name="company_name"]').val() ? `<p class="mb-1"><strong>Bedrijfsnaam:</strong> ${$('input[name="company_name"]').val()}</p>` : ''}
                ${$('input[name="vat_number"]').val() ? `<p class="mb-1"><strong>BTW Nummer:</strong> ${$('input[name="vat_number"]').val()}</p>` : ''}
            </div>
        `;
        $('#summaryContactDetails').html(contactHtml);

        // Update Address Details Summary
        let addressHtml = `
            <div class="mb-4">
                <h6 class="mb-3">Keuringsadres</h6>
                <div class="row">
                    <div class="col-12">
                        <p class="mb-1"><strong>Straat</strong>: ${$('input[name="street"]').val()}</p>
                    </div>
                    <div class="col-12">
                        <p class="mb-1"><strong>Huisnummer</strong>: ${$('input[name="number"]').val()}</p>
                    </div>
                    ${$('input[name="box"]').val() ? `
                    <div class="col-12">
                        <p class="mb-1"><strong>Bus</strong>: ${$('input[name="box"]').val()}</p>
                    </div>` : ''}
                    ${$('input[name="floor"]').val() ? `
                    <div class="col-12">
                        <p class="mb-1"><strong>Verdieping</strong>: ${$('input[name="floor"]').val()}</p>
                    </div>` : ''}
                    <div class="col-12">
                        <p class="mb-1"><strong>Postcode</strong>: ${$('input[name="postal_code"]').val()}</p>
                    </div>
                    <div class="col-12">
                        <p class="mb-1"><strong>Gemeente</strong>: ${$('input[name="city"]').val()}</p>
                    </div>
                    <div class="col-12">
                        <p class="mb-1"><strong>Provincie</strong>: ${$('select[name="province"] option:selected').text()}</p>
                    </div>
                </div>
            </div>
        `;

        // Add Invoice Address if different from delivery address
        if (!$('#sameAsDelivery').is(':checked')) {
            addressHtml += `
                <div class="mt-4">
                    <h6 class="mb-3">Factuuradres</h6>
                    <p class="mb-1">${$('input[name="invoice_street"]').val()} ${$('input[name="invoice_house_number"]').val()}</p>
                    ${$('input[name="invoice_house_number_addition"]').val() ? `<p class="mb-1">Toevoeging: ${$('input[name="invoice_house_number_addition"]').val()}</p>` : ''}
                    <p class="mb-1">${$('input[name="invoice_postal_code"]').val()} ${$('input[name="invoice_city"]').val()}</p>
                </div>
            `;
        }

        $('#summaryAddressDetails').html(addressHtml);
    }

    addServiceToSummary(serviceData) {
        const serviceId = serviceData.id;
        const price = parseFloat(serviceData.price.replace(/[^0-9.-]+/g, ""));

        // Preserve existing quantities
        this.saveCurrentQuantities();

        // Check if service already exists
        if (this.services.has(serviceId)) {
            // Update existing service
            const existingService = this.services.get(serviceId);
            existingService.price = price;
            console.log('exist - ' + existingService);
            // this.services.set(serviceId, existingService);
            this.updateServiceRow(existingService);
        } else {
            // Add new service
            const newService = {
                id: serviceId,
                name: serviceData.name,
                price: price,
                quantity: 1
            };
            // this.services.set(serviceId, newService);
            console.log('new - ' + newService);
            this.appendServiceRow(newService);
        }

        this.updateGrandTotal();
        $('.summary-table').show();
    }

    saveCurrentQuantities() {
        $('#selectedServicesBody tr').each((_, row) => {
            const $row = $(row);
            const serviceId = $row.data('service-id');
            const quantity = parseInt($row.find('input').val());

            if (this.services.has(serviceId)) {
                const service = this.services.get(serviceId);
                service.quantity = quantity;
                this.services.set(serviceId, service);
            }
        });
    }

    appendServiceRow(service) {
        const total = service.price * service.quantity;
        const html = `
            <tr class="service-row" data-id="${service.id}" data-price="${service.price}">
                <td>
                    <div class="service-info d-flex align-items-center gap-3">
                        <div class="service-icon">
                            <i class="fas fa-${service.icon}"></i>
                        </div>
                        <div>
                            <div class="service-parent text-muted">${service.name}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="quantity-control d-flex align-items-center justify-content-center">
                        <button type="button" class="qty-btn minus" data-id="${service.id}">-</button>
                        <input type="number" class="qty-input" value="${service.quantity}" min="1" max="99" style="height:57px">
                        <button type="button" class="qty-btn plus" data-id="${service.id}">+</button>
                    </div>
                </td>
                <td class="text-end price">€${service.price.toFixed(2)}</td>
                <td class="text-end total">€${total.toFixed(2)}</td>
                <td>
                    <button class="remove-btn" aria-label="Remove item" data-id="${service.id}">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#selectedServicesBody').append(html);
    }

    updateServiceRow(service) {
        const $row = $(`tr[data-service-id="${service.id}"]`);
        const total = (service.price * service.quantity).toFixed(2);

        $row.find('input').val(service.quantity);
        $row.find('.total').text(`€ ${total}`);
    }

    bindEventsToRow($row) {
        const serviceId = $row.data('service-id');
        const service = this.services.get(serviceId);
        const $input = $row.find('input');

        $row.find('.plus').click(() => {
            service.quantity = parseInt($input.val()) + 1;
            this.services.set(serviceId, service);
            this.updateServiceRow(service);
            this.updateGrandTotal();
        });

        $row.find('.minus').click(() => {
            service.quantity = Math.max(1, parseInt($input.val()) - 1);
            this.services.set(serviceId, service);
            this.updateServiceRow(service);
            this.updateGrandTotal();
        });

        $input.on('change', () => {
            service.quantity = Math.max(1, parseInt($input.val()) || 1);
            this.services.set(serviceId, service);
            this.updateServiceRow(service);
            this.updateGrandTotal();
        });

        $row.find('.remove').click(() => {
            this.services.delete(serviceId);
            $row.remove();
            this.updateGrandTotal();
            if (this.services.size === 0) {
                $('.summary-table').hide();
            }
        });
    }

    handleSubServiceClick(event) {
        const $checkbox = $(event.target);
        const serviceId = $checkbox.val();
        const parentService = $checkbox.data('parent-service');
        const parentId = $checkbox.data('parent-id');
        const $serviceCard = $checkbox.closest('.service-card');
        const serviceName = $checkbox.data('name');

        if ($checkbox.is(':checked')) {
            // Add active class to service card
            $serviceCard.addClass('active');

            if (!this.selectedServices.has(serviceId)) {
                const serviceData = {
                    id: serviceId,
                    name: `${parentService} > ${serviceName}`,
                    category_id: $checkbox.data('category_id'),
                    price: parseFloat($checkbox.data('price')),
                    quantity: 1,
                    total: parseFloat($checkbox.data('price')),
                    icon: 'house-user', // Default icon
                    parentService: parentService,
                    parentId: parentId
                };

                // Update total before adding to selected services
                serviceData.total = serviceData.price * serviceData.quantity;

                this.selectedServices.set(serviceId, serviceData);
                this.appendServiceRow(serviceData);
            }
        } else {
            // Remove active class from service card
            $serviceCard.removeClass('active');
            
            this.selectedServices.delete(serviceId);
            $(`tr[data-id="${serviceId}"]`).remove();
        }

        this.updateGrandTotal();
        $('.summary-table').toggle(this.selectedServices.size > 0);
    }

    addSelectedServicesToForm() {
        // Remove any existing hidden service inputs
        $('.hidden-service-input').remove();

        // Add hidden inputs for selected services
        Array.from(this.selectedServices.values()).forEach((service, index) => {
            $('#orderForm').append(`
                <input type="hidden" class="hidden-service-input" name="selectedServices[${index}][id]" value="${service.id}">
                <input type="hidden" class="hidden-service-input" name="selectedServices[${index}][name]" value="${service.name}">
                <input type="hidden" class="hidden-service-input" name="selectedServices[${index}][category_id]" value="${service.category_id}">
                <input type="hidden" class="hidden-service-input" name="selectedServices[${index}][quantity]" value="${service.quantity}">
                <input type="hidden" class="hidden-service-input" name="selectedServices[${index}][price]" value="${service.price}">
                <input type="hidden" class="hidden-service-input" name="selectedServices[${index}][total]" value="${service.total}">
            `);
        });
    }
}

class StepHandler {
    constructor(orderHandler) {
        this.orderHandler = orderHandler;
        this.currentStep = 1;
        this.totalSteps = $('.step-content').length;
        this.initializeSteps();
        this.initializeEventListeners();
        this.initializeServiceCards();
        this.initializeAddressHandling();
    }

    initializeSteps() {
        // Hide prev button initially
        $('.btn-prev').hide();

        // Show first step, hide others
        $('.step-content').removeClass('active');
        $('.step-content[data-step="1"]').addClass('active');

        // Update button text
        $('.btn-prev').text('Vorige');
        $('.btn-next').text('Volgende');
    }

    initializeEventListeners() {
        $('.btn-next').on('click', () => {
            if (this.orderHandler.validateCurrentStep()) {
                this.nextStep();
            }
        });

        $('.btn-prev').on('click', () => this.previousStep());
        $('.step-indicator').on('click', (e) => {
            const stepNumber = $(e.currentTarget).data('step');
            this.goToStep(stepNumber);
        });

        // Add form submit handler
        $('#orderForm').on('submit', (e) => {
            e.preventDefault();
            if (this.validateCurrentStep()) {
                this.submitOrder();
            }
        });
    }

    initializeServiceCards() {
        $('.service-card').each((_, card) => {
            const $radio = $(card).find('input[type="radio"]');
            if ($radio.length) {
                $(card).on('click', (e) => {
                    if (!$(e.target).closest('.btn').length) {
                        $radio.prop('checked', true);
                        this.updateCardSelection($radio[0]);
                    }
                });

                $radio.on('change', (e) => this.updateCardSelection(e.target));
            }
        });
    }

    initializeAddressHandling() {
        $('#sameAsDelivery').on('change', (e) => {
            const $invoiceSection = $('#invoiceAddressSection');
            if (e.target.checked) {
                $invoiceSection.hide();
                this.copyDeliveryAddress();
            } else {
                $invoiceSection.show();
                this.clearInvoiceFields();
            }
        });
    }

    goToStep(step) {
        console.log(`Attempting to go to step: ${step}`);
        if (step > this.currentStep && !this.validateCurrentStep()) {
            console.log('Validation failed for current step');
            return;
        }

        console.log(`Moving to step: ${step}`);
        $('.step-content').removeClass('active');
        $(`.step-content[data-step="${step}"]`).addClass('active');

        $('.step-indicator .step-circle').removeClass('active');
        $('.step-indicator').eq((step - 1)).find('.step-circle').addClass('active');

        this.currentStep = step;

        // Update summary when moving to step 3
        if (step === 3) {
            this.orderHandler.updateSummary();
        }

        $('.btn-prev').toggle(step > 1);

        // Change button visibility for final step
        if (step === this.totalSteps) {
            $('.btn-next').hide();
            $('.btn-submit').show();
        } else {
            $('.btn-next').show();
            $('.btn-submit').hide();
        }
    }

    previousStep() {
        if (this.currentStep > 1) {
            this.goToStep(this.currentStep - 1);
        }
    }

    nextStep() {
        if (this.currentStep < this.totalSteps) {
            this.goToStep(this.currentStep + 1);
        }
    }

    updateProgressBar() {
        const progress = ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
        $('.progress-bar').css('width', `${progress}%`);
    }

    updateStepIndicators() {
        $('.step-indicator').each((index, element) => {
            const $indicator = $(element);
            const stepNumber = $indicator.data('step');

            $indicator
                .toggleClass('completed', stepNumber < this.currentStep)
                .toggleClass('current active', stepNumber === this.currentStep)
                .toggleClass('inactive', stepNumber > this.currentStep);
        });
    }

    updateNavigationButtons() {
        $('.btn-prev').toggle(this.currentStep > 1);
        $('.btn-next').text(this.currentStep === this.totalSteps ? 'Submit Order' : 'Next');
    }

    validateCurrentStep() {
        const $currentStep = $(`.step-content[data-step="${this.currentStep}"]`);
        let isValid = true;

        // Basic required field validation
        $currentStep.find('[required]').each(function () {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        // Step-specific validations
        switch (this.currentStep) {
            case 1:
                // Validate location selection
                if (!$('input[name="location"]:checked').length) {
                    this.showError('Selecteer een locatie');
                    isValid = false;
                }

                // Validate service selection
                if (!$('input[name="service"]:checked').length) {
                    this.showError('Selecteer een service');
                    isValid = false;
                }

                // Validate sub-services selection
                const selectedSubServices = $('.sub-services:checked').length;
                if (selectedSubServices === 0) {
                    this.showError('Selecteer ten minste één extra service');
                    isValid = false;
                }

                break;

            case 2:
                // Validate name fields
                const name = $('input[name="name"]').val();
                if (!name) {
                    $('input[name="name"]').addClass('is-invalid');
                    this.showError('Naam is verplicht');
                    isValid = false;
                }

                // Validate email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const email = $('input[name="email"]').val();
                if (!email) {
                    $('input[name="email"]').addClass('is-invalid');
                    this.showError('E-mailadres is verplicht');
                    isValid = false;
                } else if (!emailRegex.test(email)) {
                    $('input[name="email"]').addClass('is-invalid');
                    this.showError('Voer een geldig e-mailadres in');
                    isValid = false;
                }

                // Validate phone number
                const phone = $('input[name="phone"]').val();
                if (!phone) {
                    $('input[name="phone"]').addClass('is-invalid');
                    this.showError('Telefoonnummer is verplicht');
                    isValid = false;
                } else if (!/^\+?[\d\s-]{8,}$/.test(phone)) {
                    $('input[name="phone"]').addClass('is-invalid');
                    this.showError('Voer een geldig telefoonnummer in');
                    isValid = false;
                }

                // Validate required address fields
                const requiredAddressFields = {
                    'street': 'Straat',
                    'number': 'Nummer',
                    'city': 'Gemeente',
                    'postal_code': 'Postcode',
                    'province': 'Provincie'
                };

                Object.entries(requiredAddressFields).forEach(([field, label]) => {
                    const $field = field === 'province' ?
                        $(`select[name="${field}"]`) :
                        $(`input[name="${field}"]`);

                    if (!$field.val()) {
                        $field.addClass('is-invalid');
                        this.showError(`${label} is verplicht`);
                        isValid = false;
                    }
                });

                // Validate invoice address if different from delivery
                if (!$('#sameAsDelivery').is(':checked')) {
                    const requiredInvoiceFields = {
                        'invoice_street': 'Factuur straat',
                        'invoice_house_number': 'Factuur huisnummer',
                        'invoice_city': 'Factuur plaats',
                        'invoice_postal_code': 'Factuur postcode'
                    };

                    Object.entries(requiredInvoiceFields).forEach(([field, label]) => {
                        const $field = $(`input[name="${field}"]`);
                        if (!$field.val()) {
                            $field.addClass('is-invalid');
                            this.showError(`${label} is verplicht`);
                            isValid = false;
                        }
                    });
                }
                break;
        }

        return isValid;
    }

    showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Validatie Fout',
            text: message,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    }

    validateServiceSelection() {
        const hasLocation = $('input[name="location"]:checked').val();
        const hasService = $('input[name="service"]:checked').val();
        if (!hasLocation || !hasService) {
            this.showError('Selecteer zowel een locatie als een service');
            return false;
        }
        return true;
    }

    validateContactInfo() {
        const email = $('#email').val();
        if (!this.validateEmail(email)) {
            this.showError('Voer een geldig e-mailadres in');
            return false;
        }
        return true;
    }

    validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    updateCardSelection(radio) {
        const name = $(radio).attr('name');
        $(`input[name="${name}"]`).closest('.service-card').removeClass('selected');
        $(radio).closest('.service-card').addClass('selected');
    }

    copyDeliveryAddress() {
        const fields = ['Street', 'HouseNumber', 'Box', 'PostalCode', 'City'];
        fields.forEach(field => {
            $(`#invoice${field}`).val($(`#${field.toLowerCase()}`).val());
        });
    }

    clearInvoiceFields() {
        $('.invoice-input').val('');
    }

    submitOrder() {
        // Show loading state
        this.showLoadingState();

        // Call addSelectedServicesToForm through orderHandler
        this.orderHandler.addSelectedServicesToForm();

        // Submit the form
        $('#orderForm').off('submit').submit();
    }

    showLoadingState() {
        $('.btn-submit')
            .prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin"></i> Verwerken...');
    }
}

// Initialize when document is ready
$(document).ready(() => {
    const orderHandler = new OrderHandler();
    const stepHandler = new StepHandler(orderHandler);

    // Store the stepHandler instance in orderHandler if needed
    orderHandler.stepHandler = stepHandler;
});

// Add this to handle remove button clicks
$(document).on('click', '.remove-service', function () {
    const serviceId = $(this).data('id');
    $(`#subService${serviceId}`).prop('checked', false)
        .closest('.service-card')
        .find('.quantity-control')
        .hide();
    this.updateSelectedServicesTable();
});