const loadInventory = (search, category) => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/inventory.php",
    data: {
      REQUEST_TYPE: "GETINVENTORY",
      search: search,
      category: category,
    },
    success: function (response) {
      const $tableBody = $("#inventoryTableBody");

      $tableBody.empty();
      console.log(response);
      if (response.length > 0) {
        $.each(response, function (index, inv) {
          const $row = $("<tr>");

          $row.append($("<td>").text(inv.ID));
          $row.append($("<td>").text(inv.INV_CODE));
          $row.append(
            $("<td>").html(
              "<img src='../items-photos/" +
                inv.IMG +
                "' style='height: 30px; width: 30px;'>"
            )
          );
          $row.append($("<td>").text(inv.ITEM_NAME));
          $row.append($("<td>").text(inv.QTY));
          $row.append($("<td>").text(inv.REMAINING_QTY));
          $row.append($("<td>").text(inv.CATEGORY));
          $row.append(
            $("<td>").text(inv.STATUS === "ACTIVE" ? "Active" : "Deactivated")
          );

          const $actionTd = $("<td>");

          const $editButton = $("<button>")
            .append('<i class="bi bi-pencil-square"></i>')
            .addClass("btn btn-primary btn-sm me-1")
            .css("font-size", "12px")
            .attr("id", "btnEditInventory")
            .attr("data-id", inv.ID)
            .attr("data-invcode", inv.INV_CODE)
            .attr("data-itemname", inv.ITEM_NAME)
            .attr("data-qty", inv.QTY)
            .attr("data-category", inv.CATEGORY);

          const $deactivateButton = $("<button>")
            .addClass(
              inv.STATUS === "ACTIVE"
                ? "btn btn-danger btn-sm"
                : "btn btn-success btn-sm"
            )
            .text(inv.STATUS === "ACTIVE" ? "Deactivate" : "Activate")
            .css("font-size", "12px")
            .attr("id", "btnDeactivate")
            .attr("data-id", inv.ID)
            .attr("data-status", inv.STATUS);

          $actionTd.append($editButton).append(" ").append($deactivateButton);

          $row.append($actionTd);

          $tableBody.append($row);
        });
      } else {
        const $noDataRow = $("<tr>").append(
          $("<td>")
            .attr("colspan", 9)
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

// Add Inventory
$("#btnAddInventory").click(function (e) {
  e.preventDefault();
  $("#ModalAddInventory").modal("show");
});

$("#formAddInventory").submit(function (e) {
  e.preventDefault();

  // var formData = $(this).serialize();
  var formData = new FormData(this);

  $.ajax({
    url: "../backend/controller/inventory.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);
      if (response == 200) {
        AlertMessage("alert-success", "Item Added!");
        $("#formAddInventory")[0].reset();

        const search = $("#inputSearch").val();
        const category = $("#selectCategory").val();

        loadInventory(search, category);
      } else {
        AlertMessage("alert-danger", "Failed to add!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
});
// End

//Edit inventory
$(document).on("click", "#btnEditInventory", function (e) {
  e.preventDefault();

  const ID = $(this).data("id");
  const INV_CODE = $(this).data("invcode");
  const ITEM_NAME = $(this).data("itemname");
  const QTY = $(this).data("qty");
  const CATEGORY = $(this).data("category");

  $("#eInventoryId").val(ID);
  $("#eInventoryCode").val(INV_CODE);
  $("#eInventoryItem").val(ITEM_NAME);
  $("#eInventoryQty").val(QTY);
  $("#eInventoryCategory").val(CATEGORY);

  $("#ModalEditInventory").modal("show");
});

$("#formEditInventory").submit(function (e) {
  e.preventDefault();

  // var formData = $(this).serialize();
  var formData = new FormData(this);

  $.ajax({
    type: "POST",
    url: "../backend/controller/inventory.php",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      if (response == 200) {
        AlertMessage("alert-success", "Item details edited!");
        $("#formEditInventory")[0].reset();
        hideModal();

        const search = $("#inputSearch").val();
        const category = $("#selectCategory").val();

        loadInventory(search, category);
      } else {
        AlertMessage("alert-danger", "Failed to edit!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
});
// End

// Deactivate
$(document).on("click", "#btnDeactivate", function (e) {
  e.preventDefault();

  const ID = $(this).data("id");
  const STATUS = $(this).data("status");

  $.ajax({
    type: "POST",
    url: "../backend/controller/inventory.php",
    data: {
      REQUEST_TYPE: "DEACTIVATE",
      ID: ID,
      STATUS: STATUS,
    },
    success: function (response) {
      console.log(response);

      if (response == 200) {
        AlertMessage("alert-success", "Item status change");

        const search = $("#inputSearch").val();
        const category = $("#selectCategory").val();

        loadInventory(search, category);
      } else {
        AlertMessage("alert-danger", "Failed to change status!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
});
// End

$("#selectCategory").change(function (e) {
  e.preventDefault();
  const search = $("#inputSearch").val();
  const category = $("#selectCategory").val();

  loadInventory(search, category);
});

$("#inputSearch").on("input", function (e) {
  const search = $("#inputSearch").val();
  const category = $("#selectCategory").val();

  loadInventory(search, category);
});

// Export

$("#btnExportInventory").on("click", function () {
  var today = new Date();
  var formattedDate =
    today.getFullYear() +
    "-" +
    (today.getMonth() + 1).toString().padStart(2, "0") +
    "-" +
    today.getDate().toString().padStart(2, "0");
  exportTableToCSV("SREMS_INV_" + formattedDate + ".csv");
});

const exportTableToCSV = (filename) => {
  var csv = [];
  var rows = $("table tr");

  rows.each(function () {
    var row = [];
    $(this)
      .find("td, th")
      .each(function () {
        row.push($(this).text());
      });
    csv.push(row.join(","));
  });

  downloadCSV(csv.join("\n"), filename);
};

const downloadCSV = (csv, filename) => {
  var csvFile;
  var downloadLink;

  csvFile = new Blob([csv], { type: "text/csv" });

  downloadLink = document.createElement("a");

  downloadLink.download = filename;

  downloadLink.href = window.URL.createObjectURL(csvFile);

  $(downloadLink).hide().appendTo("body");
  downloadLink.click();
  $(downloadLink).remove();
};

// Export End

loadInventory("", "ALL");
