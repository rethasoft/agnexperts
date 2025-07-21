$(document).ready(function () {
  $.ajaxSetup({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
  });

  $(".select2-multiple").select2();
  $(".select2-multiple-tags").select2({ tags: true });

  new DataTable(".data-table", {
    order: [[0, "desc"]],
  });

  $("#client").on("change", async function () {
    const typesBox = document.getElementById("types");
    if (typesBox) {
      let client_id = $(this).val();
      let response = await fetch("/tenant/getTypesByClient/" + client_id);
      if (!response.ok) {
        console.error("Request error");
      }
      response = await response.json();
      const types = response.data;
      typesBox.innerHTML = types;
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

  if(document.getElementById("skillDistributionChart") != null){
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
  window.toggleExtra = function(elem) {
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
});
