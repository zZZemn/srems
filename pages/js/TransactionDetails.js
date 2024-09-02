$("#btnReturnTransaction").click(function (e) {
  e.preventDefault();
  $("#ModalReturnTransaction").modal("show");
});

$("#formReturnTransaction").submit(function (e) {
  e.preventDefault();
  var formData = $(this).serialize();

  $.ajax({
    url: "../backend/controller/transaction.php",
    type: "POST",
    data: formData,
    success: function (response) {
      if (response == 200) {
        AlertMessage("alert-success", "Transcation Completed!");
        $("#formReturnTransaction")[0].reset();
        setTimeout(() => {
          window.location.reload();
        }, 1000);
      } else {
        AlertMessage("alert-danger", "Failed to complete transction!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
});
