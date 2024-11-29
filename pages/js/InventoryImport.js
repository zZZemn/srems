$("#btnImportInventory").click(function (e) {
  e.preventDefault();
  $("#ModalUploadCSV").modal("show");
});

$("#formImportInventory").submit(function (e) {
  e.preventDefault();

  const fileInput = $("#csvFile")[0].files[0];

  if (!fileInput) {
    alert("Please select a CSV file.");
    return;
  }

  Papa.parse(fileInput, {
    header: true,
    skipEmptyLines: true,
    complete: function (results) {
      console.log("Parsed data:", results.data);

      $.ajax({
        url: "../backend/controller/importItem.php",
        type: "POST",
        contentType: "application/json",
        data: JSON.stringify(results.data),
        success: function (response) {
          console.log("Server response:", response);
          AlertMessage("alert-success", "Data successfully inserted.");
          $("#formImportInventory")[0].reset();
          loadInventory("", "ALL");
          hideModal();
        },
        error: function (error) {
          console.error("Error:", error);
          AlertMessage("alert-danger", error);
        },
      });

      console.log(results);
    },
    error: function (error) {
      console.error("Error parsing CSV:", error);
      AlertMessage("alert-danger", "Failed to parse CSV file.");
    },
  });
});
