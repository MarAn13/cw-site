<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <title>Hello, world!</title>
</head>
<style>
    html,
    body {
        height: 100%;
    }

    body {
        min-height: 100vh;
    }

    .backgroundAuthentication {
        background-attachment: fixed;
        background-image: url(../images/authentication.jpg);
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        padding: 0 !important;
    }

    input {
        background: rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }

    #password_field,
    #password_field_repeat {
        border-right: none !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    #password_eye,
    #password_eye_repeat {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    #password_eye:hover,
    #password_eye_repeat:hover {
        background: transparent !important;
        transition: 0.3s;
    }

    input:hover {
        background: transparent !important;
        transition: 0.3s;
    }

    input:focus {
        background: transparent !important;
    }

    a:hover {
        color: green !important;
        text-decoration: underline !important;
    }

    select{
        background-color: transparent!important;
    }

    option {
        background-color: #212529;
    }

    .form-control::placeholder {
        /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: white;
        /* Firefox */
    }

    .form-control:-ms-input-placeholder {
        /* Internet Explorer 10-11 */
        color: white;
    }

    .form-control::-ms-input-placeholder {
        /* Microsoft Edge */
        color: white;
    }
</style>

<body class="backgroundAuthentication">
    <section class="h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-md-6 col-lg-4 my-auto">
                    <h3 class="mb-4 text-center text-white">Sign Up</h3>
                    <form action="php/signUp.inc.php" method="post">
                        <div class="form-group">
                            <input name="username" type="text" class="form-control mb-3 rounded-3" placeholder="Username" required>
                        </div>
                        <?php
                        if (isset($_GET["error_empty"]) || isset($_GET["error_username"])) {
                            echo
                            "<div class='alert alert-danger p-1' role='alert'>
                            Invalid username! Please use only latin and numbers
                        </div>";
                        }
                        if (isset($_GET["error"]) && ($_GET["error"] === "server_username_email" ||
                            $_GET["error"] === "server_username")) {
                            echo
                            "<div class='alert alert-danger p-1' role='alert'>
                            The username already exists! Please use a different username
                        </div>";
                        }
                        ?>
                        <div class="form-group">
                            <input name="email" type="email" class="form-control mb-3 rounded-3" placeholder="Email" required>
                        </div>
                        <?php
                        if (isset($_GET["error_empty"]) || isset($_GET["error_email"])) {
                            echo
                            "<div class='alert alert-danger p-1' role='alert'>
                            Invalid email! Please use only real email address
                        </div>";
                        }
                        if (isset($_GET["error"]) && ($_GET["error"] === "server_username_email" ||
                            $_GET["error"] === "server_email")) {
                            echo
                            "<div class='alert alert-danger p-1' role='alert'>
                            The email already exists! Please use a different email
                        </div>";
                        }
                        ?>
                        <div class="form-group mb-3">
                            <select name="account_type" class="form-select text-white" required>
                                <option value="" selected>Choose account type</option>
                                <option value="client">Client</option>
                                <option value="specialist">Specialist</option>
                            </select>
                        </div>
                        <?php
                        if (isset($_GET["error_empty"]) || isset($_GET["error_account_type"])) {
                            echo
                            "<div class='alert alert-danger p-1' role='alert'>
                            Invalid account type! Please use only given account types
                        </div>";
                        }
                        ?>
                        <div class="form-group input-group">
                            <input name="password" id="password_field" type="password" class="form-control mb-3 rounded-3" placeholder="Password" required>
                            <button type="button" class="btn btn-outline-light text-white mb-3" id="password_eye"><i class="bi bi-eye-fill"></i></button>
                        </div>
                        <div class="form-group input-group">
                            <input name="password_repeat" id="password_field_repeat" type="password" class="form-control mb-3 rounded-3" placeholder="Verify password" required>
                            <button type="button" class="btn btn-outline-light text-white mb-3" id="password_eye_repeat"><i class="bi bi-eye-fill"></i></button>
                        </div>
                        <?php
                        if (isset($_GET["error_empty"]) || isset($_GET["error_password"])) {
                            echo
                            "<div class='alert alert-danger p-1' role='alert'>
                            Passwords don't match!
                        </div>";
                        }
                        ?>
                        <div class="form-group">
                            <button name="submit" type="submit" class="form-control btn btn-dark btn-outline-success border-0 text-white px-3">Sign
                                Up</button>
                        </div>
                    </form>
                    <p class="w-100 text-center text-white mt-3">&mdash; Or &mdash;</p>
                    <div class="container">
                        <a href="signIn.php">
                            <button type="button" class="form-control btn btn-dark btn-outline-success border-0 text-white px-3">Sign
                                In</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <!-- own js -->
    <script src="../js/signUp.js"></script>
</body>

</html>