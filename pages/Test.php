<?php include("components/header.php") ?>


<style>
    .drawpad-dashed {
        height: 300px;
        width: 300px;
    }
</style>


<img id="base64ImagePreview" alt="Base64 Image Preview" />

<div id="target" class="drawpad-dashed"></div>
<div id="outputBase64"></div>
<form id='myform' method="POST">
    <input type='hidden' id='outputBase64FormInput' name='mybase64image'>
    <input type='submit' class="btn btn-sm btn-primary mt-2">
</form>


<?php include("components/footer.php") ?>
<!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->
<script src="https://cnbilgin.github.io/jquery-drawpad/jquery-drawpad.js"></script>
<script>
    $(document).ready(function() {

        var urlBackground = '../photos/signature-bg.png';
        var imageBackground = new Image();
        imageBackground.src = urlBackground;
        imageBackground.setAttribute('crossorigin', 'anonymous');
        $("#target").drawpad();
        var contextCanvas = $("#target canvas").get(0).getContext('2d');
        imageBackground.onload = function() {
            contextCanvas.drawImage(imageBackground, 0, 0);
        }

        // post the base64 image to some endpoint
        // $("#saveToDatabase").click(function() {
        //     var base64Image = $("#target canvas").get(0).toDataURL();
        //     console.log(base64Image);
        //     $("#outputBase64FormInput").val(base64Image);
        //     $("#outputBase64").html(base64Image);
        // });

        // form submit
        $("#myform").submit(function(e) {
            e.preventDefault();

            var base64Image = $("#target canvas").get(0).toDataURL();
            console.log(base64Image);
            $("#outputBase64FormInput").val(base64Image);
            $("#outputBase64").html(base64Image);
            $("#base64ImagePreview").attr("src", base64Image);
        });

    });
</script>
</body>

</html>




<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature</title>
    <link rel="stylesheet" href="https://cnbilgin.github.io/jquery-drawpad/jquery-drawpad.css" />
    <style>
        #target {
            width: 500px;
            height: 400px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cnbilgin.github.io/jquery-drawpad/jquery-drawpad.js"></script>
</head>

<body>

    <div id="target" class="drawpad-dashed"></div>
    <div id="outputBase64"></div>
    <form id='myform' method="POST">
        <input type='hidden' id='outputBase64FormInput' name='mybase64image'>
        <input type='submit' class="btn btn-primary">
    </form>
</body>

</html> -->