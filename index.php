<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
if (isset($_SESSION['id'])) {
    header('Location: pages/Dashboard.php');
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <title>SREMS</title>
</head>

<body class="bg-secondary">
    
    <style>
        .form-login {
            max-width: 400px;
            position: relative;
        }

        .form-label {
            font-size: 13px;
        }

        .login-error {
            position: absolute;
            font-size: 12px;
            top: 88px;
            left: 0;

            display: none;
        }
    </style>

    <div class="container pt-5 mt-5">

        <form class="form-login card p-5 m-auto mt-5" id="form-login">

            <h4 class="text-center mb-4"><i class="bi bi-person-bounding-box"></i> Login</h4>

            <span class="login-error text-danger w-100 text-center" id="incorrect-uname-pass">
                Incorrect username or password.
            </span>

            <div class="form-group mb-2 mt-2">
                <label for="username" class="form-label my-0">Username:</label>
                <input type="text" class="form-control" name="username" id="username" require>
            </div>

            <div class="form-group mb-2">
                <label for="password" class="form-label my-0">Password:</label>
                <input type="password" class="form-control" name="password" id="password" require>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Login</button>

        </form>

    </div>


    <script src="node_modules/jquery/dist/jquery.min.js"></script>
    <script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#form-login').submit(function(e) {
                e.preventDefault();

                var username = $('#username').val();
                var password = $('#password').val();

                $.ajax({
                    type: 'POST',
                    url: 'backend/controller/POST.php',
                    data: {
                        REQUEST_TYPE: 'LOGIN',
                        username: username,
                        password: password
                    },
                    success: function(response) {

                        console.log(response);

                        if (response == '200') {
                            window.location.reload();
                        } else {
                            $('#incorrect-uname-pass').css('display', 'block');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>