// Signature
var urlBackground = "../photos/signature-bg.png";
var imageBackground = new Image();
imageBackground.src = urlBackground;
imageBackground.setAttribute("crossorigin", "anonymous");
$("#drawPad").drawpad();
var contextCanvas = $("#drawPad canvas").get(0).getContext("2d");
imageBackground.onload = function () {
  contextCanvas.drawImage(imageBackground, 0, 0);
};

const itemsArray = [];

var barrowedInfo = {};

const sendEmail = (email, name, dueDate) => {
  $.ajax({
    type: "POST",
    url: "../backend/controller/email.php",
    data: {
      REQUEST_TYPE: "SENDEMAILBARROWED",
      email: email,
      name: name,
      items: JSON.stringify(itemsArray),
      dueDate: dueDate,
    },
    success: function (response) {
      console.log(response);
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    },
  });
};

const checkStudentTransactionToday = (studCode, callback) => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/transaction.php",
    data: {
      REQUEST_TYPE: "GETSTUDENTTRANSACTIONTODAY",
      STUDENT_CODE: studCode,
    },
    success: function (response) {
      const hasTransactionToday = response == 1;
      callback(hasTransactionToday);
    },
  });
};

const searchStudentCode = (studCode) => {
  $("#studentCode").val(studCode);

  $.ajax({
    url: "../backend/controller/student.php",
    type: "GET",
    data: {
      REQUEST_TYPE: "GETSTUDENTUSINGCODE",
      STUDENT_CODE: studCode,
    },
    success: function (response) {
      console.log(response);

      if (response != 400) {
        if (response.STATUS == "ACTIVE") {
          checkStudentTransactionToday(studCode, (hasTransactionToday) => {
            if (hasTransactionToday) {
              AlertMessage(
                "alert-danger",
                "This student already has a transaction today"
              );
            } else {
              $("#sdName").text(response.NAME);
              $("#sdEmail").text(response.EMAIL);
              $("#sdContactNo").text(response.CONTACT_NO);
            }
          });
        }
      } else {
        $("#sdName").text("");
        $("#sdEmail").text("");
        $("#sdContactNo").text("");
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });
};

const loadAddItemModalContents = () => {
  glLoadInventory(function (response) {
    if (response.length > 0) {
      $.each(response, function (index, inv) {
        const $option = $(
          "<option id='option-" +
            inv.ID +
            "' value='" +
            inv.ITEM_NAME +
            "' data-id='" +
            inv.ID +
            "' data-qty='" +
            inv.QTY +
            "'>" +
            inv.ITEM_NAME +
            "</option>"
        );
        $("#itemList").append($option);
      });
    }
  });
};

const loadItemSelectList = (category) => {
  $("#AddItemItemNameSelect").empty();

  $.ajax({
    type: "GET",
    url: "../backend/controller/inventory.php",
    data: {
      REQUEST_TYPE: "GETINVENTORY",
      search: "",
      category: category,
    },
    success: function (response) {
      if (response.length > 0) {
        $("#AddItemItemNameSelect").append('<option value=""></option>');

        response.forEach(function (item) {
          let option = `<option value="${item.ID}" data-qty="${item.REMAINING_QTY}">${item.ITEM_NAME}</option>`;
          $("#AddItemItemNameSelect").append(option);
        });
      }
    },
  });
};

$("#btnClearStudCode").click(function (e) {
  e.preventDefault();

  $("#studentCode").val("");
});

$("#studentCode").on("keydown", function (e) {
  let value = $("#studentCode").val();

  if (e.key === "Enter") {
    searchStudentCode(value);
  }
});

$("#studentCode").on("input", function (e) {
  e.preventDefault();

  let value = $("#studentCode").val();

  searchStudentCode(value);
});

// Add Item
const loadItemsList = () => {
  $("#transaction-item-tbody").html("");

  $.each(itemsArray, function (index, item) {
    const $tr = $(
      "<tr><td>" +
        item.itemId +
        "</td><td>" +
        item.itemName +
        "</td><td>" +
        item.itemQty +
        "</td><td><button class='btnMinusItemQty btn btn-sm' data-id='" +
        index +
        "'>-</button>" +
        item.qty +
        "<button class='btnPlusItemQty btn btn-sm' data-id='" +
        index +
        "'>+</button></td><td><button class='btn-remove-item-in-list btn btn-sm' data-id='" +
        index +
        "'><i class='bi bi-x'></i></button></td></tr>"
    );

    $("#transaction-item-tbody").append($tr);
  });
};

$("#btnAddItem").click(function (e) {
  e.preventDefault();

  $("#ModalTransactionAddItem").modal("show");
});

$("#AddItemSelectCategory").change(function (e) {
  e.preventDefault();
  loadItemSelectList($(this).val());
});

$("#AddItemItemNameSelect").change(function (e) {
  e.preventDefault();

  let selectedOption = $(this).find(":selected");

  const invID = selectedOption.val();
  const qty = selectedOption.data("qty");
  const itemName = selectedOption.text();

  $("#hiddenItemId").val(invID);
  $("#hiddenItemQty").val(qty);
  $("#hiddenItemName").val(itemName);
});

$("#formTransactionAddItem").submit(function (e) {
  e.preventDefault();

  const itemName = $("#hiddenItemName").val();

  const hiddenItemName = $("#hiddenItemName").val();
  const itemId = $("#hiddenItemId").val();
  const itemQty = $("#hiddenItemQty").val();

  if (itemQty < 1) {
    AlertMessage("alert-danger", "This item is out of stock");
    return;
  }

  if (
    itemId != null &&
    !isNaN(itemId) &&
    hiddenItemName != null &&
    itemQty != null &&
    !isNaN(itemQty) &&
    itemName == hiddenItemName
  ) {
    const item = {
      itemId: parseInt(itemId),
      itemName: itemName,
      itemQty: parseInt(itemQty),
      qty: 1,
    };

    if (itemsArray && itemsArray.length > 0) {
      const index = itemsArray.findIndex(
        (existingItem) => existingItem.itemId === item.itemId
      );

      if (index !== -1) {
        var currentQty = itemsArray[index].qty;
        if ((currentQty += 1) > item.itemQty) {
          AlertMessage(
            "alert-danger",
            "This item have reached its maximum quantity"
          );
          return;
        }

        itemsArray[index].qty += 1;
      } else {
        itemsArray.push(item);
      }
    } else {
      itemsArray.push(item);
    }

    hideModal();

    $("#AddItemSelectCategory").val("ALL");
    loadItemSelectList("ALL");
    $("#AddItemItemNameSelect").val("");

    $("#hiddenItemName").val("");
    $("#hiddenItemId").val("");
    $("#hiddenItemQty").val("");

    $("#barCode").val("");
  } else {
    AlertMessage("alert-danger", "Please select item in the list");
  }

  loadItemsList();
});

$(document).on("click", ".btnMinusItemQty", function (e) {
  e.preventDefault();
  const index = $(this).data("id");
  console.log("Minus: " + index);

  const qty = itemsArray[index].qty;
  const newQty = qty - 1;

  if (newQty > 0) {
    itemsArray[index].qty -= 1;
    loadItemsList();
  }
});

$(document).on("click", ".btnPlusItemQty", function (e) {
  e.preventDefault();
  const index = $(this).data("id");
  console.log("Plus: " + index);

  const qty = itemsArray[index].qty;
  const newQty = qty + 1;

  const itemQty = itemsArray[index].itemQty;

  if (newQty <= itemQty) {
    itemsArray[index].qty += 1;
    loadItemsList();
  } else {
    console.log("qty: " + qty);
  }
});

$(document).on("click", ".btn-remove-item-in-list", function (e) {
  e.preventDefault();

  const index = $(this).data("id");

  if (Number.isInteger(index) && index >= 0 && index < itemsArray.length) {
    itemsArray.splice(index, 1);

    loadItemsList();
  } else {
    console.log("Invalid index: " + index);
  }
});

// Scan Bar Code

const searchBarCode = (code) => {
  $.ajax({
    type: "GET",
    url: "../backend/controller/inventory.php",
    data: {
      REQUEST_TYPE: "GETINVUSINGBARCODE",
      BARCODE: code,
    },
    success: function (response) {
      if (response != 400) {
        console.log("Valid");
        console.log(response);

        const item = {
          itemId: parseInt(response.ID),
          itemName: response.ITEM_NAME,
          itemQty: parseInt(response.REMAINING_QTY),
          qty: 1,
        };

        if (item.itemQty < 1) {
          AlertMessage("alert-danger", "This item is out of stock");
          return;
        }

        if (itemsArray && itemsArray.length > 0) {
          const index = itemsArray.findIndex(
            (existingItem) => existingItem.itemId === item.itemId
          );

          if (index !== -1) {
            var currentQty = itemsArray[index].qty;
            if ((currentQty += 1) > item.itemQty) {
              AlertMessage(
                "alert-danger",
                "This item have reached its maximum quantity"
              );
              return;
            }

            itemsArray[index].qty += 1;
          } else {
            itemsArray.push(item);
          }
        } else {
          itemsArray.push(item);
        }

        hideModal();

        $("#AddItemSelectCategory").val("ALL");
        loadItemSelectList("ALL");
        $("#AddItemItemNameSelect").val("");

        $("#hiddenItemName").val("");
        $("#hiddenItemId").val("");
        $("#hiddenItemQty").val("");

        $("#barCode").val("");

        loadItemsList();
      } else {
        console.log("Barcode Invalid");
      }
    },
  });
};

$("#btnClearBarCode").click(function (e) {
  e.preventDefault();

  $("#barCode").val("");
});

$("#barCode").on("input", function (e) {
  searchBarCode($(this).val());
  $(this).val($(this).val());
});

// Add Item End

$("#frmTransactionAdd").submit(function (e) {
  e.preventDefault();

  var isInvalidCode = false;
  var isInvalidDate = false;
  var isInvalidSubmit = false;

  const studCode = $("#studentCode").val();
  const dueDate = $("#dueDate").val();
  const teacher = $("#teacher").val();
  const venue = $("#venue").val();

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const minDate = new Date(today);
  minDate.setDate(minDate.getDate() + 1);

  const selectedDate = new Date(dueDate);

  if (itemsArray.length < 1) {
    AlertMessage("alert-danger", "Please select item in the list");
    return;
  }

  var base64ImageSignature = $("#drawPad canvas").get(0).toDataURL();
  $("#base64ImagePreview").attr("src", base64ImageSignature);

  checkStudentTransactionToday(studCode, function (hasTransactionToday) {
    if (hasTransactionToday) {
      isInvalidCode = true;
      AlertMessage(
        "alert-danger",
        "This student already has a transaction today"
      );
      return;
    }

    if (selectedDate < minDate) {
      isInvalidDate = true;
      AlertMessage("alert-danger", "Invalid due date");
      return;
    }

    if (!teacher) {
      isInvalidSubmit = true;
      AlertMessage("alert-danger", "Invalid teacher");
      return;
    }

    if (!venue) {
      isInvalidSubmit = true;
      AlertMessage("alert-danger", "Invalid venue");
      return;
    }

    $.ajax({
      url: "../backend/controller/student.php",
      type: "GET",
      data: {
        REQUEST_TYPE: "GETSTUDENTUSINGCODE",
        STUDENT_CODE: studCode,
      },
      success: function (response) {
        if (response == 400) {
          isInvalidCode = true;
          AlertMessage("alert-danger", "Invalid student code");
          return;
        }

        if (!isInvalidCode && !isInvalidDate && !isInvalidSubmit) {
          $("#BtnSaveTransaction").attr("disabled", true);

          $.ajax({
            type: "POST",
            url: "../backend/controller/transaction.php",
            data: {
              REQUEST_TYPE: "INSERTNEWTRANSACTION",
              STUDENT_CODE: studCode,
              DUE_DATE: dueDate,
              TEACHER: teacher,
              VENUE: venue,
              SIGNATURE: base64ImageSignature,
              ITEMS: JSON.stringify(itemsArray),
            },
            success: function (response) {
              if (response == 200) {
                AlertMessage("alert-success", "Transaction added!");

                try {
                  sendEmail(barrowedInfo.EMAIL, barrowedInfo.NAME, dueDate);
                } catch (e) {
                  console.error(
                    "An error occurred while sending the email:",
                    e
                  );
                  setTimeout(() => {
                    window.location.reload();
                  }, 1000);
                }
              } else {
                AlertMessage("alert-danger", "Something went wrong!");
              }
            },
          });
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching student info:", error);
        AlertMessage("alert-danger", "Something went wrong!");
      },
    });
  });


});

loadAddItemModalContents();
loadItemsList();
loadItemSelectList("ALL");
