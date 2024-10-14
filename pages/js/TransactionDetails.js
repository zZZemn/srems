const sendEmail = (email, name, dot, tId) => {
  $.ajax({
    type: "POST",
    url: "../backend/controller/email.php",
    data: {
      REQUEST_TYPE: "SENDEMAILRETURNED",
      email: email,
      name: name,
      dot: dot,
      tId: tId,
    },
    success: function (response) {
      console.log(response);
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    },
  });
};

$("#btnReturnTransaction").click(function (e) {
  e.preventDefault();
  $("#ModalReturnTransaction").modal("show");
});

$("#formReturnTransaction").submit(function (e) {
  e.preventDefault();

  $("#btnReturnTransaction").attr("disabled", true);

  var formData = $(this).serialize();

  $.ajax({
    url: "../backend/controller/transaction.php",
    type: "POST",
    data: formData,
    success: function (response) {
      if (response == 200) {
        AlertMessage("alert-success", "Transcation Completed!");
        $("#formReturnTransaction")[0].reset();

        sendEmail(
          $("#sdEmail").text(),
          $("#sdName").text(),
          $("#tdDOT").text(),
          $("#txtHiddenTCode").val()
        );
      } else {
        AlertMessage("alert-danger", "Failed to complete transction!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
});
