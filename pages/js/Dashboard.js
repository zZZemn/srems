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
            labels: response.bi.labels,
            datasets: [
              {
                label: "Borrowed Items",
                data: response.bi.numbers,
                borderColor: "rgba(75, 192, 192, 1)",
                fill: true,
                tension: 0.1,
              },
              {
                label: "Added Student",
                data: response.student.numbers,
                borderColor: "rgba(153, 102, 255, 1)",
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
