$("#btnAddStudent").click(function (e) {
  e.preventDefault();
  $("#ModalAddStudent").modal("show");
});

$("#formAddStudent").submit(function (e) {
  e.preventDefault();

  var formData = $(this).serialize();

  $.ajax({
    url: "../backend/controller/student.php",
    type: "POST",
    data: formData,
    success: function (response) {
      if (response == 200) {
        AlertMessage("alert-success", "Student Added!");
        $("#formAddStudent")[0].reset();
      } else {
        AlertMessage("alert-danger", "Failed to add!");
      }
    },
    error: function (xhr, status, error) {
      // Handle error
      console.log("Form submission failed:", status, error);
    },
  });
});
