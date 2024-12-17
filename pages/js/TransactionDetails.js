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

$(document).on("click", ".btn-replace", function (e) {
  e.preventDefault();

  var tdId = $(this).data("tdid");
  var dmgQty = $(this).data("dmgqty");
  var replacedItemsQty = $(this).data("replacedqty");

  console.log(tdId);
  console.log(dmgQty);

  $("#replaceTD_ID").val(tdId);
  $("#replace_dmg_qty").val(dmgQty);
  $("#replace_qty").attr("max", dmgQty - replacedItemsQty);

  $("#ModalReplaceItems").modal("show");
});

$("#formReplaceItems").submit(function (e) {
  e.preventDefault();

  var tdId = $("#replaceTD_ID").val();
  var qty = $("#replace_qty").val();
  var dmgQty = $("#replace_dmg_qty").val();

  console.log(tdId);
  console.log(qty);
  console.log(dmgQty);

  var formData = new FormData(this);

  $.ajax({
    type: "POST",
    url: "../backend/controller/transaction.php",
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log(response);

      if (response == 200) {
        AlertMessage("alert-success", "Replacement Completed!");
        $("#formReplaceItems")[0].reset();
        $("#ModalReplaceItems").modal("hide");
        window.location.reload();
      } else {
        AlertMessage("alert-danger", "Failed to complete replacement!");
      }
    },
  });
});

$("#btn-print").click(function (e) {
  e.preventDefault();

  window.print();
});

$(".btnEditQty").click(function (e) {
  e.preventDefault();
  var id = $(this).data("id");
  var name = $(this).data("name");
  var qty = $(this).data("qty");
  var availableQty = $(this).data("available_qty");

  var maxQtyCanUpdate = parseInt(qty, 10) + parseInt(availableQty, 10);

  console.log(id);
  console.log(name);
  console.log(qty);
  console.log(availableQty);

  console.log(maxQtyCanUpdate);

  $("#spanEditQtyName").text(name);

  $("#editQtyInv_id").val(id);

  $("#editQtyCurrentQty").val(qty);

  $("#editQtyEditedQty").attr("min", 1).attr("max", maxQtyCanUpdate);

  $("#editQtyAvailableQty").val(maxQtyCanUpdate);

  $("#ModalEditQuantity").modal("show");
});

$("#formEditQuantity").submit(function (e) {
  e.preventDefault();

  var id = parseInt($("#editQtyInv_id").val(), 10);
  var currentQty = parseInt($("#editQtyCurrentQty").val(), 10);
  var maxQty = parseInt($("#editQtyAvailableQty").val(), 10);
  var editedQty = parseInt($("#editQtyEditedQty").val(), 10);

  if (currentQty == editedQty) {
    hideModal();
    $("#formEditQuantity")[0].reset();
    return;
  } else if (editedQty > maxQty || editedQty < 1) {
    AlertMessage("alert-danger", "Please input valid values");
    return;
  } else {
    console.log("Id: " + id);
    console.log("Current Qty: " + currentQty);
    console.log("maxQty: " + maxQty);
    console.log("editedQty: " + editedQty);

    var formData = $(this).serialize();

    $.ajax({
      type: "POST",
      url: "../backend/controller/transaction.php",
      data: formData,
      success: function (response) {
        hideModal();
        $("#formEditQuantity")[0].reset();
        console.log(response);
        setTimeout(() => {
          window.location.reload();
        }, 100);
      },
    });
  }
});


// ------

const video = $("#webcam")[0];
const canvas = $("#canvas")[0];
var fileInput = $("#rtnItemImg");

$("#btnAddUploadUsingWebcam").click(function (e) {
  e.preventDefault();

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
