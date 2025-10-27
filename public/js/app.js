
(function ($) {

  // functions

  fetchServicesByClient = async (clientId) => {
    const response = await fetch(`/app/tenant/ajax/getTypesByClient/${clientId}`);
    const json = await response.json();
    const types = json.data;

    const typesBox = document.getElementById("types");
    typesBox.innerHTML = types;

    // reset cart and table
    cart.reset();
  }

  // events
  $(document).ready(function () {
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
    });


    new DataTable(".data-table", {
      order: [[0, "desc"]],
    });

    // $("#client").on("change", async function () {
    //   const typesBox = document.getElementById("types");
    //   if (typesBox) {
    //     let client_id = $(this).val();
    //     let response = await fetch("/tenant/ajax/getTypesByClient/" + client_id);
    //     if (!response.ok) {
    //       console.error("Request error");
    //     }
    //     response = await response.json();
    //     const types = response.data;
    //     typesBox.innerHTML = types;
    //   }
    // });

    $('#client-select').on('change', async function () {
      const clientId = $(this).val();
      try {
        await fetchServicesByClient(clientId);
      } catch (error) {
        console.error(error);
      }
    });
    // Handle select change - add to cart and disable selected option
    $("#types").on("change", function () {
      const selectedValue = this.value;
      const selectedOption = this.options[this.selectedIndex];

      if (selectedValue && selectedValue !== '0') {
        // Get product data
        const productData = selectedOption.getAttribute('data-product');
        if (productData) {
          try {
            const product = JSON.parse(productData);

            // Add to cart if not already exists
            if (!cart.cart.find(item => parseInt(item.id) === parseInt(product.id))) {
              cart.add(product);
            }

            // Disable the selected option
            selectedOption.disabled = true;
            selectedOption.style.opacity = '0.5';
            selectedOption.style.fontStyle = 'italic';

            // Reset to placeholder
            this.selectedIndex = 0;
          } catch (e) {
            console.error('Error parsing product data:', e);
          }
        }
      }
    });

    // Employee/Performance Tab

    // Star rating interaction
    const stars = document.querySelectorAll(".rating-stars .ri-star-fill");
    stars.forEach((star) => {
      star.addEventListener("click", () => {
        const value = star.getAttribute("data-value");
        document.getElementById("rating").value = value;
        stars.forEach((s, i) => {
          s.classList.toggle("text-warning", i < value);
        });
      });
    });

    // Initialize Performance Trend Chart

    if (document.getElementById("performanceTrendChart") != null) {
      const trendCtx = document.getElementById("performanceTrendChart")
      const ctx = trendCtx.getContext("2d");
      new Chart(ctx, {
        type: "line",
        data: {
          labels: ["June", "July", "August", "September", "October", "November"],
          datasets: [
            {
              label: "Performance Score",
              data: [3.8, 4.0, 4.2, 4.1, 4.3, 4.5],
              borderColor: "#0d6efd",
              backgroundColor: "rgba(13, 110, 253, 0.1)",
              borderWidth: 2,
              pointRadius: 5,
              pointBackgroundColor: "#0d6efd",
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            y: {
              beginAtZero: true,
              max: 5,
              ticks: {
                stepSize: 0.5,
              },
            },
          },
          layout: {
            padding: {
              top: 20,
              right: 20,
              bottom: 20,
              left: 20,
            },
          },
        },
      });
    }

    if (document.getElementById("skillDistributionChart") != null) {
      const skillCtx = document
        .getElementById("skillDistributionChart")
        .getContext("2d");
      new Chart(skillCtx, {
        type: "doughnut",
        data: {
          labels: ["Technical", "Communication", "Management", "Problem Solving"],
          datasets: [
            {
              data: [40, 25, 20, 15],
              backgroundColor: ["#0d6efd", "#198754", "#ffc107", "#dc3545"],
              borderWidth: 0,
            },
          ],
        },
        options: {
          plugins: {
            legend: {
              position: "bottom",
            },
          },
        },
      });
    }

    // Make toggleExtra function available in document ready scope
    window.toggleExtra = function (elem) {
      let _target = $(elem).data("target");
      $(_target).toggleClass("d-none");

      // Toggle 'required' attribute for all inputs inside _target
      $(_target)
        .find("input")
        .each(function () {
          $(this).prop("required", function (_, value) {
            return !value;
          });
        });
    };

    // Make toggleOfferte function available in document ready scope
    window.toggleOfferte = function (elem) {
      const priceInput = document.getElementById('price');
      const extraCheckbox = document.querySelector('input[type="checkbox"][name="data[extra]"]');
      const extraPriceDiv = document.getElementById('extra-price');
      const extraPriceInput = document.getElementById('extra_price');

      if (elem.checked) {
        // If offerte is checked, disable price input and set it to 0
        if (priceInput) {
          priceInput.disabled = true;
          priceInput.value = '0';
          priceInput.classList.add('bg-light');
        }

        // Disable extra checkbox and hide extra price section
        if (extraCheckbox) {
          extraCheckbox.disabled = true;
          extraCheckbox.checked = false;
        }

        if (extraPriceDiv) {
          extraPriceDiv.classList.add('d-none');
        }

        if (extraPriceInput) {
          extraPriceInput.disabled = true;
          extraPriceInput.value = '0';
        }
      } else {
        // If offerte is unchecked, enable price input
        if (priceInput) {
          priceInput.disabled = false;
          priceInput.classList.remove('bg-light');
        }

        // Enable extra checkbox
        if (extraCheckbox) {
          extraCheckbox.disabled = false;
        }

        if (extraPriceInput) {
          extraPriceInput.disabled = false;
        }
      }
    };

    // Simple jQuery code for offerte functionality
    $(document).ready(function () {
      // Check if we're on types add/edit page
      if ($('#is_offerte').length > 0) {
        // If offerte is already checked on page load
        if ($('#is_offerte').is(':checked')) {
          toggleOfferte($('#is_offerte')[0]);
        }

        // Handle offerte checkbox change
        $('#is_offerte').on('change', function () {
          toggleOfferte(this);
        });
      }
    });
  });

  // inspection create/edit billing toggle checkbox

  $('#differentBillingAddress').on('change', function () {
    const billingSection = document.getElementById('billingAddressSection');
    if ( this.checked) { console.log('checked'); } else { console.log('unchecked'); }
  });
})(jQuery);
