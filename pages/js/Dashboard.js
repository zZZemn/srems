$(document).ready(function () {
  const loadBiChart = () => {
    var biCtx = $("#biChart");

    $.ajax({
      type: "GET",
      url: "../backend/controller/GET.php",
      data: {
        REQUEST_TYPE: "GETBIDASHBOARDDATA",
      },
      success: function (response) {
        console.log(response);
        var barrowedItemsChart = new Chart(biCtx, {
          type: "line",
          data: {
            labels: response.labels,
            datasets: [
              {
                label: "Barrowed Items",
                data: response.numbers,
                borderColor: "rgba(75, 192, 192, 1)",
                fill: true,
                tension: 0.1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        });
      },
    });
  };

  loadBiChart();
});
