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

          $row.append($("<td>").text(inv.BARCODE));

          $row.append($("<td>").text(inv.INV_CODE));
          $row.append(
            $("<td>").html(
              "<img src='../items-photos/" +
                inv.IMG +
                "' class='btn-item-image' style='height: 30px; width: 30px; cursor: zoom-in;'>"
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
            .attr("data-category", inv.CATEGORY)
            .attr("data-remainingqty", inv.REMAINING_QTY);

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

// Show image
$(document).on("click", ".btn-item-image", function (e) {
  e.preventDefault();
  var src = $(this).attr("src");

  $("#ModalItemImageImg").attr("src", src);
  $("#ModalViewItemImage").modal("show");
  console.log(src);
});

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
  const REMAINING_QTY = $(this).data("remainingqty");

  $("#eInventoryId").val(ID);
  $("#eInventoryCode").val(INV_CODE);
  $("#eInventoryItem").val(ITEM_NAME);
  $("#eInventoryQty").val(QTY);
  $("#eInventoryCategory").val(CATEGORY);

  var minumun = QTY - REMAINING_QTY;

  $("#eInventoryQty").attr("min", minumun);

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

  const confirmation = confirm(
    "Are you sure you want to change the item's status?"
  );

  if (!confirmation) {
    return;
  }

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
      .find("td:not(:last-child), th:not(:last-child)")
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

// ----
const video = $("#webcam")[0];
const canvas = $("#canvas")[0];
// const imgPreview = $("#imagePreview");
var fileInput = $("#inventoryImage");

//
$("#btnAddUploadUsingWebcam").click(function (e) {
  e.preventDefault();

  fileInput = $("#inventoryImage");

  navigator.mediaDevices
    .getUserMedia({
      video: true,
    })
    .then((stream) => {
      video.srcObject = stream;
    })
    .catch((err) => {
      console.error("Failed to access webcam:", err);
      alert("Could not access webcam. Make sure you have granted permissions.");
    });

  $("#ModalCaptureImage").modal("show");
});

$("#btnEditUploadUsingWebcam").click(function (e) {
  e.preventDefault();

  fileInput = $("#eInventoryImage");

  navigator.mediaDevices
    .getUserMedia({
      video: true,
    })
    .then((stream) => {
      video.srcObject = stream;
    })
    .catch((err) => {
      console.error("Failed to access webcam:", err);
      alert("Could not access webcam. Make sure you have granted permissions.");
    });

  $("#ModalCaptureImage").modal("show");
});

$(".btnCloseCaptureModal").click(function (e) {
  e.preventDefault();

  $("#ModalCaptureImage").modal("hide");

  const video = $("#webcam")[0];

  const stream = video.srcObject;
  const tracks = stream.getTracks();
  tracks.forEach((track) => track.stop());

  video.srcObject = null;
  console.log("Webcam stream stopped.");
});

$("#capture").click(() => {
  const context = canvas.getContext("2d");

  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;

  context.drawImage(video, 0, 0, canvas.width, canvas.height);

  // const imageDataURL = canvas.toDataURL("image/png");

  // imgPreview.attr("src", imageDataURL).show();

  canvas.toBlob((blob) => {
    const file = new File([blob], "captured-image.png", {
      type: "image/png",
    });

    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    fileInput[0].files = dataTransfer.files;

    console.log("Captured image set to file input:", fileInput[0].files[0]);

    const stream = video.srcObject;
    const tracks = stream.getTracks();
    tracks.forEach((track) => track.stop());

    video.srcObject = null;
    console.log("Webcam stream stopped.");

    $("#ModalCaptureImage").modal("hide");
  });
});

// ----

loadInventory("", "ALL");
