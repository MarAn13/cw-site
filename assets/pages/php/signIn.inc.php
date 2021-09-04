<?php
if (isset($_POST['submit'])) {
    $user_email = trim(htmlspecialchars($_POST['email']));
    $user_password = trim(htmlspecialchars($_POST['password']));

    require_once('sql.php');

    if (empty_input_sign_in($user_email, $user_password)) {
        header("location: ../signIn.php?error=empty_input");
        die();
    }
    if (($user_type = sign_in($user_email, $user_password)) === false) {
        header("location: ../signIn.php?error=invalid_input");
        die();
    }
    $user_type = preg_replace(array('/{/', '/}/'), '', $user_type);
    session_start();
    session_destroy();
    if ($user_type === "client") {
        session_start();
        $_SESSION['client'] = $user_email;
        header("location: ../client/userMain.php");
        die();
    } elseif ($user_type === "specialist") {
        if (invalid_specialist_place_of_operation($user_email)) {
            session_start();
            $_SESSION['specialist'] = $user_email;
            $_SESSION['specialist_place_of_operation'] = false;
            header("location: ../choose.php");
            die();
        }
        session_start();
        $_SESSION['specialist'] = $user_email;
        header("location: ../specialist/orderDesk.php");
        die();
    } elseif ($user_type === "admin") {
        session_start();
        $_SESSION['admin'] = $user_email;
        header("location: ../admin/reportHistory.php");
        die();
    }
} else {
    header("location: ../signIn.php");
    die();
}
