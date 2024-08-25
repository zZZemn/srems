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

        //
        const $actionTd = $("<td>").addClass("d-flex");

        const $editButton = $("<button>")
          .append('<i class="bi bi-pencil-square"></i>')
          .addClass("btn btn-primary btn-sm me-1")
          .css("font-size", "12px")
          .attr("id", "btnEditStudent")
          .attr("data-id", student.ID)
          .attr("data-studentcode", student.STUDENT_CODE)
          .attr("data-studentname", student.NAME)
          .attr("data-studentemail", student.EMAIL)
          .attr("data-studentcontactno", student.CONTACT_NO);

        const $deactivateButton = $("<button>")
          .addClass(
            student.STATUS === "ACTIVE"
              ? "btn btn-danger btn-sm"
              : "btn btn-success btn-sm"
          )
          .text(student.STATUS === "ACTIVE" ? "Deactivate" : "Activate")
          .css("font-size", "12px")
          .attr("id", "btnDeactivate")
          .attr("data-id", student.ID)
          .attr("data-status", student.STATUS);

        $actionTd.append($editButton).append(" ").append($deactivateButton);

        $row.append($actionTd);

        //

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

// Add Student
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
// End

// Edit Student
$(document).on("click", "#btnEditStudent", function (e) {
  e.preventDefault();

  const ID = $(this).data("id");
  const STUDENT_CODE = $(this).data("studentcode");
  const NAME = $(this).data("studentname");
  const EMAIL = $(this).data("studentemail");
  const CONTACT_NO = $(this).data("studentcontactno");

  $("#eStudentId").val(ID);
  $("#eStudentCode").val(STUDENT_CODE);
  $("#eStudentName").val(NAME);
  $("#eStudentEmail").val(EMAIL);
  $("#eStudentContactNo").val(CONTACT_NO);

  $("#ModalEditStudent").modal("show");
});

$("#formEditStudent").submit(function (e) {
  e.preventDefault();

  // const ID = $("#eStudentId").val();
  // const STUDENT_CODE = $("#eStudentCode").val();
  // const NAME = $("#eStudentName").val();
  // const EMAIL = $("#eStudentEmail").val();
  // const CONTACT_NO = $("#eStudentContactNo").val();

  var formData = $(this).serialize();

  $.ajax({
    type: "POST",
    url: "../backend/controller/student.php",
    data: formData,
    success: function (response) {
      if (response == 200) {
        AlertMessage("alert-success", "Student details edited!");
        $("#formEditStudent")[0].reset();
        hideModal();
        loadStudent();
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
  const ID = $(this).data("id");
  const STATUS = $(this).data("status");

  $.ajax({
    type: "POST",
    url: "../backend/controller/student.php",
    data: {
      REQUEST_TYPE: "DEACTIVATE",
      ID: ID,
      STATUS: STATUS,
    },
    success: function (response) {
      console.log(response);

      if (response == 200) {
        AlertMessage("alert-success", "Student status change");
        loadStudent();
      } else {
        AlertMessage("alert-danger", "Failed to change statu!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
});
// End

loadStudent();
