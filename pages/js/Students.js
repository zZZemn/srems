const loadStudent = () => {
  loadList("students", function (response) {
    const $tableBody = $("#studentTableBody");

    $tableBody.empty();

    if (response.length > 0) {
      $.each(response, function (index, student) {
        const $row = $("<tr>");

        $row.append($("<td>").text(student.ID));
        $row.append($("<td>").text(student.STUDENT_CODE));
        $row.append($("<td>").text(student.NAME));
        $row.append($("<td>").text(student.EMAIL));
        $row.append($("<td>").text(student.CONTACT_NO));
        $row.append($("<td>").text(student.STATUS));

        $tableBody.append($row);
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
        loadStudent();
      } else {
        AlertMessage("alert-danger", "Failed to add!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
});

loadStudent();
