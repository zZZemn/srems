const sendEmail = (email, name, message) => {
  $.ajax({
    type: "POST",
    url: "../backend/controller/email.php",
    data: {
      email: email,
      name: name,
      message: message,
    },
    success: function (response) {
      console.log(response);
    },
  });
};

$("#BtnSendEmail").click(function (e) {
  e.preventDefault();
  sendEmail("emmanuelugaban12@gmail.com", "Emmanuel Ugaban", "Test Email");
});
