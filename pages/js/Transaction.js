var currentDate = new Date();

const sendEmail = () => {
  $.ajax({
    type: "POST",
    url: "../backend/controller/email.php",
    data: {
      REQUEST_TYPE: "SENDEMAILOVERDUE",
    },
    success: function (response) {
      console.log("response:" + response);
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    },
  });
};

$("#btnSendEmail").click(function (e) {
  e.preventDefault();
  $(this).attr("disabled", true);
  sendEmail();
});

const loadTransaction = (search, status, month) => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/transaction.php",
    data: {
      REQUEST_TYPE: "GETTRANSACTIONS",
      search: search,
      status: status,
      month: month,
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

          const date = new Date(trans.DATE);
          const formattedDate = date.toISOString().split("T")[0]; // Format as YYYY-MM-DD
          const formattedTime = date.toLocaleTimeString("en-US", {
            hour: "2-digit",
            minute: "2-digit",
            hour12: true,
          });
          $row.append($("<td>").text(`${formattedDate} ${formattedTime}`));

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
  const month = $("#selectDate").val();

  loadTransaction(search, status, month);
});

$("#selectDate").change(function (e) {
  e.preventDefault();

  e.preventDefault();
  const search = $("#inputSearch").val();
  const status = $("#selectStatus").val();
  const month = $("#selectDate").val();

  loadTransaction(search, status, month);
});

$("#inputSearch").on("input", function (e) {
  const search = $("#inputSearch").val();
  const status = $("#selectStatus").val();
  const month = $("#selectDate").val();

  loadTransaction(search, status, month);
});

loadTransaction("", "ALL", "ALL");
