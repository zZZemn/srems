var currentDate = new Date();

const loadTransaction = (search, status) => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/transaction.php",
    data: {
      REQUEST_TYPE: "GETTRANSACTIONS",
      search: search,
      status: status,
    },
    success: function (response) {
      console.log(response);

      const $tableBody = $("#transactionTableBody");

      $tableBody.empty();

      if (response.length > 0) {
        $.each(response, function (index, trans) {
          var dueDate = new Date(trans.DUEDATE);

          const $row = $("<tr>");

          $row.append($("<td>").text(trans.ID));
          $row.append(
            $("<td>").html(
              "<a href='TransactionDetails.php?tId=" +
                trans.TRANSACTION_CODE +
                "'>" +
                trans.TRANSACTION_CODE +
                "</a>"
            )
          );
          $row.append($("<td>").text(trans.USERNAME));
          $row.append($("<td>").text(trans.NAME));
          $row.append($("<td>").text(trans.DATE));
          $row.append($("<td>").text(trans.DUEDATE));

          var statusCell = $("<td>").html(
            dueDate < currentDate && trans.STATUS != "RETURNED"
              ? trans.STATUS + " <span class='text-danger'>(Overdue)</span>"
              : trans.STATUS
          );
          $row.append($("<td>").html(statusCell));

          $tableBody.append($row);
        });
      } else {
        const $noDataRow = $("<tr>").append(
          $("<td>")
            .attr("colspan", 7)
            .addClass("text-center")
            .text("No Data Found!")
        );
        $tableBody.append($noDataRow);
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
};

$("#selectStatus").change(function (e) {
  e.preventDefault();
  const search = $("#inputSearch").val();
  const status = $("#selectStatus").val();

  loadTransaction(search, status);
});

$("#inputSearch").on("input", function (e) {
  const search = $("#inputSearch").val();
  const status = $("#selectStatus").val();

  loadTransaction(search, status);
});

loadTransaction("", "ALL");
