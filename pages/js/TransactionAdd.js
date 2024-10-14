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

const searchStudentCode = (studCode) => {
  $.ajax({
    url: "../backend/controller/student.php",
    type: "GET",
    data: {
      REQUEST_TYPE: "GETSTUDENTUSINGCODE",
      STUDENT_CODE: studCode,
    },
    success: function (response) {
      if (response != 400) {
        $("#sdName").text(response.NAME);
        $("#sdEmail").text(response.EMAIL);
        $("#sdContactNo").text(response.CONTACT_NO);
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

$("#btnClearStudCode").click(function (e) {
  e.preventDefault();

  $("#studentCode").val("");
});

$("#studentCode").on("input", function (e) {
  searchStudentCode($(this).val());
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

$("#AddItemInputItem").on("input", function () {
  const inputVal = $(this).val();
  const $selectedOption = $("#itemList option").filter(function () {
    return $(this).val() === inputVal;
  });

  if ($selectedOption.length) {
    const invID = $selectedOption.data("id");
    const qty = $selectedOption.data("qty");
    const itemName = $selectedOption.val();

    $("#hiddenItemId").val(invID);
    $("#hiddenItemQty").val(qty);
    $("#hiddenItemName").val(itemName);
  }
});

$("#formTransactionAddItem").submit(function (e) {
  e.preventDefault();

  const itemName = $("#AddItemInputItem").val();

  const hiddenItemName = $("#hiddenItemName").val();
  const itemId = $("#hiddenItemId").val();
  const itemQty = $("#hiddenItemQty").val();

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
        itemsArray[index].qty += 1;
      } else {
        itemsArray.push(item);
      }
    } else {
      itemsArray.push(item);
    }

    hideModal();

    $("#hiddenItemId").val("");
    $("#AddItemInputItem").val("");
    $("#hiddenItemQty").val("");
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

// Add Item End

$("#frmTransactionAdd").submit(function (e) {
  e.preventDefault();

  $("#BtnSaveTransaction").attr("disabled", true);

  var isInvalidCode = false;
  var isInvalidDate = false;

  const studCode = $("#studentCode").val();
  const dueDate = $("#dueDate").val();

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const minDate = new Date(today);
  minDate.setDate(minDate.getDate() + 1);

  const selectedDate = new Date(dueDate);

  if (itemsArray.length < 1) {
    AlertMessage("alert-danger", "Please select item in the list");
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
      barrowedInfo = response;
      console.log(response);
      console.log("test code: " + response);
      if (response == 400) {
        isInvalidCode = true;
        AlertMessage("alert-danger", "Invalid student code");
        return;
      } else {
        isInvalidCode = false;
      }
    },
    error: function (xhr, status, error) {
      console.log("Form submission failed:", status, error);
    },
  });

  if (selectedDate < minDate) {
    isInvalidDate = true;
    AlertMessage("alert-danger", "Invalid due date");
    return;
  }

  if (!isInvalidCode && !isInvalidDate) {
    $.ajax({
      type: "POST",
      url: "../backend/controller/transaction.php",
      data: {
        REQUEST_TYPE: "INSERTNEWTRANSACTION",
        STUDENT_CODE: studCode,
        DUE_DATE: dueDate,
        ITEMS: JSON.stringify(itemsArray),
      },
      success: function (response) {
        console.log(response);
        if (response == 200) {
          AlertMessage("alert-success", "Transaction added!");

          try {
            sendEmail(barrowedInfo.EMAIL, barrowedInfo.NAME, dueDate);
          } catch (e) {
            console.error("An error occurred while sending the email:", e);
            setTimeout(() => {
              window.location.reload();
            }, 1000);
          }
        } else {
          AlertMessage("alert-danger", "Something went wrong!");
          return;
        }
      },
    });
  }
});

loadAddItemModalContents();
loadItemsList();
