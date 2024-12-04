const sendEmail = (email, name, dot, tId, damagedList) => {
  $.ajax({
    type: "POST",
    url: "../backend/controller/email.php",
    data: {
      REQUEST_TYPE: "SENDEMAILRETURNED",
      email: email,
      name: name,
      dot: dot,
      tId: tId,
      damagedList: JSON.stringify(damagedList),
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

  var damagedList = [];

  $("#btnReturnTransaction").attr("disabled", true);

  var formData = new FormData(this);

  $(".input-damage-qty").each(function () {
    var id = $(this).data("id");
    var itemName = $(this).data("itemname");
    var value = $(this).val();
    var currQty = $(this).data("curqty");

    var obj = {
      id: id,
      itemName: itemName,
      value: value,
      currQty: currQty,
    };

    damagedList.push(obj);

    formData.append(`damage_qty[${id}][item_name]`, itemName);
    formData.append(`damage_qty[${id}][qty]`, value);
  });

  console.log(damagedList);

  $.ajax({
    url: "../backend/controller/transaction.php",
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);

      if (response == 200) {
        AlertMessage("alert-success", "Transcation Completed!");
        $("#formReturnTransaction")[0].reset();
        $("#formReturnTransaction").modal("hide");
        sendEmail(
          $("#sdEmail").text(),
          $("#sdName").text(),
          $("#tdDOT").text(),
          $("#txtHiddenTCode").val(),
          damagedList
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
