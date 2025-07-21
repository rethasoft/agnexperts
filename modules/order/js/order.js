// Add event listener for location selection
$('input[name="location"]').on('change', function() {
    const selectedLocation = $(this).val();
    
    // Clear existing services
    $('#servicesContainer').empty();
    
    // Show loading state
    $('#servicesContainer').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading services...</div>');
    
    // Make AJAX request to get services
    $.ajax({
        url: '/api/services', // Adjust this to your actual API endpoint
        method: 'GET',
        data: { location: selectedLocation },
        success: function(response) {
            // Clear loading state
            $('#servicesContainer').empty();
            
            // Generate services HTML
            let servicesHtml = '<div class="row g-4">';
            
            response.services.forEach(function(service) {
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
        },
        error: function(xhr, status, error) {
            $('#servicesContainer').html('<div class="alert alert-danger">Error loading services. Please try again.</div>');
            console.error('Error loading services:', error);
        }
    });
}); 