<?php
if (isset($_POST['submit'])) {
    $user_username = trim(htmlspecialchars($_POST['username']));
    $user_email = trim(htmlspecialchars($_POST['email']));
    $user_account_type = trim(htmlspecialchars($_POST['account_type']));
    $user_password = trim(htmlspecialchars($_POST['password']));
    $user_password_repeat = trim(htmlspecialchars($_POST['password_repeat']));

    require_once('sql.php');

    if (empty_input_sign_up($user_username, $user_email, $user_account_type, $user_password, $user_password_repeat)) {
        header("location: ../signUp.php?error_empty=empty_input");
        die();
    }
    if (invalid_username($user_username)) {
        header("location: ../signUp.php?error_username=invalid_username");
        die();
    }
    if (invalid_email($user_email)) {
        header("location: ../signUp.php?error_email=invalid_email");
        die();
    }
    if (invalid_account_type($user_account_type)) {
        header("location: ../signUp.php?error_account_type=invalid_account_type");
        die();
    }
    if (!password_match($user_password, $user_password_repeat)) {
        header("location: ../signUp.php?error_password=password_match");
        die();
    }
    $result = create_user($user_username, $user_email,  $user_account_type, $user_password);
    if ($result !== true) {
        if ($result === "Invalid username and email") {
            header("location: ../signUp.php?error=server_username_email");
            die();
        } else if ($result === "Invalid username") {
            header("location: ../signUp.php?error=server_username");
            die();
        } else {
            header("location: ../signUp.php?error=server_email");
            die();
        }
    } else {
        header("location: ../signIn.php");
        die();
    }
} else {
    header("location: ../signUp.php");
    die();
}
