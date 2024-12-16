const loadStudent = () => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/studentList.php",
    data: {
      REQUEST_TYPE: "GETSTUDENTS",
    },
    success: function (response) {
      console.log(response);

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
          const $actionTd = $("<td>");

          const $editButton = $("<button>")
            .append('<i class="bi bi-pencil-square"></i>')
            .addClass("btn btn-primary btn-sm me-1")
            .css("font-size", "12px")
            .attr("id", "btnEditStudent")
            .attr("data-id", student.ID)
            .attr("data-studentcode", student.STUDENT_CODE)
            .attr("data-studentname", student.NAME)
            .attr("data-studentemail", student.EMAIL)
            .attr("data-studentcontactno", student.CONTACT_NO)
            .attr("data-studentyear", student.YEAR)
            .attr("data-studentsection", student.SECTION);

          const $deactivateButton = $("<button>")
            .addClass(
              student.STATUS === "ACTIVE"
                ? "btn btn-danger btn-sm"
                : "btn btn-success btn-sm"
            )
            .text("Delete")
            .css("font-size", "12px")
            .attr("id", "btnDelete")
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
            .attr("colspan", 8)
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

// Add Student
$("#btnAddStudent").click(function (e) {
  e.preventDefault();
  $("#ModalAddStudent").modal("show");
});

$("#formAddStudent").submit(function (e) {
  e.preventDefault();

  // var formData = $(this).serialize();
  var formData = new FormData(this);

  $.ajax({
    url: "../backend/controller/studentList.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);
      if (response == 200) {
        AlertMessage("alert-success", "Student Added!");
        $("#formAddStudent")[0].reset();

        loadStudent();
      } else {
        if (response == "CODE_EXIST") {
          AlertMessage("alert-danger", "This code already exists!");
        } else if (response == "EMAIL_EXIST") {
          AlertMessage("alert-danger", "This email is already in use!");
        } else if (response == "NAME_EXIST") {
          AlertMessage("alert-danger", "This name already exists!");
        } else if (response == "CONTACTNO_EXIST") {
          AlertMessage("alert-danger", "This contact number already exists!");
        } else {
          AlertMessage("alert-danger", "Failed to add!");
        }
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
  const YEAR = $(this).data("studentyear");
  const SECTION = $(this).data("studentsection");

  $("#eStudentId").val(ID);
  $("#eStudentCode").val(STUDENT_CODE);
  $("#eStudentName").val(NAME);
  $("#eStudentEmail").val(EMAIL);
  $("#eStudentContactNo").val(CONTACT_NO);
  $("#eStudentYear").val(YEAR);
  $("#eStudentSection").val(SECTION);

  $("#ModalEditStudent").modal("show");
});

$("#formEditStudent").submit(function (e) {
  e.preventDefault();

  var formData = new FormData(this);

  $.ajax({
    type: "POST",
    url: "../backend/controller/studentList.php",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);
      if (response == 200) {
        AlertMessage("alert-success", "Student details edited!");
        $("#formEditStudent")[0].reset();
        hideModal();

        loadStudent();
      } else {
        if (response == "CODE_EXIST") {
          AlertMessage("alert-danger", "This code already exists!");
        } else if (response == "EMAIL_EXIST") {
          AlertMessage("alert-danger", "This email is already in use!");
        } else if (response == "NAME_EXIST") {
          AlertMessage("alert-danger", "This name already exists!");
        } else if (response == "CONTACTNO_EXIST") {
          AlertMessage("alert-danger", "This contact number already exists!");
        } else {
          AlertMessage("alert-danger", "Failed to edit!");
        }
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
});
// End

// Delete
$(document).on("click", "#btnDelete", function (e) {
  const ID = $(this).data("id");

  const confirmation = confirm("Are you sure you want to delete this student");

  if (!confirmation) {
    return;
  }

  $.ajax({
    type: "POST",
    url: "../backend/controller/studentList.php",
    data: {
      REQUEST_TYPE: "DELETE",
      ID: ID,
    },
    success: function (response) {
      console.log(response);

      if (response == 200) {
        AlertMessage("alert-success", "Student deleted!");
        loadStudent();
      } else {
        AlertMessage("alert-danger", "Failed to delete status!");
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
});
// End

loadStudent();
