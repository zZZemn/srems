const loadInventory = () => {
  loadList("inventory", function (response) {
    const $tableBody = $("#inventoryTableBody");

    $tableBody.empty();

    if (response.length > 0) {
      $.each(response, function (index, inv) {
        const $row = $("<tr>");
        getBarrowedQty(inv.ID, function (BarrowedQty) {
          $row.append($("<td>").text(inv.ID));
          $row.append($("<td>").text(inv.INV_CODE));
          $row.append($("<td>").text(inv.ITEM_NAME));
          $row.append($("<td>").text(inv.QTY));
          $row.append($("<td>").text(inv.QTY - BarrowedQty));
          $row.append($("<td>").text(inv.CATEGORY));
          $row.append($("<td>").text(inv.STATUS));

          const $actionTd = $("<td>").addClass("d-flex");

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
      });
    } else {
      const $noDataRow = $("<tr>").append(
        $("<td>")
          .attr("colspan", 6)
          .addClass("text-center")
          .text("No Data Found!")
      );
      $tableBody.append($noDataRow);
    }
  });
};

// Add Inventory
$("#btnAddInventory").click(function (e) {
  e.preventDefault();
  $("#ModalAddInventory").modal("show");
});

$("#formAddInventory").submit(function (e) {
  e.preventDefault();

  var formData = $(this).serialize();

  $.ajax({
    url: "../backend/controller/inventory.php",
    type: "POST",
    data: formData,
    success: function (response) {
      console.log(response);
      if (response == 200) {
        AlertMessage("alert-success", "Item Added!");
        $("#formAddInventory")[0].reset();
        loadInventory();
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

  var formData = $(this).serialize();

  $.ajax({
    type: "POST",
    url: "../backend/controller/inventory.php",
    data: formData,
    success: function (response) {
      if (response == 200) {
        AlertMessage("alert-success", "Item details edited!");
        $("#formEditInventory")[0].reset();
        hideModal();
        loadInventory();
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

loadInventory();