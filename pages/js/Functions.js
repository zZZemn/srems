const AlertMessage = (alertType, message) => {
  $("#AlertComponent").addClass(alertType);
  $("#AlertMessage").text(message);

  setTimeout(() => {
    $("#AlertComponent").removeClass(alertType);
    $("#AlertMessage").text("");
  }, 2000);
};

const hideModal = () => {
  $(".modal").modal("hide");
};

$(".btnCloseModal").click(function (e) {
  hideModal();
});

const loadList = (table, callback) => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/GET.php",
    data: {
      REQUEST_TYPE: "GETLIST",
      table: table,
    },
    success: function (response) {
      callback(response);
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error: " + status + ": " + error);
    },
  });
};

const glLoadInventory = (callback) => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/inventory.php",
    data: {
      REQUEST_TYPE: "GETINVENTORYLIST",
    },
    success: function (response) {
      callback(response);
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error: " + status + ": " + error);
    },
  });
};

const getBarrowedQty = (invId, callback) => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/inventory.php",
    data: {
      REQUEST_TYPE: "GETBARROWEDQTY",
      ID: invId,
    },
    success: function (response) {
      callback(response);
    },
    error: function (xhr, status, error) {
      console.error("AJAX Error: " + status + ": " + error);
    },
  });
};
