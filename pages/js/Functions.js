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
