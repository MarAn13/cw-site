<?php
require_once("php/auth.inc.php");
require_once("php/sql.php");

if (isset($_SESSION['client'])) {
    $email = $_SESSION['client'];
    $account_info = get_account_info($_SESSION['client']);
    $rating = false;
} elseif (isset($_SESSION['specialist'])) {
    $email = $_SESSION['specialist'];
    $account_info = get_account_info($_SESSION['specialist']);
    $rating = specialist_get_rating($_SESSION['specialist']);
} elseif (isset($_SESSION['admin'])) {
    $email = $_SESSION['admin'];
    $account_info = get_account_info($_SESSION['admin']);
}
?>
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

    .mainContentDiv {
        /*background-attachment: fixed;
        background-image: url(assets/images/backgroundTest.jpg);
        background-size: cover;
        background-repeat: no-repeat;
        background-color: rgb(150, 150, 150);
        background-blend-mode: multiply;
        filter: none;
        -webkit-filter: none;*/
        background-color: silver;
    }

    input::placeholder {
        color: gray !important;
    }

    #password_field,
    #password_field_repeat {
        border-right: none !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    #password_eye:hover,
    #password_eye_repeat:hover {
        background: transparent;
    }
</style>

<body>
    <?php
    if (isset($_SESSION['client'])) {
        include_once('client/header.php');
    } elseif (isset($_SESSION['specialist'])) {
        include_once('specialist/header.php');
    } elseif (isset($_SESSION['admin'])) {
        include_once('admin/header.php');
    }
    ?>
    <!-- Main Content -->
    <div class="container-fluid p-3 mainContentDiv">
        <div class="container-fluid bg-dark text-light rounded mainContentBgDiv">
            <h1 class="text-center">Account info</h1>
            <div class="row">
                <div class="col ms-5">
                    <div class="row fs-5 mt-5">
                        <p>Username: <span class="border rounded">
                                <?php
                                echo $account_info['username'];
                                ?></span></p>
                    </div>
                    <div class="row fs-5">
                        <p>Email: <span class="border rounded">
                                <?php
                                echo $email;
                                ?>
                            </span></p>
                    </div>
                    <?php
                    if (isset($_SESSION['specialist'])) {
                        echo
                        "<div class='row fs-5'>
                        <p>Current rating: <span class='border rounded'>";
                        if ($rating !== false && is_numeric($rating)) {
                            echo $rating;
                        } else {
                            echo '-';
                        }
                        echo
                        "</span></p>
                    </div>";
                    }
                    ?>
                    <div class="row fs-5">
                        <p>Account type: <span class="border rounded">
                                <?php
                                echo $account_info['user_type'];
                                ?>
                            </span></p>
                    </div>
                    <form action="php/account.inc.php" method="post">
                        <div class="form-group input-group mb-3 w-50">
                            <input name="password" id="password_field" type="password" class="bg-dark form-control mb-3 rounded-3 text-white" placeholder="New password" required>
                            <button type="button" class="btn btn-outline-light text-white mb-3" id="password_eye"><i class="bi bi-eye-fill"></i></button>
                        </div>
                        <div class="form-group input-group mb-3 w-50">
                            <input name="password_repeat" id="password_field_repeat" type="password" class="bg-dark form-control mb-3 rounded-3 text-white" placeholder="Verify new password" required>
                            <button type="button" class="btn btn-outline-light text-white mb-3" id="password_eye_repeat"><i class="bi bi-eye-fill"></i></button>
                        </div>
                        <button name="password_change_button" type="submit" class="btn btn-dark btn-outline-success mb-3">Change</button>
                    </form>
                </div>
                <div class="col text-center my-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20rem" height="20rem" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                        <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php
    if (isset($_SESSION['client'])) {
        include_once('client/footer.html');
    } elseif (isset($_SESSION['specialist'])) {
        include_once('specialist/footer.html');
    } elseif (isset($_SESSION['admin'])) {
        include_once('admin/footer.html');
    }
    ?>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <!-- own js -->
    <script src="../js/account.js"></script>
    <script src="../js/nav.js"></script>
    <?php
    if (isset($_GET['operation'])) {
        if ($_GET['operation'] === "success") {
            echo "<script>
            $(document).ready(function(){
        alert('The password was changed successfully');
            });
        </script>";
        }
    }
    ?>
</body>

</html>