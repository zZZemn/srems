const loadTeacher = () => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/teacher.php",
    data: {
      REQUEST_TYPE: "GETTEACHERS",
    },
    success: function (response) {
      console.log(response);

      const $tableBody = $("#teachersTableBody");

      $tableBody.empty();

      if (response.length > 0) {
        $.each(response, function (index, teacher) {
          const $row = $("<tr>");

          $row.append($("<td>").text(teacher.ID));
          $row.append($("<td>").text(teacher.NAME));
          $row.append($("<td>").text(teacher.CONTACT_NO));

          const $actionTd = $("<td>");
          const $deleteButton = $("<button>")
            .addClass("btn-delete-teacher btn btn-danger btn-sm")
            .text("Delete")
            .css("font-size", "12px")
            .attr("data-id", teacher.ID);

          $actionTd.append($deleteButton);
          $row.append($actionTd);

          $tableBody.append($row);
        });
      } else {
        const $noDataRow = $("<tr>").append(
          $("<td>")
            .attr("colspan", 4)
            .addClass("text-center")
            .text("No Data Found!")
        );
        $tableBody.append($noDataRow);
      }
    },
  });
};

$("#formAddTeacher").submit(function (e) {
  e.preventDefault();

  var formData = $(this).serialize();

  $.ajax({
    type: "POST",
    url: "../backend/controller/teacher.php",
    data: formData,
    success: function (response) {
      console.log(response);

      if (response == "200") {
        AlertMessage("alert-success", "Teacher Added!");
        $("#formAddTeacher")[0].reset();

        loadTeacher();
      } else {
        AlertMessage("alert-danger", "Failed to Add!");
      }
    },
  });
});

$(document).on("click", ".btn-delete-teacher", function (e) {
  e.preventDefault();

  var id = $(this).data("id");

  const confirmation = confirm("Are you sure you want to delete this teacher?");

  if (!confirmation) {
    return;
  }

  $.ajax({
    type: "POST",
    url: "../backend/controller/teacher.php",
    data: {
      REQUEST_TYPE: "DELETETEACHER",
      ID: id,
    },
    success: function (response) {
      console.log(response);

      if (response == "200") {
        AlertMessage("alert-success", "Teacher Deleted!");
        loadTeacher();
      } else {
        AlertMessage("alert-danger", "Failed to Delete!");
      }
    },
  });
});

loadTeacher();

// ---
const loadCategory = () => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/category.php",
    data: {
      REQUEST_TYPE: "GETCATEGORIES",
    },
    success: function (response) {
      console.log(response);

      const $tableBody = $("#categoriesTableBody");

      $tableBody.empty();

      if (response.length > 0) {
        $.each(response, function (index, category) {
          const $row = $("<tr>");

          $row.append($("<td>").text(category.ID));
          $row.append($("<td>").text(category.NAME));

          const $actionTd = $("<td>");
          const $deleteButton = $("<button>")
            .addClass("btn-delete-category btn btn-danger btn-sm")
            .text("Delete")
            .css("font-size", "12px")
            .attr("data-id", category.ID);

          $actionTd.append($deleteButton);
          $row.append($actionTd);

          $tableBody.append($row);
        });
      } else {
        const $noDataRow = $("<tr>").append(
          $("<td>")
            .attr("colspan", 3)
            .addClass("text-center")
            .text("No Data Found!")
        );
        $tableBody.append($noDataRow);
      }
    },
  });
};

$("#formAddCategory").submit(function (e) {
  e.preventDefault();

  var formData = $(this).serialize();

  $.ajax({
    type: "POST",
    url: "../backend/controller/category.php",
    data: formData,
    success: function (response) {
      console.log(response);

      if (response == "200") {
        AlertMessage("alert-success", "Category Added!");
        $("#formAddCategory")[0].reset();

        loadCategory();
      } else {
        AlertMessage("alert-danger", "Failed to Add!");
      }
    },
  });
});

$(document).on("click", ".btn-delete-category", function (e) {
  e.preventDefault();

  var id = $(this).data("id");

  const confirmation = confirm(
    "Are you sure you want to delete this category?"
  );

  if (!confirmation) {
    return;
  }

  $.ajax({
    type: "POST",
    url: "../backend/controller/category.php",
    data: {
      REQUEST_TYPE: "DELETECATEGORY",
      ID: id,
    },
    success: function (response) {
      console.log(response);

      if (response == "200") {
        AlertMessage("alert-success", "Category Deleted!");
        loadCategory();
      } else {
        AlertMessage("alert-danger", "Failed to Delete!");
      }
    },
  });
});

loadCategory();
