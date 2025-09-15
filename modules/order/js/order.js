// Add event listener for location selection
$('input[name="location"]').on('change', function() {
    const selectedLocation = $(this).val();
    
    // Clear existing services
    $('#servicesContainer').empty();
    
    // Show loading state
    $('#servicesContainer').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading services...</div>');
    
    // Make AJAX request to get services
    $.ajax({
        url: `/location/${selectedLocation}/services`, // Updated to use the correct endpoint
        method: 'GET',
        success: function(response) {
            // Clear loading state
            $('#servicesContainer').empty();
            
            // Generate services HTML
            let servicesHtml = '<div class="row g-4">';
            
            response.forEach(function(service) {
                servicesHtml += `
                    <div class="col-md-4">
                        <div class="service-card">
                            <input type="radio" name="service" id="service${service.id}" value="${service.id}">
                            <label class="form-check-label w-100" for="service${service.id}">
                                <i class="fas fa-cog"></i>
                                <span>${service.name}</span>
                                <small class="d-block text-muted">${service.short_name}</small>
                            </label>
                        </div>
                    </div>
                `;
            });
            
            servicesHtml += '</div>';
            
            // Insert services HTML
            $('#servicesContainer').html(servicesHtml);
        },
        error: function(xhr, status, error) {
            $('#servicesContainer').html('<div class="alert alert-danger">Error loading services. Please try again.</div>');
            console.error('Error loading services:', error);
        }
    });
});

// Add event listener for service selection
$(document).on('change', 'input[name="service"]', function() {
    const selectedServiceId = $(this).val();
    
    // Clear existing sub-services
    $('.sub-services-row').empty();
    
    if (selectedServiceId) {
        // Show loading state
        $('.sub-services-row').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading sub-services...</div>');
        
        // Get selected location
        const selectedLocation = $('input[name="location"]:checked').val();
        
        // Make AJAX request to get sub-services
        $.ajax({
            url: `/service/${selectedServiceId}/sub-services`,
            method: 'GET',
            data: { location: selectedLocation },
            success: function(response) {
                // Clear loading state
                $('.sub-services-row').empty();
                
                if (response.length > 0) {
                    // Generate sub-services HTML
                    let subServicesHtml = '<div class="row g-4">';
                    
                    response.forEach(function(subService) {
                        subServicesHtml += `
                            <div class="col-md-4">
                                <div class="service-card">
                                    <input type="checkbox" name="sub_services[]" id="subService${subService.id}" value="${subService.id}">
                                    <label class="form-check-label w-100" for="subService${subService.id}">
                                        <i class="fas fa-cog"></i>
                                        <span>${subService.name}</span>
                                        <small class="d-block text-muted">${subService.short_name}</small>
                                        <small class="d-block text-primary">€${subService.price}</small>
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    
                    subServicesHtml += '</div>';
                    
                    // Insert sub-services HTML
                    $('.sub-services-row').html(subServicesHtml);
                }
            },
            error: function(xhr, status, error) {
                $('.sub-services-row').html('<div class="alert alert-danger">Error loading sub-services. Please try again.</div>');
                console.error('Error loading sub-services:', error);
            }
        });
    }
});