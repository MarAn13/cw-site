<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">
    <title>SiteMAP</title>
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

    #password_field {
        border-right: none !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    #password_eye {
        background: rgba(255, 255, 255, 0.1) !important;
    }

    #password_eye:hover {
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
                    <h3 class="mb-4 text-center text-white">Sign In</h3>
                    <form action="php/signIn.inc.php" method="post">
                        <div class="form-group">
                            <input name="email" type="email" class="form-control mb-3 rounded-3" placeholder="Email" required>
                        </div>
                        <div class="form-group input-group">
                            <input name="password" id="password_field" type="password" class="form-control mb-3 rounded-3" placeholder="Password" required>
                            <button type="button" class="btn btn-outline-light text-white mb-3" id="password_eye"><i class="bi bi-eye-fill"></i></button>
                        </div>
                        <?php
                        if (isset($_GET["error"]) && ($_GET["error"] === "empty_input" ||
                            $_GET["error"] === "invalid_input")) {
                            echo
                            "<div class='alert alert-danger p-1' role='alert'>
                            Incorrect email or password!
                        </div>";
                        }
                        ?>
                        <div class="form-group">
                            <button name="submit" type="submit" class="form-control btn btn-dark btn-outline-success border-0 text-white px-3">Sign
                                In</button>
                        </div>
                        <!--<div class="form-group d-md-flex">
                            <div class="w-50 text-white">
                                <label>Remember Me
                                    <input type="checkbox" checked>
                                    <span></span>
                                </label>
                            </div>
                            <div class="w-100 justify-content-end d-flex">
                                <a href="#" class="link-light text-decoration-none">Forgot Password</a>
                            </div>
                        </div>-->
                    </form>
                    <p class="w-100 text-center text-white">&mdash; Or &mdash;</p>
                    <div class="container">
                        <a href="signUp.php">
                            <button type="submit" class="form-control btn btn-dark btn-outline-success border-0 text-white px-3">Sign
                                Up</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <!-- own js -->
    <script src="../js/signIn.js"></script>
</body>

</html>