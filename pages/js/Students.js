const loadStudent = (search, status) => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/student.php",
    data: {
      REQUEST_TYPE: "GETSTUDENTS",
      search: search,
      status: status,
    },
    success: function (response) {
      const $tableBody = $("#studentTableBody");

      $tableBody.empty();

      if (response.length > 0) {
        $.each(response, function (index, student) {
          const $row = $("<tr>");

          $row.append($("<td>").text(student.ID));
          $row.append(
            $("<td>").html(
              "<a href='StudentDetails.php?sId=" +
                student.ID +
                "'>" +
                student.STUDENT_CODE +
                "</a>"
            )
          );
          $row.append(
            $("<td>").html(
              "<img src='../student-photos/" +
                student.IMG +
                "' class='btn-item-image' style='height: 30px; width: 30px; cursor: zoom-in;'>"
            )
          );
          $row.append($("<td>").text(student.NAME));
          $row.append($("<td>").text(student.EMAIL));
          $row.append($("<td>").text(student.CONTACT_NO));
          $row.append(
            $("<td>").text(
              student.STATUS === "ACTIVE" ? "Active" : "Deactivated"
            )
          );

          //
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
// $("#btnAddStudent").click(function (e) {
//   e.preventDefault();
//   $("#ModalAddStudent").modal("show");
// });

$("#formAddStudent").submit(function (e) {
  e.preventDefault();

  // var formData = $(this).serialize();
  var formData = new FormData(this);

  $.ajax({
    url: "../backend/controller/student.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);
      if (response == 200) {
        AlertMessage("alert-success", "Student Added!");
        $("#formAddStudent")[0].reset();

        const search = $("#inputSearch").val();
        const status = $("#selectStatus").val();

        loadStudent(search, status);
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

  // const ID = $("#eStudentId").val();
  // const STUDENT_CODE = $("#eStudentCode").val();
  // const NAME = $("#eStudentName").val();
  // const EMAIL = $("#eStudentEmail").val();
  // const CONTACT_NO = $("#eStudentContactNo").val();

  // var formData = $(this).serialize();
  var formData = new FormData(this);

  $.ajax({
    type: "POST",
    url: "../backend/controller/student.php",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);
      if (response == 200) {
        AlertMessage("alert-success", "Student details edited!");
        $("#formEditStudent")[0].reset();
        hideModal();

        const search = $("#inputSearch").val();
        const status = $("#selectStatus").val();

        loadStudent(search, status);
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

// Deactivate
$(document).on("click", "#btnDeactivate", function (e) {
  const ID = $(this).data("id");
  const STATUS = $(this).data("status");

  const confirmation = confirm(
    "Are you sure you want to change the student's status?"
  );

  if (!confirmation) {
    return;
  }

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

        const search = $("#inputSearch").val();
        const status = $("#selectStatus").val();

        loadStudent(search, status);
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

$("#selectStatus").change(function (e) {
  e.preventDefault();
  const search = $("#inputSearch").val();
  const status = $("#selectStatus").val();

  loadStudent(search, status);
});

$("#inputSearch").on("input", function (e) {
  const search = $("#inputSearch").val();
  const status = $("#selectStatus").val();

  loadStudent(search, status);
});

// Show image
$(document).on("click", ".btn-item-image", function (e) {
  e.preventDefault();
  var src = $(this).attr("src");

  $("#ModalItemImageImg").attr("src", src);
  $("#ModalViewItemImage").modal("show");
  console.log(src);
});

// Add Student V2

const searchInStudentListUsingBarCode = (code) => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/studentList.php",
    data: {
      REQUEST_TYPE: "GETSTUDENTUSINGCODE",
      STUDENT_CODE: code,
    },
    success: function (response) {
      if (response != "400") {
        console.log(response);
        $("#resultName").text(response.NAME);
        $("#btnSaveAddStudentV2").attr("disabled", false);
      } else {
        $("#resultName").text("");
        $("#btnSaveAddStudentV2").attr("disabled", true);
      }
    },
  });
};

$("#btnAddStudent").click(function (e) {
  e.preventDefault();
  $("#btnSaveAddStudentV2").attr("disabled", true);
  $("#ModalAddStudentV2").modal("show");
});

$("#btnClearBarcodeV2").click(function (e) {
  e.preventDefault();
  $("#studentCodeV2").val("");
});

$("#studentCodeV2").on("input", function (e) {
  searchInStudentListUsingBarCode($(this).val());
  $(this).val($(this).val());
});

$("#formAddStudentV2").submit(function (e) {
  e.preventDefault();

  var code = $("#studentCodeV2").val();

  var formData = new FormData(this);

  $.ajax({
    type: "POST",
    url: "../backend/controller/student.php",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);
      if (response == "200") {
        AlertMessage("alert-success", "Student Added!");

        const search = $("#inputSearch").val();
        const status = $("#selectStatus").val();

        loadStudent(search, status);

        hideModal();
        $("#formAddStudentV2")[0].reset();
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
          AlertMessage("alert-danger", "Failed to add student!");
        }
      }
    },
  });
});

// Add Student V2 End

// ----
const video = $("#webcam")[0];
const canvas = $("#canvas")[0];
var fileInput = $("#eStudentImage");

$("#btnAddUploadUsingWebcam").click(function (e) {
  e.preventDefault();
  fileInput = $("#AddStudentImage");

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

  fileInput = $("#eStudentImage");

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

loadStudent("", "ALL");
