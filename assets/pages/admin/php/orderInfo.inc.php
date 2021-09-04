<?php
session_start();
if (!isset($_SESSION['admin'])) {
    echo "Access denied";
    die();
}
if (isset($_POST['approve_button'])){
    require_once("../../php/sql.php");

    admin_approve_report($_SESSION['admin_current_order_id']);
    unset($_SESSION['admin_current_order_id']);
    header("location: ../reportHistory.php?operation=approve");
    die();
}elseif(isset($_POST['decline_button'])){
    require_once("../../php/sql.php");

    admin_decline_report($_SESSION['admin_current_order_id']);
    unset($_SESSION['admin_current_order_id']);
    header("location: ../reportHistory.php?operation=decline");
    die();
}else{
    header("location: ../reportHistory.php");
    die();
}