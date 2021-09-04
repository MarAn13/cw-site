<?php
session_start();
if (!isset($_SESSION['client']) && !isset($_SESSION['specialist'])) {
    echo "Access denied";
    die();
}
if (!isset($_POST['password_change_button'])) {
    header("location: ../account.php");
    die();
}
require_once("sql.php");

$user_password = trim(htmlspecialchars($_POST['password']));
$user_password_repeat = trim(htmlspecialchars($_POST['password_repeat']));
if(empty($user_password) || empty($user_password_repeat)){
    header("location: ../account.php?operation=password_empty");
    die();
}
if(!password_match($user_password, $user_password_repeat)){
    header("location: ../account.php?operation=password_match");
    die();
}
if (isset($_SESSION['client'])) {
    change_password($_SESSION['client'], $user_password);
} elseif (isset($_SESSION['specialist'])) {
    change_password($_SESSION['specialist'], $user_password);
}
header("location: ../account.php?operation=success");
die();